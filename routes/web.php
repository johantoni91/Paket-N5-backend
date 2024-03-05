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
        $router->get('/user/search', 'UserController@search'); // Mencari user berdasarkan ketikan search
        $router->get('/users', 'UserController@show'); // Mendapatkan semua user
        $router->post('/user/{id:\d+}', 'UserController@find'); // Mencari user berdasarkan id
        $router->post('/user/{id:\d+}/update', 'UserController@update'); // Mengubah sebuah user
        $router->get('/user/{id:\d+}/status/{stat:\d+}', 'UserController@status'); // Mengubah status user
        $router->get('/user/{id:\d+}/delete', 'UserController@delete'); // Menghapus sebuah user

        // Log Activity
        $router->get('/log', 'LogController@getLog'); // Mendapatkan semua log aktivitas
        $router->get('/log/column', 'LogController@getColumn'); // Mendapatkan kolom pada table Log
        $router->get('/log/search', 'LogController@search');
        $router->get('/log/{id:\d+}/delete', 'LogController@destroy'); // Hapus log aktivitas

        // Satuan Kerja
        $router->get('/satker', 'SatkerController@index');
        $router->get('/satker_name', 'SatkerController@getSatker'); // Mendapatkan nama satker untuk dropdown
        $router->get('/satker/search', 'SatkerController@search'); // Mencari user berdasarkan ketikan search
        $router->post('/satker/store', 'SatkerController@store'); // Menambah satker
        $router->post('/satker/{id:\d+}', 'SatkerController@find'); // Mencari satker berdasarkan id
        $router->post('/satker/{id:\d+}/update', 'SatkerController@update'); // Mengubah sebuah satker
        $router->get('/satker/{id:\d+}/delete', 'SatkerController@delete'); // Menghapus sebuah satker
        $router->get('/satker/{id:\d+}/status/{stat:\d+}', 'SatkerController@status'); // Mengubah status satker

        // Pegawai
        $router->get('/pegawai', 'PegawaiController@index');
        $router->get('/pegawai/search', 'PegawaiController@search');
        $router->post('/pegawai/store', 'PegawaiController@store');
        $router->get('/pegawai/{nip:\d+}/destroy', 'PegawaiController@destroy');

        // FAQ
        $router->get('/faq', 'FaqController@index');
        $router->post('/faq/store', 'FaqController@store');
    });
});
