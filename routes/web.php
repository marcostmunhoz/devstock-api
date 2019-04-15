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

    // Rotas para a tela de usuários
    Route::get('/usuarios', 'UsuarioController@showAll');
    Route::get('/usuarios/{id}', 'UsuarioController@show');
    Route::put('/usuarios/{id}', 'UsuarioController@update');
    Route::delete('/usuarios/{id}', 'UsuarioController@delete');

    // Rotas para a tela de produtos
    Route::get('/produtos', 'ProdutoController@showAll');
    Route::get('/produtos/{id}', 'ProdutoController@show');
    Route::post('/produtos', 'ProdutoController@create');
    Route::put('/produtos/{id}', 'ProdutoController@update');
    Route::delete('/produtos/{id}', 'ProdutoController@delete');

    // Rotas para a tela de fornecedores
    Route::get('/fornecedores', 'FornecedorController@showAll');
    Route::get('/fornecedores/{id}', 'FornecedorController@show');
    Route::post('/fornecedores', 'FornecedorController@create');
    Route::put('/fornecedores/{id}', 'FornecedorController@update');
    Route::delete('/fornecedores/{id}', 'FornecedorController@delete');

    //Rotas para a tela de estoque
    Route::group([ 'prefix' => '/estoque' ], function() {
        Route::get('/{id}', 'EstoqueController@listarMovimentacoes');
        Route::post('/realizar-movimentacao', 'EstoqueController@realizarMovimentacao');
    });
});