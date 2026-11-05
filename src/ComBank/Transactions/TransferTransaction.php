<?php namespace ComBank\Transactions;

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class TransferTransaction extends BaseTransaction implements BankTransactionInterface
{
    private $destinationAccount;
    public function __construct(float $amount, BankAccountInterface $destinationAccount)
    {
        parent::__construct($amount);
        $this->destinationAccount = $destinationAccount;
    }

    public function applyTransaction(BankAccountInterface $bankAccount): float 
    {
        $newBalance = $bankAccount->getBalance() - $this->amount;
        $newBalance2= $this->destinationAccount->getBalance() + $this->amount;
    $this->destinationAccount->setBalance($newBalance2);
        return $newBalance;
        

    }

    public function getTransactionInfo(): string
    {
        return 'TRANSFER_TRANSACTION';
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

     public function setDestinationAccount(BankAccountInterface $bankAccount)
    {
        $this->destinationAccount=$bankAccount;
       return $bankAccount;
    }

    public function getDestinationAccount() : BankAccountInterface
    {
      return $this->destinationAccount;
    }

}
