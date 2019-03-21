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

    Route::group(['middleware' => ['Bodeguero']], function () {
        Route::get('/categoria', 'CategoriaController@index');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        
        Route::get('/articulo', 'ArticuloController@index');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
    });

    Route::group(['middleware' => ['Vendedor']], function () {
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
    });

    Route::group(['middleware' => ['Administrador']], function () {
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');

        Route::get('/categoria', 'CategoriaController@index');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        
        Route::get('/articulo', 'ArticuloController@index');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');
        
        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/activar', 'UserController@activar');
        Route::put('/user/desactivar', 'UserController@desactivar');  
    });
      
});

// Route::get('/home', 'HomeController@index')->name('home');