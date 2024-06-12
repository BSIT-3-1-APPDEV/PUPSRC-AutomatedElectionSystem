<?php
include_once 'file-utils.php';
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/../default-time-zone.php');

class DateTimeUtils
{
    protected $date_time;

    public function __construct($time = 'now')
    {
        $this->date_time = new DateTime($time);
    }

    public function getFDatetime()
    {
        return $this->date_time->format('Y-m-d H:i:s');
    }

    public function getFDatetimeTz()
    {
        // return $this->date_time->format('Y-m-d H:i:s') . ' (' . $this->date_time->getTimezone()->getName() . ')';
        return $this->date_time->format('Y-m-d H:i:s (e)');
    }

    public function printFDatetime()
    {
        echo $this->date_time->format('Y-m-d H:i:s');
    }

    public function printDatetimeTzJS()
    {
        echo '<script>
            const JS_DATE_TZ = () => {
                try {
                    const formattedDatetimeTz = "' . $this->getFDatetimeTz() . '";
                    return new Date(formattedDatetimeTz);
                } catch (error) {
                    console.error("Error occurred in time settings:", error);
                    return new Date();
                }
            };
        </script>';
    }
}

/**
 * Class TimeValidator
 * Extends the DateTimeUtils class to provide time validation functionality.
 */
class TimeValidator extends DateTimeUtils
{
    /**
     * Validate the given DateTime object against the current DateTime.
     *
     * @param DateTime $datetime The DateTime object to validate.
     * @return bool True if the time is valid, false otherwise.
     */
    public function validate($datetime)
    {
        $time = $datetime->format('H:i');
        $minTime = '00:00';
        $maxTime = '23:59';

        // Check if the date to be set is today
        if ($this->date_time->format('Y-m-d') === $datetime->format('Y-m-d')) {
            // Time to be set shall not be less than current time
            $minTime = $this->date_time->format('H:i');
        }

        if (!self::checkMin($time, $minTime)) {
            return false;
        }

        if (!self::checkMax($time)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given time is not less than the minimum time.
     *
     * @param string $time The time to check in 'H:i' format.
     * @param string $minTime The minimum time in 'H:i' format, defaults to '00:00'.
     * @return bool True if the time is not less than the minimum time, false otherwise.
     */
    public static function checkMin($time, $minTime = '00:00')
    {
        return $time >= $minTime;
    }

    /**
     * Check if the given time is not greater than the maximum time.
     *
     * @param string $time The time to check in 'H:i' format.
     * @param string $maxTime The maximum time in 'H:i' format, defaults to '23:59'.
     * @return bool True if the time is not greater than the maximum time, false otherwise.
     */
    public static function checkMax($time, $maxTime = '23:59')
    {
        return $time <= $maxTime;
    }
}


/**
 * Class TimeValidator
 * Extends the DateTimeUtils class to provide time validation functionality.
 */
class DateValidator extends DateTimeUtils
{

    public function validate($datetime)
    {
        $maxDate = clone $this->date_time;
        $maxDate->modify('+5 years');
        $maxDate->setDate($maxDate->format('Y'), 12, 31);

        $isMin = self::checkMin($datetime->format('Y-m-d'), $this->date_time->format('Y-m-d'));
        $isMax = self::checkMax($datetime->format('Y-m-d'), $maxDate);

        return $isMin && $isMax;
    }


    public static function checkMin($date, $minDate)
    {
        return $date >= $minDate;
    }


    public static function checkMax($date, $maxDate)
    {
        return $date <= $maxDate;
    }
}
