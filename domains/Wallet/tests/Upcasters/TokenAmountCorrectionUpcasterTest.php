<?php

namespace Workshop\Domains\Wallet\Tests\Upcasters;

use PHPUnit\Framework\TestCase;
use Workshop\Domains\Wallet\Upcasters\TokenAmountCorrectionUpcaster;

class TokenAmountCorrectionUpcasterTest extends TestCase
{
    public function setUp(): void
    {
        $this->upcaster = new TokenAmountCorrectionUpcaster();
        parent::setUp();
    }

    /** @test */
    public function events_not_in_corrections_config_will_not_be_changed()
    {
        $input = [
            'headers' => [
                '__event_type' => 'tokens_deposited',
                '__event_id' => '42df5a04-d806-48b7-b0f6-8723d6bd9be6',
            ],
            'payload' => [
                'tokens' => 100
            ]
        ];

        $output = $this->upcast($input);

        $this->assertEquals($output, $input);
    }

    /** @test */
    public function events_in_corrections_will_be_changed()
    {
        $input = [
            'headers' => [
                '__event_type' => 'tokens_deposited',
                '__event_id' => '3457bb73-0a77-49a5-9efb-35ee4dee4f26',
            ],
            'payload' => [
                'tokens' => 100,
            ]
        ];

        $output = $this->upcast($input);

        $input['payload']['tokens'] = 10;
        $this->assertEquals($output, $input);
    }

    private function upcast(array $input): array
    {
        return $this->upcaster->upcast($input);
    }
}
