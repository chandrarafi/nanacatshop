<?php

if (!function_exists('rupiah')) {
    function rupiah($number, int $decimals = 0): string
    {
        if ($number === null || $number === '') {
            $number = 0;
        }
        return 'Rp ' . number_format((float)$number, $decimals, ',', '.');
    }
}



