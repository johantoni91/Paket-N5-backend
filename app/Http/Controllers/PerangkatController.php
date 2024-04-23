<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Perangkat;
use App\Models\Satker;
use Illuminate\Http\Request;
use Faker\Factory;

class PerangkatController extends Controller
{
    function index()
    {
        return Endpoint::success(200, 'Berhasil', Perangkat::orderBy('satker')->paginate(10));
    }

    function import()
    {
        try {
            $satker = Satker::where('satker_code', 'NOT LIKE', null)->get();
            $faker = Factory::create();
            foreach ($satker as $sat) {
                Perangkat::insert([
                    'user'      => $faker->userName,
                    'password'  => $faker->password,
                    'satker'    => $sat->satker_code,
                ]);
            }
            return Endpoint::success(200, 'Berhasil', Perangkat::paginate(5));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }
}
