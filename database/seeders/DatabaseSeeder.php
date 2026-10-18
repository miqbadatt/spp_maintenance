<?php

namespace Database\Seeders;

use App\Models\KategoriSpp;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════
        //  1. USERS (admin & kepala sekolah)
        // ══════════════════════════════════════════

        User::create([
            'name'     => 'Admin La-Taksal',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Kepala Sekolah La-Taksal',
            'email'    => 'kepsek@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'kepala_sekolah',
        ]);

        // ══════════════════════════════════════════
        //  2. KATEGORI SPP
        // ══════════════════════════════════════════

        $kategori2025 = KategoriSpp::create([
            'tahun_ajaran' => '2025/2026',
            'nominal_spp'  => 250000,
        ]);

        $kategori2026 = KategoriSpp::create([
            'tahun_ajaran' => '2026/2027',
            'nominal_spp'  => 275000,
        ]);

        // ══════════════════════════════════════════
        //  3. DATA SANTRI ASLI (dari Data_Santri.xlsx)
        //     1 siswa = 1 wali murid
        //     Nama wali : "Orangtua {nama siswa}"
        //     Email wali: orangtua.{nama.siswa}@gmail.com
        // ══════════════════════════════════════════

        $dataSantri = [
            ['kelas' => 'VII', 'nis' => '26.27.8.001', 'nama' => 'Aat Atmada Aan'],
            ['kelas' => 'VII', 'nis' => '26.27.8.002', 'nama' => 'Achmad Aditya Nurwahid'],
            ['kelas' => 'VII', 'nis' => '26.27.8.003', 'nama' => 'Dani Saputra'],
            ['kelas' => 'VII', 'nis' => '26.27.8.004', 'nama' => 'Dhern Andrana Mirdad'],
            ['kelas' => 'VII', 'nis' => '26.27.8.005', 'nama' => 'Fatir Qura Haqudin'],
            ['kelas' => 'VII', 'nis' => '26.27.8.006', 'nama' => 'Muhamad Baihaqi Hafiz'],
            ['kelas' => 'VII', 'nis' => '26.27.8.007', 'nama' => 'Muhammad Baqi Bilal Pratama'],
            ['kelas' => 'VII', 'nis' => '26.27.8.008', 'nama' => 'Muhammad Raffi Wijaya'],
            ['kelas' => 'VII', 'nis' => '26.27.8.009', 'nama' => 'Siti Yuningsih'],
            ['kelas' => 'VII', 'nis' => '26.27.8.010', 'nama' => 'Zaki Ibnu Hafidz'],
            ['kelas' => 'VII', 'nis' => '26.27.8.011', 'nama' => 'Zein Malik Ibrohim'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.001', 'nama' => 'Abdul Wahhab Asyamna'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.002', 'nama' => 'Anisa Awalia'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.003', 'nama' => 'Ardita Anggraeni'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.004', 'nama' => 'Ayu Amelia'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.005', 'nama' => 'Ega Dwi Cahya'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.006', 'nama' => 'Elki Ardian'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.007', 'nama' => 'Fitri Salwa Adelia'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.008', 'nama' => 'Manda Aulia'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.009', 'nama' => 'Mayda Aldinia Maharani'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.010', 'nama' => 'Muhamad Adwa'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.011', 'nama' => 'Muhamad Aldiansyah'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.012', 'nama' => 'Muhamad Reihan Nurizki'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.013', 'nama' => 'Putri Naila'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.014', 'nama' => 'Rifqi Zainul Muttaqin'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.015', 'nama' => 'Riskia Alifa Saputri'],
            ['kelas' => 'VIII', 'nis' => '25.26.7.016', 'nama' => 'Zidni Nur Alia'],
            ['kelas' => 'IX', 'nis' => '24.25.6.001', 'nama' => 'Firda Nur Azizzahra'],
            ['kelas' => 'IX', 'nis' => '24.25.6.002', 'nama' => 'Haerul Adzam'],
            ['kelas' => 'IX', 'nis' => '24.25.6.003', 'nama' => 'Indiani'],
            ['kelas' => 'IX', 'nis' => '24.25.6.004', 'nama' => 'Ipariz Maulidani Akbar'],
            ['kelas' => 'IX', 'nis' => '24.25.6.005', 'nama' => 'Jidan Abrori'],
            ['kelas' => 'IX', 'nis' => '24.25.6.006', 'nama' => 'M. Ade Putra Rizalil Alam'],
            ['kelas' => 'IX', 'nis' => '24.25.6.007', 'nama' => 'Muhamad Alpian'],
            ['kelas' => 'IX', 'nis' => '24.25.6.008', 'nama' => 'Muhamad Apriyansah'],
            ['kelas' => 'IX', 'nis' => '24.25.6.009', 'nama' => 'Muhamad Fathan Fadillah'],
            ['kelas' => 'IX', 'nis' => '24.25.6.010', 'nama' => 'Muhamad Ibnu Solihin'],
            ['kelas' => 'IX', 'nis' => '24.25.6.011', 'nama' => 'Muhammad Riswandi'],
            ['kelas' => 'IX', 'nis' => '24.25.6.012', 'nama' => 'Najwa Kirania Saputra'],
            ['kelas' => 'IX', 'nis' => '24.25.6.013', 'nama' => 'Rohimat'],
            ['kelas' => 'IX', 'nis' => '24.25.6.014', 'nama' => 'Siti Mutmainatun Kamilah'],
            ['kelas' => 'X', 'nis' => '23.24.5.002', 'nama' => 'Faisal Luthfi'],
            ['kelas' => 'X', 'nis' => '23.24.5.003', 'nama' => 'Fuji Rahmawati'],
            ['kelas' => 'X', 'nis' => '23.24.5.004', 'nama' => 'Mahmud Badarrudin'],
        ];

        $siswas = collect();

        foreach ($dataSantri as $s) {
            $wali = User::create([
                'name'     => 'Orangtua ' . $s['nama'],
                'email'    => 'orangtua.' . Str::slug($s['nama'], '.') . '@gmail.com',
                'password' => Hash::make('password123'),
                'role'     => 'wali_murid',
            ]);

            $siswas->push(Siswa::create([
                'id_user'     => $wali->id,
                'id_kategori' => $kategori2026->id_kategori,
                'nis'         => $s['nis'],
                'nama_siswa'  => $s['nama'],
                'kelas'       => $s['kelas'],
            ]));
        }
    }
}