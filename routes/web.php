<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\FormatMessage;
use App\Http\Controllers\GSMController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransfertController;
use App\Models\Transfert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/configuration', [HomeController::class, 'configuration'])->name('configuration');

Route::post('/settings/update', [SettingController::class, 'update'])->name('setting.update');

Route::get('/test', function () {

    $message = "Vous avez transfere 50 Fcfa de credit vers le numero 0150388646. Votre solde actuel est 5 250 Fcfa.Ref 02285979804";
    $reference = FormatMessage::getReference($message);
    $transfert = Transfert::find(15);
    
    $transfert->transfert_id = $transfert->id;
    $transfert->reference = $reference;
    $transfert->sms = $message;

    $response = APIController::post(SettingController::smsStorage(), $transfert->toArray());

    dd($response);

    return $transfert->toArray();
});
