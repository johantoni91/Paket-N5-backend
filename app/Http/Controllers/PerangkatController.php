<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Perangkat;
use App\Models\Satker;
use App\Models\TcHardware;
use App\Models\TmHardware;
use Illuminate\Http\Request;

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

    function search(Request $req)
    {
        try {
            $arr = [];
            $profile = SatkerCode::parent($req->profile);
            if ($profile == '0') {
                $satker = Satker::orderBy('satker_type')
                    ->where('satker_code', 'NOT LIKE', null)
                    ->where('satker_name', 'LIKE', '%' . $req->satker_name . '%')
                    ->where('satker_type', $req->satker_type)
                    ->get();
            } else {
                $satker = Satker::orderBy('satker_type')
                    ->where('satker_code', 'NOT LIKE', null)
                    ->where('satker_code', 'LIKE', $req->profile . '%')
                    ->where('satker_name', 'LIKE', '%' . $req->satker_name . '%')
                    ->where('satker_type', $req->satker_type)
                    ->get();
            }
            for ($i = 0; $i < count($satker); $i++) {
                $arr[$i] = $satker[$i]['satker_code'];
            }
            $perangkat = Perangkat::orderBy('satker')->whereIn('satker', $arr)->paginate(10)->appends([
                'satker'    => $arr
            ]);
            if (!$perangkat) {
                return Endpoint::success(200, 'Perangkat tidak ada');
            }
            return Endpoint::success(200, 'Berhasil', $perangkat);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }

    function find($id)
    {
        try {
            $perangkat = Perangkat::find($id);
            if (!$perangkat) {
                return Endpoint::success(200, 'Perangkat tidak ada');
            }
            return Endpoint::success(200, 'Berhasil', $perangkat);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
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
            Perangkat::truncate();
            $satker = Satker::where('satker_code', 'NOT LIKE', null)->get();
            foreach ($satker as $sat) {
                Perangkat::insert([
                    'user'      => mt_rand(),
                    'password'  => mt_rand(),
                    'satker'    => $sat->satker_code,
                ]);
            }
            return Endpoint::success(200, 'Berhasil', Perangkat::paginate(5));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function indexTmHardware()
    {
        return Endpoint::success(200, 'Berhasil', TmHardware::get());
    }

    function findTmHardware($id)
    {
        try {
            return Endpoint::success(200, 'Berhasil', TmHardware::find($id));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function storeTmHardware(Request $req)
    {
        try {
            $this->validate($req, [
                'perangkat' => 'required'
            ]);
            TmHardware::insert([
                'perangkat' => $req->perangkat
            ]);

            return Endpoint::success(200, 'Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }

    function updateTmHardware(Request $req, $id)
    {
        try {
            $hardware = TmHardware::find($id);
            if ($hardware) {
                $this->validate($req, [
                    'perangkat' => 'required'
                ]);

                $hardware->update([
                    'perangkat' => $req->perangkat,
                    'status' => $req->status ?? $hardware->status
                ]);
                $hardware->save();
                return Endpoint::success(200, 'Berhasil', TmHardware::find($id));
            }
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function destroyTmHardware($id)
    {
        $hardware = TmHardware::find($id);
        if ($hardware) {
            $hardware->delete();
            return Endpoint::success(200, 'Berhasil');
        } else {
            return Endpoint::failed(400, 'Gagal', 'perangkat tidak ditemukan!');
        }
    }

    function indexTcHardware()
    {
        return Endpoint::success(200, 'Berhasil', TcHardware::get());
    }

    function findTcHardware($id)
    {
        try {
            return Endpoint::success(200, 'Berhasil', TcHardware::find($id));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function findToolsBySatkerId($id)
    {
        try {
            $tools = TcHardware::where('id_satker', $id)->get();
            if (!$tools) {
                return Endpoint::success(200, 'Belum ada perangkat pada satker ini');
            }
            return Endpoint::success(200, 'Berhasil', $tools);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function storeTcHardware(Request $req)
    {
        try {
            $this->validate($req, [
                'id_perangkat'  => 'required',
                'id_satker'     => 'required',
                'serial_number' => 'required',
                'photo'         => 'required'
            ]);

            if (!TmHardware::find($req->id_perangkat)) {
                return Endpoint::failed(400, ' Gagal', 'Perangkat tidak ditemukan');
            }
            $req->file('photo')->move('perangkat', $req->file('photo')->getClientOriginalName());
            $insert = TcHardware::insert([
                'id_perangkat'  => $req->id_perangkat,
                'id_satker'     => $req->id_satker,
                'serial_number' => $req->serial_number,
                'photo'         => env('API_URL', '') . '/perangkat/' . $req->file('photo')->getClientOriginalName()
            ]);

            if (!$insert) {
                return Endpoint::failed(400, 'Gagal');
            }
            return Endpoint::success(200, 'Berhasil', TcHardware::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function updateTcHardware(Request $req, $id)
    {
        try {
            $hardware = TcHardware::find($id);
            if (!$hardware) {
                return Endpoint::failed(400, 'Gagal', 'Perangkat tidak ditemukan');
            }
            if ($req->hasFile('photo')) {
                unlink('../public' . parse_url($hardware->photo)['path']);
                $req->file('photo')->move('perangkat', $req->file('photo')->getClientOriginalName());
            }
            $update = $hardware->update([
                'id_perangkat'  => $req->id_perangkat ?? $hardware->id_perangkat,
                'id_satker'     => $req->id_satker ?? $hardware->id_satker,
                'serial_number' => $req->serial_number ?? $hardware->serial_number,
                'photo'         => $req->hasFile('photo') ? env('API_URL', '') . '/perangkat/' . $req->file('photo')->getClientOriginalName() : $hardware->photo
            ]);
            if (!$update) {
                return Endpoint::failed(400, 'Gagal');
            }
            return Endpoint::success(200, 'Berhasil', $hardware);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function destroyTcHardware($id)
    {
        try {
            $hardware = TcHardware::find($id);
            if (!$hardware) {
                return Endpoint::failed(400, 'Gagal');
            }
            unlink('../public' . parse_url($hardware->photo)['path']);
            $hardware->delete();
            return Endpoint::success(200, 'Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }
}
