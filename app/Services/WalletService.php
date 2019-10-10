<?php

namespace App\Services;

use App\Exceptions\WalletException;
use App\Wallet;
use Illuminate\Support\Facades\DB;

/**
 * Class WalletService
 * @package App\Services
 */
class WalletService
{
    /**
     * Update amount in wallet
     *
     * @param array $data
     * @return Wallet
     * @throws WalletException
     */
    public function updateAmount(array $data): Wallet
    {
        $wallet = Wallet::find($data['wallet_id']);

        if ($data['currency'] == Wallet::CUR_USD) {
            $data['amount'] = $this->currencyConversion($data['amount']);
        }

        if (!$wallet) {
                return $this->create($data);
        } else {
            if ($data['transaction_type'] == Wallet::TYPE_DEBIT) {
                $this->debit($data);
            } else {
                $this->credit($data);
            }

            return Wallet::find($data['wallet_id']);
        }
    }

    /**
     * Create wallet
     *
     * @param array $data
     * @return Wallet
     * @throws WalletException
     */
    protected function create(array $data): Wallet
    {
        if ($data['transaction_type'] == Wallet::TYPE_CREDIT) {
            throw new WalletException('The amount is too small');
        }

        return Wallet::create($data);
    }

    /**
     * Debit update wallet
     *
     * @param array $data
     * @return bool
     */
    protected function debit(array $data): bool
    {
        DB::table('wallets')
            ->where('wallet_id', $data['wallet_id'])
            ->increment('amount', $data['amount']);

        return true;
    }

    /**
     * Credit update amount
     *
     * @param array $data
     * @return bool
     */
    protected function credit(array $data): bool
    {
        DB::transaction(function() use ($data) {
            $wallet = Wallet::where('wallet_id', $data['wallet_id'])->lockForUpdate()->first();

            $wallet->amount -= $data['amount'];

            if($wallet->amount < 0) {
                throw new WalletException('The amount is too small');
            }

            $wallet->save();
        });

        return true;
    }

    /**
     * Currency conversion from USD to RUB
     *
     * @param $amount
     * @return float|int
     */
    public function currencyConversion(float $amount): float
    {
        return round($amount * Wallet::EXCHANGE_RATE, 2);
    }
}
