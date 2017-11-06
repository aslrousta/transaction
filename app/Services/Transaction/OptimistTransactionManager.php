<?php

namespace App\Services\Transaction;

use App\Account;
use App\Transaction;

class OptimistTransactionManager implements TransactionManagerInterface
{
    /**
     * {@InheritDoc}
     */
    public function transfer($fromAccountId, $toAccountId, $amount)
    {
        $fromQuery = Account::whereId($fromAccountId);
        if (! $fromQuery->exists()) {
            throw new InvalidAccountException();
        }

        $toQuery = Account::whereId($toAccountId);
        if (! $toQuery->exists()) {
            throw new InvalidAccountException();
        }

        do {

            /** @var Account $fromAccount */
            $fromAccount = $fromQuery->first();
            if ($fromAccount->balance < $amount) {
                throw new InsufficientBalanceException();
            }

            $updated = Account::whereId($fromAccountId)
                ->where('updated_at', '=', $fromAccount->updated_at)
                ->update(['balance' => $fromAccount->balance - $amount]);

        } while (! $updated);

        do {

            /** @var Account $fromAccount */
            $toAccount = $toQuery->first();

            $updated = Account::whereId($toAccountId)
                ->where('updated_at', '=', $toAccount->updated_at)
                ->update(['balance' => $toAccount->balance + $amount]);

        } while (! $updated);

        $transaction = new Transaction();
        $transaction->from_account_id = $fromAccountId;
        $transaction->to_account_id   = $toAccountId;
        $transaction->amount          = $amount;
        $transaction->save();
    }
}
