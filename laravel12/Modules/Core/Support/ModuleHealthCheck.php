<?php

namespace Modules\Core\Support;

/**
 * Provides a simple health check for the Modules namespace.
 *
 * This class exists only to confirm that Composer PSR-4 autoloading
 * for the Modules namespace is working correctly.
 *
 * Module: Core
 * Layer: Support
 */
class ModuleHealthCheck
{
    /**
     * Return a simple confirmation message.
     *
     * @return string
     */
    public static function message(): string
    {
        return 'Modules namespace is working.';
    }
}