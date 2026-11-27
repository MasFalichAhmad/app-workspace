<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\ServiceType;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@servis.id'],
            ['name' => 'Admin Falich', 'password' => Hash::make('password'), 'role' => 'admin']
        );
        User::updateOrCreate(
            ['email' => 'teknisi@servis.id'],
            ['name' => 'Teknisi Fakih', 'password' => Hash::make('password'), 'role' => 'teknisi']
        );

        $services = [
            ['nama_layanan' => 'Ganti LCD / Layar', 'biaya_jasa' => 150000, 'estimasi_hari' => 2, 'deskripsi' => 'Pemasangan layar baru + kalibrasi'],
            ['nama_layanan' => 'Ganti Baterai', 'biaya_jasa' => 75000, 'estimasi_hari' => 1, 'deskripsi' => 'Penggantian baterai dan cek arus'],
            ['nama_layanan' => 'Servis Motherboard / IC', 'biaya_jasa' => 350000, 'estimasi_hari' => 4, 'deskripsi' => 'Perbaikan jalur daya / IC mati total'],
            ['nama_layanan' => 'Install Ulang & Cleaning', 'biaya_jasa' => 100000, 'estimasi_hari' => 1, 'deskripsi' => 'Install OS dan ganti thermal paste'],
        ];
        foreach ($services as $s) {
            ServiceType::updateOrCreate(['nama_layanan' => $s['nama_layanan']], $s);
        }

        $parts = [
            ['kode_part' => 'PRT-001', 'nama_part' => 'LCD OLED Universal', 'kategori' => 'Layar', 'harga' => 450000, 'stok' => 10],
            ['kode_part' => 'PRT-002', 'nama_part' => 'Baterai Original 5000mAh', 'kategori' => 'Baterai', 'harga' => 180000, 'stok' => 15],
            ['kode_part' => 'PRT-003', 'nama_part' => 'SSD NVMe 512GB', 'kategori' => 'Storage', 'harga' => 650000, 'stok' => 8],
            ['kode_part' => 'PRT-004', 'nama_part' => 'IC Power Management', 'kategori' => 'Board', 'harga' => 120000, 'stok' => 12],
        ];
        foreach ($parts as $p) {
            Sparepart::updateOrCreate(['kode_part' => $p['kode_part']], $p);
        }

        Customer::updateOrCreate(
            ['no_hp' => '081234567890'],
            ['nama' => 'Umair Muhammad', 'email' => 'umair@kampus.ac.id', 'alamat' => 'Jl. Sukolilo No. 12, Surabaya']
        );
        Customer::updateOrCreate(
            ['no_hp' => '081234567891'],
            ['nama' => 'Adam Gusti', 'email' => 'adam@kampus.ac.id', 'alamat' => 'Jl. Gebang Putih No. 5, Surabaya']
        );
    }
}