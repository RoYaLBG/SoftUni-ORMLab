<?php

namespace SoftUni\Collections;

use SoftUni\Models\UserBuilding;

class UserBuildingCollection
{
    /**
     * @var UserBuilding[];
     */
    private $collection = [];

    public function __construct($models = [])
    {
        $this->collection = $models;
    }

    /**
     * @param callable $callback
     */
    public function each(Callable $callback)
    {
        foreach ($this->collection as $model) {
            $callback($model);
        }
    }
}