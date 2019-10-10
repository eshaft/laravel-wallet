<?php

namespace Tests\Feature;

use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Class WalletTest
 * @package Tests\Feature
 */
class WalletTest extends TestCase
{
    /**
     * If true, setup has run at least once.
     * @var boolean
     */
    protected static $setUpHasRunOnce = false;

    /**
     * After the first run of setUp "migrate:fresh --seed"
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if (!static::$setUpHasRunOnce) {
            Artisan::call('migrate:fresh');
            Artisan::call(
                'db:seed', ['--class' => 'DatabaseSeeder']
            );
            static::$setUpHasRunOnce = true;
        }
    }

    public function testCreditCreateError()
    {
        Artisan::call('migrate:refresh');

        $response = $this->postJson('/api/wallet', [
            'wallet_id' => 1,
            'transaction_type' => Wallet::TYPE_CREDIT,
            'amount' => 1000,
            'currency' => Wallet::CUR_RUB
        ]);

        $response->assertStatus(500);
        $response->assertJsonFragment(['success' => false]);
    }

    public function testDebitCreateSuccess()
    {
        $response = $this->postJson('/api/wallet', [
            'wallet_id' => 1,
            'transaction_type' => Wallet::TYPE_DEBIT,
            'amount' => 1000,
            'currency' => Wallet::CUR_RUB
        ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'success' => true,
            'wallet_id' => 1,
            'amount' => 1000,
        ]);
    }

    public function testDebitUpdate()
    {
        $response = $this->postJson('/api/wallet', [
            'wallet_id' => 1,
            'transaction_type' => Wallet::TYPE_DEBIT,
            'amount' => 1000,
            'currency' => Wallet::CUR_RUB
        ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'success' => true,
            'wallet_id' => 1,
            'amount' => '2000.00',
        ]);
    }

    public function testCreditUpdateSuccess()
    {
        $response = $this->postJson('/api/wallet', [
            'wallet_id' => 1,
            'transaction_type' => Wallet::TYPE_CREDIT,
            'amount' => 1000,
            'currency' => Wallet::CUR_RUB
        ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'success' => true,
            'wallet_id' => 1,
            'amount' => '1000.00',
        ]);
    }

    public function testCreditUpdateError()
    {
        $response = $this->postJson('/api/wallet', [
            'wallet_id' => 1,
            'transaction_type' => Wallet::TYPE_CREDIT,
            'amount' => 10000,
            'currency' => Wallet::CUR_RUB
        ]);

        $response->assertStatus(500);
        $response->assertJsonFragment(['success' => false]);
    }
}
