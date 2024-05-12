<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\HelpersPengajuan;
use App\Helpers\Log;
use App\Helpers\SatkerCode;
use App\Models\Kartu;
use App\Models\Notif;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    function index($id)
    {
        try {
            $kode = SatkerCode::parent($id);
            $data = HelpersPengajuan::index($kode, $id);
            return Endpoint::success(200, 'Berhasil mendapatkan data pengajuan', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pengajuan');
        }
    }

    function findByUser($user)
    {
        return Endpoint::success(200, 'Berhasil', Pengajuan::where('nip', $user)->get());
    }

    function monitor($id)
    {
        try {
            $kode = SatkerCode::parent($id);
            if ($kode == '0') {
                $data = Pengajuan::orderBy('created_at', 'asc')->paginate(5);
            } else {
                $data = Pengajuan::orderBy('created_at', 'asc')->where('kode_satker', 'LIKE', $id . '%')->paginate(5);
            }
            return Endpoint::success(200, 'Berhasil monitoring data pengajuan', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal monitoring data pengajuan');
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
            return Endpoint::failed(400, 'Gagal mendapatkan data pengajuan!');
        }
    }

    function find($id)
    {
        try {
            return Endpoint::success(200, 'Berhasil', Pengajuan::findOrFail($id));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, "Gagal");
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

            if (!Pegawai::where('nip', $req->nip)->where('nama', $req->nama)->first()) {
                return Endpoint::warning(200, 'Pengajuan gagal, pegawai tidak ditemukan.');
            }

            $kartu = Kartu::where('id', $req->kartu)->first();
            if (!$kartu) {
                return Endpoint::warning(200, 'Kartu belum / tidak ada. Tanyakan pada superadmin.');
            }
            Pengajuan::insert($input);
            $kartu->update(['total' => DB::raw('total + 1')]);
            Notif::insert([
                'notifikasi'  => $req->nama . ' mengajukan kartu.',
                'kode_satker' => $req->satker_code,
                'satker'      => $approve_satker,
            ]);

            if ($req->hasFile('photo')) {
                $req->file('photo')->move('pengajuan', $req->file('photo')->getClientOriginalName());
            }
            return Endpoint::success(200, 'Berhasil menambahkan data pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan data pengajuan');
        }
    }

    function update(Request $req, $id)
    {
        try {
            $pengajuan = Pengajuan::find($id);
            $pengajuan->update(['kartu' => $req->kartu]);
            return Endpoint::success(200, 'Data pengajuan telah berubah');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Data pengajuan gagal berubah');
        }
    }

    function destroy($id)
    {
        try {
            $pengajuan = Pengajuan::find($id);
            $pengajuan->delete();
            return Endpoint::success(200, 'Berhasil menghapus data pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menghapus data pengajuan');
        }
    }

    function approve($id, $satker)
    {
        try {
            HelpersPengajuan::approve($id, $satker);
            return Endpoint::success(200, 'Berhasil menyetujui pengajuan', Pengajuan::where('id', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menyetujui pengajuan');
        }
    }

    function reject($id)
    {
        try {
            Pengajuan::where('id', $id)->update(['status' => '0']);
            return Endpoint::success(200, 'Berhasil menolak pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menolak pengajuan');
        }
    }

    function print($id)
    {
        try {
            Pengajuan::where('id', $id)->update(['status' => '3']);
            return Endpoint::success(200, "Berhasil");
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function getCount()
    {
        try {
            return Endpoint::success(200, 'Berhasil', Pengajuan::count('kartu')->distinct());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function getCountBySatker()
    {
        try {
            return Endpoint::success(200, 'Berhasil',);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function status()
    {
        $res = [
            'ditolak'    => Pengajuan::where('status', '0')->count(),
            'proses'     => Pengajuan::where('status', '1')->count(),
            'verifikasi' => Pengajuan::where('status', '2')->count(),
            'setuju'     => Pengajuan::where('status', '3')->count(),
        ];
        return Endpoint::success(200, 'Berhasil', $res);
    }

    function top5()
    {
        try {
            $pengajuan = Pengajuan::groupBy('kode_satker')->limit(5);
            if (!$pengajuan) {
                return Endpoint::success(200, 'Data pengajuan masih kosong / tidak ada!');
            }
            return Endpoint::success(200, 'Berhasil', $pengajuan);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }
}
