<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\TransactionAddException;
use App\View;

class TransactionModel extends Model
{
    // Здесь будет ввод данных в бд, вывод данных,
    public function addTransactions ($transactions): void
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                'INSERT INTO transactions (date, check_number, descriptions, amount) VALUES (?, ?, ?, ?)'
            );
            foreach ($transactions as $transaction) {
               if (!$stmt->execute(
                    [
                        $transaction['date'],
                        $transaction['check_number'],
                        $transaction['descriptions'],
                        $transaction['amount']
                    ]
                )) {
                    throw new TransactionAddException('Failed to add transaction');
               }
            }

            $this->db->commit();

        } catch (\PDOException |TransactionAddException) {

            $this->db->rollBack();
            http_response_code(500);
            echo View::make('error/500');
            exit;
        }
    }

    public function getAllTransactions(): array
    {

        try {
            $stmt = $this->db->prepare('SELECT * FROM transactions');

            $stmt->execute();

            $transactions = $stmt->fetchAll();

            $formattedAmount = [];

            foreach ($transactions as $transaction) {
                (float) $transaction['amount'] = str_replace(['$', ','], '', $transaction['amount']);

                $formattedAmount[] = $transaction['amount'];
            }

            return $formattedAmount;

        } catch (\Exception $e) {

            http_response_code(502);

            throw new TransactionAddException('Failed to get transactions');
        }
    }
}