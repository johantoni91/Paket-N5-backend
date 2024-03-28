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
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@registration');
    $router->get('/kartu/{id:\d+}/load-kartu', 'KartuController@loadKartu');
    $router->patch('/kartu/{id:\d+}/store-kartu', 'KartuController@storeKartu');

    $router->group(['middleware' => 'auth'], function () use ($router) {

        // Management Users
        $router->get('/users', 'UserController@show'); // Mendapatkan semua user
        $router->get('/user/search', 'UserController@search'); // Mencari user berdasarkan ketikan search
        $router->post('/user/{id:\d+}', 'UserController@find'); // Mencari user berdasarkan id
        $router->get('/user/{id:\d+}/delete', 'UserController@delete'); // Menghapus sebuah user
        $router->post('/user/{id:\d+}/update', 'UserController@update'); // Mengubah sebuah user
        $router->get('/user/{id:\d+}/status/{stat:\d+}', 'UserController@status'); // Mengubah status user

        // Log Activity
        $router->get('/log', 'LogController@getLog'); // Mendapatkan semua log aktivitas
        $router->get('/log/search', 'LogController@search');
        $router->get('/log/column', 'LogController@getColumn'); // Mendapatkan kolom pada table Log
        $router->get('/log/{id:\d+}/delete', 'LogController@destroy'); // Hapus log aktivitas

        // Satuan Kerja
        $router->get('/satker', 'SatkerController@index');
        $router->post('/satker/store', 'SatkerController@store'); // Menambah satker
        $router->get('/satker/search', 'SatkerController@search'); // Mencari user berdasarkan ketikan search
        $router->get('/satker_name', 'SatkerController@getSatker'); // Mendapatkan nama satker untuk dropdown
        $router->post('/satker/{id:\d+}', 'SatkerController@find'); // Mencari satker berdasarkan id
        $router->get('/satker/{id:\d+}/delete', 'SatkerController@delete'); // Menghapus sebuah satker
        $router->post('/satker/{id:\d+}/update', 'SatkerController@update'); // Mengubah sebuah satker
        $router->get('/satker/{id:\d+}/status/{stat:\d+}', 'SatkerController@status'); // Mengubah status satker

        // Pegawai
        $router->get('/pegawai', 'PegawaiController@index');
        $router->post('/pegawai/store', 'PegawaiController@store');
        $router->get('/pegawai/search', 'PegawaiController@search');
        $router->post('/pegawai/{nip:\d+}/update', 'PegawaiController@update');
        $router->get('/pegawai/{nip:\d+}/destroy', 'PegawaiController@destroy');

        // Pengajuan
        $router->get('/pengajuan', 'PengajuanController@index');
        $router->post('/pengajuan/store', 'PengajuanController@store');
        $router->get('/pengajuan/search', 'PengajuanController@search');
        $router->get('/pengajuan/{id:\d+}', 'PengajuanController@find');
        $router->get('/pengajuan/{id:\d+}/print', 'PengajuanController@print');
        $router->get('/pengajuan/jumlah', 'PengajuanController@getCount');
        $router->get('/pengajuan/reject/{id:\d+}', 'PengajuanController@reject');
        $router->get('/pengajuan/approve/{id:\d+}', 'PengajuanController@approve');
        $router->get('/pengajuan/{id:\d+}/destroy', 'PengajuanController@destroy');

        // Kartu
        $router->get('/kartu', 'KartuController@index');
        $router->post('/kartu/store', 'KartuController@store');
        $router->get('/kartu/{id:\d+}', 'KartuController@find');
        $router->get('/kartu/{kartu:\d+}/title', 'KartuController@namaKartu');
        $router->post('/kartu/{id:\d+}/update', 'KartuController@update');
        $router->get('/kartu/{id:\d+}/destroy', 'KartuController@destroy');

        // Roles
        $router->get('/roles', 'RoleController@index');
        $router->get('/roles/find', 'RoleController@find');
        // $router->post('/roles/store', 'RoleController@store');
        $router->post('/roles/{id:\d+}/update', 'RoleController@update');
        // $router->get('/roles/{id:\d+}/destroy', 'RoleController@destroy');

        // FAQ
        $router->get('/faq', 'FaqController@index');
        $router->post('/faq/store', 'FaqController@store');
        $router->post('/faq/{id:\d+}/update', 'FaqController@update');
        $router->get('/faq/{id:\d+}/destroy', 'FaqController@destroy');

        //Rating

        // Notifikasi
        $router->get('/notif', 'NotificationController@index');
        $router->post('notif/store', 'NotificationController@store');
        $router->get('notif/{id:\d+}/destroy', 'NotificationController@destroy');
    });
});
