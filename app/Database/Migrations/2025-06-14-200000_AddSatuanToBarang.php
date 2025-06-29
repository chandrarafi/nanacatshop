<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSatuanToBarang extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang', [
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'hargajual'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang', 'satuan');
    }
}
