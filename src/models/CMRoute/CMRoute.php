<?php

/**
* Model for route-handling
*
* @package NocturnalCore
*/

class CMRoute extends CObject implements ArrayAccess
{
	public $route;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->route[] = $value; } else { $this->route[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->route[$offset]); }
	public function offsetUnset($offset) { unset($this->route[$offset]); }
	public function offsetGet($offset) { return isset($this->route[$offset]) ? $this->route[$offset] : null; }
	
	public function ReadAll()
	{
		return $this->config['routing'];
	}
	
	public function ApplyRoutes($aRoutes)
	{
		foreach($aRoutes as $route)
		{
			if (!empty($route['old']))
			{
				unset($this->config['routing'][$route['old']]);
			}
			
			if (!empty($route['trigger']))
			{
				$this->config['routing'][$route['trigger']] = array(
					'enabled'	=> $route['enabled'],
					'url'		=> $route['route'],
				);
			}
		}
		
		$model = new CMConfig();
		$model->saveConfigToFile();
		
		return true;
	}
}