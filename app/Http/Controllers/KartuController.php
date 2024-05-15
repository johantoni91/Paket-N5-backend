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
            return Endpoint::success('Berhasil mengubah kartu');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mengubah kartu');
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
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
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
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
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
}
