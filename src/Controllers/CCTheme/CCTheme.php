<?php

class CCTheme extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
		$this->views->AddStyle('body:hover{background:#fff url('.$this->request->base_url.'themes/grid/grid_12_60_20.png) repeat-y center top;}');
	}
	
	public function Index()
	{
		$this->views->SetTitle('Theme');
		$this->views->AddView('Theme/index.tpl.php', array(
			'theme_name'	=> $this->config['theme']['name'],
			),
		'primary'
		);
	}
	
	/**
	* Testing method for all formatting
	*/
	
	public function Test()
	{
		$this->views->SetTitle('Testing theme formatting');
		$this->views->AddView('Theme/h1h6.tpl.php',array(),'primary');
	}
	
	/**
	* Put content in some regions.
	*/
	public function SomeRegions()
	{
		$this->views->SetTitle('Theme display content for some regions');
		$this->views->AddString('This is the primary region', array(), 'primary');
               
		if(func_num_args())
		{
			foreach(func_get_args() as $val)
			{
				$this->views->AddString("This is region: $val", array(), $val);
				$this->views->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
			}
		}
	}
	
	public function AllRegions()
	{
		$this->views->SetTitle('Theme display content for all regions');
		foreach($this->config['theme']['regions'] as $val)
		{
			$this->views->AddString("This is region: $val", array(), $val);
            $this->views->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
		}
	}
}