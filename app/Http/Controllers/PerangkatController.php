<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Perangkat;
use App\Models\Satker;
use Illuminate\Http\Request;
use Faker\Factory;

class PerangkatController extends Controller
{
    function index($id)
    {
        $kode = SatkerCode::parent($id);
        if ($kode == '0') {
            $data = Perangkat::orderBy('satker')->paginate(10);
        } else {
            $data = Perangkat::orderBy('satker')->where('satker', 'LIKE', $id . '%')->paginate(10);
        }
        return Endpoint::success(200, 'Berhasil', $data);
    }

    function update(Request $req, $id)
    {
        $update = Perangkat::where('id', $id)->first();
        $input = [
            'user'      => $req->user,
            'password'  => $req->password,
            'satker'    => $req->satker ?? $update->satker
        ];
        $updated = $update->update($input);
        if ($updated) {
            return Endpoint::success(200, 'Berhasil mengubah');
        } else {
            return Endpoint::failed(400, 'Gagal mengubah');
        }
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
