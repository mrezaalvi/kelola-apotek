<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('profile.nama_apotek', 'APOTEK AR-RAHMAH');
        $this->migrator->add('profile.nama_pemilik', 'Junaidi Fatrizal');
        $this->migrator->add('profile.alamat', 'Komp.Ruko Sampurna Centre Blok  B No. 3A');
        $this->migrator->add('profile.provinsi', '');
        $this->migrator->add('profile.kabupaten_kota', '');
        $this->migrator->add('profile.kecamatan', '');
        $this->migrator->add('profile.kelurahan_desa', '');
        $this->migrator->add('profile.no_telp', '0811666300');
        $this->migrator->add('profile.email', '');
        $this->migrator->add('profile.logo', 'apotek/logo.png');
        $this->migrator->add('profile.slogan', '');
        $this->migrator->add('profile.sia_no', '05052300195860001');
        $this->migrator->add('profile.tgl_exp_sia', null);
        $this->migrator->add('profile.berkas_sia', null);
        $this->migrator->add('profile.apoteker_id', null);
    }
};
