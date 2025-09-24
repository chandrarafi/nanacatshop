<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentBank extends Migration
{
    public function up()
    {
        // Add column to bookings
        if (!$this->db->fieldExists('payment_bank', 'bookings')) {
            $this->forge->addColumn('bookings', [
                'payment_bank' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'payment_type'
                ],
            ]);
        }

        // Add column to orders
        if (!$this->db->fieldExists('payment_bank', 'orders')) {
            $this->forge->addColumn('orders', [
                'payment_bank' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'payment_proof'
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('payment_bank', 'bookings')) {
            $this->forge->dropColumn('bookings', 'payment_bank');
        }
        if ($this->db->fieldExists('payment_bank', 'orders')) {
            $this->forge->dropColumn('orders', 'payment_bank');
        }
    }
}
