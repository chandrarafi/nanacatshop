<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShippingFieldsToOrders extends Migration
{
    public function up()
    {
        $fields = [
            'courier' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'payment_method',
            ],
            'tracking_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'courier',
            ],
            'shipped_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'tracking_number',
            ],
        ];

        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', ['courier', 'tracking_number', 'shipped_at']);
    }
}



