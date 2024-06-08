<?php
include_once 'file-utils.php';
require_once FileUtils::normalizeFilePath(__DIR__ . '/../error-reporting.php');
include_once FileUtils::normalizeFilePath(__DIR__ . '/../default-time-zone.php');

class DateTimeUtils
{
    private $dateTime;

    public function __construct($time = 'now')
    {
        $this->dateTime = new DateTime($time);
    }

    public function getFDatetime()
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    public function getFDatetimeTz()
    {
        // return $this->dateTime->format('Y-m-d H:i:s') . ' (' . $this->dateTime->getTimezone()->getName() . ')';
        return $this->dateTime->format('Y-m-d H:i:s (e)');
    }

    public function printFDatetime()
    {
        echo $this->dateTime->format('Y-m-d H:i:s');
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
