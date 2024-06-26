<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Assessment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    function index()
    {
        return Endpoint::success('Berhasil', Assessment::orderBy('created_at')->paginate(4));
    }

    function store(Request $req)
    {
        try {
            $filename = $req->nip . '_' . $req->satker . '_' . Carbon::now()->format('d_m_Y_h_i_s') . '.' . $req->file('dokumen')->getClientOriginalExtension();
            $req->file('dokumen')->move('files', $filename);
            Assessment::insert([
                'id'        => mt_rand(),
                'title'     => $req->title,
                'dokumen'   => env('APP_IMG', '') . '/files/' . $filename,
                'nip'       => $req->nip,
                'satker'    => $req->satker
            ]);
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function destroy($id)
    {
        try {
            $assessment = Assessment::find($id);
            unlink('../public' . parse_url($assessment->dokumen)['path']);
            $assessment->delete();
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }
}
