<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Solde;
use App\Models\Transfert;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getNavItems()
    {
        return  [
            ['libelle' => 'Les transferts d\'aujourd\'hui', 'link' => route('transferts')],
            ['libelle' => 'Tous les transferts', 'link' => route('transferts.all')],
            ['libelle' => 'Historique des soldes', 'link' => route('soldes')],
            ['libelle' => 'Configurations', 'link' => route('configuration')],
        ];
    }

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
            'resetTransfert' => true,
            'colors' => $this->getColors(),
        ]);
    }

    public function allTransferts()
    {
        return view('pages.all-transferts', [
            'transferts' => Transfert::orderBy('created_at', 'desc')->get(),
            'colors' => $this->getColors(),
        ]);
    }

    public function getColors()
    {
        return ['text-warning',
                'text-success',
                'text-danger',
                'text-primary',
                'text-danger',
                'bg-secondary'
        ];
    }

    public function soldes()
    {
        $soldes = Solde::orderBy('id', 'desc')->get();
        return view('pages.soldes', [
            'soldes' => $soldes
        ]);
    }
}
