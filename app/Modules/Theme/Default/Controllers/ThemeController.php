<?php

namespace App\Modules\Theme\Default\Controllers;

use App\Http\Controllers\Controller;

class ThemeController extends Controller
{
    public function home()
    {
        return view('Modules.Theme.Default.Views.home');
    }
}
