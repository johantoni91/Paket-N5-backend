<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

$router->get('/', function () {
    return "API Ready";
});

$router->group(['prefix' => 'api'], function () use ($router) {


    // Autentikasi
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@registration');
    $router->get('/kartu/{id:\d+}/load-kartu', 'KartuController@loadKartu');
    $router->patch('/kartu/{id:\d+}/store-kartu', 'KartuController@storeKartu');

    // Kios
    $router->post('/login-kios', 'KiosController@login');
    $router->group(['middleware' => 'kios'], function () use ($router) {
        $router->get('/satker/{code:\d+}/code-kios', 'SatkerController@findByCode'); // Mencari satker berdasarkan satker_code
        $router->get('/kios/token', 'KiosController@token');
        $router->post('/kios/check-token', 'KiosController@checkToken');
        $router->post('/kios/verifikasi', 'KiosController@verifikasi');
        $router->get('/kios/{token}/kartu', 'KiosController@kartu');
    });

    $router->group(['middleware' => 'auth'], function () use ($router) {

        // Dashboard
        $router->get('/dashboard/{id}', 'DashboardController@index');

        // Management Users
        $router->get('/users', 'UserController@all'); // Mendapatkan semua user
        $router->get('/users/{satker}', 'UserController@show'); // Mendapatkan semua user per satker
        $router->post('/user/store', 'UserController@store'); // Melakukan penambahan user setelah login
        $router->get('/user/search', 'UserController@search'); // Mencari user berdasarkan ketikan search
        $router->post('/user/{id:\d+}', 'UserController@find'); // Mencari user berdasarkan id (include log activity)
        $router->get('/user/{id:\d+}/find', 'UserController@findById'); // Mencari user berdasarkan id (tanpa log activity)
        $router->get('/user/{id:\d+}/delete', 'UserController@delete'); // Menghapus sebuah user
        $router->post('/user/{id:\d+}/update', 'UserController@update'); // Mengubah sebuah user
        $router->get('/user/{id:\d+}/status/{stat:\d+}', 'UserController@status'); // Mengubah status user

        // Log Activity
        $router->get('/log/{id}/index', 'LogController@getLog'); // Mendapatkan semua log aktivitas
        $router->get('/log/search', 'LogController@search');
        $router->get('/log/column', 'LogController@getColumn'); // Mendapatkan kolom pada table Log
        $router->get('/log/{id:\d+}/delete', 'LogController@destroy'); // Hapus log aktivitas

        // Satuan Kerja
        $router->get('/satker/{satker}/index', 'SatkerController@index');
        $router->get('/satker/all', 'SatkerController@all');
        $router->post('/satker/store', 'SatkerController@store'); // Menambah satker
        $router->get('/satker/search', 'SatkerController@search'); // Mencari user berdasarkan ketikan search
        $router->get('/satker_name', 'SatkerController@getSatker'); // Mendapatkan nama satker untuk dropdown
        $router->post('/satker/{id:\d+}', 'SatkerController@find'); // Mencari satker berdasarkan id
        $router->get('/satker/{code:\d+}/code', 'SatkerController@findByCode'); // Mencari satker berdasarkan satker_code
        $router->post('/satker/find/name', 'SatkerController@findByName'); // Mencari satker berdasarkan satker_name
        $router->post('/satker/name', 'SatkerController@name');
        $router->get('/satker/{id:\d+}/delete', 'SatkerController@delete'); // Menghapus sebuah satker
        $router->post('/satker/{id:\d+}/update', 'SatkerController@update'); // Mengubah sebuah satker
        $router->get('/satker/{id:\d+}/status/{stat:\d+}', 'SatkerController@status'); // Mengubah status satker

        // Pegawai
        $router->post('/pegawai/store', 'PegawaiController@store');
        $router->get('/pegawai/index/{id}', 'PegawaiController@index');
        $router->get('/pegawai/{id}/search', 'PegawaiController@search');
        $router->get('/pegawai/{id:\d+}/find', 'PegawaiController@find');
        $router->post('/pegawai/{id:\d+}/update', 'PegawaiController@update');
        $router->get('/pegawai/{id:\d+}/destroy', 'PegawaiController@destroy');

        // Integrasi Pegawai
        $router->get('/integrasi', 'IntegrasiController@index');
        $router->post('/integrasi/search', 'IntegrasiController@search');
        $router->post('/integrasi/store', 'IntegrasiController@store');
        $router->post('/integrasi/{id}/update', 'IntegrasiController@update');
        $router->post('/integrasi/{id}/update/type', 'IntegrasiController@updateType');
        $router->get('/integrasi/{id}/destroy', 'IntegrasiController@delete');
        $router->post('/integration', 'IntegrasiController@integration');

        // Pengajuan
        $router->get('/pengajuan/top', 'PengajuanController@top5');
        $router->post('/pengajuan/store', 'PengajuanController@store');
        $router->get('/pengajuan/status', 'PengajuanController@status');
        $router->get('/pengajuan/{id:\d+}', 'PengajuanController@find');
        $router->get('/pengajuan/search', 'PengajuanController@search');
        $router->get('/pengajuan/jumlah', 'PengajuanController@getCount');
        $router->get('/pengajuan/{id:\d+}/index', 'PengajuanController@index');
        $router->get('/pengajuan/{token}/find/token', 'PengajuanController@findByToken');
        $router->get('/pengajuan/{id:\d+}/print', 'PengajuanController@print');
        $router->get('/pengajuan/{user}/user', 'PengajuanController@findByUser');
        $router->get('/pengajuan/reject/{id:\d+}', 'PengajuanController@reject');
        $router->get('/pengajuan/{id:\d+}/destroy', 'PengajuanController@destroy');
        $router->post('/pengajuan/{id:\d+}/approve/{satker:\d+}', 'PengajuanController@approve');

        // Monitoring Pengajuan
        $router->get('/monitor/{id:\d+}', 'MonitorController@index');
        $router->post('/monitor/search', 'MonitorController@search');

        // SmartCard
        $router->get('/smart/{satker:\d+}', 'SmartController@index');

        // Kartu
        $router->get('/kartuPagination', 'KartuController@indexPagination');
        $router->get('/kartu', 'KartuController@index');
        $router->get('/kartu/view', 'KartuController@cardView');
        $router->get('/kartu/title', 'KartuController@getKartuTitle');
        $router->post('/kartu/title', 'KartuController@title');
        $router->post('/kartu/store', 'KartuController@store');
        $router->get('/kartu/{id:\d+}', 'KartuController@find');
        $router->post('/kartu/{id:\d+}/card', 'KartuController@card');
        $router->post('/kartu/typing', 'KartuController@typing');
        $router->post('/kartu/category', 'KartuController@category');
        $router->post('/kartu/{id:\d+}/back', 'KartuController@back');
        $router->post('/kartu/{id:\d+}/front', 'KartuController@front');
        $router->post('/kartu/{id:\d+}/update', 'KartuController@update');
        $router->get('/kartu/{id:\d+}/destroy', 'KartuController@destroy');
        $router->get('/kartu/test', 'KartuController@test');

        // Perangkat
        $router->get('/perangkat/import', 'PerangkatController@import');
        $router->get('/perangkat/search', 'PerangkatController@search');
        $router->get('/perangkat/status', 'PerangkatController@status');
        $router->get('/perangkat/{id:\d+}', 'PerangkatController@index');
        $router->get('/perangkat/{id}/find', 'PerangkatController@find');
        $router->post('/perangkat/{id}/update', 'PerangkatController@update');
        $router->get('/perangkat/tm_hardware', 'PerangkatController@indexTmHardware');
        $router->get('/perangkat/tc_hardware', 'PerangkatController@indexTcHardware');
        $router->post('/perangkat/tm_hardware', 'PerangkatController@storeTmHardware');
        $router->post('/perangkat/tc_hardware', 'PerangkatController@storeTcHardware');
        $router->get('/perangkat/{id}/find/tm_hardware', 'PerangkatController@findTmHardware');
        $router->get('/perangkat/{id}/find/tc_hardware', 'PerangkatController@findTcHardware');
        $router->get('/perangkat/{id}/find/tools/tc_hardware', 'PerangkatController@findToolsBySatkerId');
        $router->post('/perangkat/{id}/update/tm_hardware', 'PerangkatController@updateTmHardware');
        $router->post('/perangkat/{id}/update/tc_hardware', 'PerangkatController@updateTcHardware');
        $router->get('/perangkat/{id}/destroy/tm_hardware', 'PerangkatController@destroyTmHardware');
        $router->get('/perangkat/{id}/destroy/tc_hardware', 'PerangkatController@destroyTcHardware');

        // Roles
        $router->get('/roles', 'RoleController@index');
        $router->get('/roles/find', 'RoleController@find');
        $router->post('/roles/store', 'RoleController@store');
        $router->post('/roles/find/id', 'RoleController@findId');
        $router->post('/roles/{id:\d+}/update', 'RoleController@update');

        // FAQ
        $router->get('/faq', 'FaqController@index');
        $router->post('/faq/store', 'FaqController@store');
        $router->post('/faq/{id:\d+}/update', 'FaqController@update');
        $router->get('/faq/{id:\d+}/destroy', 'FaqController@destroy');

        // Rating
        $router->get('/rate', 'RatingController@index');
        $router->post('/rate/search', 'RatingController@search');
        $router->post('/rate/insert', 'RatingController@insert');
        $router->get('/rate/{id:\d+}/find', 'RatingController@find');
        $router->get('/rate/{id:\d+}/id', 'RatingController@findById');
        $router->get('/rate/additional', 'RatingController@additional');
        $router->get('/rate/{id:\d+}/destroy', 'RatingController@destroy');

        // Assessment
        $router->get('/assessment', 'AssessmentController@index');
        $router->post('/assessment/store', 'AssessmentController@store');
        $router->get('/assessment/{id}/destroy', 'AssessmentController@destroy');

        // Inbox
        $router->get('/inbox/{user1}/room/{user2}', 'InboxController@room');
        $router->post('/inbox/{room}/chat', 'InboxController@chat');
        $router->get('/inbox/{id:\d+}/read', 'InboxController@getRead');

        // Notifikasi
        $router->post('notif/store', 'NotificationController@store');
        $router->get('notif/{nip}/find', 'NotificationController@find');
        $router->get('/notif/{id:\d+}', 'NotificationController@index');
        $router->get('/notif/truncate', 'NotificationController@truncate');
        $router->get('/notif/{id:\d+}/message', 'NotificationController@message');
        $router->get('notif/{id:\d+}/destroy', 'NotificationController@destroy');

        // Tanda Tangan
        $router->post('signature/store', 'SignatureController@store');
        $router->get('signature/{satker}', 'SignatureController@find');
        $router->post('signature/update', 'SignatureController@update');
        $router->get('signature/{satker}/destroy', 'SignatureController@destroy');

        // NFC
        $router->post('nfc/store', 'NFCController@retrieveAndUpdate');
    });
});
