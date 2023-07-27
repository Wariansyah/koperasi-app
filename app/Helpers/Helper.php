<?php

namespace App\Helpers;

class FormatHelper
{
    public static function formatRupiah($nominal)
    {
        return "Rp " . number_format($nominal, 0, ',', '.');
    }
}
