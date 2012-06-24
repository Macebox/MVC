<?php/*** Model for handling content.** @package NocturnalCMF*/class CMContent extends CObject implements ArrayAccess, IModule{	public function __construct($id=null)	{		parent::__construct();		if (isset($id))		{			$this->LoadById($id);		}		else		{			$this->data = array();		}	}		/**	* Manage method.	*	* @param String action to be run	*/		public function Manage($action=null)	{		switch($action)		{			case 'install':			{				if ($this->Init())				{					return array('success', 'Successfully created the database tables (or left them untouched if they already existed).');				}				else				{					return array('success', 'Unable to create the database-tables.');				}			}break;			default:				throw new Exception('Unsupported action for this module.');				break;		}	}		/**	* Init method which creates database tables.	*	*	*/		private function Init()	{		return $this->database->RunQuery("CREATE TABLE IF NOT EXISTS `content` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `key` varchar(32) NOT NULL,  `type` text NOT NULL,  `filter` text NOT NULL,  `title` text NOT NULL,  `data` text NOT NULL,  `idUser` int(11) NOT NULL,  `created` datetime NOT NULL,  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  `deleted` datetime DEFAULT NULL,  PRIMARY KEY (`id`),  UNIQUE KEY `key` (`key`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;", true);	}		public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}	public function offsetExists($offset) { return isset($this->data[$offset]); }	public function offsetUnset($offset) { unset($this->data[$offset]); }	public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }		/**	* Saves the current handled content.	*	*	*/		public function Save()	{		if ($this->user['id']==0)		{			return;		}		$msg = null;		$succ = false;		if ($this['id'])		{			$updateArray = array(				'key'		=> $this['key'],				'type'		=> $this['type'],				'title'		=> $this['title'],				'data'		=> $this['data'],				'filter'	=> $this['filter'],				'deleted'	=> null,			);						if ($this->database->Update('content',$updateArray,array('id'=>$this['id'])))			{				$succ = true;			}			$msg = 'update';		}		else		{			$insertArray = array(				'key'		=> $this['key'],				'type'		=> $this['type'],				'filter'	=> $this['filter'],				'title'		=> $this['title'],				'data'		=> $this['data'],				'idUser'	=> $this->user['id'],				'created'	=> date('o-m-d H:i:s'),			);						if ($this->database->Insert('content', $insertArray))			{				$succ = true;				$this['id'] = $this->database->getLastId();			}						$msg = 'create';		}		if ($succ)		{			$this->session->AddMessage('success', "Successfully {$msg} content '{$this['key']}'.");		}		else		{			$this->session->AddMessage('error', "Failed to {$msg} content '{$this['key']}'.");		}	}		/**	* Removes the current handled content.	*	*	*/		public function Remove()	{		if ($this->user['isAuthenticated'])		{			return $this->database->Update('content',array('deleted'=>date('o-m-d H:i:s')),array('id'=>$this['id']));		}		return false;	}		/**	* Loads data from content into this object.	*	* @param integer id to load	*/		public function LoadById($id)	{		$res = $this->database->Get('content','',array('id'=>$id));		if (empty($res))		{			$this->session->AddMessage('error', "Failed to load content '{$id}'.");		}		else		{			$this->data = $res[0];		}				return true;	}		/**	* Lists all content of type.	*	* @param String content-type	* @param String order by	* @param boolean order-order	* @param Array equals	*/		public function ListAll($type='page',$ob='id',$oo=true,$equals=null)	{		$eq = array();		if (isset($type))		{			$eq['type'] = $type;		}		if (isset($equals) && is_array($equals))		{			$eq = array_merge($eq, $equals);		}		$ret = $this->database->Get('content','',$eq,$ob,$oo);				$acronymArray = array();				for ($i=0;$i<count($ret); $i++)		{			$id = $ret[$i]['idUser'];			if (!isset($acronymArray[$id]))			{				$acronym = $this->database->Get('user',array('acronym'),array('id'=>$id));				if (!empty($acronym))				{					$acronymArray[$id] = $acronym[0]['acronym'];				}				else				{					$acronymArray[$id] = 'Anonymous';				}			}			$ret[$i]['owner'] = $acronymArray[$id];		}				return $ret;	}		/**	* Filters data using a specified filter for output.	*	* @param String data to be filtered	* @param String filter to be used	*/		public static function Filter($data, $filter)	{		switch($filter)		{			/*	case 'php':		$data = nl2br(makeClickable(eval('?>'.$data))); break;				case 'html':	$data = nl2br(makeClickable($data)); 			break;*/			case 'htmlpurify':	$data = nl2br(CHTMLPurifier::Purify($data));	break;			case 'bbcode':		$data = nl2br(bbcode2html(htmlEnt($data)));		break;			case 'plain':			default: 			$data = nl2br(makeClickable(htmlEnt($data)));	break;		}		return $data;	}}