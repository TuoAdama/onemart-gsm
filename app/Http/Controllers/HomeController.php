<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->to(route('configuration'));
    }

    public function configuration()
    {
        $settings = Setting::all();
        return view('pages.configurations', ['settings' => $settings]);
    }
}
