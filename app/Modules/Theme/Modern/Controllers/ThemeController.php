<?php

namespace App\Modules\Theme\Modern\Controllers;

use App\Http\Controllers\Controller;

class ThemeController extends Controller
{
    public function home()
    {
        return view('Modules.Theme.Modern.Views.home');
    }
}
