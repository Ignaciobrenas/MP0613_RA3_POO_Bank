<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\OverdraftStrategy\GoldOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Transactions\InterestTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
$bankAccount1 = new BankAccount(400.0);
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // show balance account
    pl('My balance : ' . $bankAccount1->getBalance());

    // close account
    $bankAccount1->closeAccount();
    pl('My account is now closed.');

    // reopen account
    $bankAccount1->reopenAccount();
    pl('My account is now reopened.');


    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());

    //añado una transaccion de tipo deposito
    $bankAccount1->transaction(new DepositTransaction(150.0));

    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());

    $bankAccount1->transaction(new WithdrawTransaction(25.0));

    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());

    $bankAccount1->transaction(new WithdrawTransaction(600.0));

} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());

// close account
$bankAccount1->closeAccount();
pl('My account is now closed.');




//---[Bank account 2]---/
//creo la cuenta2
$bankAccount2 = new BankAccount(200.0);

//le pongo silver para que se pueda quedar a -100 en vez de 0
$bankAccount2->applyOverdraft(new SilverOverdraft());
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {
    
    // show balance account
    pl('My balance : ' . $bankAccount2->getBalance());
   
    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new DepositTransaction(100.0));
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(300.0));
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(50.0));
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(120.0));
    
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(20.0));
    
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}

pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

// close account
$bankAccount2->closeAccount();
pl('My account is now closed.');

try {
    $bankAccount2->closeAccount();
} catch (BankAccountException $e) {
    pl($e->getMessage());
}

pl('sample 1.');
$accountSample1 = new BankAccount(1000.0);
$accountSample1->applyOverdraft(new NoOverdraft());
pl('Account Balance: $' . number_format($accountSample1->getBalance(), 2));
pl('Overdraft Strategy: NoOverdraft');
pl('Interest Rate: ' . $accountSample1->getOverdraft()->getInterestRate() . '%');
$accountSample1->transaction(new InterestTransaction());
pl('Balance After Transaction: $' . number_format($accountSample1->getBalance(), 2));

pl('sample 2.');
$accountSample2 = new BankAccount(1000.0);
$accountSample2->applyOverdraft(new SilverOverdraft());
pl('Account Balance: $' . number_format($accountSample2->getBalance(), 2));
pl('Overdraft Strategy: SilverOverdraft');
pl('Interest Rate: ' . $accountSample2->getOverdraft()->getInterestRate() . '%');
$accountSample2->transaction(new InterestTransaction());
pl('Balance After Transaction: $' . number_format($accountSample2->getBalance(), 2));

// sample 3.
pl('sample 3.');
$accountSample3 = new BankAccount(1000.0);
$accountSample3->applyOverdraft(new GoldOverdraft());
pl('Account Balance: $' . number_format($accountSample3->getBalance(), 2));
pl('Overdraft Strategy: GoldOverdraft');
pl('Interest Rate: ' . $accountSample3->getOverdraft()->getInterestRate() . '%');
$accountSample3->transaction(new InterestTransaction());
pl('Balance After Transaction: $' . number_format($accountSample3->getBalance(), 2));
