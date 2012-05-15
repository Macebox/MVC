<?php

class CCTheme extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		$this->views->SetTitle('Theme');
		$this->views->AddView('Theme/index.tpl.php', array(
			'theme_name'	=> $this->config['theme']['name'],
			)
		);
	}
}