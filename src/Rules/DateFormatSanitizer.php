<?php

namespace Truongwp\Sanitizer\Rules;

use Truongwp\Sanitizer\Contracts\RuleSanitizer;

class DateFormatSanitizer implements RuleSanitizer
{
    /**
     * Sanitize value.
     *
     * @param mixed $value Value need to sanitize.
     * @return mixed
     */
    public function sanitize($value)
    {
        $args = func_get_args();
        $format = empty($args[1]) ? 'Y-m-d' : $args[1];

        $timestamp = strtotime($value);
        return date($format, $timestamp);
    }
}
