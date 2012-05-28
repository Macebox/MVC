<?php

require_once(MVC_CORE_PATH.'/CDatabase/IDBDriver.php');

class CMysqli implements IDBDriver
{
	private $queries = array();
	private $db = null;
	private $dbName = null;
	
	public function __construct($host, $user, $password, $dbName)
	{
		$this->db = new Mysqli($host, $user, $password, $dbName);
		$this->dbName = $dbName;
	}
	
	public function GetQueries()
	{
		return $this->queries;
	}
	
	public function RunQuery($q, $secure=false)
	{
		return $this->db->query($q);
	}
	
	public function Get($table, $columns, $equals, $order, $asc, $distinct)
	{
		$ret = array();
		if (!empty($this->db))
		{
			if (!is_array($equals))
			{
				/*Error message*/
			}
			else
			{
				$columnsSelected = '*';
				if (!empty($columns) && count($columns)>0 && $columns!='*')
				{
					$columnsSelected = $this->db->real_escape_string(implode(', ', $columns));
				}
				$query = 'SELECT '.($distinct?' DISTINCT ':'').$columnsSelected.' FROM '.$this->dbName.'.'.$table;
				if (count($equals)>0)
				{
					$query .= ' WHERE '.$this->getEquals($equals);
				}
				
				if (!empty($order))
				{
					$query .= ' ORDER BY ';
					if (is_array($order))
					{
						foreach($order as $value)
						{
							$query .= $value.', ';
						}
						$query = substr($query, 0, strlen($query)-2);
					}
					else
					{
						$query .= $order;
					}
					$query .= ' '.($asc?'ASC':'DESC');
					
				}
				
				$this->queries[] = $query;
				
				if ($result = $this->db->query($query))
				{
					while($row = $result->fetch_array(MYSQLI_ASSOC))
					{
						$ret[] = $row;
					}
				}
			}
		}
		else
		{
			/*Error message*/
		}
		return $ret;
	}
	
	public function Insert($table, $columns)
	{
		if (!empty($this->db))
		{
			if (!is_array($columns))
			{
				/*Error message*/
			}
			else
			{
				$query = "INSERT INTO `".$this->dbName."`.`".$this->db->real_escape_string($table)."` ";
				if (count($columns)>0)
				{
					$cols = '';
					$vals = '';
					foreach($columns as $col => $val)
					{
						$cols .= "`".$this->db->real_escape_string($col)."`, ";
						$vals .= "'".$this->db->real_escape_string($val)."', ";
					}
					$cols=substr($cols, 0, strlen($cols)-2);
					$vals=substr($vals, 0, strlen($vals)-2);
					$query .= '('.$cols.') VALUES ('.$vals.')';
				}
				
				$this->queries[] = $query;
				
				return $this->db->query($query);
			}
		}
		else
		{
			/*Error message*/
		}
	}
	
	public function Delete($table, $equals)
	{
		if (!empty($this->db))
		{
			if (!is_array($equals))
			{
				/*Error message*/
			}
			else
			{
				$query = 'DELETE FROM '.$this->dbName.'.'.$this->db->real_escape_string($table);
				
				if (count($equals)>0)
				{
					$query .= ' WHERE '.$this->getEquals($equals);
				}
				
				$this->queries[] = $query;
				
				$this->db->query($query);
			}
		}
		else
		{
			/*Error message*/
		}
	}
	
	public function Update($table, $columns, $equals)
	{
		if (!empty($this->db))
		{
			if (!is_array($equals))
			{
				/*Error message*/
			}
			else
			{
				
				$query = 'UPDATE `'.$this->dbName.'`.`'.$table.'`';
				
				if (count($columns)>0)
				{
					$query .= ' SET ';
					foreach($columns as $col => $value)
					{
						if ($value==null)
						{
							$value = "NULL";
						}
						else
						{
							$value = "'{$this->db->real_escape_string($value)}'";
						}
						$query .= '`'.$this->db->real_escape_string($col)."`={$value}, ";
					}
					
					$query = substr($query, 0, strlen($query)-2);
				}
				
				if (count($equals)>0)
				{
					$query .= ' WHERE ';
					foreach($equals as $col => $value)
					{
						$query .= '`'.$this->db->real_escape_string($col).'`=\''.$this->db->real_escape_string($value).'\' AND ';
					}
					
					$query = substr($query, 0, strlen($query)-4);
				}
				
				$this->queries[] = $query;
				
				return $this->db->query($query);
			}
		}
		else
		{
			/*Error message*/
		}
	}
	
	private function getEquals($equals)
	{
		$query = '';
		foreach($equals as $col => $value)
		{
			if (is_array($value))
			{
				if (!empty($col))
				{
					$column = $col;
				}
				$eq = '(';
				foreach($value as $subcol => $subval)
				{
					if (is_array($subval))
					{
						$eq .= '('.$this->getEquals($subval).') OR ';
					}
					else
					{
						if ($subval==null)
						{
							$subval==" IS NULL";
						}
						else
						{
							if (strpos($subval, '%'))
							{
								$subval = " LIKE '".$this->db->real_escape_string(substr($subval,0,-1))."'";
							}
							else
							{
								$subval = "='".$this->db->real_escape_string($subval)."'";
							}
						}
						$eq .= $this->db->real_escape_string($subcol)."{$subval} OR ";
					}
				}
				$eq = substr($eq, 0, strlen($eq)-4).')';
			}
			else
			{
				if ($value==null)
				{
					$value = " IS NULL";
				}
				else
				{
					if (strpos($value, '%'))
					{
						$value = " LIKE '".$this->db->real_escape_string(substr($value,0,-1))."'";
					}
					else
					{
						$value = "='".$this->db->real_escape_string($value)."'";
					}
				}
				$eq = $this->db->real_escape_string($col)."{$value}";
			}
			$query .= $eq.' AND ';
		}
		
		$query = substr($query, 0, strlen($query)-4);
		
		return $query;
	}
	
	public function getLastId()
	{
		return $this->db->insert_id;
	}
}