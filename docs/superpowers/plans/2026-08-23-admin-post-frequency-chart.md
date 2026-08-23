# Admin Post-Frequency Chart Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Show a bar chart of "number of posts created per period" on the `/admin` dashboard, with Day / Week / Month / Year filter buttons that reload the chart via AJAX.

**Architecture:** A new framework-agnostic `PostFrequencyCalculator` service computes the bucket boundaries (last 30 days / 12 weeks / 12 months / 5 years) and turns raw DB counts into a zero-filled, labeled series. `PostRepository` gets one new native-SQL aggregate method (`GROUP BY` via `DATE_FORMAT`, since this Doctrine 2.5 setup has no custom DQL date functions registered). `AdminController` gets a `chartDataAction` JSON endpoint under the existing `/admin` route group. `Admin/index.html.twig` (currently an empty dashboard) gets a chart container + filter buttons, and a small vanilla-JS file renders an inline SVG bar chart from the JSON and re-fetches on filter clicks. No new third-party JS dependency is introduced, and the existing Gulp/Bower asset pipeline is left untouched — the two new front-end files are served through Symfony's standard bundle-assets mechanism (`php app/console assets:install`), the same mechanism already used for `favicon.ico` in `app/Resources/views/admin.html.twig:22`.

**Tech Stack:** Symfony 2.8.52, PHP (declared `>=5.3.3`, runtime observed PHP 7.2), Doctrine ORM 2.5.1 / DBAL, Twig, vanilla JS (no jQuery/Chart.js dependency added), PHPUnit.

---

## Important context for the engineer

- This is a **hand-rolled admin panel**, not SonataAdmin. The dashboard route is `volley_face_admin_homepage` → `AdminController::indexAction()` → `VolleyFaceBundle:Admin:index.html.twig`, and that template is currently just 3 lines with no body content (`src/Volley/FaceBundle/Resources/views/Admin/index.html.twig`).
- `/admin` and everything under it already requires `ROLE_ADMIN` per `app/config/security.yml:59` (`{ path: ^/admin/, role: ROLE_ADMIN }`), so the new JSON endpoint is automatically protected — no extra security config needed.
- The `Post` entity (`src/Volley/FaceBundle/Entity/Post.php`) has a `created` column (`datetime`, set automatically by `@Gedmo\Timestampable(on="create")` at line 68-72), which is what "number of posts per day" is based on — it's always populated, unlike `published` which is set manually and can be in the future or effectively unused for drafts.
- **Correction found during Task 2 code review:** this plan originally claimed "the DB table name for `Post` is literally `Post`" based only on `Post.php:15`'s `@ORM\Table()` having no explicit `name=`. That's wrong for this project: `app/config/config.yml:65` sets `orm: naming_strategy: doctrine.orm.naming_strategy.underscore`, which resolves the unnamed table to `post` (lowercase), not `Post`. Any code that queries this table must derive the name from Doctrine metadata (`$repository->getClassMetadata()->getTableName()`) rather than hardcoding either casing, since the naming strategy — not just the entity annotation — determines the real table name.
- There is **no charting library anywhere in this project** (checked `bower.json`, `package.json`, `web/`, all `Resources/public` dirs). Rather than add a new Bower/CDN dependency (which this sandboxed environment can't verify by actually running `bower install`), this plan draws the chart as a small inline SVG built by vanilla JS. This keeps the change self-contained and testable by reading the code.
- **`web/js/` and `web/css/` are gitignored build output** of the Gulp pipeline (`.gitignore:4-5`) and don't exist in a fresh checkout — they only appear after running `gulp`. That pipeline uses old `gulp@3.x` + `gulp-babel`, and this sandbox has no `node_modules` installed, so it's an unreliable place to add new files for this task. Instead, the two new asset files go under `src/Volley/FaceBundle/Resources/public/{js/custom,css}/` and are exposed via Symfony's **separate**, already-used-in-this-project `assets:install` mechanism (see `{{ asset('bundles/volleyface/images/favicon.ico') }}` in `app/Resources/views/admin.html.twig:22`). After `php app/console assets:install web --symlink`, they'll be reachable at `web/bundles/volleyface/js/custom/dashboard-chart.js` and `web/bundles/volleyface/css/dashboard-chart.css`.
- This codebase's existing controller tests (e.g. `src/Volley/FaceBundle/Tests/Controller/PostControllerTest.php`) are all commented-out boilerplate, there's no fixtures/test-DB setup (`app/config/config_test.yml` just imports dev config), and `require-dev` in `composer.json` is empty — there is no working functional-test harness to extend. This plan therefore writes real PHPUnit **unit tests** for the one piece of pure, DB-free logic (`PostFrequencyCalculator`), and calls out manual browser verification for the repository/controller/Twig/JS wiring, matching how the rest of this app is actually verified.
- Doctrine QueryBuilder is the house style for existing repository methods, but there's no prior GROUP BY/aggregate example in the app code, and no custom DQL functions are registered in `app/config/config.yml`. The new repository method therefore uses `$this->getEntityManager()->getConnection()` (DBAL) with a plain parameterized native SQL query, which is simpler and safer than registering a DQL `DATE()`/`DATE_FORMAT()` function just for this.

---

## Task 1: `PostFrequencyCalculator` service (pure logic, TDD)

**Files:**
- Create: `src/Volley/FaceBundle/Service/PostFrequencyCalculator.php`
- Test: `src/Volley/FaceBundle/Tests/Service/PostFrequencyCalculatorTest.php`

This class has no Symfony/Doctrine dependencies — it only computes date-bucket keys, labels, and zero-filled series from a `\DateTime` "now" and a raw `[bucketKey => count]` map. Keeping it dependency-free is what makes it unit-testable without booting the kernel or a database.

- [ ] **Step 1: Write the failing tests**

Create `src/Volley/FaceBundle/Tests/Service/PostFrequencyCalculatorTest.php`:

```php
<?php

namespace Volley\FaceBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Volley\FaceBundle\Service\PostFrequencyCalculator;

class PostFrequencyCalculatorTest extends TestCase
{
    /**
     * @var PostFrequencyCalculator
     */
    private $calculator;

    /**
     * @var \DateTime
     */
    private $now;

    protected function setUp()
    {
        $this->calculator = new PostFrequencyCalculator();
        // Fixed "now" so every assertion below is deterministic.
        $this->now = new \DateTime('2026-08-23 15:30:00');
    }

    public function testIsValidPeriod()
    {
        $this->assertTrue($this->calculator->isValidPeriod('day'));
        $this->assertTrue($this->calculator->isValidPeriod('week'));
        $this->assertTrue($this->calculator->isValidPeriod('month'));
        $this->assertTrue($this->calculator->isValidPeriod('year'));
        $this->assertFalse($this->calculator->isValidPeriod('bogus'));
    }

    public function testDayBucketsAreThirtyConsecutiveDaysEndingToday()
    {
        $keys = $this->calculator->getBucketKeys(PostFrequencyCalculator::PERIOD_DAY, $this->now);

        $this->assertCount(30, $keys);
        $this->assertSame('2026-08-23', end($keys));
        $this->assertSame('2026-07-25', reset($keys));

        for ($i = 1; $i < count($keys); $i++) {
            $prev = new \DateTime($keys[$i - 1]);
            $curr = new \DateTime($keys[$i]);
            $this->assertSame(1, (int) $prev->diff($curr)->days, "Days at index $i are not consecutive");
        }
    }

    public function testWeekBucketsAreTwelveConsecutiveWeeksEndingThisWeek()
    {
        $keys = $this->calculator->getBucketKeys(PostFrequencyCalculator::PERIOD_WEEK, $this->now);

        $this->assertCount(12, $keys);
        $this->assertSame($this->now->format('o-W'), end($keys));

        $mondays = array();
        foreach ($keys as $key) {
            list($isoYear, $isoWeek) = explode('-', $key);
            $monday = new \DateTime();
            $monday->setISODate((int) $isoYear, (int) $isoWeek, 1);
            $monday->setTime(0, 0, 0);
            $mondays[] = $monday;
        }

        for ($i = 1; $i < count($mondays); $i++) {
            $diffDays = (int) $mondays[$i - 1]->diff($mondays[$i])->days;
            $this->assertSame(7, $diffDays, "Week buckets at index $i are not 7 days apart");
        }
    }

    public function testMonthBucketsAreTwelveConsecutiveMonthsEndingThisMonth()
    {
        $keys = $this->calculator->getBucketKeys(PostFrequencyCalculator::PERIOD_MONTH, $this->now);

        $this->assertCount(12, $keys);
        $this->assertSame('2026-08', end($keys));
        $this->assertSame('2025-09', reset($keys));
    }

    public function testYearBucketsAreFiveConsecutiveYearsEndingThisYear()
    {
        $keys = $this->calculator->getBucketKeys(PostFrequencyCalculator::PERIOD_YEAR, $this->now);

        $this->assertSame(array('2022', '2023', '2024', '2025', '2026'), $keys);
    }

    public function testFormatLabelDay()
    {
        $this->assertSame('Aug 23', $this->calculator->formatLabel(PostFrequencyCalculator::PERIOD_DAY, '2026-08-23'));
    }

    public function testFormatLabelMonth()
    {
        $this->assertSame('Aug 2026', $this->calculator->formatLabel(PostFrequencyCalculator::PERIOD_MONTH, '2026-08'));
    }

    public function testFormatLabelYear()
    {
        $this->assertSame('2026', $this->calculator->formatLabel(PostFrequencyCalculator::PERIOD_YEAR, '2026'));
    }

    public function testFormatLabelWeekUsesMondayOfIsoWeek()
    {
        // 2024-01-01 is a known Monday, and is ISO week 1 of ISO year 2024.
        $this->assertSame('Jan 1', $this->calculator->formatLabel(PostFrequencyCalculator::PERIOD_WEEK, '2024-01'));
    }

    public function testBuildSeriesFillsGapsWithZeroAndKeepsCountsWhereProvided()
    {
        $counts = array('2026-08-23' => 5);

        $series = $this->calculator->buildSeries(PostFrequencyCalculator::PERIOD_DAY, $counts, $this->now);

        $this->assertCount(30, $series);
        $last = end($series);
        $this->assertSame('Aug 23', $last['label']);
        $this->assertSame(5, $last['count']);

        $first = reset($series);
        $this->assertSame(0, $first['count']);
    }

    public function testGetWindowStartForDayIsMidnightThirtyDaysAgo()
    {
        $start = $this->calculator->getWindowStart(PostFrequencyCalculator::PERIOD_DAY, $this->now);

        $this->assertSame('2026-07-25 00:00:00', $start->format('Y-m-d H:i:s'));
    }

    public function testGetWindowStartForMonthIsFirstOfMonthMidnight()
    {
        $start = $this->calculator->getWindowStart(PostFrequencyCalculator::PERIOD_MONTH, $this->now);

        $this->assertSame('2025-09-01 00:00:00', $start->format('Y-m-d H:i:s'));
    }

    public function testGetWindowStartForYearIsJanFirstMidnight()
    {
        $start = $this->calculator->getWindowStart(PostFrequencyCalculator::PERIOD_YEAR, $this->now);

        $this->assertSame('2022-01-01 00:00:00', $start->format('Y-m-d H:i:s'));
    }

    public function testGetWindowStartForWeekIsMondayMidnight()
    {
        $start = $this->calculator->getWindowStart(PostFrequencyCalculator::PERIOD_WEEK, $this->now);

        $this->assertSame('1', $start->format('N'), 'Window start must be a Monday');
        $this->assertSame('00:00:00', $start->format('H:i:s'));

        $keys = $this->calculator->getBucketKeys(PostFrequencyCalculator::PERIOD_WEEK, $this->now);
        $this->assertSame(reset($keys), $start->format('o-W'));
    }

    public function testGetWindowEndIsEndOfNowDay()
    {
        $end = $this->calculator->getWindowEnd($this->now);

        $this->assertSame('2026-08-23 23:59:59', $end->format('Y-m-d H:i:s'));
    }

    public function testGetSqlDateFormatMapsEveryValidPeriod()
    {
        $this->assertSame('%Y-%m-%d', $this->calculator->getSqlDateFormat(PostFrequencyCalculator::PERIOD_DAY));
        $this->assertSame('%x-%v', $this->calculator->getSqlDateFormat(PostFrequencyCalculator::PERIOD_WEEK));
        $this->assertSame('%Y-%m', $this->calculator->getSqlDateFormat(PostFrequencyCalculator::PERIOD_MONTH));
        $this->assertSame('%Y', $this->calculator->getSqlDateFormat(PostFrequencyCalculator::PERIOD_YEAR));
    }
}
```

- [ ] **Step 2: Run the tests to verify they fail**

Run: `vendor/bin/phpunit src/Volley/FaceBundle/Tests/Service/PostFrequencyCalculatorTest.php`
Expected: FAIL / ERROR — `Volley\FaceBundle\Service\PostFrequencyCalculator` not found.

(If `vendor/bin/phpunit` isn't present in your environment, run `composer install` first — this plan assumes a normal dev checkout with dependencies installed, unlike the bare exploration sandbox this plan was written against.)

- [ ] **Step 3: Write the implementation**

Create `src/Volley/FaceBundle/Service/PostFrequencyCalculator.php`:

```php
<?php

namespace Volley\FaceBundle\Service;

class PostFrequencyCalculator
{
    const PERIOD_DAY = 'day';
    const PERIOD_WEEK = 'week';
    const PERIOD_MONTH = 'month';
    const PERIOD_YEAR = 'year';

    private static $bucketCounts = array(
        self::PERIOD_DAY => 30,
        self::PERIOD_WEEK => 12,
        self::PERIOD_MONTH => 12,
        self::PERIOD_YEAR => 5,
    );

    private static $bucketKeyFormats = array(
        self::PERIOD_DAY => 'Y-m-d',
        self::PERIOD_WEEK => 'o-W',
        self::PERIOD_MONTH => 'Y-m',
        self::PERIOD_YEAR => 'Y',
    );

    private static $sqlDateFormats = array(
        self::PERIOD_DAY => '%Y-%m-%d',
        self::PERIOD_WEEK => '%x-%v',
        self::PERIOD_MONTH => '%Y-%m',
        self::PERIOD_YEAR => '%Y',
    );

    public static function getValidPeriods()
    {
        return array_keys(self::$bucketCounts);
    }

    public function isValidPeriod($period)
    {
        return in_array($period, self::getValidPeriods(), true);
    }

    public function getSqlDateFormat($period)
    {
        return self::$sqlDateFormats[$period];
    }

    /**
     * Ordered oldest-to-newest list of bucket keys for the fixed window of the given period, ending at $now.
     *
     * @return string[]
     */
    public function getBucketKeys($period, \DateTime $now)
    {
        $count = self::$bucketCounts[$period];
        $keys = array();

        for ($i = $count - 1; $i >= 0; $i--) {
            $keys[] = $this->formatBucketKey($period, $this->shiftDate($period, $now, -$i));
        }

        return $keys;
    }

    public function formatLabel($period, $bucketKey)
    {
        switch ($period) {
            case self::PERIOD_DAY:
                return \DateTime::createFromFormat('Y-m-d', $bucketKey)->format('M j');
            case self::PERIOD_WEEK:
                list($isoYear, $isoWeek) = explode('-', $bucketKey);
                $monday = new \DateTime();
                $monday->setISODate((int) $isoYear, (int) $isoWeek, 1);

                return $monday->format('M j');
            case self::PERIOD_MONTH:
                return \DateTime::createFromFormat('Y-m-d', $bucketKey . '-01')->format('M Y');
            case self::PERIOD_YEAR:
                return $bucketKey;
            default:
                return $bucketKey;
        }
    }

    /**
     * @param array $countsByBucket Map of bucket key => raw count, as returned by
     *                               PostRepository::countGroupedByPeriod().
     *
     * @return array[] Ordered list of ['label' => string, 'count' => int]
     */
    public function buildSeries($period, array $countsByBucket, \DateTime $now)
    {
        $series = array();

        foreach ($this->getBucketKeys($period, $now) as $key) {
            $series[] = array(
                'label' => $this->formatLabel($period, $key),
                'count' => isset($countsByBucket[$key]) ? (int) $countsByBucket[$key] : 0,
            );
        }

        return $series;
    }

    public function getWindowStart($period, \DateTime $now)
    {
        $date = $this->shiftDate($period, $now, -(self::$bucketCounts[$period] - 1));

        switch ($period) {
            case self::PERIOD_WEEK:
                $date->modify('monday this week');
                break;
        }

        $date->setTime(0, 0, 0);

        return $date;
    }

    public function getWindowEnd(\DateTime $now)
    {
        $date = clone $now;
        $date->setTime(23, 59, 59);

        return $date;
    }

    private function shiftDate($period, \DateTime $now, $offset)
    {
        $date = clone $now;

        switch ($period) {
            case self::PERIOD_DAY:
                $date->modify($offset . ' day');
                break;
            case self::PERIOD_WEEK:
                $date->modify(($offset * 7) . ' day');
                break;
            case self::PERIOD_MONTH:
                // Normalize to day 1 first so PHP's month-overflow quirk
                // (e.g. Mar 31 - 1 month => Mar 3, not Feb) can't happen.
                $date->setDate((int) $date->format('Y'), (int) $date->format('n'), 1);
                $date->modify($offset . ' month');
                break;
            case self::PERIOD_YEAR:
                $date->setDate((int) $date->format('Y') + $offset, 1, 1);
                break;
        }

        return $date;
    }

    private function formatBucketKey($period, \DateTime $date)
    {
        return $date->format(self::$bucketKeyFormats[$period]);
    }
}
```

- [ ] **Step 4: Run the tests to verify they pass**

Run: `vendor/bin/phpunit src/Volley/FaceBundle/Tests/Service/PostFrequencyCalculatorTest.php`
Expected: `OK (16 tests, ...)` — all pass. (A later review round added 4 more tests guarding against invalid `$period` values — see the note at the end of this task.)

- [ ] **Step 5: Commit**

```bash
git add src/Volley/FaceBundle/Service/PostFrequencyCalculator.php src/Volley/FaceBundle/Tests/Service/PostFrequencyCalculatorTest.php
git commit -m "feat: add PostFrequencyCalculator for post-per-period bucketing"
```

**Post-implementation fix (from code review):** the initial version did raw array lookups on `$period` in `getSqlDateFormat()`, `getBucketKeys()`, and `getWindowStart()` with no validation, meaning an invalid period would silently misbehave (undefined-index notice) or, via `formatLabel()`, throw an uncaught `Error`. The fix adds an `assertValidPeriod($period)` guard (throwing `\InvalidArgumentException`) at the top of those three methods, plus a class-level docblock stating that bucket keys passed into `formatLabel()`/`buildSeries()` must originate from `getBucketKeys()` for the same period. Four more tests were added asserting the exception is thrown for an invalid period — bringing the suite to 20 tests total. `AdminController::chartDataAction` (Task 3) is written to call `isValidPeriod()` and fall back to the default period BEFORE calling any of these methods, so the guard is never actually triggered in normal operation — it exists purely as defense-in-depth for this class's public contract.

**Known limitation, not yet resolved:** `composer.json` has no `require-dev` entry for `phpunit/phpunit` at all, so `vendor/bin/phpunit` (referenced above) will not exist even after a full `composer install` in this project as currently configured. The tests above were written correctly and verified against the real implementation using a standalone PHPUnit toolchain outside this repo, but there is currently no in-repo command that runs them. Before relying on this test suite for regression protection, add a `require-dev` entry (e.g. `"phpunit/phpunit": "^6.5"`, compatible with the PHP 7.2 runtime observed in this environment) and confirm `vendor/bin/phpunit src/Volley/FaceBundle/Tests/Service/PostFrequencyCalculatorTest.php` actually runs.

---

## Task 2: `PostRepository::countGroupedByPeriod`

**Files:**
- Modify: `src/Volley/FaceBundle/Entity/PostRepository.php`

This is a thin native-SQL aggregate query. It can't be meaningfully unit-tested without a real database (there's no test-DB harness in this project — see the "Important context" section above), so it's verified manually in Task 4's manual verification step instead of with an automated test. Keep the method small and obviously correct so that manual review is enough.

- [ ] **Step 1: Add the method**

Edit `src/Volley/FaceBundle/Entity/PostRepository.php`, adding this method inside the `PostRepository` class (after `findWithOptions`, before the closing `}` on line 50):

```php

    /**
     * Returns post counts grouped by a DATE_FORMAT() bucket, keyed by the formatted bucket string.
     *
     * @param string    $sqlDateFormat MySQL DATE_FORMAT() pattern, e.g. '%Y-%m-%d'
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return array Map of bucket string => integer count
     */
    public function countGroupedByPeriod($sqlDateFormat, \DateTime $from, \DateTime $to)
    {
        $connection = $this->getEntityManager()->getConnection();
        $tableName = $connection->quoteIdentifier($this->getClassMetadata()->getTableName());

        $sql = sprintf(
            'SELECT DATE_FORMAT(created, :format) AS bucket, COUNT(id) AS cnt
             FROM %s
             WHERE created BETWEEN :from AND :to
             GROUP BY bucket',
            $tableName
        );

        $statement = $connection->prepare($sql);
        $statement->bindValue('format', $sqlDateFormat);
        $statement->bindValue('from', $from->format('Y-m-d H:i:s'));
        $statement->bindValue('to', $to->format('Y-m-d H:i:s'));
        $statement->execute();

        $counts = array();
        foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $counts[$row['bucket']] = (int) $row['cnt'];
        }

        return $counts;
    }
```

- [ ] **Step 2: Sanity-check with a manual query**

This step just confirms the `created`/`id` column names assumed above are correct in your actual database before wiring up the controller. First find the real table name (don't assume it's `Post` — see the naming-strategy correction in "Important context" above):

```bash
php app/console doctrine:mapping:info
```

Then run (adjust connection details and the table name to match your local `app/config/parameters.yml` / mapping info output — it will most likely be `post`, lowercase):

```bash
mysql -u<user> -p<password> <database> -e "SELECT DATE_FORMAT(created, '%Y-%m-%d') AS bucket, COUNT(id) AS cnt FROM post GROUP BY bucket ORDER BY bucket DESC LIMIT 5;"
```

Expected: a small table of recent dates and counts, with no SQL error. If you get "Unknown column" or "Table doesn't exist", re-check the table/column names against your schema (`SHOW CREATE TABLE post;`) — but note the implementation in Step 1 doesn't hardcode the table name at all (it derives it from `getClassMetadata()->getTableName()`), so a mismatch here would only affect this manual sanity-check command, not the actual repository method.

- [ ] **Step 3: Commit**

```bash
git add src/Volley/FaceBundle/Entity/PostRepository.php
git commit -m "feat: add PostRepository::countGroupedByPeriod aggregate query"
```

---

## Task 3: `AdminController::chartDataAction` + route

**Files:**
- Modify: `src/Volley/FaceBundle/Controller/AdminController.php`
- Modify: `src/Volley/FaceBundle/Resources/config/routing/admin.yml`

- [ ] **Step 1: Add the route**

Edit `src/Volley/FaceBundle/Resources/config/routing/admin.yml`, replacing its full contents with:

```yaml
volley_face_admin_homepage:
    pattern:  /
    defaults: { _controller: VolleyFaceBundle:Admin:index }

volley_face_admin_chart_data:
    pattern:  /posts-chart-data
    defaults: { _controller: VolleyFaceBundle:Admin:chartData }
```

This is imported with `prefix: /admin` from `src/Volley/FaceBundle/Resources/config/routing.yml:1-4`, so the final URL is `/admin/posts-chart-data`, which is already covered by the `{ path: ^/admin/, role: ROLE_ADMIN }` rule in `app/config/security.yml:59`.

- [ ] **Step 2: Add the controller action**

Edit `src/Volley/FaceBundle/Controller/AdminController.php`, replacing its full contents with:

```php
<?php

namespace Volley\FaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Volley\FaceBundle\Service\PostFrequencyCalculator;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('VolleyFaceBundle:Admin:index.html.twig');
    }

    public function chartDataAction(Request $request)
    {
        $calculator = new PostFrequencyCalculator();

        $period = $request->query->get('period', PostFrequencyCalculator::PERIOD_DAY);
        if (!$calculator->isValidPeriod($period)) {
            $period = PostFrequencyCalculator::PERIOD_DAY;
        }

        $now = new \DateTime();
        $from = $calculator->getWindowStart($period, $now);
        $to = $calculator->getWindowEnd($now);

        $repository = $this->getDoctrine()->getRepository('VolleyFaceBundle:Post');
        $counts = $repository->countGroupedByPeriod($calculator->getSqlDateFormat($period), $from, $to);

        $series = $calculator->buildSeries($period, $counts, $now);

        return new JsonResponse(array(
            'period' => $period,
            'series' => $series,
        ));
    }
}
```

- [ ] **Step 3: Clear the routing/config cache**

Run: `php app/console cache:clear --env=dev`
Expected: exits 0 with no errors (this app doesn't have `parameters.yml`/`vendor/` set up in every environment — if this fails because of missing dependencies, follow your project's normal setup steps first; that's outside this feature's scope).

- [ ] **Step 4: Manually verify the endpoint**

With the dev server running (e.g. `php app/console server:run` or your usual local setup) and logged in as a `ROLE_ADMIN` user:

```bash
curl -s "http://localhost:8000/admin/posts-chart-data?period=month" -H "Cookie: <your session cookie>"
```

Expected: a JSON body like:

```json
{"period":"month","series":[{"label":"Sep 2025","count":0},"...","{"label":"Aug 2026","count":3}]}
```

with exactly 12 entries for `period=month`, 30 for `period=day`, 12 for `period=week`, 5 for `period=year`, and an unknown `period` value (e.g. `period=bogus`) falling back to the 30-entry `day` series.

- [ ] **Step 5: Commit**

```bash
git add src/Volley/FaceBundle/Controller/AdminController.php src/Volley/FaceBundle/Resources/config/routing/admin.yml
git commit -m "feat: add /admin/posts-chart-data JSON endpoint"
```

---

## Task 4: Dashboard UI — Twig, CSS, JS

**Files:**
- Modify: `src/Volley/FaceBundle/Resources/views/Admin/index.html.twig`
- Create: `src/Volley/FaceBundle/Resources/public/css/dashboard-chart.css`
- Create: `src/Volley/FaceBundle/Resources/public/js/custom/dashboard-chart.js`

- [ ] **Step 1: Update the dashboard template**

Replace the full contents of `src/Volley/FaceBundle/Resources/views/Admin/index.html.twig` with:

```twig
{% extends '::admin.html.twig' %}

{% block title %}Dashboard{% endblock %}

{% block styles %}
    {{ parent() }}
    <link rel="stylesheet" href="{{ asset('bundles/volleyface/css/dashboard-chart.css') }}" type="text/css"/>
{% endblock %}

{% block body %}
    <h1 class="page-header">Posts per period</h1>

    <div class="chart-filters" data-chart-filters>
        <button type="button" class="btn btn-default active" data-period="day">Day</button>
        <button type="button" class="btn btn-default" data-period="week">Week</button>
        <button type="button" class="btn btn-default" data-period="month">Month</button>
        <button type="button" class="btn btn-default" data-period="year">Year</button>
    </div>

    <div id="post-frequency-chart" data-chart-url="{{ path('volley_face_admin_chart_data') }}"></div>
{% endblock %}

{% block scripts %}
    {{ parent() }}
    <script type="text/javascript" src="{{ asset('bundles/volleyface/js/custom/dashboard-chart.js') }}"></script>
{% endblock %}
```

- [ ] **Step 2: Add the chart CSS**

Create `src/Volley/FaceBundle/Resources/public/css/dashboard-chart.css`:

```css
.chart-filters {
    margin-bottom: 15px;
}

.chart-filters .btn.active {
    background-color: #337ab7;
    border-color: #2e6da4;
    color: #fff;
}

#post-frequency-chart {
    max-width: 900px;
}

.post-frequency-chart-svg {
    width: 100%;
    height: auto;
}

.post-frequency-bar rect {
    fill: #337ab7;
}

.post-frequency-bar rect:hover {
    fill: #23527c;
}

.post-frequency-bar-label {
    font-size: 9px;
    fill: #666;
}
```

- [ ] **Step 3: Add the chart JS**

Create `src/Volley/FaceBundle/Resources/public/js/custom/dashboard-chart.js`:

```javascript
(function () {
    var container = document.getElementById('post-frequency-chart');
    if (!container) {
        return;
    }

    var chartUrl = container.getAttribute('data-chart-url');
    var filterButtons = document.querySelectorAll('[data-chart-filters] button');

    function renderChart(series) {
        var maxCount = 1;
        for (var i = 0; i < series.length; i++) {
            if (series[i].count > maxCount) {
                maxCount = series[i].count;
            }
        }

        var width = 900;
        var height = 260;
        var barGap = 4;
        var barWidth = (width / series.length) - barGap;

        var svg = '<svg viewBox="0 0 ' + width + ' ' + (height + 20) + '" preserveAspectRatio="xMinYMin meet" class="post-frequency-chart-svg">';

        for (var j = 0; j < series.length; j++) {
            var point = series[j];
            var barHeight = Math.round((point.count / maxCount) * height);
            var x = j * (barWidth + barGap);
            var y = height - barHeight;

            svg += '<g class="post-frequency-bar">';
            svg += '<rect x="' + x + '" y="' + y + '" width="' + barWidth + '" height="' + barHeight + '">';
            svg += '<title>' + point.label + ': ' + point.count + '</title>';
            svg += '</rect>';
            svg += '<text x="' + (x + barWidth / 2) + '" y="' + (height + 14) + '" text-anchor="middle" class="post-frequency-bar-label">' + point.label + '</text>';
            svg += '</g>';
        }

        svg += '</svg>';

        container.innerHTML = svg;
    }

    function loadChart(period) {
        fetch(chartUrl + '?period=' + encodeURIComponent(period))
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Unexpected response status: ' + response.status);
                }
                return response.json();
            })
            .then(function (data) {
                renderChart(data.series);
            })
            .catch(function () {
                container.textContent = 'Failed to load chart data.';
            });
    }

    for (var k = 0; k < filterButtons.length; k++) {
        filterButtons[k].addEventListener('click', function (event) {
            for (var m = 0; m < filterButtons.length; m++) {
                filterButtons[m].classList.remove('active');
            }
            event.target.classList.add('active');
            loadChart(event.target.getAttribute('data-period'));
        });
    }

    loadChart('day');
})();
```

- [ ] **Step 4: Expose the new bundle assets**

Run: `php app/console assets:install web --symlink`
Expected: exits 0. This regenerates `web/bundles/volleyface/`, including the new `css/dashboard-chart.css` and `js/custom/dashboard-chart.js` files (same mechanism that already exposes `web/bundles/volleyface/images/favicon.ico`, referenced in `app/Resources/views/admin.html.twig:22`).

If your deploy process copies assets instead of symlinking, drop `--symlink`.

- [ ] **Step 5: Manually verify in the browser**

1. Start the dev server (`php app/console server:run` or your usual local setup).
2. Log in as a `ROLE_ADMIN` user and visit `/admin`.
3. Confirm a bar chart renders under "Posts per period" with one bar per day for the last 30 days, and that days with no posts show a zero-height bar rather than being skipped.
4. Click "Week", "Month", and "Year" in turn; confirm the chart re-renders each time with the corresponding bucket count (12, 12, 5) and the clicked button gets the `active` style while the others lose it.
5. Hover a bar; confirm the `<title>` tooltip shows `"<label>: <count>"`.
6. Open browser dev tools → Network tab and confirm each filter click fires a `GET /admin/posts-chart-data?period=...` request returning `200` with the expected JSON shape.

- [ ] **Step 6: Commit**

```bash
git add src/Volley/FaceBundle/Resources/views/Admin/index.html.twig src/Volley/FaceBundle/Resources/public/css/dashboard-chart.css src/Volley/FaceBundle/Resources/public/js/custom/dashboard-chart.js
git commit -m "feat: render post-frequency chart with day/week/month/year filters on admin dashboard"
```

**Post-implementation fix (from code review):** the `loadChart()` JS code block above already reflects a fix made during review — the original version had no `response.ok` check and no `.catch()`, so a 500 from the endpoint, an expired-session HTML login-page redirect, or a network failure would leave the chart silently blank with only a console error. The fixed version throws on a non-ok response and shows a plain-text `"Failed to load chart data."` fallback in the chart container on any failure. This was a separate commit (`fix: handle failed fetch/non-ok responses in dashboard chart loader`) on top of the one above.

---

## Self-review notes

- **Spec coverage:** chart of posts-per-day on `/admin` ✅ (Task 4), filter by day/week/month/year ✅ (Task 1 buckets + Task 3 `period` query param + Task 4 buttons).
- **No DB test harness exists** in this project (see "Important context"), so Task 2/3's DB-touching code is covered by manual verification steps instead of fabricated automated tests that would need infrastructure this repo doesn't have.
- **No new front-end dependency** was introduced, avoiding a `bower install`/network dependency this sandboxed plan-writing session couldn't verify would succeed.
