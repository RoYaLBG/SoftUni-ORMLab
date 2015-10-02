<?php
namespace SoftUni\Repositories;

use SoftUni\Core\Database;
use SoftUni\Models\UserBuilding;
use SoftUni\Collections\UserBuildingCollection;

class UserBuildingsRepository
{
    private $query;

    private $where = " WHERE 1";

    private $placeholders = [];

    private $order = '';

    private static $selectedObjectPool = [];
    private static $insertObjectPool = [];

    /**
     * @var UserBuildingsRepository
     */
    private static $inst = null;

    private function __construct() { }

    /**
     * @return UserBuildingsRepository
     */
    public static function create()
    {
        if (self::$inst == null) {
            self::$inst = new self();
        }

        return self::$inst;
    }

    /**
     * @param $id
     * @return $this
     */
    public function filterById($id)
    {
        $this->where .= " AND id = ?";
        $this->placeholders[] = $id;

        return $this;
    }
    /**
     * @param $user_id
     * @return $this
     */
    public function filterByUser_id($user_id)
    {
        $this->where .= " AND user_id = ?";
        $this->placeholders[] = $user_id;

        return $this;
    }
    /**
     * @param $building_id
     * @return $this
     */
    public function filterByBuilding_id($building_id)
    {
        $this->where .= " AND building_id = ?";
        $this->placeholders[] = $building_id;

        return $this;
    }
    /**
     * @param $level_id
     * @return $this
     */
    public function filterByLevel_id($level_id)
    {
        $this->where .= " AND level_id = ?";
        $this->placeholders[] = $level_id;

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
     * @return UserBuildingCollection
     * @throws \Exception
     */
    public function findAll()
    {
        $db = Database::getInstance('app');

        $this->query = "SELECT * FROM user_buildings" . $this->where . $this->order;
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);

        $collection = [];
        foreach ($result->fetchAll() as $entityInfo) {
            $entity = new UserBuilding($entityInfo['user_id'],
$entityInfo['building_id'],
$entityInfo['level_id'],
$entityInfo['id']);

            $collection[] = $entity;
            self::$selectedObjectPool[] = $entity;
        }

        return new UserBuildingCollection($collection);
    }

    /**
     * @return UserBuilding
     * @throws \Exception
     */
    public function findOne()
    {
        $db = Database::getInstance('app');

        $this->query = "SELECT * FROM user_buildings" . $this->where . $this->order . " LIMIT 1";
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);
        $entityInfo = $result->fetch();
        $entity = new UserBuilding($entityInfo['user_id'],
$entityInfo['building_id'],
$entityInfo['level_id'],
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

        $this->query = "DELETE FROM user_buildings" . $this->where;
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);

        return $result->rowCount() > 0;
    }

    public static function add(UserBuilding $model)
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

    private static function update(UserBuilding $model)
    {
        $db = Database::getInstance('app');

        $query = "UPDATE user_buildings SET user_id= :user_id, building_id= :building_id, level_id= :level_id WHERE id = :id";
        $result = $db->prepare($query);
        $result->execute(
            [
                ':id' => $model->getId(),
':user_id' => $model->getUser_id(),
':building_id' => $model->getBuilding_id(),
':level_id' => $model->getLevel_id()
            ]
        );
    }

    private static function insert(UserBuilding $model)
    {
        $db = Database::getInstance('app');

        $query = "INSERT INTO users (user_id,building_id,level_id) VALUES (:user_id, :building_id, :level_id);";
        $result = $db->prepare($query);
        $result->execute(
            [
                ':user_id' => $model->getUser_id(),
':building_id' => $model->getBuilding_id(),
':level_id' => $model->getLevel_id()
            ]
        );
        $model->setId($db->lastId());
    }

    private function isColumnAllowed($column)
    {
        $refc = new \ReflectionClass('\SoftUni\UserBuilding');
        $consts = $refc->getConstants();

        return in_array($column, $consts);
    }
}