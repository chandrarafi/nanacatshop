<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDendaFieldsToPenitipan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('penitipan', [
            'tglpenjemputan' => [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'tglselesai'
            ],
            'is_terlambat' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'status',
                'comment'    => '0=Tidak Terlambat, 1=Terlambat'
            ],
            'jumlah_hari_terlambat' => [
                'type'       => 'INT',
                'default'    => 0,
                'after'      => 'is_terlambat'
            ],
            'biaya_denda' => [
                'type'       => 'DOUBLE',
                'default'    => 0,
                'after'      => 'jumlah_hari_terlambat'
            ],
            'total_biaya_dengan_denda' => [
                'type'       => 'DOUBLE',
                'default'    => 0,
                'after'      => 'grandtotal'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('penitipan', ['tglpenjemputan', 'is_terlambat', 'jumlah_hari_terlambat', 'biaya_denda', 'total_biaya_dengan_denda']);
    }
}
