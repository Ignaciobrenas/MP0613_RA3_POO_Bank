<?php namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class InterestTransaction extends BaseTransaction implements BankTransactionInterface
{
    public function __construct(float $amount = 0.0)
    {
        $this->amount = $amount;
    }

    public function applyTransaction(BankAccountInterface $bankAccount): float 
    {
        $interestRate = $bankAccount->getOverdraft()->getInterestRate();
        $this->amount = $bankAccount->getBalance() * ($interestRate / 100);

        return $bankAccount->getBalance() + $this->amount;
    }

    public function getTransactionInfo(): string
    {
        return 'INTEREST_TRANSACTION';
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
