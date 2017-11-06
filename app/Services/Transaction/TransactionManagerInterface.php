<?php

namespace App\Services\Transaction;

interface TransactionManagerInterface
{
    /**
     * Transfers given amount from one account to another.
     *
     * @param int $fromAccountId
     * @param int $toAccountId
     * @param int $amount
     * @return int remaining account balance
     * @throws InvalidAccountException if at least one of accounts is non-existent
     * @throws InsufficientBalanceException if source account has insufficient balance
     */
    public function transfer($fromAccountId, $toAccountId, $amount);
}
