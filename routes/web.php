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

// $router->get()

$router->group(['prefix' => 'api'], function () use ($router) {

    // Autentikasi
    $router->post('/user/register', 'AuthController@registration');
    $router->post('/user/login', 'AuthController@login');

    // $router->group(['middleware' => 'auth'], function () use ($router) {


});
// });
