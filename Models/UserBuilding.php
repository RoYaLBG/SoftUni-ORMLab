<?php

namespace SoftUni\Models;

class UserBuilding
{
	const COL_ID = 'id';
	const COL_USER_ID = 'user_id';
	const COL_BUILDING_ID = 'building_id';
	const COL_LEVEL_ID = 'level_id';

	private $id;
	private $user_id;
	private $building_id;
	private $level_id;

	public function __construct($user_id, $building_id, $level_id, $id = null)
	{
		$this->setId($id);
		$this->setUser_id($user_id);
		$this->setBuilding_id($building_id);
		$this->setLevel_id($level_id);
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
	public function getUser_id()
	{
		return $this->user_id;
	}

	/**
	* @param $user_id
	* @return $this
	*/
	public function setUser_id($user_id)
	{
		$this->user_id = $user_id;
		
		return $this;
	}


	/**
	* @return mixed
	*/
	public function getBuilding_id()
	{
		return $this->building_id;
	}

	/**
	* @param $building_id
	* @return $this
	*/
	public function setBuilding_id($building_id)
	{
		$this->building_id = $building_id;
		
		return $this;
	}


	/**
	* @return mixed
	*/
	public function getLevel_id()
	{
		return $this->level_id;
	}

	/**
	* @param $level_id
	* @return $this
	*/
	public function setLevel_id($level_id)
	{
		$this->level_id = $level_id;
		
		return $this;
	}

}