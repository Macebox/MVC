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
	
	private function saveConfigToFile()
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
		/****************************** Open file **********************************/
		$filename = MVC_INSTALL_PATH . '/site/config.php';
		
		$file = fopen($filename, 'w');
		
		fwrite($file, $_CONFIGFILE.PHP_EOL.PHP_EOL);
		fwrite($file, COutputVariable::getRunnableVariable("mvc->config", $this->config));
		
		fclose($file);
	}
	
	public function Complete()
	{
		/****************************** Clean up index-code etc **********************************/
		$this->config['installed'] = true;
		$this->config['controllers']['index']['enabled'] = false;
		$this->config['session_key'] = $this->config['theme']['data']['header'];
		$this->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);
		
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
}