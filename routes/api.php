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

// Rotas para registro e login
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register-form');
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login-form');
Route::post('/login', 'Auth\LoginController@login')->name('login');

// Grupo de rotas que exigem login do usuário
Route::group([ 'middleware' => [ 'jwt.auth' ] ], function() {
    // Rotas para a tela de usuários
    Route::get('/usuarios', 'UsuarioController@showAll');
    Route::get('/usuario/{id}', 'UsuarioController@show');
    Route::get('/usuarios/{q}', 'UsuarioController@searchLike');
    Route::put('/usuarios/{id}', 'UsuarioController@update');
    Route::delete('/usuarios/{id}', 'UsuarioController@delete');

    // Rotas para a tela de produtos
    Route::get('/produtos', 'ProdutoController@showAll');
    Route::get('/produto/{id}', 'ProdutoController@show');
    Route::get('/produtos/{q}', 'ProdutoController@searchLike');
    Route::post('/produtos', 'ProdutoController@create');
    Route::put('/produtos/{id}', 'ProdutoController@update');
    Route::delete('/produtos/{id}', 'ProdutoController@delete');

    // Rotas para a tela de fornecedores
    Route::get('/fornecedores', 'FornecedorController@showAll');
    Route::get('/fornecedor/{id}', 'FornecedorController@show');
    Route::get('/fornecedores/{q}', 'FornecedorController@searchLike');
    Route::post('/fornecedores', 'FornecedorController@create');
    Route::put('/fornecedores/{id}', 'FornecedorController@update');
    Route::delete('/fornecedores/{id}', 'FornecedorController@delete');

    //Rotas para a tela de estoque
    Route::group([ 'prefix' => '/estoque' ], function() {
        Route::get('/{id}', 'EstoqueController@listarMovimentacoes');
        Route::post('/realizar-movimentacao', 'EstoqueController@realizarMovimentacao')->name('realizar-movimentacao');
    });
});