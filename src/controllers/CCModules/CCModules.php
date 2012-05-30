<?php/*** Controller for the Modules** @package NocturnalExtra*/class CCModules extends CObject implements IController{	public function __construct()	{		parent::__construct();	}		/**	* Index-page for the module.	*	*	*/		public function Index()	{		if ($this->user['isAuthenticated'])		{			$modules		= new CMModules();			$controllers	= $modules->AvailableControllers();			$allModules		= $modules->ReadAndAnalyse();			$this->views->SetTitle('Manage Modules');			$this->views->AddView('modules/index.tpl.php', array(				'controllers'	=> $controllers,				),				'primary'			);			$this->views->AddView('modules/sidebar.tpl.php',array(				'modules'		=> $allModules,				),				'sidebar'			);		}	}		/**	* View specifics about a single module.	*	* @param String module name to be viewed	*/		public function View($module)	{		if ($this->user['isAuthenticated'])		{			if(!preg_match('/^C[a-zA-Z]+$/', $module)) {throw new Exception('Invalid characters in module name.');}			$modules = new CMModules();			$controllers = $modules->AvailableControllers();			$allModules = $modules->ReadAndAnalyse();			$aModule = $modules->ReadAndAnalyseModule($module);			$this->views->SetTitle('Manage Modules');			$this->views->AddView('modules/view.tpl.php', array('module'=>$aModule), 'primary');			$this->views->AddView('modules/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');		}	}		/**	* Install feature for Nocturnal, installs all modules which use databases.	*	*	*/		public function Install()	{		if ($this->user->InGrop($this->config['CMUser-Groups']['admin']['acronym']))		{			$modules = new CMModules();			$results = $modules->Install();			$allModules = $modules->ReadAndAnalyse();			$this->views->SetTitle('Install Modules');			$this->views->AddView('modules/install.tpl.php', array('modules'=>$results), 'primary');			$this->views->AddView('modules/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');		}	}}