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

Route::group(['middleware' => ['guest']], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Usuario']], function () {

        Route::get('/nota', 'NotaController@index');
        Route::get('/nota/buscarNota', 'NotaController@buscarNota');
        Route::post('/nota/registrar', 'NotaController@store');
        Route::get('/nota/listarNota', 'NotaController@listarNota');
        Route::put('/nota/actualizar', 'NotaController@modificarNota');
        Route::put('/nota/borrar', 'NotaController@borrarNota');
        
        Route::get('/categoria/buscarCategoria', 'CategoriaController@buscarCategoria');

    });
      
});