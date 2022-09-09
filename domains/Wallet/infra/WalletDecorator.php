<?php

namespace Workshop\Domains\Wallet\Infra;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class WalletDecorator implements MessageDecorator
{
    public function decorate(Message $message): Message
    {
        return $message->withHeader('x-random-number', rand());
    }
}
