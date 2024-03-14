<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    function index()
    {
        try {
            $data = Pengajuan::orderBy('nama', 'asc')->paginate(10);
            if (!$data) {
                return Endpoint::warning(200, 'Data pengajuan masih kosong', $data);
            }
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

    function store(Request $req)
    {
        try {
            $input = [
                'nip'       => $req->nip,
                'nama'      => $req->nama
            ];
            Pengajuan::insert($input);
            return Endpoint::success(200, 'Berhasil menambahkan data pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan data pengajuan', $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $pengajuan = Pengajuan::find($id);
            $pengajuan->update(['status' => $req->status]);
            return Endpoint::success(200, 'Status pengajuan telah berubah');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Status pengajuan gagal berubah', $th->getMessage());
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

    function approve($id)
    {
        try {
            Pengajuan::where('id', $id)->update(['status' => '2']);
            return Endpoint::success(200, 'Berhasil menyetujui pengajuan');
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
}