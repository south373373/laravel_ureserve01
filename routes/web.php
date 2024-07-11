<?php

use Illuminate\Support\Facades\Route;
// 追記分
use App\Http\Controllers\LivewireTestController;
use App\Http\Controllers\AlpineTestController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;

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

Route::get('/', function () {
    // return view('welcome');
    return view('calendar');
});

// 今回はAPI通信を使用しないため以下をコメント。
// 
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });


// 追記分
// Gate設定用
Route::prefix('manager')
->middleware('can:manager-higher')
->group(function(){
    // 「/past」のパスが「events/{event}」のidと判定されるため、
    // 「/past」のパスを最上位に記載。
    Route::get('/events/past', [EventController::class, 'past'])->name('events.past');
    Route::resource('/events', EventController::class);
});

Route::middleware('can:user-higher')
->group(function(){
    Route::get('/dashboard', [ReservationController::class, 'dashboard'])->name('dashboard');
    Route::get('/{id}', [ReservationController::class, 'detail'])->name('events.detail');
    Route::post('/{id}', [ReservationController::class, 'reserve'])->name('events.reserve');
});

// 以下の記載でまとめて記載。
Route::controller(LivewireTestController::class)
->prefix('livewire-test')->name('livewire-test.')->group(function(){
    Route::get('index', 'index')->name('index');
    Route::get('register', 'register')->name('register');
});

// Alpine用
Route::get('alpine-test/index', [AlpineTestController::class, 'index']);