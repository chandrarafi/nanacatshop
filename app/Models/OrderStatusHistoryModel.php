<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderStatusHistoryModel extends Model
{
    protected $table = 'order_status_histories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['order_id', 'status', 'note'];
    protected $useTimestamps = true;

    public function logStatus(int $orderId, string $status, ?string $note = null): bool
    {
        return (bool) $this->insert([
            'order_id' => $orderId,
            'status' => $status,
            'note' => $note,
        ]);
    }

    public function getTimeline(int $orderId): array
    {
        return $this->where('order_id', $orderId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }
}



