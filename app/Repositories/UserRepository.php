<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Get Total Unit
     *
     * @return float
    */
    public function getTotalUnit()
    {
        return $this->model->sum('total_unit');
    }
}
