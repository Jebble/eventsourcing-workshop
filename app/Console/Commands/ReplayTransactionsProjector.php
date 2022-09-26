<?php

namespace App\Console\Commands;

use Assert\Assert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Workshop\Domains\Wallet\WalletId;
use EventSauce\EventSourcing\OffsetCursor;
use Workshop\Domains\Wallet\Infra\WalletRepository;
use Workshop\Domains\Wallet\Exceptions\SorryCantWithdraw;
use Workshop\Domains\Wallet\Infra\WalletMessageRepository;
use Workshop\Domains\Wallet\Projectors\TransactionsProjector;
use EventSauce\EventSourcing\ReplayingMessages\ReplayMessages;
use Workshop\Domains\Wallet\Exceptions\TokenWithdrawalException;

class ReplayTransactionsProjector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replay:transactions-projector';

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
    public function handle(WalletMessageRepository $walletMessageRepository, TransactionsProjector $transactionsProjector)
    {
        $replayMessages = new ReplayMessages(
            $walletMessageRepository,
            $transactionsProjector,
        );

        DB::table('transactions')->truncate();

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
