<?php
/**
* Holding a instance of CLydia to enable use of $this in subclasses.
*
* @package LydiaCore
*/
class CObject
{
	public $config;
	public $request;
	public $data;
	public $database;
	public $views;
	public $session;

	/**
	 * Constructor
	 */
	protected function __construct()
	{
		$mvc = CNocturnal::Instance();
		$this->config   = &$mvc->config;
		$this->request  = &$mvc->request;
		$this->data     = &$mvc->data;
		$this->database = &$mvc->database;
		$this->views	= &$mvc->views;
		$this->session	= &$mvc->session;
	}

}