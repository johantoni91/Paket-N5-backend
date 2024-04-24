<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\HelpersPengajuan;
use App\Helpers\SatkerCode;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    function index($id)
    {
        try {
            $kode = SatkerCode::parent($id);
            $data = HelpersPengajuan::index($kode, $id);
            return Endpoint::success(200, 'Berhasil mendapatkan data pengajuan', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pengajuan', $th->getMessage());
        }
    }

    function search(Request $req)
    {
        try {
            $data = Pengajuan::orderBy('nama', 'asc')
                ->where('nip', 'LIKE', '%' . $req->nip . '%')
                ->where('nama', 'LIKE', '%' . $req->nama . '%')
                ->where('status', 'LIKE', '%' . $req->status . '%')
                ->paginate(10)->appends([
                    'nip'    => $req->nip,
                    'nama'    => $req->nama,
                    'status'   => $req->status,
                ]);
            if (!$data) {
                return Endpoint::success(200, 'Tidak ada data pengajuan');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data pengajuan', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pengajuan!', $th->getMessage());
        }
    }

    function find($id)
    {
        try {
            return Endpoint::success(200, 'Berhasil', Pengajuan::findOrFail($id));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, "Gagal", $th->getMessage());
        }
    }

    function store(Request $req)
    {
        try {
            $approve_satker = SatkerCode::parent($req->satker_code);
            $input = [
                'id'             => mt_rand(),
                'nip'            => $req->nip,
                'nama'           => $req->nama,
                'kode_satker'    => $req->satker_code,
                'photo'          => $req->hasFile('photo') ? env('APP_IMG', '') . '/kartu/' . $req->file('photo')->getClientOriginalName() : '',
                'kartu'          => $req->kartu,
                'approve_satker' => $approve_satker == '0' ? '1' : $approve_satker
            ];

            $this->validate($req, [
                'nip'   => 'required',
                'kartu' => 'required'
            ]);

            HelpersPengajuan::store($req->nip, $req->nama, $req->kartu, $input, $req->satker_code, $approve_satker);
            if ($req->hasFile('photo')) {
                $req->file('photo')->move('pengajuan', $req->file('photo')->getClientOriginalName());
            }
            return Endpoint::success(200, 'Berhasil menambahkan data pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan data pengajuan', $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $pengajuan = Pengajuan::find($id);
            $pengajuan->update(['kartu' => $req->kartu]);
            return Endpoint::success(200, 'Data pengajuan telah berubah');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Data pengajuan gagal berubah', $th->getMessage());
        }
    }

    function destroy($id)
    {
        try {
            $pengajuan = Pengajuan::find($id);
            $pengajuan->delete();
            return Endpoint::success(200, 'Berhasil menghapus data pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menghapus data pengajuan', $th->getMessage());
        }
    }

    function approve($id, $satker)
    {
        try {
            HelpersPengajuan::approve($id, $satker);
            return Endpoint::success(200, 'Berhasil menyetujui pengajuan', Pengajuan::where('id', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menyetujui pengajuan', $th->getMessage());
        }
    }

    function reject($id)
    {
        try {
            Pengajuan::where('id', $id)->update(['status' => '0']);
            return Endpoint::success(200, 'Berhasil menolak pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menolak pengajuan', $th->getMessage());
        }
    }

    function print($id)
    {
        try {
            Pengajuan::where('id', $id)->update(['status' => '3']);
            return Endpoint::success(200, "Berhasil");
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }

    function getCount()
    {
        try {
            return Endpoint::success(200, 'Berhasil', Pengajuan::count('kartu')->distinct());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal', $th->getMessage());
        }
    }
}
