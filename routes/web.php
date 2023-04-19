<?php

use App\Http\Controllers\SubscriberController;
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

Route::get('/', function () {
    return redirect('/subscriber');
});

Route::resource('/subscriber', SubscriberController::class, [
    'except' => ['show']
]);

Route::get('/subscriber/list', [SubscriberController::class, 'list']);
