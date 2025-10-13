<?php

namespace App\Modules\Plugin\SamplePlugin\Controllers;

use App\Http\Controllers\Controller;

class SamplePluginController extends Controller
{
    public function index()
    {
        return 'Sample Plugin Active';
    }
}
