<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PelangganModel;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        $pelangganModel = new PelangganModel();

        // Fungsi untuk generate ID pelanggan
        $generateId = function ($index) {
            $prefix = 'PLG';
            $date = date('Ymd');
            $sequence = str_pad($index, 4, '0', STR_PAD_LEFT);
            return $prefix . $date . $sequence;
        };

        // Data dummy pelanggan
        $data = [
            [
                'idpelanggan' => $generateId(1),
                'nama' => 'Budi Santoso',
                'jenkel' => 'L',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'nohp' => '081234567890',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(2),
                'nama' => 'Siti Nurhaliza',
                'jenkel' => 'P',
                'alamat' => 'Jl. Pahlawan No. 45, Bandung',
                'nohp' => '082345678901',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(3),
                'nama' => 'Ahmad Dhani',
                'jenkel' => 'L',
                'alamat' => 'Jl. Sudirman No. 78, Surabaya',
                'nohp' => '083456789012',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(4),
                'nama' => 'Dewi Lestari',
                'jenkel' => 'P',
                'alamat' => 'Jl. Gatot Subroto No. 56, Yogyakarta',
                'nohp' => '084567890123',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(5),
                'nama' => 'Rudi Hartono',
                'jenkel' => 'L',
                'alamat' => 'Jl. Ahmad Yani No. 34, Semarang',
                'nohp' => '085678901234',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(6),
                'nama' => 'Ratna Sari',
                'jenkel' => 'P',
                'alamat' => 'Jl. Diponegoro No. 67, Malang',
                'nohp' => '086789012345',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(7),
                'nama' => 'Andi Wijaya',
                'jenkel' => 'L',
                'alamat' => 'Jl. Veteran No. 89, Makassar',
                'nohp' => '087890123456',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(8),
                'nama' => 'Rina Marlina',
                'jenkel' => 'P',
                'alamat' => 'Jl. Imam Bonjol No. 12, Medan',
                'nohp' => '088901234567',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(9),
                'nama' => 'Doni Kusuma',
                'jenkel' => 'L',
                'alamat' => 'Jl. Thamrin No. 23, Denpasar',
                'nohp' => '089012345678',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpelanggan' => $generateId(10),
                'nama' => 'Maya Putri',
                'jenkel' => 'P',
                'alamat' => 'Jl. Gajah Mada No. 45, Palembang',
                'nohp' => '081234567891',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert data
        foreach ($data as $item) {
            $pelangganModel->insert($item);
        }
    }
}
