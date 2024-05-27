<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Signature;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    function store(Request $req)
    {
        try {
            $user = Signature::where('satker', $req->satker)->first();
            if (!$user) {
                $req->file('signature')->move('signature', $req->file('signature')->getClientOriginalName());
                Signature::insert([
                    'id'        => mt_rand(),
                    'signature' => env('APP_IMG', '') . '/signature/' . $req->file('signature')->getClientOriginalName(),
                    'satker'    => $req->satker
                ]);
                return Endpoint::success('Berhasil', Signature::where('satker', $req->satker));
            } else {
                return Endpoint::success('Sudah ada tanda tangan dalam satker yang sama', $user);
            }
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    function find($satker)
    {
        $signature = Signature::where('satker', $satker)->first();
        if (!$signature) {
            return Endpoint::success('Tanda tangan tidak ada');
        } else {
            return Endpoint::success('Berhasil', $signature);
        }
    }

    function update(Request $req)
    {
        try {
            $signature = Signature::where('satker', $req->satker)->first();
            unlink('../public' . parse_url($signature->signature)['path']);
            $signature->update([
                'signature' => env('APP_IMG', '') . '/signature/' . $req->file('signature')->getClientOriginalName()
            ]);
            $req->file('signature')->move('signature', $req->file('signature')->getClientOriginalName());
            return Endpoint::success('Berhasil', Signature::where('satker', $req->satker)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    function destroy($satker)
    {
        $signature = Signature::where('satker', $satker)->first();
        if (!$signature) {
            return Endpoint::success('Tanda tangan tidak ada');
        } else {
            unlink('../public' . parse_url($signature->signature)['path']);
            $signature->delete();
            return Endpoint::success('Tanda tangan berhasil dihapus');
        }
    }
}
