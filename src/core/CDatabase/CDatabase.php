<?php

require_once(MVC_CORE_PATH.'/CDatabase/IDBDriver.php');

require_once(MVC_CORE_PATH.'/CDatabase/CMysqli.php');

/**
* Class "interface" which can be extended to using different kinds of database-connection-models.
*
* @package NocturnalCore
*/

class CDatabase implements IDBDriver
{
	private $db = null;
	private $stmt = null;
	private static $numQueries = 0;
	
	/**
	* Returns number of queries this run.
	*
	*
	*/
	
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
	
	/**
	* Returns the queries executed this run as an array.
	*
	*
	*/
	
	public function GetQueries()
	{
		return $this->db->GetQueries();
	}
	
	/**
	* SELECT ... FROM ... WHERE ... ORDER BY ..., interface
	*
	* @param String table to user
	* @param Array columns to select(array() for *)
	* @param Array equals (described below)
	* @param Array order_by ...
	* @param boolean ascending=true
	*
	* ---------------------------------------------------------
	*	<--- AND EXAMPLE --->
	*	$equals =
	*	array(
	*		'column'	=> 'value',
	*		'column2'	=> 'value2'
	*		);
	*	-----> WHERE column='value' AND column2='value2'
	*
	*	<--- OR EXAMPLE --->
	*	$equals =
	*	array(
	*		'column'	=> 'value',
	*		array(
	*			'column2'	=> 'value2',
	*			'column3'	=> 'value3',
	*			array(
	*				'column2' => 'value3'
	*				)
	*			)
	*		);
	*	-----> WHERE column='value' AND (column2='value2' OR column3='value3' OR (column2='value3'))
	*/
	
	public function Get($table, $columns=array(), $equals=array(), $order=null, $asc=true, $distinct=false)
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
				return $this->db->Get($table, $columns, $equals, $order, $asc, $distinct);
			}
		}
		else
		{
			/*Error message*/
		}
	}
	
	/**
	* Insert INTO ... (...,...) VALUES (...,...) 
	*
	* @param String table name
	* @param Array columns and their values
	* ----------------------------------------------
	* columns = array('column'=>'value');
	*/
	
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
	
	/**
	* DELETE FROM ... WHERE ...
	*
	* @param String table name
	* @param Array equals(works as Get's equals)
	*/
	
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
	
	/**
	* UPDATE ... SET ...=... WHERE ...
	*
	* @param String table name
	* @param Array columns to update(works as Inserts' columns)
	* @param Array equals(works as Get's equals)
	*/
	
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
				return $this->db->Update($table, $columns, $equals);
			}
		}
		else
		{
			/*Error message*/
		}
	}
	
	/**
	* Execute a database query unprotected.
	*
	* @param String question
	* @param boolean Is this a secure on or not?(leaves notice if not)
	*/
	
	public function RunQuery($q, $secure=false)
	{
		if (!$secure)
		{
			CNocturnal::Instance()->session->AddMessage('notice','The query: "'.$q.'" was run without protection.');
		}
		return $this->db->RunQuery($q, $secure);
	}
	
	/**
	* Get last inserted id this run.
	*
	*
	*/
	
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