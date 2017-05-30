<?php

namespace Truongwp\Sanitizer;

use Truongwp\Sanitizer\Contracts\RuleSanitizer;
use Truongwp\Sanitizer\Registries\SanitizerRegistry;
use Truongwp\Sanitizer\Rules\DateFormatSanitizer;

/**
 * Class Sanitizer
 *
 * @package Truongwp\Sanitizer
 */
class Sanitizer
{

    /**
     * Sanitizer constructor.
     */
    public function __construct()
    {
        // Register rule sanitizers.
        SanitizerRegistry::set('date_format', new DateFormatSanitizer());
    }

    /**
     * Sanitizes input data.
     *
     * @param array $input Array of input data.
     * @param array $rules Array of rules.
     * @return array Sanitized data.
     */
    public function sanitize($input, $rules)
    {
        foreach ($rules as $name => $rule) {
            if (!isset($input[$name])) {
                continue;
            }
            $input[$name] = $this->sanitizeField($input[$name], $rule);
        }

        return $input;
    }

    /**
     * Sanitize each input field.
     *
     * @param mixed        $value Field value.
     * @param array|string $rules Sanitize rules.
     * @return mixed
     */
    protected function sanitizeField($value, $rules)
    {
        $rules = is_array( $rules ) ? $rules : explode('|', $rules);

        foreach ($rules as $rule) {
            $ruleArgs = explode(':', $rule);
            $ruleName = array_shift($ruleArgs);
            array_unshift($ruleArgs, $value);

            if ($sanitizer = $this->getRuleSanitizer($ruleName)) {
                $value = call_user_func_array(array($sanitizer, 'sanitize'), $ruleArgs);
            } elseif (function_exists($ruleName)) {
                $value = call_user_func_array($ruleName, $ruleArgs);
            }
        }

        return $value;
    }

    /**
     * Get sanitizer instance for rule.
     *
     * @param string $ruleName Rule name.
     * @return RuleSanitizer|null
     */
    protected function getRuleSanitizer($ruleName)
    {
        return SanitizerRegistry::get($ruleName);
    }
}
