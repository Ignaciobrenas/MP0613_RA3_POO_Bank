<?php namespace ComBank\OverdraftStrategy;

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

/**
 * @description: Grant 500.00 overdraft funds.
 * */
class GoldOverdraft implements OverdraftInterface 
{
    public function isGrantOverdraftFunds(float $newAmount): bool
    {
        return $newAmount >= -500.0;
    }

    public function getOverdraftFundsAmount(): float
    {
        return 500.0;
    }

    public function getInterestRate(): int
    {
        return 10;
    }
}
