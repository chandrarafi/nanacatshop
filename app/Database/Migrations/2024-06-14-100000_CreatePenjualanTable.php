<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanTable extends Migration
{
    public function up()
    {
        // Tabel penjualan
        $this->forge->addField([
            'kdpenjualan' => [
                'type' => 'CHAR',
                'constraint' => 30,
            ],
            'tglpenjualan' => [
                'type' => 'DATE',
            ],
            'idpelanggan' => [
                'type' => 'CHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'grandtotal' => [
                'type' => 'DOUBLE',
                'default' => 0,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '0=pending, 1=selesai',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('kdpenjualan', true); // Primary Key
        $this->forge->addForeignKey('idpelanggan', 'pelanggan', 'idpelanggan', '', 'CASCADE', '', 'SET NULL'); // FK ke pelanggan dengan SET NULL jika pelanggan dihapus
        $this->forge->createTable('penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('penjualan');
    }
}
