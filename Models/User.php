<?php

namespace SoftUni\Models;

class User
{
	const COL_ID = 'id';
	const COL_USERNAME = 'username';
	const COL_PASSWORD = 'password';
	const COL_GOLD = 'gold';
	const COL_FOOD = 'food';
	const COL_GRASS = 'grass';

	private $id;
	private $username;
	private $password;
	private $gold;
	private $food;
	private $grass;

	public function __construct($username, $password, $gold, $food, $grass, $id = null)
	{
		$this->setId($id);
		$this->setUsername($username);
		$this->setPassword($password);
		$this->setGold($gold);
		$this->setFood($food);
		$this->setGrass($grass);
	}

	/**
	* @return mixed
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* @param $id
	* @return $this
	*/
	public function setId($id)
	{
		$this->id = $id;
		
		return $this;
	}


	/**
	* @return mixed
	*/
	public function getUsername()
	{
		return $this->username;
	}

	/**
	* @param $username
	* @return $this
	*/
	public function setUsername($username)
	{
		$this->username = $username;
		
		return $this;
	}


	/**
	* @return mixed
	*/
	public function getPassword()
	{
		return $this->password;
	}

	/**
	* @param $password
	* @return $this
	*/
	public function setPassword($password)
	{
		$this->password = $password;
		
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


	/**
	* @return mixed
	*/
	public function getGrass()
	{
		return $this->grass;
	}

	/**
	* @param $grass
	* @return $this
	*/
	public function setGrass($grass)
	{
		$this->grass = $grass;
		
		return $this;
	}

}