<?php/*** The view-handler for Nocturnal.** @package NocturnalCore*/class CViewContainer{	private $data = array();	private $views = array();		public function __construct()	{	}		/**	* Returns data stored in the manager.	*	*	*/		public function GetData()	{		return $this->data;	}		/**	* Sets page title.	*	* @param String title	*/		public function SetTitle($value)	{		$this->SetVariable('title', $value);	}		/**	* Sets data variable for views.	*	* @param String key	* @param Mixed value	*/		public function SetVariable($key, $value)	{		$this->data[$key] = $value;	}		/**	* Adds a view to the view-list.	*	* @param String filename	* @param Array variables	* @param String region to be rendered in	*/		public function AddView($file, $variables=array(), $region='default')	{		$this->views[$region][] = array(			'type'=>'include',			'file'=>$file,			'variables'=>$variables		);	}		/**	* Inserts string in region.	*	* @param String string to show	* @param Array variables	* @param String region to render in	*/		public function AddString($string, $variables=array(), $region='default')	{		$this->views[$region][] = array('type' => 'string', 'string' => '<p>'.$string.'</p>', 'variables' => $variables);		return $this;	}		/**	* Renders a selected region	*	* @param String region	*/		public function Render($region='default')	{		if (!isset($this->views[$region])) return;		foreach($this->views[$region] as $view)		{			switch($view['type'])			{				case 'include':				{					extract($view['variables']);					if (file_exists(MVC_SITE_PATH.'/views/'.$view['file']))					{						include(MVC_SITE_PATH.'/views/'.$view['file']);					} else if (file_exists(MVC_INSTALL_PATH.'/src/views/'.$view['file']))					{						include(MVC_INSTALL_PATH.'/src/views/'.$view['file']);					}					break;				}				case 'string': extract($view['variables']); echo $view['string']; break;			}		}	}		/**	* Checks wheter region has views.	*	* @param String region	*/		public function RegionHasView($region)	{		if(is_array($region))		{			foreach($region as $val)			{				if(isset($this->views[$val]))				{					return true;				}			}			return false;		} else		{			return(isset($this->views[$region]));		}	}		/**   * Add inline style.   *   * @param $value string to be added as inline style.   * @returns $this.   */	public function AddStyle($value)	{		if(isset($this->data['inline_style']))		{			$this->data['inline_style'] .= $value;		} else		{			$this->data['inline_style'] = $value;		}		return $this;	}}