<?php

declare(strict_types=1);

namespace App;

class CalculateTransaction
{
    // Здесь будет логика итога, дохода и расхода

    public static function calculateTotal(array $transactions): array
    {
        $total = [
            'income' => 0,
            'expense' => 0,
            'net'
        ];

        foreach ($transactions as $transaction) {
            if ($transaction['amount'] > 0) {
                $total['income'] += $transaction['amount'];
            } else if ($transaction['amount'] < 0) {
                $total['expense'] += abs($transaction['amount']);
            }
        }

        $total['net'] = $total['income'] - $total['expense'];

        return $total;
    }

    public static function formatTotal(array $total): array
    {
        return [
            'income' => Helper::formatAmount($total['income']),
            'expense' => Helper::formatDate($total['expense']),
            'net' => Helper::formatAmount($total['net']),
        ];
    }
}