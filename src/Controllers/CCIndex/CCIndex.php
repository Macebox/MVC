<?php

class CCIndex extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		$this->views->SetTitle('Index Controller');
		$this->views->AddView('Index/index.tpl.php', array(),'primary');
	}
}