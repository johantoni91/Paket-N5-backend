<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class RatingController extends Controller
{
    function index()
    {
        try {
            return Endpoint::success(200, 'Berhasil mendapatkan data komentar', Rate::orderBy('created_at')->paginate(5));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data komentar');
        }
    }

    function additional()
    {
        return Endpoint::success(200, 'Berhasil mendapatkan data tambahan komentar', [
            'stars'         => [
                '1' => Rate::where('stars', 1)->count(),
                '2' => Rate::where('stars', 2)->count(),
                '3' => Rate::where('stars', 3)->count(),
                '4' => Rate::where('stars', 4)->count(),
                '5' => Rate::where('stars', 5)->count(),
            ],
            'total_records' => Rate::count()
        ]);
    }

    function insert(Request $req)
    {
        try {
            $input = [
                'name'      => $req->name,
                'stars'     => $req->stars,
                'comment'   => $req->comment
            ];
            if ($req->hasFile('photo')) {
                $input['photo'] = Str::slug($input['name']) . '.' . $req->file('photo')->getClientOriginalExtension();
                $req->file('photo')->move('komentar', Str::slug($input['name']) . '.' . $req->file('photo')->getClientOriginalExtension());
            }
            Rate::insert($input);
            return Endpoint::success(200, 'Berhasil menambahkan komentar');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan komentar', $th->getMessage());
        }
    }

    function find($id)
    {
        $komentar = Rate::find($id);
        if ($komentar) {
            return Endpoint::success(200, 'Berhasil mendapatkan komentar', $komentar);
        }
        return Endpoint::failed(400, 'Gagal mendapatkan komentar');
    }

    function destroy($id)
    {
        $komentar = Rate::find($id);
        if ($komentar) {
            if ($komentar->photo) {
                if (File::exists(env('APP_IMG', '') . '/komentar/' . $komentar->photo)) {
                    unlink('../public/komentar/' . $komentar->photo);
                }
            }
            $komentar->delete();
            return Endpoint::success(200, 'Berhasil menghapus komentar');
        } else {
            return Endpoint::failed(400, 'Gagal menghapus komentar');
        }
    }
}
