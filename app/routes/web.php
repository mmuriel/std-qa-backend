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

/*
Route::get('/', function () {
    return view('welcome');
});
*/



Route::get('/', "\Siba\QA\WebApp\Controllers\QaController@index");
Route::get('/evento/{id}', "\Siba\QA\Evento\Controllers\EventoWebController@index");
Route::post('/reporte',"\Siba\QA\Reporte\Controllers\ReporteWebController@add");
//Route::get('/login', "\Siba\QA\Evento\Controllers\EventoWebController@index");
//Route::post('/reporte/{id}', "\Siba\QA\Reporte\Controllers\ReporteWebController@add");




Route::get('/mm/{num}', function ($num) {
    return ("MMMMM".$num);
});

Route::get('/test/error', function () {
    
    return view('error');

});


/*

	Route to test with phpunit

*/

Route::get('/test/phpunit', function () {
    
    return view('welcome');

});	

Route::get('/test/info',function(){

	return phpinfo();

});

//Registra todas las rutas relacionadas con el sistema "built-in" de laravel para autenticación de usuario
Auth::routes();

//Registra la ruta Home
Route::get('/home', 'HomeController@index')->name('home');
