<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    const CUR_RUB = 'RUB';
    const CUR_USD = 'USD';

    const TYPE_DEBIT = 'debit';
    const TYPE_CREDIT = 'credit';

    const EXCHANGE_RATE = 65;

    /**
     * The primary key associated with the table.
     *
     * @var int
     */
    protected $primaryKey = 'wallet_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wallet_id',
        'amount',
    ];
}
