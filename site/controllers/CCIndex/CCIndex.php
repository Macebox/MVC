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
		$this->views->AddView('Index/index.tpl.php', array('menu'=>$this->Menu()),'primary');
	}
	
	
	private function Menu()
	{   
		$items = array();
		foreach($this->config['controllers'] as $key => $val)
		{
			if($val['enabled'])
			{
				$rc = new ReflectionClass($val['class']);
				$items[] = $key;
				$methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
				foreach($methods as $method)
				{
					if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index')
					{
						$items[] = "$key/" . mb_strtolower($method->name);
					}
				}
			}
		}
		return $items;
	}
}