<?php
namespace SoftUni\Repositories;

use SoftUni\Core\Database;
use SoftUni\Models\BuildingLevel;
use SoftUni\Collections\BuildingLevelCollection;

class BuildingLevelsRepository
{
    private $query;

    private $where = " WHERE 1";

    private $placeholders = [];

    private $order = '';

    private static $selectedObjectPool = [];
    private static $insertObjectPool = [];

    /**
     * @var BuildingLevelsRepository
     */
    private static $inst = null;

    private function __construct() { }

    /**
     * @return BuildingLevelsRepository
     */
    public static function create()
    {
        if (self::$inst == null) {
            self::$inst = new self();
        }

        return self::$inst;
    }

    /**
     * @param $buildings_id
     * @return $this
     */
    public function filterByBuildings_id($buildings_id)
    {
        $this->where .= " AND buildings_id = ?";
        $this->placeholders[] = $buildings_id;

        return $this;
    }
    /**
     * @param $level
     * @return $this
     */
    public function filterByLevel($level)
    {
        $this->where .= " AND level = ?";
        $this->placeholders[] = $level;

        return $this;
    }
    /**
     * @param $gold
     * @return $this
     */
    public function filterByGold($gold)
    {
        $this->where .= " AND gold = ?";
        $this->placeholders[] = $gold;

        return $this;
    }
    /**
     * @param $food
     * @return $this
     */
    public function filterByFood($food)
    {
        $this->where .= " AND food = ?";
        $this->placeholders[] = $food;

        return $this;
    }

    /**
     * @param $column
     * @return $this
     * @throws \Exception
     */
    public function orderBy($column)
    {
        if (!$this->isColumnAllowed($column)) {
            throw new \Exception("Column not found");
        }

        if (!empty($this->order)) {
            throw new \Exception("Cannot do primary order, because you already have a primary order");
        }

        $this->order .= " ORDER BY $column";

        return $this;
    }

    /**
     * @param $column
     * @return $this
     * @throws \Exception
     */
    public function orderByDescending($column)
    {
        if (!$this->isColumnAllowed($column)) {
            throw new \Exception("Column not found");
        }

        if (!empty($this->order)) {
            throw new \Exception("Cannot do primary order, because you already have a primary order");
        }

        $this->order .= " ORDER BY $column DESC";

        return $this;
    }

    /**
     * @param $column
     * @return $this
     * @throws \Exception
     */
    public function thenBy($column)
    {
        if (empty($this->order)) {
            throw new \Exception("Cannot do secondary order, because you don't have a primary order");
        }

        if (!$this->isColumnAllowed($column)) {
            throw new \Exception("Column not found");
        }

        $this->order .= ", $column ASC";

        return $this;
    }

    /**
     * @param $column
     * @return $this
     * @throws \Exception
     */
    public function thenByDescending($column)
    {
        if (empty($this->order)) {
            throw new \Exception("Cannot do secondary order, because you don't have a primary order");
        }

        if (!$this->isColumnAllowed($column)) {
            throw new \Exception("Column not found");
        }

        $this->order .= ", $column DESC";

        return $this;
    }

    /**
     * @return BuildingLevelCollection
     * @throws \Exception
     */
    public function findAll()
    {
        $db = Database::getInstance('app');

        $this->query = "SELECT * FROM building_levels" . $this->where . $this->order;
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);

        $collection = [];
        foreach ($result->fetchAll() as $entityInfo) {
            $entity = new BuildingLevel($entityInfo['buildings_id'],
$entityInfo['level'],
$entityInfo['gold'],
$entityInfo['food'],
$entityInfo['id']);

            $collection[] = $entity;
            self::$selectedObjectPool[] = $entity;
        }

        return new BuildingLevelCollection($collection);
    }

    /**
     * @return BuildingLevel
     * @throws \Exception
     */
    public function findOne()
    {
        $db = Database::getInstance('app');

        $this->query = "SELECT * FROM building_levels" . $this->where . $this->order . " LIMIT 1";
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);
        $entityInfo = $result->fetch();
        $entity = new BuildingLevel($entityInfo['buildings_id'],
$entityInfo['level'],
$entityInfo['gold'],
$entityInfo['food'],
$entityInfo['id']);

        self::$selectedObjectPool[] = $entity;

        return $entity;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete()
    {
        $db = Database::getInstance('app');

        $this->query = "DELETE FROM building_levels" . $this->where;
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);

        return $result->rowCount() > 0;
    }

    public static function add(BuildingLevel $model)
    {
        if ($model->getId()) {
            throw new \Exception('This entity is not new');
        }

        self::$insertObjectPool[] = $model;
    }

    public static function save()
    {
        foreach (self::$selectedObjectPool as $entity) {
            self::update($entity);
        }

        foreach (self::$insertObjectPool as $entity) {
            self::insert($entity);
        }

        return true;
    }

    private static function update(BuildingLevel $model)
    {
        $db = Database::getInstance('app');

        $query = "UPDATE building_levels SET buildings_id= :buildings_id, level= :level, gold= :gold, food= :food WHERE id = :id";
        $result = $db->prepare($query);
        $result->execute(
            [
                ':buildings_id' => $model->getBuildings_id(),
':level' => $model->getLevel(),
':gold' => $model->getGold(),
':food' => $model->getFood()
            ]
        );
    }

    private static function insert(BuildingLevel $model)
    {
        $db = Database::getInstance('app');

        $query = "INSERT INTO users (buildings_id,level,gold,food) VALUES (:buildings_id, :level, :gold, :food);";
        $result = $db->prepare($query);
        $result->execute(
            [
                ':buildings_id' => $model->getBuildings_id(),
':level' => $model->getLevel(),
':gold' => $model->getGold(),
':food' => $model->getFood()
            ]
        );
        $model->setId($db->lastId());
    }

    private function isColumnAllowed($column)
    {
        $refc = new \ReflectionClass('\SoftUni\BuildingLevel');
        $consts = $refc->getConstants();

        return in_array($column, $consts);
    }
}