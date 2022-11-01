<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

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

//Route::redirect('/user', 'login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('logout', [AuthController::class, 'logout']);
});

//Deportes
Route::get('/deportes', 'App\Http\Controllers\DeporteController@index'); //editar todos los registros
Route::post('/deportes', 'App\Http\Controllers\DeporteController@store'); //crear registro
Route::put('/deportes/{id}', 'App\Http\Controllers\DeporteController@update'); //actualizar registro
Route::delete('/deportes/{id}', 'App\Http\Controllers\DeporteController@destroy'); //borrar registro

//Pistas
Route::get('/pistas', 'App\Http\Controllers\PistaController@index'); //editar todos los registros
Route::get('/busqueda/{fecha}&{socio}&{deporte}', 'App\Http\Controllers\PistaController@buscador'); //buscar pistas
Route::post('/pistas', 'App\Http\Controllers\PistaController@store'); //crear registro
Route::put('/pistas/{id}', 'App\Http\Controllers\PistaController@update'); //actualizar registro
Route::delete('/pistas/{id}', 'App\Http\Controllers\PistaController@destroy'); //borrar registro

//Socios
Route::get('/socios', 'App\Http\Controllers\SocioController@index'); //editar todos los registros
Route::post('/socios', 'App\Http\Controllers\SocioController@store'); //crear registro
Route::put('/socios/{id}', 'App\Http\Controllers\SocioController@update'); //actualizar registro
Route::delete('/socios/{id}', 'App\Http\Controllers\SocioController@destroy'); //borrar registro

//Reservas
Route::get('/reservas', 'App\Http\Controllers\ReservaController@index'); //editar todos los registros
Route::get('/listado/{fecha}', 'App\Http\Controllers\ReservaController@lista'); //listados reservas
Route::post('/reservas', 'App\Http\Controllers\ReservaController@store'); //crear registro
Route::put('/reservas/{id}', 'App\Http\Controllers\ReservaController@update'); //actualizar registro
Route::delete('/reservas/{id}', 'App\Http\Controllers\ReservaController@destroy'); //borrar registro

//Usuarios
Route::get('/usuarios', 'App\Http\Controllers\UserController@index'); //obtener usuarios
Route::post('/usuarios', 'App\Http\Controllers\UserController@store'); //obtener usuarios
Route::put('/usuarios/{id}', 'App\Http\Controllers\UserController@update'); //actualizar registro
Route::delete('/usuarios/{id}', 'App\Http\Controllers\UserController@destroy'); //borrar registro

