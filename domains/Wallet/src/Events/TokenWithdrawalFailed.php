<?php

namespace Workshop\Domains\Wallet\Events;

use Workshop\Domains\Wallet\WithdrawalFailureType;

final class TokenWithdrawalFailed
{
    public function __construct(
        public readonly WithdrawalFailureType $reason
    ) {
    }

    public static function insufficientFunds(): self
    {
        return new self(WithdrawalFailureType::InsufficientFunds);
    }
}
