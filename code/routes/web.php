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
Auth::routes();

Route::get('/', 'SeedboxController@index')->name('home');
Route::get('/file-create', 'SeedboxController@fileCreate')->name('file-create');
Route::post('/file-create', 'SeedboxController@fileCreateSave')->name('file-create');