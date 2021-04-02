<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Get User Member.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return JsonResponse
    */
    public function index(Request $request)
    {
        $where   = [];
        $limit   = $request->limit ?? 20;
        $select  = ['id as user_id', 'total_unit'];
        $appends = ['total', 'current_nab'];
        $orderBy = 'id';
        $sort    = 'ASC';

        if (isset($request->userid)) {
            $where['id'] = $request->userid;
        }
        $where['total_unit'] = ['condition' => '>', 'value' => 0];
        $result = app(UserRepository::class)->pagination($limit, $select, $where, [], $appends, $orderBy, $sort);
        return new JsonResponse([
            'code' => 200,
            'message' => 'User data displayed successfully.',
            'result' => $result,
        ], 200);
    }

    /**
     * Create User.
     *
     * @param \App\Http\Requests\UserRequest $request
     * @param \App\Models\User $user
     *
     * @return JsonResponse
    */
    public function store(UserRequest $request, User $user)
    {
        $user->fill($request->only($user->offsetGet('fillable')));
        $user->save();
        return new JsonResponse([
            'code' => 201,
            'message' => 'User data added successfully',
            'result' => $user,
        ], 201);
    }
}