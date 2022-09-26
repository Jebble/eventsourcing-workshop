<?php

namespace Workshop\Domains\Wallet\Upcasters;

use EventSauce\EventSourcing\Upcasting\Upcaster;

class TransactedAtUpcaster implements Upcaster
{
    public const EVENT_TYPES_TO_UPCAST = [
        'tokens_deposited',
        'tokens_withdrawn'
    ];

    public function upcast(array $message): array
    {
        if ($this->shouldUpcastMessage($message)) {
            $message['payload']['transacted_at'] = $message['headers']['__time_of_recording'];
        }

        return $message;
    }

    private function shouldUpcastMessage(array $message): bool
    {
        return in_array($message['headers']['__event_type'], self::EVENT_TYPES_TO_UPCAST) &&
            !in_array('transacted_at', array_keys($message['payload']));
    }
}
