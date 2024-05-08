<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Kartu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KartuController extends Controller
{
    function index()
    {
        try {
            $kartu = Kartu::paginate(5);
            if (!$kartu) {
                return Endpoint::warning(200, 'Data kartu masih kosong');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data kartu', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan kartu', $th->getMessage());
        }
    }

    function getKartuTitle()
    {
        try {
            $kartu = Kartu::orderBy('created_at')->select('id', 'title', 'categories')->get();
            if (!$kartu) {
                return Endpoint::warning(200, 'Data kartu masih kosong');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data kartu', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan kartu', $th->getMessage());
        }
    }

    function category(Request $req)
    {
        try {
            $kartu = Kartu::select('title')->orderBy('title')->where('categories', $req->category)->get();
            if ($kartu) {
                return Endpoint::success(200, 'Berhasil', $kartu);
            } else {
                return Endpoint::success(200, 'Berhasil', $kartu);
            }
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function typing(Request $req)
    {
        $kartu = Kartu::orderBy('title')->where('categories', $req->category)->get();
        return Endpoint::success(200, 'Berhasil', $kartu);
    }

    function store(Request $req)
    {
        try {
            $this->validate($req, [
                'title' => 'required'
            ]);

            $requestData = [
                'id'            => mt_rand(),
                'title'         => $req->title,
                'profile'       => $req->profile,
                'categories'    => $req->categories,
                'icon'          => $req->hasFile('icon') ? env('APP_IMG', '') . '/kartu/' . $req->file('icon')->getClientOriginalName() : '',
                'front'         => $req->hasFile('front') ? env('APP_IMG', '') . '/kartu/' . $req->file('front')->getClientOriginalName() : '',
                'back'          => $req->hasFile('back') ? env('APP_IMG', '') . '/kartu/' . $req->file('back')->getClientOriginalName() : '',
                'orientation'   => $req->orientation,
                'nama'          => $req->nama,
                'golongan'      => $req->golongan,
                'jabatan'       => $req->jabatan,
                'nip'           => $req->nip,
                'nrp'           => $req->nrp,
            ];
            Kartu::insert($requestData);
            if ($req->hasFile('icon')) {
                $req->file('icon')->move('kartu', $req->file('icon')->getClientOriginalName());
            }
            if ($req->hasFile('front')) {
                $req->file('front')->move('kartu', $req->file('front')->getClientOriginalName());
            }
            if ($req->hasFile('back')) {
                $req->file('back')->move('kartu', $req->file('back')->getClientOriginalName());
            }
            return Endpoint::success(200, 'Berhasil menambahkan kartu');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $this->validate($req, [
                'title' => 'required'
            ]);

            $kartu = Kartu::where('id', $id)->first();
            if ($req->hasFile('icon')) {
                unlink('../public' . parse_url($kartu->icon)['path']);
                $req->file('icon')->move('kartu', $req->file('icon')->getClientOriginalName());
            }
            $requestData = [
                'id'            => mt_rand(),
                'title'         => $req->title,
                'profile'       => $req->profile,
                'categories'    => $req->categories,
                'icon'          => $req->hasFile('icon') ? env('APP_IMG', '') . '/kartu/' . $req->file('icon')->getClientOriginalName() : $kartu->icon,
                'orientation'   => $req->orientation,
                'nip'           => $req->nip,
                'nrp'           => $req->nrp,
                'golongan'      => $req->golongan,
                'jabatan'       => $req->jabatan,
            ];
            $kartu->update($requestData);
            return Endpoint::success(200, 'Berhasil mengubah kartu');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mengubah kartu', $th->getMessage());
        }
    }

    public function front(Request $req, $id)
    {
        try {
            $kartu = Kartu::where('id', $id)->first();
            unlink('../public' . parse_url($kartu->front)['path']);
            $fileName = $req->file('front')->getClientOriginalName();
            $kartu->update([
                'front' => env('APP_IMG', '') . '/kartu/' . $fileName
            ]);
            $req->file('front')->move('kartu', $fileName);
            return Endpoint::success(200, 'Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }

    public function back(Request $req, $id)
    {
        try {
            $kartu = Kartu::where('id', $id)->first();
            unlink('../public' . parse_url($kartu->back)['path']);
            $fileName = $req->file('back')->getClientOriginalName();
            $kartu->update([
                'back' => env('APP_IMG', '') . '/kartu/' . $fileName
            ]);
            $req->file('back')->move('kartu', $fileName);
            return Endpoint::success(200, 'Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }

    public function find($id)
    {
        $kartu = Kartu::findOrFail($id);
        return Endpoint::Success(200, 'Berhasil', $kartu);
    }

    public function title(Request $req)
    {
        return Endpoint::success(200, 'Berhasil', Kartu::where('id', $req->title)->orWhere('title', $req->title)->first());
    }

    public function destroy($id)
    {
        try {
            $kartu = Kartu::find($id);
            if ($kartu->icon) {
                unlink('../public' . parse_url($kartu->icon)['path']);
            }
            if ($kartu->front) {
                unlink('../public' . parse_url($kartu->front)['path']);
            }
            if ($kartu->back) {
                unlink('../public' . parse_url($kartu->back)['path']);
            }
            $kartu->delete();
            return Endpoint::Success(200, 'Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }

    public function namaKartu($kartu)
    {
        try {
            $kartu = Kartu::where('title', $kartu)->first();
            return Endpoint::success(200, 'Berhasil', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Kartu tidak ditemukan');
        }
    }
}
