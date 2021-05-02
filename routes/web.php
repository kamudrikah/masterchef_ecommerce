<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LandingController::class, 'index'])->name('home');

Route::prefix('payment')->group(function () {
    Route::post('pay', [PaymentController::class, 'pay'])->name('make_payment');
    Route::post('checkout', [StripeController::class, 'checkout'])->name('checkout');
});

// Route::prefix('admin')->group(function () {
//     Route::resource('user', [UserController::class]);
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
