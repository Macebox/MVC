<?php

/**
* Model for update-handling
*
* @package NocturnalCore
*/

class CMUpdate extends CObject
{
	const version = '0.3.10';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Update()
	{
		/***** If it doesn't have a version, run first version update ******/
		if (!isset($this->config['MVC-Version']))
		{
			$this->v03xx_v0310();
		}
		
		/*************** Else select what update is next *******************/
		
		else if (isset($this->config['MVC-Version']))
		{
			switch($this->config['MVC-Version'])
			{
				case '0.3.10':
					{
						/* Start from version right after 0.3.10 */
					}
					break;
				default:
					break;
			}
		}
		
		/** Set to latest version **/
		
		$this->config['MVC-Version'] = CMUpdate::version;
		
		/************************* Save updates **************************/
		
		$config = new CMConfig();
		
		$config->saveConfigToFile();
		
		$this->user->SyncGroupsWithDatabase();
	}
	
	/**
	 * First version update, recursively calls all the upcoming updates.
	 *
	 *
	 */
	
	public function v03xx_v0310()
	{
		$this->config['CMUser-Groups']['contentmanager'] = array(
			'acronym'	=> 'contentmanager',
			'name'		=> 'The Content Manager Group');
		
		$this->session->AddMessage('info', 'Added content manager group.');
		
		$frame = new CMFrame();
		$msg = $frame->Manage('install');
		$this->session->AddMessage($msg[0], $msg[1]);
		
		$this->config['controllers']['frame'] = array(
			'enabled' => true,
			'class' => 'CCFrame',
		);
		$this->session->AddMessage('info', 'Added frame controller.');
		
		$this->config['theme']['primary-region'] = 'primary';
		
		$this->config['theme']['themes']['nocturnal'] = array(
			'name'		=> 'nocturnal',
			'file'		=> 'index.tpl.php',
			'path'		=> '/themes/nocturnal',
			'parent'	=> 'themes/grid',
			);
		$this->session->AddMessage('info', 'Added nocturnal theme.');
		
		$this->config['controllers']['file'] = array(
			'enabled' => true,
			'class' => 'CCFile',
		);
		$this->session->AddMessage('info', 'Added file controller.');
		
		$this->config['CMUser-Groups']['filemanager'] = array(
			'acronym'	=> 'filemanager',
			'name'		=> 'The File Manager Group');
		$this->session->AddMessage('info', 'Added file manager group.');
	}
}