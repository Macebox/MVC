<?php

/**
* Model for the frame-handling
*
* @package NocturnalCMF
*/

class CMFrame extends CObject implements ArrayAccess
{
	public $frame;
	
	public function __construct($id=null)
	{
		parent::__construct();
		
		if ($id!=null)
		{
			$this->LoadById($id);
		}
		else
		{
			$this['id']			= '';
			$this['key']		= '';
			$this['content']	= array();
			$this['created']	= '';
			$this['deleted']	= '';
			$this['title']		= '';
		}
	}
	
	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->frame[] = $value; } else { $this->frame[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->frame[$offset]); }
	public function offsetUnset($offset) { unset($this->frame[$offset]); }
	public function offsetGet($offset) { return isset($this->frame[$offset]) ? $this->frame[$offset] : null; }
	
	public function Manage($action=null)
	{
		switch($action)
		{
			case 'install':
			{
				if ($this->Init())
				{
					return array('success', 'Succesfully created the frame database table.');
				}
				break;
			}
			default:
				break;
		}
	}
	
	private function Init()
	{
		$this->database->RunQuery('CREATE TABLE IF NOT EXISTS `frame` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(30) NOT NULL,
  `data` text NOT NULL,
  `created` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;', true);
		return true;
	}
	
	public function ReadAll()
	{
		$tmp = $this->database->Get('frame');
		$ret = array();
		foreach($tmp as $frame)
		{
			$array = $frame;
			$array['content'] = unserialize($array['data']);
			unset($array['data']);
			$ret[] = $array;
		}
		
		return $ret;
	}
	
	public function StoreInDatabase()
	{
		$dateTime = date('o-m-d H:i:s');
		if (!empty($this['id']))
		{
			$updateArray = array(
				'title'		=> $this['title'],
				'key'		=> $this['key'],
				'data'		=> serialize($this['content']),
				'deleted'	=> null,
			);
			if (empty($this['created']))
			{
				$this['created']		= $dateTime;
				$updateArray['created'] = $dateTime;
			}
			
			return $this->database->Update('frame', $updateArray, array('id' => $this['id']));
		}
		else if (!empty($this['key']))
		{
			$insertArray = array(
				'title'		=> $this['title'],
				'key'		=> $this['key'],
				'data'		=> serialize($this['content']),
				'created'	=> $dateTime,
			);
			
			$this->database->Insert('frame', $insertArray);
			$result = $this->database->Get('frame', array('id'), array('key' => $this['key']));
			if (!empty($result))
			{
				$this['id'] = $result[0]['id'];
				return true;
			}
		}
		return false;
	}
	
	public function SaveFrame($title, $key, $regions)
	{
		$this['title']	= $title;
		$this['key']	= $key;
		$this['content'] = array();
		foreach($regions as $region => $pkey)
		{
			$page = $this->database->Get('content', array('id'), array('key' => $pkey, 'type' => 'page'));
			if (!empty($page))
			{
				$this->frame['content'][$region] = $page[0]['id'];
			}
		}
		
		return $this->StoreInDatabase();
	}
	
	public function RemoveFrame()
	{
		if (!empty($this['id']))
		{
			$updateArray = array(
				'deleted'	=> date('o-m-d H:i:s'),
			);
			$this->database->Update('frame', $updateArray, array('id' => $this['id']));
			return true;
		}
		
		return false;
	}
	
	public function getAllContent()
	{
		$model = new CMContent();
		$content = $model->ListAll('page');
		
		$ret = array('none');
		
		foreach($content as $value)
		{
			$ret[] = $value['key'];
		}
		
		return $ret;
	}
	
	public function getAllRegions()
	{
		$ret = array();
		
		foreach($this->config['theme']['regions'] as $region)
		{
			$ret[$region] = $region;
		}
		
		foreach($this['content'] as $region => $id)
		{
			$ret[$region] = $region;
		}
		
		return $ret;
	}
	
	public function LoadById($id)
	{
		$tmp = $this->database->Get('frame', array('*'), array('id' => $id));
		if (!empty($tmp))
		{
			$this['id']			= $tmp[0]['id'];
			$this['key']		= $tmp[0]['key'];
			$this['content']	= unserialize($tmp[0]['data']);
			$this['created']	= $tmp[0]['created'];
			$this['deleted']	= $tmp[0]['deleted'];
			$this['title']		= $tmp[0]['title'];
		}
		else
		{
			$this['id']			= '';
			$this['key']		= '';
			$this['content']	= array();
			$this['created']	= '';
			$this['deleted']	= '';
			$this['title']		= '';
		}
	}
}