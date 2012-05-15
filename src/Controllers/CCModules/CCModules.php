<?phpclass CCModules extends CObject implements IController{	public function __construct()	{		parent::__construct();	}		public function Index()	{		$modules		= new CMModules();		$controllers	= $modules->AvailableControllers();		$allModules		= $modules->ReadAndAnalyse();		$this->views->SetTitle('Manage Modules');		$this->views->AddView('Modules/index.tpl.php', array(			'controllers'	=> $controllers,			),			'primary'		);		$this->views->AddView('Modules/sidebar.tpl.php',array(			'modules'		=> $allModules,			),			'sidebar'		);	}		public function Install()	{		$modules = new CMModules();		$results = $modules->Install();		$allModules = $modules->ReadAndAnalyse();		$this->views->SetTitle('Install Modules');		$this->views->AddView('Modules/install.tpl.php', array('modules'=>$results), 'primary');		$this->views->AddView('Modules/sidebar.tpl.php', array('modules'=>$allModules), 'sidebar');  }}