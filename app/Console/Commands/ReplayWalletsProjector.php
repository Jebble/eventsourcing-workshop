<?php

namespace App\Console\Commands;

use Assert\Assert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use EventSauce\EventSourcing\OffsetCursor;
use Workshop\Domains\Wallet\Infra\WalletMessageRepository;
use Workshop\Domains\Wallet\Projectors\WalletsProjector;
use EventSauce\EventSourcing\ReplayingMessages\ReplayMessages;

class ReplayWalletsProjector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replay:wallets-projector';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(WalletMessageRepository $walletMessageRepository, WalletsProjector $WalletsProjector)
    {
        $replayMessages = new ReplayMessages(
            $walletMessageRepository,
            $WalletsProjector,
        );

        DB::table('Wallets')->truncate();

        $cursor = OffsetCursor::fromStart(limit: 100);

        process_batch:
        $result = $replayMessages->replayBatch($cursor);
        $cursor = $result->cursor();

        if ($result->messagesHandled() > 0) {
            goto process_batch;
        }

        return 0;
    }
}
