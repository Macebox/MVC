<?phpclass CMGuestbook extends CObject{	private $db = null;	public function __construct()	{		parent::__construct();	}		public function Add($entry)	{		$this->database->Insert('posts', $entry);	}		public function ReadAll()	{		return $this->database->Get('posts');	}		public function DeleteAll()	{		$this->database->Delete('posts');		$this->session->AddMessage('info', 'Removed all messages from the database table.');	}}