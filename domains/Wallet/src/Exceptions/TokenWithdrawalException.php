<?php

namespace Workshop\Domains\Wallet\Exceptions;

use RuntimeException;

class TokenWithdrawalException extends RuntimeException
{
    public static function insufficientFunds(): static
    {
        return new self("Not enough tokens available to withdraw the amount requested.");
    }
}
