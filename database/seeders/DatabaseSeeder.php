<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        // Create divisions
        $divisi1 = Divisi::create(['nama_divisi' => 'Web Development']);
        $divisi2 = Divisi::create(['nama_divisi' => 'Mobile Development']);
        $divisi3 = Divisi::create(['nama_divisi' => 'Data Science']);

        // Create positions
        $jabatan1 = Jabatan::create(['nama_jabatan' => 'Ketua']);
        $jabatan2 = Jabatan::create(['nama_jabatan' => 'Wakil Ketua']);
        $jabatan3 = Jabatan::create(['nama_jabatan' => 'Anggota']);

        // Create members
        Anggota::create([
            'nama' => 'Admin Anggota',
            'email' => 'admin@gmail.com',
            'nim' => '12345678',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('admin123'),
            'divisi_id' => $divisi1->id,
            'jabatan_id' => $jabatan1->id,
        ]);

        Anggota::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'nim' => '12345679',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('budi123'),
            'divisi_id' => $divisi2->id,
            'jabatan_id' => $jabatan2->id,
        ]);

        Anggota::create([
            'nama' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'nim' => '12345680',
            'jurusan' => 'Sistem Informasi',
            'password' => Hash::make('siti123'),
            'divisi_id' => $divisi3->id,
            'jabatan_id' => $jabatan3->id,
        ]);
    }
}