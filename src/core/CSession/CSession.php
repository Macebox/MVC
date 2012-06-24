<?php

/**
* Session-manager for Nocturnal.
*
* @package NocturnalCore
*/

class CSession
{
	private $key = null;
	private $data = array();
	private $flash = null;
	
	public function __construct($session_key)
	{
		$this->key = $session_key;
	}
	
	/**
	* Stores all session data in the session.
	*
	*
	*/
	
	public function StoreInSession()
	{
		$_SESSION[$this->key] = $this->data;
	}
	
	/**
	* Sets data in this.
	*
	* @param String key
	* @param Mixed value
	*/
	
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}
	
	/**
	* Returns value stored in this.
	*
	* @param String key
	*/
	
	public function __get($key)
	{
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
	
	/**
	* Sets flash-memory(removed every refresh, read just before).
	*
	* @param String key
	* @param Mixed value
	*/
	
	public function SetFlash($key, $value)
	{
		$this->data['flash'][$key] = $value;
	}
	
	/**
	* Returns value stored in flash-memory.
	*
	* @param String key
	*/
	
	public function GetFlash($key)
	{
		return isset($this->flash[$key]) ? $this->flash[$key] : null;
	}
	
	/**
	* Gets data from session.
	*
	*
	*/
	
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
	
	/**
	* Adds message which will be showed next page-refresh.
	*
	* @param String type
	* @param String message
	*/
	
	public function AddMessage($type, $message)
	{
		$this->data['flash']['messages'][] = array('type' => $type, 'message' => $message);
	}
	
	/**
	* Returns all messages from last page.
	*
	*
	*/
	
	public function GetMessages()
	{
		if (isset($this->data['flash']['messages']))
		{
			foreach($this->data['flash']['messages'] as $msg)
			{
				$this->flash['messages'][] = $msg;
			}
			
			$this->data['flash']['messages'] = array();
			$this->StoreInSession();
		}
		return isset($this->flash['messages']) ? $this->flash['messages'] : null;
	}
	
	/**
	* Sets the session user.
	*
	* @param CMUser user-data
	*/
	
	public function SetAuthenticatedUser($user)
	{
		$this->data['CMUser'] = $user;
	}
	
	/**
	* Removes user data from session.
	*
	*
	*/
	
	public function UnsetAuthenticatedUser()
	{
		unset($this->data['CMUser']);
	}
	
	/**
	* Returns user data from session.
	*
	*
	*/
	
	public function GetAuthenticatedUser()
	{
		return isset($this->data['CMUser'])?$this->data['CMUser']:false;
	}
}