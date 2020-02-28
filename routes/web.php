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


Route::get('/', 'MuseumController@index')->name('index');
Route::post('/create', 'MuseumController@store')->name('store');
Route::post('/ajaxLoader', 'MuseumController@ajaxLoader')->name('ajaxLoader');
Route::post('/ajaxTimeLoader', 'MuseumController@ajaxTimeLoader')->name('ajaxTimeLoader');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
