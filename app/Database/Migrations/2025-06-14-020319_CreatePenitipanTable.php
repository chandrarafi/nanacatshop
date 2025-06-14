<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenitipanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kdpenitipan' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'tglpenitipan' => [
                'type'       => 'DATE',
            ],
            'tglselesai' => [
                'type'       => 'DATE',
            ],
            'idpelanggan' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
            ],
            'durasi' => [
                'type'       => 'INT',
                'default'    => 1,
            ],
            'grandtotal' => [
                'type'       => 'DOUBLE',
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '0=Pending, 1=Dalam Penitipan, 2=Selesai',
            ],
            'keterangan' => [
                'type'       => 'TEXT',
                'null'       => true,
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

        $this->forge->addKey('kdpenitipan', true);
        $this->forge->addForeignKey('idpelanggan', 'pelanggan', 'idpelanggan', 'CASCADE', 'SET NULL');
        $this->forge->createTable('penitipan');
    }

    public function down()
    {
        $this->forge->dropTable('penitipan');
    }
}
