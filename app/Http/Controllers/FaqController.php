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
            return Endpoint::success(200, 'Berhasil mendapatkan FAQ', Faq::orderBy('created_at', 'desc')->paginate(5));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan FAQ', $th->getMessage());
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
            return Endpoint::success(200, 'Berhasil menambahkan FAQ');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan FAQ', $th->getMessage());
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
            return Endpoint::success(200, 'Berhasil mengubah FAQ');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mengubah FAQ', $th->getMessage());
        }
    }

    function destroy($id)
    {
        try {
            Faq::where('id', $id)->delete();
            return Endpoint::success(200, 'Berhasil menghapus FAQ');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menghapus FAQ', $th->getMessage());
        }
    }
}
