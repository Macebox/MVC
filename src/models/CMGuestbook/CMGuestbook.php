<?php/*** The guestbook model.** @package NocturnalExtra*/class CMGuestbook extends CObject implements IModule{	private $db = null;	public function __construct()	{		parent::__construct();	}		/**	* Manage method for the module.	*	* @param String action to be run	*/		public function Manage($action=null)	{		switch($action)		{			case 'install':			{				if ($this->Init())				{					return array('success', 'Successfully created the database tables (or left them untouched if they already existed).');				}				else				{					return array('error', 'Unable to run query.');				}			} break;			default:				throw new Exception('Unsupported action for this module.');				break;		}	}		/**	* Inits the database tables.	*	*	*/		private function Init()	{		return $this->database->RunQuery("CREATE TABLE IF NOT EXISTS `posts` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `text` text NOT NULL,  `author` varchar(128) NOT NULL,  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1;", true);	}		/**	* Adds an entry to the guestbook.	*	* @param Array entry to be inserted	*/		public function Add($entry)	{		if ($this->user['isAuthenticated'])		{			$this->database->Insert('posts', $entry);			$this->session->AddMessage('info', 'Message inserted successfully.');		}	}		/**	* Reads all guestbook posts into an array.	*	*	*/		public function ReadAll()	{		return $this->database->Get('posts', '', array(), array('time'), false);	}		/**	* Clears all data from the guestbook-table.	*	*	*/		public function DeleteAll()	{		if ($this->user['isAuthenticated'])		{			$this->database->Delete('posts');			$this->session->AddMessage('success', 'Removed all messages from the database table.');		}	}}