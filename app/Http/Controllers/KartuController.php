<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Kartu;
use Illuminate\Http\Request;

class KartuController extends Controller
{
    function index()
    {
        try {
            $kartu = Kartu::paginate(5);
            if (!$kartu) {
                return Endpoint::warning('Data kartu masih kosong');
            }
            return Endpoint::success('Berhasil mendapatkan data kartu', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan kartu');
        }
    }

    function cardView()
    {
        try {
            $kartu = Kartu::get()->toArray();
            $res = [];
            for ($i = 0; $i < count($kartu); $i++) {
                $res = [
                    'view'      => json_decode($kartu[$i]['card'])
                ];
            }
            return Endpoint::success('Berhasil', $res);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function getKartuTitle()
    {
        try {
            $kartu = Kartu::orderBy('created_at')->select('id', 'title', 'categories')->get();
            if (!$kartu) {
                return Endpoint::warning('Data kartu masih kosong');
            }
            return Endpoint::success('Berhasil mendapatkan data kartu', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan kartu');
        }
    }

    function category(Request $req)
    {
        try {
            $kartu = Kartu::select('title')->orderBy('title')->where('categories', $req->category)->get();
            if ($kartu) {
                return Endpoint::success('Berhasil', $kartu);
            } else {
                return Endpoint::success('Berhasil', $kartu);
            }
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function typing(Request $req)
    {
        $kartu = Kartu::orderBy('title')->where('categories', $req->category)->get();
        return Endpoint::success('Berhasil', $kartu);
    }

    function store(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required',
                'icon'  => 'required',
                'front' => 'required',
                'back'  => 'required'
            ]);

            $requestData = [
                'id'            => mt_rand(),
                'title'         => $req->title,
                'profile'       => $req->profile,
                'categories'    => $req->categories,
                'icon'          => $req->icon,
                'front'         => $req->front,
                'back'          => $req->back,
                'orientation'   => $req->orientation,
                'nama'          => $req->nama,
                'golongan'      => $req->golongan,
                'jabatan'       => $req->jabatan,
                'nip'           => $req->nip,
                'nrp'           => $req->nrp,
            ];
            Kartu::insert($requestData);
            return Endpoint::success('Berhasil menambahkan kartu');
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $this->validate($req, [
                'title' => 'required'
            ]);

            $kartu = Kartu::where('id', $id)->first();
            $requestData = [
                'id'            => mt_rand(),
                'title'         => $req->title,
                'profile'       => $req->profile,
                'categories'    => $req->categories,
                'icon'          => $req->icon,
                'orientation'   => $req->orientation,
                'nip'           => $req->nip,
                'nrp'           => $req->nrp,
                'golongan'      => $req->golongan,
                'jabatan'       => $req->jabatan,
            ];
            $kartu->update($requestData);
            return Endpoint::success('Berhasil mengubah kartu');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mengubah kartu');
        }
    }

    public function front(Request $req, $id)
    {
        try {
            $kartu = Kartu::where('id', $id)->first();
            $kartu->update(['front' => $req->front]);
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    public function back(Request $req, $id)
    {
        try {
            $kartu = Kartu::where('id', $id)->first();
            $kartu->update(['back' => $req->back]);
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    public function card(Request $req, $id)
    {
        try {
            $kartu = Kartu::where('id', $id)->update(['card' => $req->card]);
            return Endpoint::success('Berhasil', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    public function find($id)
    {
        $kartu = Kartu::findOrFail($id);
        return Endpoint::Success('Berhasil', $kartu);
    }

    public function title(Request $req)
    {
        return Endpoint::success('Berhasil', Kartu::where('id', $req->title)->orWhere('title', $req->title)->first());
    }

    public function destroy($id)
    {
        try {
            $kartu = Kartu::find($id);
            $kartu->delete();
            return Endpoint::Success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    public function namaKartu($kartu)
    {
        try {
            $kartu = Kartu::where('title', $kartu)->first();
            return Endpoint::success('Berhasil', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed('Kartu tidak ditemukan');
        }
    }

    function test()
    {
        $kartu = Kartu::latest()->first();
        return Endpoint::success('berhasil', json_decode($kartu['card']));
    }
}
