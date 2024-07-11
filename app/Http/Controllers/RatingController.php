<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    function index()
    {
        try {
            $rating = Rate::with(['user'])->orderBy('created_at')->paginate(5);
            return Endpoint::success('Berhasil mendapatkan data komentar', $rating);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan data komentar');
        }
    }

    function search(Request $req)
    {
        try {
            $user = User::where('name', 'LIKE', '%' . $req->name . '%')->first();
            if (!$user) {
                return Endpoint::success(
                    'Berhasil',
                    Rate::with(['user'])->orderBy('created_at')
                        ->where('stars', 'LIKE', '%' . $req->stars . '%')
                        ->where('comment', 'LIKE', '%' . $req->comment . '%')
                        ->paginate(5)->appends([
                            'user_id'   => $req->user_id,
                            'stars'     => $req->stars,
                            'comment'   => $req->comment
                        ])
                );
            }
            return Endpoint::success(
                'Berhasil',
                Rate::with(['user'])->orderBy('created_at')
                    ->where('user_id', 'LIKE', '%' . $user->id . '%')
                    ->where('stars', 'LIKE', '%' . $req->stars . '%')
                    ->where('comment', 'LIKE', '%' . $req->comment . '%')
                    ->paginate(5)->appends([
                        'user_id'   => $req->user_id,
                        'stars'     => $req->stars,
                        'comment'   => $req->comment
                    ])
            );
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function findById($id)
    {
        return Endpoint::success('Berhasil', Rate::where('name', $id)->first());
    }

    function additional()
    {
        return Endpoint::success('Berhasil mendapatkan data tambahan komentar', [
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
            if (!User::where('id', $req->user_id)->first()) {
                return Endpoint::warning('User tidak ditemukan');
            }
            $input = [
                'user_id'   => $req->user_id,
                'stars'     => $req->stars,
                'comment'   => $req->comment
            ];
            Rate::insert($input);
            return Endpoint::success('Berhasil menambahkan komentar');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menambahkan komentar');
        }
    }

    function find($id)
    {
        $komentar = Rate::find($id);
        if ($komentar) {
            return Endpoint::success('Berhasil mendapatkan komentar', $komentar);
        }
        return Endpoint::failed('Gagal mendapatkan komentar');
    }

    // function destroy($id)
    // {
    //     $komentar = Rate::find($id);
    //     $komentar->delete();
    //     return Endpoint::success('Berhasil menghapus komentar');
    // }
}
