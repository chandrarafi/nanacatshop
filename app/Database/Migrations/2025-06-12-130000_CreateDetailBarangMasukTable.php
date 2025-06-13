<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailBarangMasukTable extends Migration
{
    public function up()
    {
        // Tabel Detail Barang Masuk
        $this->forge->addField([
            'iddetail' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'detailkdmasuk' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
                'null'           => false,
            ],
            'detailkdbarang' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
                'null'           => false,
            ],
            'jumlah' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'null'           => false,
                'default'        => 0,
            ],
            'harga' => [
                'type'           => 'DOUBLE',
                'null'           => false,
                'default'        => 0,
            ],
            'totalharga' => [
                'type'           => 'DOUBLE',
                'null'           => false,
                'default'        => 0,
            ],
            'namabarang' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => false,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addKey('iddetail', true);
        $this->forge->addForeignKey('detailkdmasuk', 'barangmasuk', 'kdmasuk', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('detailkdbarang', 'barang', 'kdbarang', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detailbarangmasuk');
    }

    public function down()
    {
        $this->forge->dropTable('detailbarangmasuk');
    }
}
