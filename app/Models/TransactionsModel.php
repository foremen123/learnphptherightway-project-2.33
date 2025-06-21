<?php

declare(strict_types=1);

namespace App\Models;

use App\TransactionsFormatter;
use App\View;

class TransactionsModel extends Model
{
    // будем здесь делать запросы на запись и вывод данных из бд

    public function addTransactions (array $transactions): void
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare(
                'INSERT INTO transactions (date, check_number, description, amount) VALUES (?, ?, ?, ?)'
            );

            foreach ($transactions as $transaction) {
                if (!($stmt->execute(
                    [
                        $transaction['date'],
                        $transaction['check_number'],
                        $transaction['description'],
                        $transaction['amount'],
                    ]
                ))) {
                    throw new \PDOException('Ошибка при вставке данных');
                }
            }
            $this->db->commit();

        } catch (\PDOException $e) {
            if ($this->db->inTransaction()){
                $this->db->rollBack();
            }

            http_response_code(500);
            throw new \PDOException($e->getMessage() . $e->getLine());
        }
    }

    public function getAll (): array
    {
        $this->db->beginTransaction();

        $stmt = $this->db->prepare('SELECT * FROM transactions');
        $stmt->execute();
        $transactions = $stmt->fetchAll();

        $formattedTransactions = [];

        foreach ($transactions as $transaction) {
            $transaction =
                [
                    'date' => TransactionsFormatter::dateFormatted($transaction['date']),
                    'check_number' => (int) $transaction['check_number'] ?: '',
                    'description' => $transaction['description'],
                    'amount' => TransactionsFormatter::amountDollars((float) $transaction['amount'])
                ];
            $formattedTransactions[] = $transaction;
        }

        return $formattedTransactions;
    }
}