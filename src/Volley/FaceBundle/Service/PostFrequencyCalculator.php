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
