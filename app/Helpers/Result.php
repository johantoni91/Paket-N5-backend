<?php

namespace App\Helpers;

class Result
{
    public static function user($user)
    {
        return [
            "id" => $user->id,
            "username" => $user->username,
            "name" => $user->name,
            "satker" => $user->satker,
            "roles" => $user->roles,
            "status" => $user->status,
            "nip" => $user->nip,
            "nrp" => $user->nrp ?? '',
            "email" => $user->email ?? '',
            "phone" => $user->phone ?? '',
            "photo" => $user->photo ?? '',
            "email_verified_at" => $user->email_verified_at ?? '',
            "token" => $user->token,
            "remember_token" => $user->remember_token,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at
        ];
    }

    public static function pegawai($pegawai)
    {
        return [
            "nama" => $pegawai->nama,
            "jabatan" => $pegawai->jabatan ?? '',
            "nip" => $pegawai->nip,
            "nrp" => $pegawai->nrp ?? '',
            "tgl_lahir" => $pegawai->tgl_lahir,
            "eselon" => $pegawai->eselon ?? '',
            "jenis_kelamin" => $pegawai->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN',
            "GOL_KD" => $pegawai->GOL_KD ?? '',
            "golpang" => $pegawai->golpang ?? '',
            "foto_pegawai" => $pegawai->foto_pegawai ?? '',
            "nama_satker" => $pegawai->nama_satker,
            "agama" => $pegawai->agama ?? '',
            "status_pegawai" => $pegawai->status_pegawai,
            "jaksa_tu" => $pegawai->jaksa_tu,
            "struktural_non" => $pegawai->struktural_non
        ];
    }
}
