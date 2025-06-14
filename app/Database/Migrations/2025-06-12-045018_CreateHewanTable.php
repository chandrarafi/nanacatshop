<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHewanTable extends Migration
{
    public function up()
    {
        // Tambahkan kolom satuan_umur ke tabel hewan yang sudah ada
        $tableExists = $this->db->tableExists('hewan');
        if ($tableExists) {
            // Cek apakah kolom sudah ada
            $fieldExists = $this->db->fieldExists('satuan_umur', 'hewan');
            if (!$fieldExists) {
                // Tambahkan kolom baru
                $this->forge->addColumn('hewan', [
                    'satuan_umur' => [
                        'type'       => 'ENUM',
                        'constraint' => ['tahun', 'bulan'],
                        'default'    => 'tahun',
                        'after'      => 'umur',
                        'comment'    => 'Satuan umur hewan: tahun atau bulan',
                    ],
                ]);
            }
        } else {
            // Buat tabel jika belum ada
            $this->forge->addField([
                'idhewan' => [
                    'type'           => 'VARCHAR',
                    'constraint'     => 30,
                ],
                'namahewan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'jenis' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'comment'    => 'Jenis hewan: Domestic, Campuran, Persian, Maine Coon, Siamese, British Shorthair, Ragdoll, Bengal, Sphynx, Scottish Fold, Angora, Himalayan',
                ],
                'umur' => [
                    'type'       => 'INT',
                    'constraint' => 3,
                    'null'       => true,
                ],
                'satuan_umur' => [
                    'type'       => 'ENUM',
                    'constraint' => ['tahun', 'bulan'],
                    'default'    => 'tahun',
                    'comment'    => 'Satuan umur hewan: tahun atau bulan',
                ],
                'jenkel' => [
                    'type'       => 'ENUM',
                    'constraint' => ['L', 'P'],
                    'comment'    => 'L=Laki-laki, P=Perempuan',
                ],
                'foto' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                ],
                'idpelanggan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
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

            $this->forge->addKey('idhewan', true);
            $this->forge->addForeignKey('idpelanggan', 'pelanggan', 'idpelanggan', 'CASCADE', 'CASCADE');
            $this->forge->createTable('hewan');
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('satuan_umur', 'hewan')) {
            $this->forge->dropColumn('hewan', 'satuan_umur');
        }
    }
}
