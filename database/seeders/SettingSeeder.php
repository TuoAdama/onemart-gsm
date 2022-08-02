<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public array $settings = [
        'host' => [
            'display' => "APPLI Distante",
            'value' => 'http://localhost:8000',
        ],
        'gsmURL' => [
            'display' => 'GSM URL',
            'value' => 'http://192.168.5.150/cgi/WebCGI?1500102=account=apiuser&password=apipass&port=1&content='
        ],
        'smsStorage' => [
            'display' => 'SMS STORAGE',
            'value' => '/api/AddTransfertAndroid'
        ],
        'soldeURL' => [
            'display' => 'STORAGE SOLDE',
            'value' => '/api/solde/store'
        ],
        'appOnlineURL' => [
            'display' => 'APP ONLINE URL',
            'value' => '/api/gsmlist'
        ],
        'syntaxeSoldeURL' => [
            'display' => 'Get syntaxe URL',
            'value' => '/api/soldeSyntaxe'
        ],
        'syntaxeTransfertURL' => [
            'display' => 'Transfert syntaxe URL',
            'value' => '/api/transfertCabineSyntaxe'
        ],
        'executionTimeMakeTransfert' => [
            'display' => 'Make transfert time execution',
            'value' => '5000'
        ],
        'executionTimeStoreTransfert' => [
            'display' => 'Get transfert time execution',
            'value' => '10000'
        ],
        'authentificationAPI' => [
            'display' => "Code d'authenfication API",
            'value' => 12345,
        ],
        'max_failed' => [
            'display' => 'Nombre de tentative',
            'value' => 5,
        ],
        'number_of_failed' => [
            'display' => "Nombre d'echec",
            'value' => 0,
        ],
        'check_solde_by_ussd' => [
            'display' => 'Verifier solde par ussd',
            'value' => 1,
        ],
        'notification_url' => [
            'display' => 'URL de notification',
            'value' => '/api/notification',
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
            }else{
                $set->display = $v['display'];
                $set->save();
            }
        }
    }

}
