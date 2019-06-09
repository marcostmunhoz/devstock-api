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
Route::get('/check-token/{token}', 'Auth\LoginController@checkToken')->name('check-token');

// Grupo de rotas que exigem login do usuário
Route::group([ 'middleware' => [ 'jwt.auth' ] ], function() {
    // Rotas para a tela de usuários
    Route::get('/usuarios', 'UsuarioController@showAll');
    Route::get('/usuarios/{q}', 'UsuarioController@searchLike');
    Route::get('/usuario/{id}', 'UsuarioController@show');
    Route::post('/usuario', 'UsuarioController@create');
    Route::put('/usuario/{id}', 'UsuarioController@update');
    Route::delete('/usuario/{id}', 'UsuarioController@delete');
    Route::get('/usuario/resetar-senha/{id}', 'UsuarioController@resetPassword');
    Route::put('/usuario/alterar-senha/{id}', 'UsuarioController@changePassword');

    // Rotas para a tela de produtos
    Route::get('/produtos', 'ProdutoController@showAll');
    Route::get('/produtos/{q}', 'ProdutoController@searchLike');
    Route::get('/produto/{id}', 'ProdutoController@show');
    Route::post('/produto', 'ProdutoController@create');
    Route::put('/produto/{id}', 'ProdutoController@update');
    Route::delete('/produto/{id}', 'ProdutoController@delete');

    // Rotas para a tela de fornecedores
    Route::get('/fornecedores', 'FornecedorController@showAll');
    Route::get('/fornecedores/{q}', 'FornecedorController@searchLike');
    Route::get('/fornecedor/{id}', 'FornecedorController@show');
    Route::post('/fornecedor', 'FornecedorController@create');
    Route::put('/fornecedor/{id}', 'FornecedorController@update');
    Route::delete('/fornecedor/{id}', 'FornecedorController@delete');

    //Rotas para a tela de estoque
    Route::group([ 'prefix' => '/estoque' ], function() {
        Route::post('/', 'EstoqueController@listarMovimentacoes');
        Route::post('/realizar-movimentacao', 'EstoqueController@realizarMovimentacao')->name('realizar-movimentacao');
    });
});