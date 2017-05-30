<?php
/**
 * Sanitizer registry class
 *
 * @package Truongwp/Sanitizer/Registries
 */

namespace Truongwp\Sanitizer\Registries;

use Truongwp\Sanitizer\Contracts\RuleSanitizer;

/**
 * Class SanitizerRegistry
 *
 * @package Truongwp\Sanitizer\Registries
 */
class SanitizerRegistry
{
    /**
     * Array of RuleSanitizer instance.
     *
     * @var array
     */
    protected static $sanitizers = array();

    /**
     * Set new rule sanitizer.
     *
     * @param string        $name      Rule name.
     * @param RuleSanitizer $sanitizer RuleSanitizer instance.
     */
    public static function set($name, RuleSanitizer $sanitizer)
    {
        self::$sanitizers[$name] = $sanitizer;
    }

    /**
     * Get RuleSanitizer instance for given rule.
     *
     * @param string $name Rule name.
     * @return RuleSanitizer|null
     */
    public static function get($name)
    {
        if (isset(self::$sanitizers[$name])) {
            return self::$sanitizers[$name];
        }

        return null;
    }
}
