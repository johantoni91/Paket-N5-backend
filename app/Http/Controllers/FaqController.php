<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
                'answer'   => $req->answer,
                'image'    => env('APP_IMG') . '/faq/' . $req->file('image')->getClientOriginalName()
            ];
            $req->file('image')->move('faq', $req->file('image')->getClientOriginalName());
            Faq::insert($input);
            return Endpoint::success('Berhasil menambahkan FAQ', $req->file('image')->getClientOriginalName());
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menambahkan FAQ', $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $faq = Faq::where('id', $id)->first();
            $input = [
                'question' => $req->question,
                'answer'   => $req->answer,
                'image'    => $req->hasFile('image') ? env('APP_IMG') . '/faq/' . $req->file('image')->getClientOriginalName() : $faq->image
            ];
            if ($req->hasFile('image')) {
                if (File::exists(parse_url($faq->image)['path'])) {
                    File::delete(parse_url($faq->image)['path']);
                }
                $req->file('image')->move('faq', $req->file('image')->getClientOriginalName());
            }
            Faq::where('id', $id)->update($input);
            return Endpoint::success('Berhasil mengubah FAQ');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mengubah FAQ', $th->getMessage());
        }
    }

    function destroy($id)
    {
        try {
            $faq = Faq::where('id', $id)->first();
            if (File::exists('../public' . parse_url($faq->image)['path'])) {
                unlink('../public' . parse_url($faq->image)['path']);
            }
            $faq->delete();
            return Endpoint::success('Berhasil menghapus FAQ');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menghapus FAQ', $th->getMessage());
        }
    }
}
