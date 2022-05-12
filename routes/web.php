<?php

use App\Http\Controllers\GSMController;
use App\Http\Controllers\TransfertController;
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

Route::get('/store', [TransfertController::class, 'store']);
Route::get('/make', [TransfertController::class, 'make']);

Route::get('/', function ()
{
    $url = GSMController::gsmURL().urlencode('*414*41253#');
    dd(file_get_contents($url));
    dd($url);
});
