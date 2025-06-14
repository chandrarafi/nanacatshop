<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPenitipanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'iddetail' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kdpenitipan' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'idhewan' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'kdfasilitas' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'default'    => 1,
            ],
            'harga' => [
                'type'       => 'DOUBLE',
                'default'    => 0,
            ],
            'totalharga' => [
                'type'       => 'DOUBLE',
                'default'    => 0,
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

        $this->forge->addKey('iddetail', true);
        $this->forge->addForeignKey('kdpenitipan', 'penitipan', 'kdpenitipan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('idhewan', 'hewan', 'idhewan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kdfasilitas', 'fasilitas', 'kdfasilitas', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detailpenitipan');
    }

    public function down()
    {
        $this->forge->dropTable('detailpenitipan');
    }
}
