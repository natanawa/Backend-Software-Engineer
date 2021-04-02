<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithDrawRequest;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Throwable;

class WithDrawController extends Controller
{
    /**
     * Create Withdraw.
     *
     * @param \App\Http\Requests\WithdrawRequest $request
     *
     * @return JsonResponse
    */
    public function create(WithDrawRequest $request)
    {
        \DB::beginTransaction();

        try {
            $result = app(TransactionRepository::class)->create($request, "withdraw");
        } catch (Throwable $e) {
            \DB::rollback();
            return new JsonResponse([
                'code' => 400,
                'message' => 'Withdraw has been failed.',
                'result' => [],
            ], 400);
        }
        \DB::commit();
        return new JsonResponse([
            'code' => 201,
            'message' => 'Withdraw has been success.',
            'result' => $result,
        ], 201);
    }
}