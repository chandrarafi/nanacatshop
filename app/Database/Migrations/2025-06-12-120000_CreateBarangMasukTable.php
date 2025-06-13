<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBarangMasukTable extends Migration
{
    public function up()
    {
        // Tabel Barang Masuk
        $this->forge->addField([
            'kdmasuk' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
            ],
            'tglmasuk' => [
                'type'           => 'DATE',
                'null'           => false,
            ],
            'kdspl' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
                'null'           => false,
            ],
            'grandtotal' => [
                'type'           => 'DOUBLE',
                'null'           => false,
                'default'        => 0,
            ],
            'keterangan' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'status' => [
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'null'           => false,
                'default'        => 0,
                'comment'        => '0=pending, 1=selesai',
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

        $this->forge->addKey('kdmasuk', true);
        $this->forge->addForeignKey('kdspl', 'supplier', 'kdspl', 'CASCADE', 'CASCADE');
        $this->forge->createTable('barangmasuk');
    }

    public function down()
    {
        $this->forge->dropTable('barangmasuk');
    }
}
