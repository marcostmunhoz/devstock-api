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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register-form');
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login-form');
Route::post('/login', 'Auth\LoginController@login')->name('login');