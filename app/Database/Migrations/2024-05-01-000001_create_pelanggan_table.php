<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePelangganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idpelanggan' => [
                'type'           => 'CHAR',
                'constraint'     => 30,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'jenkel' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'comment'    => 'L=Laki-laki, P=Perempuan',
            ],
            'alamat' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'nohp' => [
                'type'       => 'CHAR',
                'constraint' => 15,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('idpelanggan', true);
        $this->forge->createTable('pelanggan');
    }

    public function down()
    {
        $this->forge->dropTable('pelanggan');
    }
}
