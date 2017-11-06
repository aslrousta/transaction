<?php

namespace App\Services\Transaction;

use App\Account;
use App\Transaction;
use Illuminate\Support\Facades\DB;

class PessimistTransactionManager implements TransactionManagerInterface
{
    /**
     * {@InheritDoc}
     */
    public function transfer($fromAccountId, $toAccountId, $amount)
    {
        DB::beginTransaction();

        try {

            $fromQuery = Account::whereId($fromAccountId);
            if (! $fromQuery->exists()) {
                throw new InvalidAccountException();
            }

            $toQuery = Account::whereId($toAccountId);
            if (! $toQuery->exists()) {
                throw new InvalidAccountException();
            }

            /** @var Account $fromAccount */
            $fromAccount = $fromQuery->lockForUpdate()->first();
            if ($fromAccount->balance < $amount) {
                throw new InsufficientBalanceException();
            }

            /** @var Account $toAccount */
            $toAccount = $toQuery->lockForUpdate()->first();

            $toAccount->balance += $amount;
            $toAccount->save();

            $fromAccount->balance -= $amount;
            $fromAccount->save();

            $transaction = new Transaction();
            $transaction->from_account_id = $fromAccountId;
            $transaction->to_account_id   = $toAccountId;
            $transaction->amount          = $amount;
            $transaction->save();

            DB::commit();

            return $fromAccount->balance;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
