<?php

namespace App\Models;

use App\Exceptions\DontSaveTransactionException;

class TransactionModel extends Model
{
    /**
     * @throws DontSaveTransactionException
     */
    public function saveTodDataBase(array $transactions): void
    {
        try {
            $this->db->beginTransaction();
            foreach ($transactions as $transaction) {
                $stmt = $this->db->prepare
                ('
                INSERT INTO transactions (date, check_number, description, amount) VALUES (?, ?, ?, ?)
            ');

                $stmt->execute(
                    [
                        $transaction['date'],
                        $transaction['check_number'],
                        $transaction['description'],
                        $transaction['amount']
                    ]
                );
            }
            $this->db->commit();
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            http_response_code(500);
            throw new DontSaveTransactionException();
        }
    }

    public function getAll(): array
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare('SELECT * FROM transactions');
            $stmt->execute();

            $this->db->commit();

            return $stmt->fetchAll();
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            http_response_code(501);

            throw new \Exception('Error fetching transactions: ' . $e->getMessage());
        }

    }
}