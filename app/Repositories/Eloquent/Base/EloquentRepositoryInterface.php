<?php

namespace App\Repositories\Eloquent\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create($attributes);

    /**
     * @param $id
     * @return Array
     */
    public function find($id);

    /**
     * @return Array
     */
    public function all();
}
