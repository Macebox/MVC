<?php/*** Controller for the Pages** @package NocturnalCMF*/class CCPage extends CObject implements IController{	public function __construct()	{		parent::__construct();	}		/**	* Index-page which just returns error-code 404.	*	*	*/		public function Index()	{		$content = new CMContent();		$this->views->SetTitle('Page Controller');		$admin = $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym'])?true:false;		$this->views->AddView('page/index.tpl.php', array(			'content'	=> null,			'contents'	=> $content->ListAll(),			'admin'		=> $admin,			'user'		=> $this->user->GetUserProfile(), 			),		'primary'		);	}		/**	* View-page for the module.	*	* @param Integer id for the page to be shown.	*/		public function View($id=null)	{		$content = new CMContent($id);		$this->views->SetTitle('Page: '.htmlEnt($content['title']));		$this->views->AddView('page/index.tpl.php', array(			'content'	=> $content,			),		'primary'		);	}}