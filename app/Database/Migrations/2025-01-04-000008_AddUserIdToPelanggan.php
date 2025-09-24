<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToPelanggan extends Migration
{
    public function up()
    {
        $fields = [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'idpelanggan'
            ]
        ];
        $this->forge->addColumn('pelanggan', $fields);
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_pelanggan_user_id ON pelanggan (user_id)');
    }

    public function down()
    {
        $this->forge->dropColumn('pelanggan', 'user_id');
    }
}
