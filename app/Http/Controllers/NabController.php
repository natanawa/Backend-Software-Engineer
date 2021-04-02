<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\NabRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NabController extends Controller
{
    /**
     * Get Nab List.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return JsonResponse
    */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->limit ?? 20;
        $select = ['nab', 'created_at'];
        $nab = app(NabRepository::class)->pagination($limit, $select);
        return new JsonResponse([
            'code' => 200,
            'message' => 'Get NAB Success.',
            'result' => $nab,
        ], 200);
    }
}