<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBarangTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kdbarang' => [
                'type'           => 'VARCHAR',
                'constraint'     => 25,
            ],
            'namabarang' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'hargabeli' => [
                'type'       => 'DOUBLE',
                'default'    => 0,
            ],
            'hargajual' => [
                'type'       => 'DOUBLE',
                'default'    => 0,
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'kdkategori' => [
                'type'       => 'CHAR',
                'constraint' => 7,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('kdbarang', true);
        $this->forge->addForeignKey('kdkategori', 'kategori', 'kdkategori', 'CASCADE', 'CASCADE');
        $this->forge->createTable('barang');
    }

    public function down()
    {
        $this->forge->dropTable('barang');
    }
}
