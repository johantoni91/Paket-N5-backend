<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return "API Ready";
});

$router->group(['prefix' => 'api'], function () use ($router) {

    // Autentikasi
    $router->post('/register', 'AuthController@registration');
    $router->post('/login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {

        // Management Users
        $router->get('/users', 'UserController@show'); // Mendapatkan semua user
        $router->get('/user/{id:\d+}', 'UserController@find'); // Mencari user berdasarkan id
        $router->post('/user/{id:\d+}/update', 'UserController@update'); // Mengubah sebuah user
        $router->get('/user/{id:\d+}/delete', 'UserController@delete'); // Menghapus sebuah user

        // Log Activity
        $router->get('/log', 'LogController@getLog'); // Mendapatkan semua log aktivitas
        $router->get('/log/{id:/d+}/delete', 'LogController@destroy'); // Hapus log aktivitas
    });
});
