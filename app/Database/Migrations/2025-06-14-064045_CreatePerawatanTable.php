<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePerawatanTable extends Migration
{
    public function up()
    {
        // Tabel Perawatan
        $this->forge->addField([
            'kdperawatan' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
            ],
            'tglperawatan' => [
                'type'           => 'DATE',
                'null'           => false,
            ],
            'idpelanggan' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
                'null'           => true,
            ],
            'idhewan' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
                'null'           => true,
            ],
            'grandtotal' => [
                'type'           => 'DOUBLE',
                'null'           => false,
                'default'        => 0,
            ],
            'status' => [
                'type'           => 'INT',
                'constraint'     => 1,
                'null'           => false,
                'default'        => 0,
                'comment'        => '0: Pending, 1: Dalam Proses, 2: Selesai',
            ],
            'keterangan' => [
                'type'           => 'TEXT',
                'null'           => true,
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
        $this->forge->addKey('kdperawatan', true);
        $this->forge->addForeignKey('idpelanggan', 'pelanggan', 'idpelanggan', 'CASCADE', 'SET NULL');
        $this->forge->createTable('perawatan');

        // Tabel Detail Perawatan
        $this->forge->addField([
            'iddetail' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'detailkdperawatan' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
                'null'           => false,
            ],

            'detailkdfasilitas' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
                'null'           => false,
            ],
            'jumlah' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'null'           => false,
                'default'        => 1,
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
            'statusdetail' => [
                'type'           => 'INT',
                'constraint'     => 1,
                'null'           => false,
                'default'        => 0,
                'comment'        => '0: Menunggu, 1: Dalam Proses, 2: Selesai',
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
        $this->forge->addForeignKey('detailkdperawatan', 'perawatan', 'kdperawatan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('detailidhewan', 'hewan', 'idhewan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('detailkdfasilitas', 'fasilitas', 'kdfasilitas', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detailperawatan');
    }

    public function down()
    {
        // Hapus tabel detail terlebih dahulu karena memiliki foreign key ke tabel perawatan
        $this->forge->dropTable('detailperawatan');

        // Kemudian hapus tabel perawatan
        $this->forge->dropTable('perawatan');
    }
}
