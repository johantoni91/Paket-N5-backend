<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Kewenangan;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class KewenanganController extends Controller
{
    function getAll()
    {
        return Endpoint::success(200, 'Berhasil mendapatkan data', Kewenangan::with(['users', 'satker'])->get());
    }

    public function find($id)
    {
        try {
            $data = Kewenangan::with('users')->where('users_id', $id)->first();
            return Endpoint::success(200, 'Berhasil menemukan user!', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }

    public function updateStatus(Request $req, $id)
    {
        try {
            $data = Kewenangan::find($id);
            if (!$data) {
                return Endpoint::failed(400, 'Data user tidak ditemukan');
            }
            $data->update([
                'status' => $req->status
            ]);
            return Endpoint::success(200, 'Berhasil mengubah status');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mengubah status', $th->getMessage());
        }
    }
}
