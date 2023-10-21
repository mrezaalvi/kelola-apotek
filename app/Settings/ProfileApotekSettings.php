<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProfileApotekSettings extends Settings
{
    public string | null $nama_apotek = null;
    public string | null $alamat;
    public string | null $provinsi;
    public string | null $kabupaten_kota;
    public string | null $kecamatan;
    public string | null $kelurahan_desa;
    public string | null $no_telp;
    public string | null $email;
    public string | null $logo;
    public string | null $slogan;
    public string | null $sia_no;
    public string | null $tgl_exp_sia;
    public string | null $berkas_sia;
    public int | null $apoteker_id;

    public static function group(): string
    {
        return 'profile';
    }
}