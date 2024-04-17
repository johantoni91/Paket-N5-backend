<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RatingController extends Controller
{
    function index()
    {
        $rate = Rate::orderBy('created_at')->paginate(5);
        if (!$rate) {
            return Endpoint::warning(200, 'Belum ada penilaian', $rate);
        } else {
            return Endpoint::success(200, 'Berhasil mendapatkan penilaian', $rate);
        }
    }

    function stars()
    {
        return Endpoint::success(200, 'Berhasil mendapatkan rating', Rate::select('stars', DB::raw('COUNT(*) as count'))->groupBy('stars')->get());
    }

    function avgStars()
    {
        return Endpoint::success(200, 'Berhasil mendapatkan rating', Rate::select(DB::raw('COUNT(*) as count'))->groupBy('stars')->avg('stars'));
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
