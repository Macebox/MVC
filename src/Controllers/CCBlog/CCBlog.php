<?php

class CCBlog extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		$content = new CMContent();
		$this->views->SetTitle('Blog');
		$this->views->AddView('Blog/index.tpl.php', array(
			'contents'	=> $content->ListAll('post','title',false)
			),
		'primary'
		);
	}
}