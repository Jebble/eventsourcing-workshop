<?php

namespace Workshop\Domains\Wallet\Upcasters;

use EventSauce\EventSourcing\Upcasting\Upcaster;

class TokenAmountCorrectionUpcaster implements Upcaster
{
    private array $corrections = [
        '3457bb73-0a77-49a5-9efb-35ee4dee4f26' => 10
    ];

    public function upcast(array $message): array
    {
        if ($correction = $this->corrections[$message['headers']['__event_id']] ?? null) {
            $message['payload']['tokens'] = $correction;
        }

        return $message;
    }
}
