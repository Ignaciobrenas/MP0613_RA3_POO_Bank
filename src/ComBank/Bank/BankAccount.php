<?php namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BankAccountInterface
{
    use AmountValidationTrait;

    private float $balance;
    private string $status;
    private OverdraftInterface $overdraft;

    public function __construct(float $balance = 100.0)
    {
        $this->validateAmount($balance);
        $this->balance = $balance;
        $this->status = BankAccountInterface::STATUS_OPEN;
        //añado la deuda que puede tener la cuenta 
        $this->overdraft = new NoOverdraft();
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }

    public function isOpen(): bool
    {
        return $this->status === BankAccountInterface::STATUS_OPEN;
    }

    public function closeAccount(): void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException('Account is already closed.');
        }
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    public function reopenAccount(): void
    {
        if ($this->isOpen()) {
            throw new BankAccountException('Account is already open.');
        }
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    public function transaction(BankTransactionInterface $transaction): void
    {
        if (!$this->isOpen()) {
            throw new BankAccountException('Account is closed.');
        }

        $this->setBalance($transaction->applyTransaction($this));
    }
}
