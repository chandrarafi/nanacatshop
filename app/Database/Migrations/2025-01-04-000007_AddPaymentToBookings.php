<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentToBookings extends Migration
{
    public function up()
    {
        $fields = [
            'payment_type' => [
                'type' => 'ENUM',
                'constraint' => ['dp', 'lunas'],
                'default' => 'dp',
                'null' => false,
                'after' => 'status'
            ],
            'payment_proof' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'payment_type'
            ],
        ];
        $this->forge->addColumn('bookings', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', 'payment_proof');
        $this->forge->dropColumn('bookings', 'payment_type');
    }
}
