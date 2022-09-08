<?php

use Workshop\Domains\Wallet\Events\TokensDeposited;
use Workshop\Domains\Wallet\Events\TokensWithdrawn;
use Workshop\Domains\Wallet\Events\TokenWithdrawalFailed;
use Workshop\Domains\Wallet\Exceptions\TokenWithdrawalException;
use Workshop\Domains\Wallet\Tests\WalletTestCase;
use Workshop\Domains\Wallet\Wallet;
use Workshop\Domains\Wallet\WithdrawalFailureType;

class DepositTokensTest extends WalletTestCase
{
    /** @test */
    public function it_can_deposit_tokens()
    {
        $this->given()
            ->when(fn (Wallet $wallet) => $wallet->deposit(100))
            ->then(new TokensDeposited(100));
    }

    /** @test */
    public function it_can_withdraw_tokens()
    {
        $this->given(new TokensDeposited(100))
            ->when(fn (Wallet $wallet) => $wallet->withdraw(100))
            ->then(new TokensWithdrawn(100));
    }

    /** @test */
    public function it_can_not_withdraw_more_tokens_than_available()
    {
        $this->given(new TokensDeposited(10))
            ->when(fn (Wallet $wallet) => $wallet->withdraw(50))
            ->then(new TokenWithdrawalFailed(WithdrawalFailureType::InsufficientFunds))
            ->expectToFail(TokenWithdrawalException::insufficientFunds());
    }
}
