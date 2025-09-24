<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class BookingConfig extends BaseConfig
{
    /**
     * Default maximum bookings per time slot. Can be overridden via env:
     * booking.maxPerSlot=5
     */
    public int $maxPerSlot = 5;

    /**
     * Available booking time slots in 24h format (HH:MM)
     */
    public array $timeSlots = [
        '09:00',
        '10:00',
        '11:00',
        '13:00',
        '14:00',
        '15:00',
        '16:00'
    ];
}
