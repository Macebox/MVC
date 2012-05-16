<?php

/**
* Controller for the Blog
*
* @package NocturnalCMF
*/

class CCBlog extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	*  Index page for the module.
	*
	*
	*/
	
	public function Index()
	{
		$content = new CMContent();
		$this->views->SetTitle('Blog');
		$this->views->AddView('Blog/index.tpl.php', array(
			'contents'	=> $content->ListAll('post','id',true,array('deleted'=>null)),
			),
		'primary'
		);
	}
}