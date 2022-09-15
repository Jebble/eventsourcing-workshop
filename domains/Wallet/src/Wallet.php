<?php

namespace Workshop\Domains\Wallet;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Workshop\Domains\Wallet\Events\TokensDeposited;
use Workshop\Domains\Wallet\Events\TokensWithdrawn;
use Workshop\Domains\Wallet\Events\TokenWithdrawalFailed;
use Workshop\Domains\Wallet\Exceptions\TokenWithdrawalException;
use Workshop\Domains\Wallet\WithdrawalFailureType;

class Wallet implements AggregateRoot
{
    use AggregateRootBehaviour;

    protected int $tokens = 0;

    public function deposit(int $tokens)
    {
        $this->recordThat(new TokensDeposited($tokens));
    }

    public function withdraw(int $tokens)
    {
        if ($this->tokens < $tokens) {
            $this->recordThat(new TokenWithdrawalFailed(WithdrawalFailureType::InsufficientFunds));
            throw TokenWithdrawalException::insufficientFunds();
        }

        $this->recordThat(new TokensWithdrawn($tokens));
    }

    private function applyTokensDeposited(TokensDeposited $event): void
    {
        //2033fd7f-2d9e-4009-ae9c-1e512845e886
        $this->tokens += $event->tokens;
    }

    private function applyTokensWithdrawn(TokensWithdrawn $event): void
    {
        $this->tokens -= $event->tokens;
    }

    private function applyTokenWithdrawalFailed(TokenWithdrawalFailed $event): void
    {
    }
}
