<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * Cacheable model object.
     *
     * @var mixed
     */
    protected $model;

    /**
     * EloquentRepository class constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Return the model object which would
     * be used by the repository.
     *
     * @return \Illuminate\Database\Eloquent\Model
    */
    public function model()
    {
        return $this->model;
    }

    /**
     * Return object of model
     *
     * @return \Illuminate\Database\Eloquent\Model
    */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get Result of pagination be used by the repository.
     *
     * @return LengthAwaraPagination
    */
    public function pagination($limit = 15,$select = '*',$where = [],$withs = [],$appends= [],$orderBy = 'created_at',$sort = 'DESC')
    {
        $models = $this->model->with($withs)->select($select)->orderBy($orderBy, $sort);
        if (count($where) > 0) {
            foreach ($where as $column => $expression) {
                $condition = is_array($expression) && isset($expression['condition']) ?
                $expression['condition'] : '=';
                $value = is_array($expression) && isset($expression['value']) ?
                $expression['value'] : $expression;
                $models = $models->where($column, $condition, $value);
            }
        }
        $models = $models->paginate($limit)->withQueryString();
        if (count($appends) > 0) {
            $models->getCollection()->each->setAppends($appends);
        }
        return $models;
    }
}