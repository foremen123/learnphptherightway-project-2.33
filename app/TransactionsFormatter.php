<?php

declare(strict_types=1);

namespace App;

use DateTime;

class TransactionsFormatter
{
    public static function amountDollars (float $amount): string
    {
        $isNegative = $amount < 0;

        return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
    }

    public static function dateDB (string $date): string
    {
        return DateTime::createFromFormat('y-m-d', $date)->format('y-m-d');
    }

    public static function dateFormatted (string $date):string
    {
        return DateTime::createFromFormat('y-m-d', $date)->format('M-j-o');
    }

    public static function amountDB (string $amount): float
    {
       return (float) str_replace(['$', ','], '', $amount);
    }
}