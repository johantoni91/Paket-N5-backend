<?php

namespace app\Helpers;

use App\Models\Pegawai;

class HelpersPegawai
{
    public static function searchRes($nama, $nip, $nrp, $satker)
    {
        if ($nama && !$nip && !$nrp) {
            if ($satker == "KEJAKSAAN AGUNG") {
                return Pegawai::orderBy('nama')
                    ->where('nama', 'LIKE', '%' . $nama . '%')
                    ->paginate(10)->appends([
                        'nama'           =>  $nama
                    ]);
            }
            return Pegawai::orderBy('nama')
                ->where('nama_satker', ucwords($satker))
                ->where('nama', 'LIKE', '%' . $nama . '%')
                ->paginate(10)->appends([
                    'nama'           =>  $nama
                ]);
        } elseif (!$nama && $nip && !$nrp) {
            if ($satker == "KEJAKSAAN AGUNG") {
                return Pegawai::orderBy('nama')
                    ->where('nama', 'LIKE', '%' . $nama . '%')
                    ->paginate(10)->appends([
                        'nama'           =>  $nama
                    ]);
            }
            return Pegawai::orderBy('nama')
                ->where('nama_satker', ucwords($satker))
                ->where('nip', 'LIKE', '%' . $nip . '%')
                ->paginate(10)->appends([
                    'nip'           =>  $nip
                ]);
        } elseif (!$nama && !$nip && $nrp) {
            if ($satker == "KEJAKSAAN AGUNG") {
                return Pegawai::orderBy('nama')
                    ->where('nama', 'LIKE', '%' . $nama . '%')
                    ->paginate(10)->appends([
                        'nama'           =>  $nama
                    ]);
            }
            return Pegawai::orderBy('nama')
                ->where('nama_satker', ucwords($satker))
                ->where('nrp', 'LIKE', '%' . $nrp . '%')
                ->paginate(10)->appends([
                    'nrp'           =>  $nrp
                ]);
        } elseif ($nama && $nip && !$nrp) {
            if ($satker == "KEJAKSAAN AGUNG") {
                return Pegawai::orderBy('nama')
                    ->where('nama', 'LIKE', '%' . $nama . '%')
                    ->paginate(10)->appends([
                        'nama'           =>  $nama
                    ]);
            }
            return Pegawai::orderBy('nama')
                ->where('nama_satker', ucwords($satker))
                ->where('nama', 'LIKE', '%' . $nama . '%')
                ->where('nip', 'LIKE', '%' . $nip . '%')
                ->paginate(10)->appends([
                    'nama'           =>  $nama,
                    'nip'            =>  $nip,
                ]);
        } elseif ($nama && !$nip && $nrp) {
            if ($satker == "KEJAKSAAN AGUNG") {
                return Pegawai::orderBy('nama')
                    ->where('nama', 'LIKE', '%' . $nama . '%')
                    ->paginate(10)->appends([
                        'nama'           =>  $nama
                    ]);
            }
            return Pegawai::orderBy('nama')
                ->where('nama_satker', ucwords($satker))
                ->where('nama', 'LIKE', '%' . $nama . '%')
                ->where('nrp', 'LIKE', '%' . $nrp . '%')
                ->paginate(10)->appends([
                    'nama'           =>  $nama,
                    'nrp'            =>  $nrp,
                ]);
        } elseif (!$nama && $nip && $nrp) {
            if ($satker == "KEJAKSAAN AGUNG") {
                return Pegawai::orderBy('nama')
                    ->where('nama', 'LIKE', '%' . $nama . '%')
                    ->paginate(10)->appends([
                        'nama'           =>  $nama
                    ]);
            }
            return Pegawai::orderBy('nama')
                ->where('nama_satker', ucwords($satker))
                ->where('nip', 'LIKE', '%' . $nip . '%')
                ->where('nrp', 'LIKE', '%' . $nrp . '%')
                ->paginate(10)->appends([
                    'nip'           =>  $nip,
                    'nrp'            =>  $nrp,
                ]);
        } else {
            if ($satker == "KEJAKSAAN AGUNG") {
                return Pegawai::orderBy('nama')
                    ->where('nama', 'LIKE', '%' . $nama . '%')
                    ->paginate(10)->appends([
                        'nama'           =>  $nama
                    ]);
            }
            return Pegawai::orderBy('nama')
                ->where('nama_satker', ucwords($satker))
                ->where('nama', 'LIKE', '%' . $nama . '%')
                ->where('nip', 'LIKE', '%' . $nip . '%')
                ->where('nrp', 'LIKE', '%' . $nrp . '%')
                ->paginate(10)->appends([
                    'nama'           =>  $nama,
                    'nip'            =>  $nip,
                    'nrp'            =>  $nrp,
                ]);
        }
    }
}
