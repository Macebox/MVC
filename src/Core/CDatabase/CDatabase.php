<?php

require_once(MVC_INSTALL_PATH.'/src/Core/CDatabase/IDBDriver.php');

require_once(MVC_INSTALL_PATH.'/src/Core/CDatabase/CMysqli.php');

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
	
	public function Get($table, $columns=array(), $equals=array(), $order=null, $asc=true)
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
				return $this->db->Get($table, $columns, $equals, $order, $asc);
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
	
	public function RunQuery($q)
	{
		CNocturnal::Instance()->session->AddMessage('notice','The query: "'.$q.'" was run without protection.');
		return $this->db->RunQuery($q);
	}
	
	public function getLastId()
	{
		if (!empty($this->db))
		{
			return $this->db->getLastId();
		}
		else
		{
			/*Error message*/
		}
		
		return -1;
	}
}