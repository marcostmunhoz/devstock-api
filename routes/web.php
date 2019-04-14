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

// Rotas para registro e login
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register-form');
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login-form');
Route::post('/login', 'Auth\LoginController@login')->name('login');

// Grupo de rotas que exigem login do usuário
Route::group([ 'middleware' => [ 'auth' ] ], function() {
    // Rota para logout do usuário logado
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/usuarios', 'UsuarioController@showAll')->name('exibir-todos-usuarios');
    Route::get('/usuarios/{id}', 'UsuarioController@show')->name('exibir-usuario');
    Route::put('/usuarios/{id}', 'UsuarioController@update')->name('editar-usuario');
    Route::delete('/usuarios/{id}', 'UsuarioController@delete')->name('excluir-usuario');

    Route::get('/produtos', 'ProdutoController@showAll')->name('exibir-todos-produtos');
    Route::get('/produtos/{id}', 'ProdutoController@show')->name('exibir-produto');
    Route::post('/produtos', 'ProdutoController@create')->name('cadastrar-produto');
    Route::put('/produtos/{id}', 'ProdutoController@update')->name('editar-produto');
    Route::delete('/produtos/{id}', 'ProdutoController@delete')->name('excluir-produto');
});