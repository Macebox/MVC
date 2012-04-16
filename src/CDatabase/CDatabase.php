<?php

require_once(MVC_INSTALL_PATH.'/src/CDatabase/IDBDriver.php');

require_once(MVC_INSTALL_PATH.'/src/CDatabase/CMysqli.php');

class CDatabase implements IDBDriver
{
	private $db = null;
	private $stmt = null;
	private static $numQueries = 0;
	
	public function GetNumQueries()
	{
		return CDatabase::$numQueries;
	}
	
	public function __construct($driver, $host, $username = null, $password = null, $dbName=null, $mode=null)
	{
		if ($driver=='Mysqli')
		{
			$this->db = new CMysqli($host, $username, $password, $dbName);
		}
	}
	
	public function GetQueries()
	{
		return $this->db->GetQueries();
	}
	
	public function Get($table, $columns=array(), $equals=array())
	{
		if (!empty($this->db))
		{
			if (!is_array($equals))
			{
				/*Error message*/
			}
			else
			{
				CDatabase::$numQueries += 1;
				return $this->db->Get($table, $columns, $equals);
			}
		}
		else
		{
			/*Error message*/
		}
	}
	
	public function Insert($table, $columns=array())
	{
		if (!empty($this->db))
		{
			if (!is_array($columns))
			{
				/*Error message*/
			}
			else
			{
				CDatabase::$numQueries += 1;
				return $this->db->Insert($table, $columns);
			}
		}
		else
		{
			/*Error message*/
		}
	}
	
	public function Delete($table, $equals=array())
	{
		if (!empty($this->db))
		{
			if (!is_array($equals))
			{
				/*Error message*/
			}
			else
			{
				CDatabase::$numQueries += 1;
				return $this->db->Delete($table, $equals);
			}
		}
		else
		{
			/*Error message*/
		}
	}
	
	public function Update($table, $columns=array(), $equals=array())
	{
		if (!empty($this->db))
		{
			if (!is_array($equals) || !is_array($columns))
			{
				/*Error message*/
			}
			else
			{
				CDatabase::$numQueries += 1;
				$this->db->Update($table, $columns, $equals);
			}
		}
		else
		{
			/*Error message*/
		}
	}
}