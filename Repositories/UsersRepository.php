<?php
namespace SoftUni\Repositories;

use SoftUni\Core\Database;
use SoftUni\Models\User;
use SoftUni\Collections\UserCollection;

class UsersRepository
{
    private $query;

    private $where = " WHERE 1";

    private $placeholders = [];

    private $order = '';

    private static $selectedObjectPool = [];
    private static $insertObjectPool = [];

    /**
     * @var UsersRepository
     */
    private static $inst = null;

    private function __construct() { }

    /**
     * @return UsersRepository
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
     * @param $username
     * @return $this
     */
    public function filterByUsername($username)
    {
        $this->where .= " AND username = ?";
        $this->placeholders[] = $username;

        return $this;
    }
    /**
     * @param $password
     * @return $this
     */
    public function filterByPassword($password)
    {
        $this->where .= " AND password = ?";
        $this->placeholders[] = $password;

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
     * @param $grass
     * @return $this
     */
    public function filterByGrass($grass)
    {
        $this->where .= " AND grass = ?";
        $this->placeholders[] = $grass;

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
     * @return UserCollection
     * @throws \Exception
     */
    public function findAll()
    {
        $db = Database::getInstance('app');

        $this->query = "SELECT * FROM users" . $this->where . $this->order;
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);

        $collection = [];
        foreach ($result->fetchAll() as $entityInfo) {
            $entity = new User($entityInfo['username'],
$entityInfo['password'],
$entityInfo['gold'],
$entityInfo['food'],
$entityInfo['grass'],
$entityInfo['id']);

            $collection[] = $entity;
            self::$selectedObjectPool[] = $entity;
        }

        return new UserCollection($collection);
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function findOne()
    {
        $db = Database::getInstance('app');

        $this->query = "SELECT * FROM users" . $this->where . $this->order . " LIMIT 1";
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);
        $entityInfo = $result->fetch();
        $entity = new User($entityInfo['username'],
$entityInfo['password'],
$entityInfo['gold'],
$entityInfo['food'],
$entityInfo['grass'],
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

        $this->query = "DELETE FROM users" . $this->where;
        $result = $db->prepare($this->query);
        $result->execute($this->placeholders);

        return $result->rowCount() > 0;
    }

    public static function add(User $model)
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

    private static function update(User $model)
    {
        $db = Database::getInstance('app');

        $query = "UPDATE users SET username= :username, password= :password, gold= :gold, food= :food, grass= :grass WHERE id = :id";
        $result = $db->prepare($query);
        $result->execute(
            [
                ':id' => $model->getId(),
':username' => $model->getUsername(),
':password' => $model->getPassword(),
':gold' => $model->getGold(),
':food' => $model->getFood(),
':grass' => $model->getGrass()
            ]
        );
    }

    private static function insert(User $model)
    {
        $db = Database::getInstance('app');

        $query = "INSERT INTO users (username,password,gold,food,grass) VALUES (:username, :password, :gold, :food, :grass);";
        $result = $db->prepare($query);
        $result->execute(
            [
                ':username' => $model->getUsername(),
':password' => $model->getPassword(),
':gold' => $model->getGold(),
':food' => $model->getFood(),
':grass' => $model->getGrass()
            ]
        );
        $model->setId($db->lastId());
    }

    private function isColumnAllowed($column)
    {
        $refc = new \ReflectionClass('\SoftUni\User');
        $consts = $refc->getConstants();

        return in_array($column, $consts);
    }
}