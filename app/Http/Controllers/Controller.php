<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Get active theme name
     */
    public static function getActiveTheme()
    {
        return config('theme.active', 'default');
    }
}
