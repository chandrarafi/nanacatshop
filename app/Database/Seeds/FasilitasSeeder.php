<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kdfasilitas' => 'FSL001',
                'namafasilitas' => 'Kandang Kucing Standard',
                'kategori' => 'Kandang',
                'harga' => 50000,
                'satuan' => 'Hari',
                'keterangan' => 'Kandang ukuran standard untuk 1 kucing'
            ],
            [
                'kdfasilitas' => 'FSL002',
                'namafasilitas' => 'Kandang Kucing Premium',
                'kategori' => 'Kandang',
                'harga' => 75000,
                'satuan' => 'Hari',
                'keterangan' => 'Kandang ukuran besar dengan tempat bermain'
            ],
            [
                'kdfasilitas' => 'FSL003',
                'namafasilitas' => 'Kandang Anjing Kecil',
                'kategori' => 'Kandang',
                'harga' => 60000,
                'satuan' => 'Hari',
                'keterangan' => 'Kandang untuk anjing ras kecil'
            ],
            [
                'kdfasilitas' => 'FSL004',
                'namafasilitas' => 'Kandang Anjing Besar',
                'kategori' => 'Kandang',
                'harga' => 100000,
                'satuan' => 'Hari',
                'keterangan' => 'Kandang untuk anjing ras besar'
            ],
            [
                'kdfasilitas' => 'FSL005',
                'namafasilitas' => 'Makanan Kucing Premium',
                'kategori' => 'Makanan',
                'harga' => 25000,
                'satuan' => 'Hari',
                'keterangan' => 'Makanan kucing kualitas premium 3x sehari'
            ],
            [
                'kdfasilitas' => 'FSL006',
                'namafasilitas' => 'Makanan Anjing Premium',
                'kategori' => 'Makanan',
                'harga' => 30000,
                'satuan' => 'Hari',
                'keterangan' => 'Makanan anjing kualitas premium 3x sehari'
            ],
            [
                'kdfasilitas' => 'FSL007',
                'namafasilitas' => 'Grooming Kucing Basic',
                'kategori' => 'Grooming',
                'harga' => 80000,
                'satuan' => 'Kali',
                'keterangan' => 'Mandi, potong kuku, sisir bulu'
            ],
            [
                'kdfasilitas' => 'FSL008',
                'namafasilitas' => 'Grooming Kucing Premium',
                'kategori' => 'Grooming',
                'harga' => 150000,
                'satuan' => 'Kali',
                'keterangan' => 'Mandi, potong kuku, sisir bulu, styling, parfum'
            ],
            [
                'kdfasilitas' => 'FSL009',
                'namafasilitas' => 'Grooming Anjing Basic',
                'kategori' => 'Grooming',
                'harga' => 100000,
                'satuan' => 'Kali',
                'keterangan' => 'Mandi, potong kuku, sisir bulu'
            ],
            [
                'kdfasilitas' => 'FSL010',
                'namafasilitas' => 'Grooming Anjing Premium',
                'kategori' => 'Grooming',
                'harga' => 200000,
                'satuan' => 'Kali',
                'keterangan' => 'Mandi, potong kuku, sisir bulu, styling, parfum'
            ],
            [
                'kdfasilitas' => 'FSL011',
                'namafasilitas' => 'Pemeriksaan Kesehatan Dasar',
                'kategori' => 'Medis',
                'harga' => 75000,
                'satuan' => 'Kali',
                'keterangan' => 'Pemeriksaan kondisi fisik hewan oleh dokter hewan'
            ],
            [
                'kdfasilitas' => 'FSL012',
                'namafasilitas' => 'Vaksinasi',
                'kategori' => 'Medis',
                'harga' => 250000,
                'satuan' => 'Kali',
                'keterangan' => 'Vaksinasi rutin untuk kucing/anjing'
            ],
            [
                'kdfasilitas' => 'FSL013',
                'namafasilitas' => 'Obat Kutu',
                'kategori' => 'Medis',
                'harga' => 60000,
                'satuan' => 'Kali',
                'keterangan' => 'Pemberian obat kutu dan parasit'
            ],
            [
                'kdfasilitas' => 'FSL014',
                'namafasilitas' => 'Bermain dan Olahraga',
                'kategori' => 'Lainnya',
                'harga' => 30000,
                'satuan' => 'Hari',
                'keterangan' => 'Sesi bermain dan olahraga dengan pengasuh 2x sehari'
            ],
            [
                'kdfasilitas' => 'FSL015',
                'namafasilitas' => 'Foto dan Video Update',
                'kategori' => 'Lainnya',
                'harga' => 15000,
                'satuan' => 'Hari',
                'keterangan' => 'Update foto dan video hewan peliharaan setiap hari'
            ],
        ];

        // Insert data ke tabel fasilitas
        $this->db->table('fasilitas')->insertBatch($data);
    }
}
