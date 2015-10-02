<?php


function create_collection($model) {
    $modelCollection = $model . 'Collection';
    $modelArray = $model . '[]';
    return <<<KUF
<?php

namespace SoftUni\Collections;

use SoftUni\Models\\$model;

class $modelCollection
{
    /**
     * @var $modelArray;
     */
    private \$collection = [];

    public function __construct(\$models = [])
    {
        \$this->collection = \$models;
    }

    /**
     * @param callable \$callback
     */
    public function each(Callable \$callback)
    {
        foreach (\$this->collection as \$model) {
            \$callback(\$model);
        }
    }
}
KUF;

}