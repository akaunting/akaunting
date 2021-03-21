<?php

namespace Http\Message;

use Http\Message\Exception\UnexpectedValueException;

final class CookieUtil
{
    /**
     * Handles dates as defined by RFC 2616 section 3.3.1, and also some other
     * non-standard, but common formats.
     *
     * @var array
     */
    private static $dateFormats = [
        'D, d M y H:i:s T',
        'D, d M Y H:i:s T',
        'D, d-M-y H:i:s T',
        'D, d-M-Y H:i:s T',
        'D, d-m-y H:i:s T',
        'D, d-m-Y H:i:s T',
        'D M j G:i:s Y',
        'D M d H:i:s Y T',
    ];

    /**
     * @see https://github.com/symfony/symfony/blob/master/src/Symfony/Component/BrowserKit/Cookie.php
     *
     * @param string $dateValue
     *
     * @return \DateTime
     *
     * @throws UnexpectedValueException if we cannot parse the cookie date string
     */
    public static function parseDate($dateValue)
    {
        foreach (self::$dateFormats as $dateFormat) {
            if (false !== $date = \DateTime::createFromFormat($dateFormat, $dateValue, new \DateTimeZone('GMT'))) {
                return $date;
            }
        }

        // attempt a fallback for unusual formatting
        if (false !== $date = date_create($dateValue, new \DateTimeZone('GMT'))) {
            return $date;
        }

        throw new UnexpectedValueException(sprintf(
            'Unparseable cookie date string "%s"',
            $dateValue
        ));
    }
}
