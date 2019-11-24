<?php

namespace App\Utils;

use DateTime;
use Exception;

class DateTimeUtil
{
    /**
     * @param string $time
     * @return DateTime
     * @throws Exception
     */
    public function getCurrentDataTime($time = 'now')
    {
        return new DateTime($time);
    }
}
