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
        $transferts = Transfert::orderBy('created_at', 'desc')->paginate(10);

        return view('pages.transferts', [
            'transferts' => $transferts,
            'colors' => ['text-warning', 'text-success', 'text-danger', 'text-primary']
        ]);
    }

    public function soldes()
    {
        $soldes = Solde::orderBy('id', 'desc')->paginate(10);
        return view('pages.soldes', [
            'soldes' => $soldes
        ]);
    }
}
