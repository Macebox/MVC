<?php

class CCMe extends CObject implements IController
{
	/**
   * Constructor
   */
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		$this->views->SetTitle('Problem htmlfags?');
		
		$this->views->AddView('Me/index.tpl.php', array(),'primary');
		
	}
}