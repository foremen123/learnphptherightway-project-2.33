<?php

namespace App\Transactions;

use DateTime;

class TransactionFormatter
{
    public static function formatAmountDB(string $amount): string
    {
        return str_replace(['$', ','], '', $amount, );
    }

    public static function formatDateDB(string $date): string
    {
        $originalDate = $date;
        $date = DateTime::createFromFormat('d/m/Y', $date);

        if (!$date) throw new \InvalidArgumentException("Неверный формат даты: $originalDate");

        return $date->format('Y/m/d');
    }

    public static function formatAmountView(string $amount): string
    {
        return ( $amount < 0 ? '-' : '') . '$' . number_format(abs((float) $amount), 2);
    }

    public static function formatDateView(string $date): string
    {
        return DateTime::createFromFormat('Y-m-d', $date)->format('M-d-Y');
    }
}