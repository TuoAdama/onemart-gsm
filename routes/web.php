<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\FormatMessage;
use App\Http\Controllers\GSMController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SoldeController;
use App\Http\Controllers\TransfertController;
use App\Models\OperationMessage;
use App\Models\Solde;
use App\Models\Transfert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/configuration', [HomeController::class, 'configuration'])->name('configuration');
Route::get('/transferts', [HomeController::class, 'transferts'])->name('transferts');
Route::get('/soldes', [HomeController::class, 'soldes'])->name('soldes');
Route::post('/settings/update', [SettingController::class, 'update'])->name('setting.update');