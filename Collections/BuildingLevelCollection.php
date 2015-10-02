<?php

namespace SoftUni\Collections;

use SoftUni\Models\BuildingLevel;

class BuildingLevelCollection
{
    /**
     * @var BuildingLevel[];
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