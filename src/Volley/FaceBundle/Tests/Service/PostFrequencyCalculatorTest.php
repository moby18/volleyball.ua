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
