<!DOCTYPE html>
<html>
    <head>
        <title>Transactions</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            table tr th, table tr td {
                padding: 5px;
                border: 1px #eee solid;
            }

            tfoot tr th, tfoot tr td {
                font-size: 20px;
            }

            tfoot tr th {
                text-align: right;
            }
        </style>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check #</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($transactions as $transaction) {
                        if (count($transaction) < 4) {
                            throw new \Exception('Invalid transaction format: ' . implode(',', $transaction));
                        }

                        $date = htmlspecialchars($transaction['date']);
                        $checkNumber = htmlspecialchars($transaction['check_number']);
                        $description = htmlspecialchars($transaction['description']);
                        $amount = htmlspecialchars($transaction['amount']);

                        echo "<tr>
                                <td>{$date}</td>
                                <td>{$checkNumber}</td>
                                <td>{$description}</td>
                                <td>{$amount}</td>
                              </tr>";
                    }

                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Income:</th>
                    <td><?=$total['income']?></td>
                </tr>
                <tr>
                    <th colspan="3">Total Expense:</th>
                    <td><?=$total['expense']?></td>
                </tr>
                <tr>
                    <th colspan="3">Net Total:</th>
                    <td><?=$total['net']?></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>
