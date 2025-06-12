<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Jalankan semua seeder
        $this->call('UserSeeder');
        $this->call('PelangganSeeder');
        $this->call('HewanSeeder');
    }
}
