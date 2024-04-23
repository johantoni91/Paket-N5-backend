<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Kartu;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    function index($id)
    {
        try {
            if ($id == "00") {
                $data = Pengajuan::orderBy('nama', 'asc')->where('approve_satker', '0')->where('status', '2')->paginate(5);
            } elseif (preg_match('/^\d{2}$/', $id) && $id != "00") {
                $data = Pengajuan::orderBy('nama', 'asc')->where('kode_satker', 'LIKE', $id . '%')->where('approve_satker', '1')->where('status', '1')->paginate(5);
            } elseif (preg_match('/^\d{4}$/', $id)) {
                $data = Pengajuan::orderBy('nama', 'asc')->where('kode_satker', 'LIKE', $id . '%')->where('approve_satker', '2')->where('status', '1')->paginate(5);
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
                'approve_satker' => $approve_satker
            ];

            $this->validate($req, [
                'nip'   => 'required',
                'kartu' => 'required'
            ]);

            if (!Pegawai::where('nip', $req->nip)->orWhere('nama', $req->nama)->first()) {
                return Endpoint::warning(200, 'Pengajuan gagal, pegawai tidak ditemukan.');
            }

            $kartu = Kartu::where('title', $input['kartu'])->first();
            if (!$kartu) {
                return Endpoint::warning(200, 'Kartu belum / tidak ada. Tanyakan pada superadmin.');
            }
            SatkerCode::notification($input['nama'], $approve_satker, $req->satker_code);
            Pengajuan::insert($input);
            $kartu->update(['total' => DB::raw('total + 1')]);

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
            $pengajuan = Pengajuan::where('id', $id)->where('kode_satker', 'LIKE', $satker . '%')->first();
            $kode = SatkerCode::parent($satker);
            if ($kode == '0' && ($pengajuan->approve_satker == '0' || $pengajuan->approve_satker == '1')) {
                Pengajuan::where('id', $id)->where('kode_satker', 'LIKE', $satker . '%')->update([
                    'token'          => mt_rand(),
                    'status'         => '3',
                    'approve_satker' => '0'
                ]);
            } elseif ($kode == '1' && $pengajuan->approve_satker == '2') {
                Pengajuan::where('id', $id)->where('kode_satker', 'LIKE', $satker . '%')->update([
                    'status'         => '2',
                    'approve_satker' => '1'
                ]);
            }
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
