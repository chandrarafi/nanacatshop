<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPenjualanTable extends Migration
{
    public function up()
    {
        // Tabel detail penjualan
        $this->forge->addField([
            'iddetail' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'detailkdpenjualan' => [
                'type' => 'CHAR',
                'constraint' => 30,
            ],
            'detailkdbarang' => [
                'type' => 'CHAR',
                'constraint' => 30,
            ],
            'jumlah' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'harga' => [
                'type' => 'DOUBLE',
                'default' => 0,
            ],
            'totalharga' => [
                'type' => 'DOUBLE',
                'default' => 0,
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

        $this->forge->addKey('iddetail', true); // Primary Key
        $this->forge->addForeignKey('detailkdpenjualan', 'penjualan', 'kdpenjualan', '', 'CASCADE'); // FK ke penjualan
        $this->forge->addForeignKey('detailkdbarang', 'barang', 'kdbarang', '', 'CASCADE'); // FK ke barang
        $this->forge->createTable('detailpenjualan');
    }

    public function down()
    {
        $this->forge->dropTable('detailpenjualan');
    }
}
