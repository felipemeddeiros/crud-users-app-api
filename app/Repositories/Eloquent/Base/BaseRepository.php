<?php

namespace App\Repositories\Eloquent\Base;

use App\Repositories\Eloquent\Base\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Array
     */
    public function create($attributes)
    {
        return $this->model->create($attributes)->toArray();
    }

    /**
     * @param $id
     * @return Array
     */
    public function find($id)
    {
        return $this->model->find($id)->toArray();
    }

    /**
     * @return Array
     */
    public function all()
    {
        return $this->model->all()->toArray();
    }
}
