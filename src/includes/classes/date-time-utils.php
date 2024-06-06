<?php
class DateTimeUtils
{
    private $dateTime;

    public function __construct($time = 'now')
    {
        $this->dateTime = new DateTime($time, new DateTimeZone('Asia/Manila'));
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
