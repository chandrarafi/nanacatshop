<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\HewanModel;

class HewanSeeder extends Seeder
{
    public function run()
    {
        $hewanModel = new HewanModel();

        // Fungsi untuk generate ID hewan
        $generateId = function ($index) {
            $prefix = 'HWN';
            $date = date('Ymd');
            $sequence = str_pad($index, 4, '0', STR_PAD_LEFT);
            return $prefix . $date . $sequence;
        };

        // Get existing pelanggan IDs from database
        $db = \Config\Database::connect();
        $pelangganIds = $db->table('pelanggan')->select('idpelanggan')->limit(5)->get()->getResultArray();

        // Fallback if no pelanggan exist
        if (empty($pelangganIds)) {
            $pelangganIds = [
                ['idpelanggan' => 'PLG' . date('Ymd') . '0001'],
                ['idpelanggan' => 'PLG' . date('Ymd') . '0002'],
                ['idpelanggan' => 'PLG' . date('Ymd') . '0003'],
                ['idpelanggan' => 'PLG' . date('Ymd') . '0004'],
                ['idpelanggan' => 'PLG' . date('Ymd') . '0005'],
            ];
        }

        // Jenis hewan (1=Kucing, 2=Anjing)
        $jenisHewan = ['1', '2'];

        // Jenis kelamin (L=Laki-laki, P=Perempuan)
        $jenkelOptions = ['L', 'P'];

        // Nama-nama hewan untuk kucing dan anjing
        $namaKucing = ['Milo', 'Luna', 'Bella', 'Charlie', 'Max', 'Lucy', 'Leo', 'Coco', 'Simba', 'Kitty'];
        $namaAnjing = ['Rocky', 'Buddy', 'Max', 'Bailey', 'Cooper', 'Daisy', 'Sadie', 'Molly', 'Lola', 'Jack'];

        // Data hewan
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $jenis = $jenisHewan[rand(0, 1)];
            $namahewan = ($jenis == '1') ? $namaKucing[rand(0, 9)] : $namaAnjing[rand(0, 9)];

            $data[] = [
                'idhewan' => $generateId($i),
                'namahewan' => $namahewan,
                'jenis' => $jenis,
                'umur' => rand(1, 10),
                'jenkel' => $jenkelOptions[rand(0, 1)],
                'foto' => 'default_' . ($jenis == '1' ? 'cat' : 'dog') . '.jpg',
                'idpelanggan' => $pelangganIds[rand(0, count($pelangganIds) - 1)]['idpelanggan'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        // Insert data
        $hewanModel->insertBatch($data);
    }
}
