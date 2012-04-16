<?php

class CSession
{
	private $key = null;
	private $data = array();
	private $flash = null;
	
	public function __construct($session_key)
	{
		$this->key = $session_key;
	}
	
	public function StoreInSession()
	{
		$_SESSION[$this->key] = $this->data;
	}
	
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}
	
	public function __get($key)
	{
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
	
	public function SetFlash($key, $value)
	{
		$this->data['flash'][$key] = $value;
	}
	
	public function GetFlash($key)
	{
		return isset($this->flash[$key]) ? $this->flash[$key] : null;
	}
	
	public function PopulateFromSession()
	{
		if(isset($_SESSION[$this->key]))
		{
			$this->data = $_SESSION[$this->key];
			if(isset($this->data['flash']))
			{
				$this->flash = $this->data['flash'];
				unset($this->data['flash']);
			}
		}
	}
	
	public function AddMessage($type, $message)
	{
		$this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);
	}
	
	public function GetMessages()
	{
		return isset($this->flash['messages']) ? $this->flash['messages'] : null;
	}
}