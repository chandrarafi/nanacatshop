<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'pelanggan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'service_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'price' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
            ],
            'booking_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'booking_time' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'completed', 'cancelled'],
                'default' => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('service_id');
        $this->forge->createTable('bookings', true);
    }

    public function down()
    {
        $this->forge->dropTable('bookings', true);
    }
}
