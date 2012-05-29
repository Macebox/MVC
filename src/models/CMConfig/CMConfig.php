<?php

/**
* Configuration handling model
*
* @package NocturnalCore
*/

class CMConfig extends CObject
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function ApplyConfig($form)
	{
		/****************************** Theme **********************************/
		$this->config['theme']['data']['header']			= $form['header']['value'];
		$this->config['theme']['data']['slogan']			= $form['slogan']['value'];
		$this->config['theme']['data']['footer']			= $form['footer']['value'];
		
		$selectedTheme = $this->config['theme']['themes'][$form['theme']['value']];
		
		$this->config['theme']['path']						= $selectedTheme['path'];
		$this->config['theme']['parent']					= $selectedTheme['parent'];
		$this->config['theme']['template_file']				= $selectedTheme['file'];
		
		/****************************** Navigation **********************************/
		
		$this->config['navbar'] = array();
		
		for ($i=0; $i<9; $i++)
		{
			$class	= $form['nav-'.($i+1)]['value'];
			$title	= $form['nav-'.($i+1).'-title']['value'];
			$url	= null;
			
			foreach($this->config['controllers'] as $key => $value)
			{
				if ($value['class']==$class && $value['enabled'])
				{
					$url = $key;
				}
			}
			
			foreach($this->config['routing'] as $key => $value)
			{
				if ($key == $class && $value['enabled'])
				{
					$url = $key;
				}
			}
			
			if ($class!="None" && $url!=null)
			{
				$this->config['navbar'][$url]				= array(
					'text'		=> $title,
					'url'		=> $url,
				);
			}
		}
		
		/****************************** Database **********************************/
		
		if (isset($form['dbactive']['value']))
		{
			$this->config['database']['active']				= true;
		}
		else
		{
			$this->config['database']['active']				= false;
		}
		
		$this->config['database']['dsn']					= $form['dbdsn']['value'];
		$this->config['database']['host']					= $form['dbhost']['value'];
		$this->config['database']['user']					= $form['dbuser']['value'];
		$this->config['database']['dbDriver']				= $form['dbdriver']['value'];
		$this->config['database']['db']						= $form['db']['value'];
		
		if (isset($form['usedbpw']['value']) && $form['dbpassword']['value']==$form['dbpassword2']['value'] && !empty($form['dbpassword']['value']))
		{
			$this->config['database']['password']			= $form['dbpassword']['value'];
		} else if (isset($form['usedbpw']['value']) && !empty($form['dbpassword']['value']))
		{
			$this->session->AddMessage('warning','Database password must match.');
		} else if (!isset($form['usedbpw']['value']))
		{
			$this->config['database']['password']			= "";;
		}
		
		/****************************** Debug **********************************/
		
		if (isset($form['debug']['value']))
		{
			$this->config['debugEnabled']					= in_array('debug', $form['debug']['value'])		? true : false;
			$this->config['debug']['mvc']					= in_array('debugMVC', $form['debug']['value'])		? true : false;
			$this->config['debug']['db-num-queries']		= in_array('debugNQ', $form['debug']['value'])		? true : false;
			$this->config['debug']['db-queries']			= in_array('debugQ', $form['debug']['value'])		? true : false;
		}
		
		/****************************** Other **********************************/
		
		$this->config['create_new_users']					= isset($form['allowusercreation']['value'])		? true : false;
		
		$this->config['timezone']							= $form['timezone']['value'];
		
		
		/****************************** OUTPUT TO site/config.php **********************************/
		
		$this->saveConfigToFile();
		
	}
	
	public function ApplyControllers($form)
	{
		$cons = $this->getControllers();
		
		foreach($cons as $controllers)
		{
			if (isset($form[$controllers]['value']))
			{
				if (!empty($form[$controllers.'-old-url']['value']))
				{
					unset($this->config['controllers'][$form[$controllers.'-old-url']['value']]);
				}
				if (!empty($form[$controllers.'-url']['value']))
				{
					$this->config['controllers'][$form[$controllers.'-url']['value']] = array('enabled' => true, 'class' => $controllers);
				}
			}
			else if (!empty($form[$controllers.'-url']['value']))
			{
				if (!empty($form[$controllers.'-old-url']['value']))
				{
					unset($this->config['controllers'][$form[$controllers.'-old-url']['value']]);
				}
				
				$this->config['controllers'][$form[$controllers.'-url']['value']] = array('enabled' => false, 'class' => $controllers);
			}
		}
		
		$this->saveConfigToFile();
	}
	
	public function saveConfigToFile()
	{
		/****************************** Header for file **********************************/
		$_CONFIGFILE = <<<EOD
<?php

/*
	Site configuration file
*/

error_reporting(-1);
ini_set('display_errors', 1);
EOD;
		/*********************** Check if comment-file exists***********************/
		
		foreach($this->config['commentfiles'] as $value)
		{
			if (file_exists(MVC_SITE_PATH . $value))
			{
				require_once(MVC_SITE_PATH . $value);
			}
		}
		
		if (!isset($configComments))
		{
			$configComments = array();
		}
		
		/****************************** Open file **********************************/
		$filename = MVC_SITE_PATH . '/config.php';
		
		$file = fopen($filename, 'w');
		
		fwrite($file, $_CONFIGFILE.PHP_EOL.PHP_EOL);
		fwrite($file, COutputVariable::getRunnableVariable("mvc->config", $this->config, $configComments));
		
		fclose($file);
	}
	
	public function Complete()
	{
		/****************************** Clean up index-code etc **********************************/
		$this->config['installed'] = true;
		$this->config['controllers']['index']['enabled'] = false;
		$this->config['session_key'] = $this->config['theme']['data']['header'];
		$this->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
		$this->config['url_type'] = 1;
		
		$basicUrl = null;
		
		foreach($this->config['navbar'] as $key => $value)
		{
			$basicUrl = $key;
			break;
		}
		
		$this->config['routing']['index'] = array('enabled'=>true, 'url'=>$basicUrl);
		
		/****************************** Change .htaccess-file **********************************/
		
		$rewBase = substr($this->request->request_uri,0,strpos($this->request->request_uri, 'index.php'));
		
		$htaccess=<<<EOD
<IfModule mod_rewrite.c>
  RewriteEngine on
  RewriteBase {$rewBase}
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule (.*) index.php/$1 [NC,L]
</IfModule>
EOD;
		
		$file = fopen(MVC_INSTALL_PATH."/.htaccess", 'w');
		fwrite($file, $htaccess);
		fclose($file);
		
		/****************************** OUTPUT TO site/config.php **********************************/
		
		$this->saveConfigToFile();
	}
	
	private function getControllers()
	{
		$ret = array();
		$tmp = new CMModules();
		
		foreach($tmp->ReadAndAnalyse() as $key => $array)
		{
			if ($array['isController'])
			{
				$ret[] = $key;
			}
		}
		
		return $ret;
	}
}