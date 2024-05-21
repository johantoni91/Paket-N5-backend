<?php

namespace App\Helpers;

use App\Models\Pengajuan;

// NB : SATKER_KODE || KODE 
// 0 = KEJAGUNG
// 1 = KEJATI
// 2 = KEJARI
// 3 = CABJARI

// NB : APPROVE_SATKER
// 0 = KEJAGUNG
// 1 = KEJATI
// 2 = KEJARI
// 3 = KEJARI

// NB : STATUS APPROVAL
// 0 = DITOLAK
// 1 = PROSES
// 2 = APPROVAL DARI KEJATI
// 3 = DITERIMA OLEH KEJAGUNG

class HelpersPengajuan
{
    public static function index($kode, $id)
    {
        if ($kode == '0') {
            return Pengajuan::orderBy('created_at', 'asc')->where('approve_satker', '0')->orWhere('approve_satker', '1')->where('status', '2')->paginate(5);
        } elseif ($kode == '1') {
            return Pengajuan::orderBy('created_at', 'asc')->where('kode_satker', 'LIKE', $id . '%')->where('approve_satker', '2')->where('status', '1')->paginate(5);
        } elseif ($kode == '2') {
            return Pengajuan::orderBy('created_at', 'asc')->where('kode_satker', 'LIKE', $id . '%')->where('approve_satker', '3')->where('status', '1')->paginate(5);
        } else {
            return Pengajuan::orderBy('created_at', 'asc')->where('kode_satker', 'LIKE', $id . '%')->paginate(5);
        }
    }

    public static function approve($id, $satker, $token, $barcode, $qrCode = null)
    {
        $pengajuan = Pengajuan::where('id', $id)->where('kode_satker', 'LIKE', $satker . '%')->first();
        $kode = SatkerCode::parent($satker);
        $pengajuan_agung = Pengajuan::where('id', $id)->first();
        if ($kode == '0' && $pengajuan_agung->approve_satker == '1') {
            Pengajuan::where('id', $id)->where('approve_satker', '1')->update([
                'token'          => $token,
                'status'         => '3',
                'approve_satker' => '0',
                'barcode'        => $barcode,
                'qrcode'         => $qrCode
            ]);
        } elseif ($kode == '1' && $pengajuan->approve_satker == '2') {
            Pengajuan::where('id', $id)->where('kode_satker', 'LIKE', $satker . '%')->update([
                'token'          => $token,
                'status'         => '2',
                'approve_satker' => '1'
            ]);
        } elseif ($kode == '2' && $pengajuan->approve_satker == '3') {
            Pengajuan::where('id', $id)->where('kode_satker', 'LIKE', $satker . '%')->update([
                'token'          => $token,
                'status'         => '1',
                'approve_satker' => '2'
            ]);
        }
    }
}
