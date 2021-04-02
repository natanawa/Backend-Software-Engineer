<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTotalBalanceRequest;
use App\Repositories\NabRepository;
use Illuminate\Http\JsonResponse;

class UpdateTotalBalanceController extends Controller
{
    /**
     * Update Balance.
     *
     * @param \App\Http\Requests\UpdateTotalBalanceRequest $request
     *
     * @return JsonResponse
    */
    public function update(UpdateTotalBalanceRequest $request) {
        $nab = app(NabRepository::class)->updateBalance($request);
        return new JsonResponse([
            'code' => 200,
            'message' => 'Update Balance Success',
            'result' => $nab
        ]);
    }
}
