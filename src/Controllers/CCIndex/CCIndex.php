<?php

/**
* Controller for the Index-page
*
* @package NocturnalExtra
*/

class CCIndex extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Index-page for the module.
	*
	*
	*/
	
	public function Index()
	{
		$this->views->SetTitle('Index Controller');
		$this->views->AddView('index/index.tpl.php', array(),'primary');
	}
}