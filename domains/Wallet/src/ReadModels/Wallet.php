<?php

namespace Workshop\Domains\Wallet\ReadModels;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Workshop\Domains\Wallet\WalletId;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'wallet_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
