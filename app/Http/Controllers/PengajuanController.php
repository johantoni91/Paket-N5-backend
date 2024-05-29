<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Kartu;
use App\Models\Notif;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// NB : STATUS
// 0 = DITOLAK
// 1 = PROSES
// 2 = APPROVED

// NB : ALASAN
// 0 = RUSAK
// 1 = BARU
// 2 = GANTI SATKER
// 3 = DLL

class PengajuanController extends Controller
{
    function index($id)
    {
        try {
            $kode = SatkerCode::parent($id);
            if ($kode == '2' && $id != '98') {
                $data = Pengajuan::orderBy('created_at', 'asc')->where('kode_satker', 'LIKE', $id . '%')->paginate(5);
            } elseif ($kode == '0') {
                $data = Pengajuan::orderBy('created_at', 'asc')->paginate(5);
            } else {
                $data = Pengajuan::orderBy('created_at', 'asc')->where('kode_satker', $id)->paginate(5);
            }
            return Endpoint::success('Berhasil mendapatkan data pengajuan', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan data pengajuan');
        }
    }

    function findByUser($user)
    {
        return Endpoint::success('Berhasil', Pengajuan::where('nip', $user)->get());
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
            return Endpoint::success('Berhasil monitoring data pengajuan', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal monitoring data pengajuan');
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
                return Endpoint::success('Tidak ada data pengajuan');
            }
            return Endpoint::success('Berhasil mendapatkan data pengajuan', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan data pengajuan!');
        }
    }

    function find($id)
    {
        try {
            return Endpoint::success('Berhasil', Pengajuan::findOrFail($id));
        } catch (\Throwable $th) {
            return Endpoint::failed("Gagal");
        }
    }

    function store(Request $req)
    {
        try {
            $this->validate($req, [
                'nip'         => 'required',
                'kartu'       => 'required',
                'alasan'      => 'required',
            ]);

            $pegawai = Pegawai::where('nip', $req->nip)->first();
            if (!$pegawai) {
                return Endpoint::warning('Pengajuan gagal, pegawai tidak ditemukan.');
            }

            $satker = Satker::where('satker_name', 'LIKE', '%' . $pegawai->nama_satker . '%')->first();
            if ($satker->satker_code != $req->satker_code && $req->satker_code != '00') {
                return Endpoint::warning('Pengajuan harus dengan satker yang sama!');
            }
            $input = [
                'id'          => mt_rand(),
                'nip'         => $req->nip,
                'kode_satker' => $satker->satker_code,
                'photo'       => $req->hasFile('photo') ? env('APP_IMG', '') . '/kartu/' . $req->file('photo')->getClientOriginalName() : '',
                'kartu'       => $req->kartu,
                'alasan'      => $req->alasan
            ];

            $kartu = Kartu::where('id', $req->kartu)->first();
            if (!$kartu) {
                return Endpoint::warning('Kartu belum / tidak ada. Tanyakan pada superadmin.');
            } elseif ($kartu->card == '' || $kartu->card == null) {
                return Endpoint::warning('Kartu yang anda ajukan belum ditambahkan dengan tanda tangan, mohon agar menambahkan tanda tangan pada profil.');
            }

            Pengajuan::insert($input);
            $kartu->update(['total' => DB::raw('total + 1')]);
            Notif::insert([
                'notifikasi'  => $req->nama . ' mengajukan kartu.',
                'kode_satker' => $req->satker_code,
            ]);

            if ($req->hasFile('photo')) {
                $req->file('photo')->move('pengajuan', $req->file('photo')->getClientOriginalName());
            }
            return Endpoint::success('Berhasil menambahkan data pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menambahkan data pengajuan', $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $pengajuan = Pengajuan::find($id);
            $pengajuan->update(['kartu' => $req->kartu]);
            return Endpoint::success('Data pengajuan telah berubah');
        } catch (\Throwable $th) {
            return Endpoint::failed('Data pengajuan gagal berubah');
        }
    }

    function destroy($id)
    {
        try {
            $pengajuan = Pengajuan::find($id);
            $pengajuan->delete();
            return Endpoint::success('Berhasil menghapus data pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menghapus data pengajuan');
        }
    }

    function approve(Request $req, $id, $satker)
    {
        try {
            $kode = SatkerCode::parent($satker);
            if ($kode == '3') {
                return Endpoint::warning('Pengajuan CABJARI hanya dapat disetujui oleh KEJARI');
            } else {
                Pengajuan::where('id', $id)->where('status', '1')->update([
                    'token'          => $req->token,
                    'status'         => '2',
                    'barcode'        => $req->barcode,
                    'qrcode'         => $req->qrCode
                ]);
            }
            return Endpoint::success('Berhasil menyetujui pengajuan', Pengajuan::where('id', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menyetujui pengajuan', $th->getMessage());
        }
    }

    function reject($id)
    {
        try {
            Pengajuan::where('id', $id)->update(['status' => '0']);
            return Endpoint::success('Berhasil menolak pengajuan');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menolak pengajuan');
        }
    }

    function print($id)
    {
        try {
            Pengajuan::where('id', $id)->update(['status' => '2']);
            return Endpoint::success("Berhasil");
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function getCount()
    {
        try {
            return Endpoint::success('Berhasil', Pengajuan::count('kartu'));
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function getCountBySatker()
    {
        try {
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
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
        return Endpoint::success('Berhasil', $res);
    }

    function top5()
    {
        try {
            $pengajuan = Pengajuan::select('kode_satker', DB::raw('count(*) as count'))
                ->groupBy('kode_satker')->orderByDesc('count')->take(5)->get();
            return Endpoint::success('Berhasil', $pengajuan);
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }
}
