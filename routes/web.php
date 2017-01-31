<?php

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

Route::get('/', 'Controller@getIndex');
Route::get('api/doctors', 'Controller@getDoctors');
Route::get('api/doctor/{doctor_id}/schedule', ['uses' => 'Controller@getSchedule']);
Route::get('api/schedule/{schedule_id}/consultations', ['uses' => 'Controller@getConsultations']);
Route::post('api/consultation/create', ['uses' => 'Controller@postConsultation']);

