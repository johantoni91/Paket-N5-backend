<?php

namespace App\Helpers;

use App\Models\Pengajuan;

// NB : SATKER_KODE || KODE 
// 0 = KEJAGUNG
// 1 = KEJATI
// 2 = KEJARI
// 3 = CABJARI

// NB : STATUS
// 0 = DITOLAK
// 1 = PROSES
// 2 = APPROVAL KEJARI (KHUSUS CABJARI)
// 3 = DITERIMA OLEH KEJAGUNG

class HelpersPengajuan
{
    public static function approve($id, $satker, $token, $barcode, $qrCode = null)
    {
        $pengajuan = Pengajuan::where('id', $id)->where('kode_satker', 'LIKE', $satker . '%')->first();
        $kode = SatkerCode::parent($satker);
        $pengajuan_agung = Pengajuan::where('id', $id)->first();
        if ($kode == '3') {
            Pengajuan::where('id', $id)->where('approve_satker', '1')->update([
                'token'          => $token,
                'status'         => '3',
                'approve_satker' => '0',
                'barcode'        => $barcode,
                'qrcode'         => $qrCode
            ]);
        } else {
            Pengajuan::where('id', $id)->where('kode_satker', 'LIKE', $satker . '%')->update([
                'token'          => $token,
                'status'         => '2',
                'approve_satker' => '1'
            ]);
        }
    }
}
