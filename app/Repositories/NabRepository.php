<?php

namespace App\Repositories;

use App\Models\Nab;
use App\Repositories\BaseRepository;

class NabRepository extends BaseRepository
{
    public function __construct(Nab $model)
    {
        parent::__construct($model);
    }

    /**
     * Get Last Nab
     *
     * @return \Illuminate\Database\Eloquent\Model
    */
    public function getLastNab()
    {
        return $this->model->orderBy('created_at', 'desc')->first();
    }

    /**
     * Update Balance
     *
     * @return \Illuminate\Database\Eloquent\Model
    */
    public function updateBalance($request)
    {
        $model = $this->model;
        $userRepo = app(UserRepository::class);
        $totalUnit = $userRepo->getTotalUnit();
        $model->nab = 1;
        if ($totalUnit > 0) {
            $model->nab = round($request->current_balance / $totalUnit, 4, PHP_ROUND_HALF_DOWN);
        }
        $model->nab_amount = $request->current_balance;
        $model->save();
        return $model;
    }
}
