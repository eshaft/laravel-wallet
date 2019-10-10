<?php

namespace App\Http\Controllers;

use App\Exceptions\WalletException;
use App\Http\Requests\WalletRequest;
use App\Services\WalletService;

/**
 * Class WalletController
 * @package App\Http\Controllers
 */
class WalletController extends Controller
{
    /**
     * Update amount in wallet
     *
     * @param WalletRequest $request
     * @param WalletService $walletService
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(WalletRequest $request, WalletService $walletService): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->validated();

            $wallet = $walletService->updateAmount($data);

            return response()->json([
                'success' => true,
                'wallet' => $wallet
            ]);
        } catch (WalletException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
