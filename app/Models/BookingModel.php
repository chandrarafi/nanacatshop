<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id',
        'pelanggan_id',
        'service_id',
        'service_name',
        'price',
        'booking_date',
        'booking_time',
        'status',
        'notes',
        'payment_type',
        'payment_proof'
    ];

    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'service_id' => 'permit_empty|is_natural',
        'service_name' => 'required|string|min_length[3]|max_length[150]',
        'price' => 'required|is_natural',
        'booking_date' => 'required|valid_date',
        'booking_time' => 'required',
        'status' => 'permit_empty|in_list[pending,confirmed,completed,cancelled]',
        'payment_type' => 'permit_empty|in_list[dp,lunas]'
    ];

    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
