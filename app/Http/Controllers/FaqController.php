<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    function index()
    {
        try {
            return Endpoint::success('Berhasil mendapatkan FAQ', Faq::orderBy('created_at')->paginate(5));
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan FAQ');
        }
    }

    function search(Request $req)
    {
    }

    function store(Request $req)
    {
        try {
            $input = [
                'question' => $req->question,
                'answer'   => $req->answer
            ];
            Faq::insert($input);
            return Endpoint::success('Berhasil menambahkan FAQ');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menambahkan FAQ');
        }
    }

    function update(Request $req, $id)
    {
        try {
            $input = [
                'question' => $req->question,
                'answer'   => $req->answer
            ];
            Faq::where('id', $id)->update($input);
            return Endpoint::success('Berhasil mengubah FAQ');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mengubah FAQ');
        }
    }

    function destroy($id)
    {
        try {
            Faq::where('id', $id)->delete();
            return Endpoint::success('Berhasil menghapus FAQ');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menghapus FAQ');
        }
    }
}
