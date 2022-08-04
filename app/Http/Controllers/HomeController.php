<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Solde;
use App\Models\Transfert;
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

    public function transferts()
    {
        $transferts = Transfert::orderBy('updated_at', 'desc')
        ->whereDate('created_at', date('Y-m-d'))
        ->get();

        return view('pages.transferts', [
            'setting' => Setting::where('key', 'number_of_failed')->first(),
            'transferts' => $transferts,
            'colors' => ['text-warning', 'text-success', 'text-danger', 'text-primary', 'text-danger']
        ]);
    }

    public function soldes()
    {
        $soldes = Solde::orderBy('id', 'desc')->get();
        return view('pages.soldes', [
            'soldes' => $soldes
        ]);
    }
}
