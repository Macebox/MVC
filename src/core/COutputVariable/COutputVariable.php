<?php/*** COutputVariable** @package NocturnalCore*/class COutputVariable{	/**	* Returns PHP-runnable code to assigning to a variabel	*	* @param String assign variable name without '$'	* @param Mixed variable with data	* @param Array comments	*/	public static function getRunnableVariable($name, $var, $comments=array())	{		$ret = null;		if (is_array($var))		{			foreach($var as $key => $value)			{				if (isset($comments[$key]))				{					$ret .= PHP_EOL."/**".PHP_EOL;					$cmts = preg_split( '/\r\n|\r|\n/', $comments[$key]);					foreach($cmts as $cmt)					{						$ret .= " * ".$cmt.PHP_EOL;					}					$ret .= " */".PHP_EOL;				}				if (is_numeric($key))				{					$key = "";				}				else				{					$key = "'{$key}'";				}				$ret .= '$'.$name.'['.$key."] = ";				if (is_array($value))				{					$ret .= COutputVariable::getArray($value,"\t").";".PHP_EOL.PHP_EOL;				}				else				{					if (is_bool($value)===true && $value)					{						$ret .= "true;".PHP_EOL;					}					else if (is_bool($value)===true)					{						$ret .= "false;".PHP_EOL;					}					else if (is_numeric($value))					{						$ret .= "{$value};".PHP_EOL;					}					else					{						$ret .= "'{$value}';".PHP_EOL;					}				}			}		}		else		{			if (isset($comments[$key]))			{				$ret .= PHP_EOL."/**".PHP_EOL;				$cmts = preg_split( '/\r\n|\r|\n/', $comments[$key]);				foreach($cmts as $cmt)				{					$ret .= " * ".$cmt.PHP_EOL;				}				$ret .= " */".PHP_EOL;			}			$ret .= '$'.$name." = ";			if (is_array($var))			{				$ret .= COutputVariable::getArray($var,"\t").";".PHP_EOL.PHP_EOL;			}			else			{				if (is_bool($var)===true && $var)				{					$ret .= "true;".PHP_EOL;				}				else if (is_bool($var)===true)				{					$ret .= "false;".PHP_EOL;				}				else if (is_numeric($var))				{					$ret .= "{$var};".PHP_EOL;				}				else				{					$ret .= "'{$var}';".PHP_EOL;				}			}		}		return $ret;	}		/**	* Get the array data from an array.	*	* @param Array data-array	* @param String tabulator, used to create \t...\t for each new array inside the array	*/		private static function getArray($array, $t="")	{		$ret = null;		if (!is_array($array)) return;		else		{			$ret .= "array(".PHP_EOL;			foreach($array as $key => $value)			{				if (!is_numeric($key))				{					$key = "'{$key}' => ";				}				else				{					$key = "";				}				if (!is_array($value))				{					if (is_bool($value)===true && $value)					{						$ret .= "{$t}{$key}true,".PHP_EOL;					}					else if (is_bool($value)===true)					{						$ret .= "{$t}{$key}false,".PHP_EOL;					}					else if (is_numeric($value))					{						$ret .= "{$t}{$key}{$value},".PHP_EOL;					}					else					{						$ret .= "{$t}{$key}'{$value}',".PHP_EOL;					}				}				else				{					$ret .= "{$t}{$key}".COutputVariable::getArray($value, $t."\t").",".PHP_EOL;				}			}			$ret .= $t.")";		}		return $ret;	}}