<?php

namespace SoftUni\Models;

class BuildingLevel
{
	const COL_BUILDINGS_ID = 'buildings_id';
	const COL_LEVEL = 'level';
	const COL_GOLD = 'gold';
	const COL_FOOD = 'food';

	private $buildings_id;
	private $level;
	private $gold;
	private $food;

	public function __construct($buildings_id, $level, $gold, $food, $id = null)
	{
		$this->setBuildings_id($buildings_id);
		$this->setLevel($level);
		$this->setGold($gold);
		$this->setFood($food);
	}

	/**
	* @return mixed
	*/
	public function getBuildings_id()
	{
		return $this->buildings_id;
	}

	/**
	* @param $buildings_id
	* @return $this
	*/
	public function setBuildings_id($buildings_id)
	{
		$this->buildings_id = $buildings_id;
		
		return $this;
	}


	/**
	* @return mixed
	*/
	public function getLevel()
	{
		return $this->level;
	}

	/**
	* @param $level
	* @return $this
	*/
	public function setLevel($level)
	{
		$this->level = $level;
		
		return $this;
	}


	/**
	* @return mixed
	*/
	public function getGold()
	{
		return $this->gold;
	}

	/**
	* @param $gold
	* @return $this
	*/
	public function setGold($gold)
	{
		$this->gold = $gold;
		
		return $this;
	}


	/**
	* @return mixed
	*/
	public function getFood()
	{
		return $this->food;
	}

	/**
	* @param $food
	* @return $this
	*/
	public function setFood($food)
	{
		$this->food = $food;
		
		return $this;
	}

}