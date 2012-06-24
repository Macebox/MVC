<?php

/**
* Folder model
*
* @package NocturnalCore
*/

class CMFolder extends CObject implements IModule
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Manage method.
	*
	* @param String action to be run
	*/
	
	public function Manage($action=null)
	{
		switch($action)
		{
			case 'install':
			{
				if ($this->Init())
				{
					return array('success', 'Successfully created the database tables (or left them untouched if they already existed).');
				}
				else
				{
					return array('success', 'Unable to create the database-tables.');
				}
			}break;
			default:
				throw new Exception('Unsupported action for this module.');
				break;
		}
	}
	
	/**
	* Init method which creates database tables.
	*
	*
	*/
	
	private function Init()
	{
		$this->database->RunQuery(
"CREATE TABLE IF NOT EXISTS `map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text NOT NULL,
  `idRoot` int(11) NOT NULL,
  `deleted` DATETIME NULL DEFAULT NULL
  PRIMARY KEY (`id`),
  KEY `idRoot` (`idRoot`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;", true);

		$this->database->RunQuery(
"ALTER TABLE `map`
  ADD CONSTRAINT `idRoot` FOREIGN KEY (`idRoot`) REFERENCES `map` (`id`);", true);
  
  return $this->database->Insert('map', array(
	'id'		=> '1',
	'path'		=> '/',
	'idRoot'	=> '1',
	));
	}
	
	public function ReadAll($id)
	{
		return $this->database->Get('map', array(), array('idRoot' => $id, 'deleted' => null));
	}
	
	public function GetRoot($id)
	{
		$root = $this->database->Get('map', array('idRoot'), array('id' => $id));
		
		return $root[0]['idRoot'];
	}
	
	public function CreateFolder($mapname, $idMap)
	{
		$mapExists = $this->database->Get('map', array(), array('path' => $mapname, 'idRoot' => $idMap));
		if (empty($mapExists))
		{
			$insertArray = array(
				'path'		=> $mapname,
				'idRoot'	=> $idMap
			);
			
			return $this->database->Insert('map', $insertArray);
		}
		else
		{
			$this->session->AddMessage('error', 'Map already exists.');
		}
	}
	
	public function GetFolder($id)
	{
		$ret = $this->database->Get('map', array(), array('id' => $id));
		return (!empty($ret))?$ret[0]:array('id'=>0);
	}
	
	public function RemoveFolder($mapid)
	{
		$datetime = date('o-m-d H:i:s');
		return $this->database->Update('map', array('deleted' => $datetime), array('id' => $mapid));
	}
}