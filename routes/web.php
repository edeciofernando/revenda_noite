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

//Route::get('/', function () {
//    return view('index');
//});

Route::resource('carros', 'CarroController');
Route::get('carrosfoto/{id}', 'CarroController@foto')
        ->name('carros.foto');
Route::post('carrosfotostore', 'CarroController@storeFoto')
        ->name('carros.store.foto');
Route::get('carrospesq', 'CarroController@pesq')
        ->name('carros.pesq');
Route::post('carrosfiltros', 'CarroController@filtros')
        ->name('carros.filtros');
Route::get('carrosgraf', 'CarroController@graf')
        ->name('carros.graf');

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('register', function() {
    return "<h1> Acesso Restrito </h1>";  
});
