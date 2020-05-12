<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// No_Accession Availability
Route::get('libra_e/no_accession_availability/{id}', 'ApiController@fetch_no_accession_availability');

// Egames current month usage
Route::get('libra_e/fetch_month_egames_usage', 'ApiController@fetch_month_egames_usage')
->name('libra_e.fetch_month_egames_usage');


// Attendance APi
Route::post('attendance', 'ApiController@attendance_scanner')
    ->name('attendance_api');




/*
//Admin
Route::post('admin/login', 'Admin\LoginController@login');
Route::post('admin/register', 'Admin\RegisterController@register');

Route::get('try/try', 'HomeController@try_api');
*/