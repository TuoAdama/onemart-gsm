<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public $settings = [
        'gsmURL' => [
            'display' => 'GSM URL',
            'value' => 'http://192.168.5.150/cgi/WebCGI?1500102=account=apiuser&password=apipass&port=1&content='
        ],
        'smsStorage' => [
            'display' => 'SMS STORAGE',
            'value' => 'http://localhost:8000/api/AddTransfertAndroid'
        ],
        'soldeURL' => [
            'display' => 'STORAGE SOLDE',
            'value' => 'http://localhost:8000/api/solde/store'
        ],
        'appOnlineURL' => [
            'display' => 'APP ONLINE URL',
            'value' => 'https://www.onemart.tel/api/gsmlist'
        ],
        'syntaxeSoldeURL' => [
            'display' => 'Get syntaxe URL',
            'value' => 'http://localhost:8000/api/soldeSyntaxe'
        ],
        'syntaxeTransfertURL' => [
            'display' => 'Transfert syntaxe URL',
            'value' => 'http://localhost:8000/api/transfertCabineSyntaxe'
        ],
        'executionTimeMakeTransfert' => [
            'display' => 'Make transfert time execution',
            'value' => '5000'
        ],
        'executionTimeStoreTransfert' => [
            'display' => 'Get transfert time execution',
            'value' => '10000'
        ],
    ];

    public function run()
    {
        foreach ($this->settings as $k => $v) {
            $set = Setting::where('key', $k)->first();
            if($set == null){
                Setting::create([
                    'key' => $k,
                    'display' => $v['display'],
                    'value' => $v['value'],
                ]);
            }
        }
    }
}
