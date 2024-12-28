<?php

namespace App\Http\Controllers\Api;

use App\Concerns\BcvCurrencies;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BcvCurrencyController extends Controller
{
    protected BcvCurrencies $bcvCurrencies;
    public function __construct(BcvCurrencies $bcvCurrencies)
    {
        $this->bcvCurrencies = $bcvCurrencies;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->bcvCurrencies->scrape();
            return response()->json(['message' => 'Exchange rates updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
