<?php

class CMUser extends CObject
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Init()
	{
		$userExist = $this->database->Get('user','',array(
			'acronym'	=>	$this->config['CMUser-Admin']['acronym'],
			)
		);
		if (empty($userExist))
		{
			$this->config['CMUser-Admin']['created'] = date('o-m-d H:i:s');
			$this->database->Insert('user', $this->config['CMUser-Admin']);
		}
	}
	
	public function Login($acronymOrEmail, $password)
	{
		$user	= $this->database->Get('user','',array(
			'password'	=>	$password,
			'userAuth'	=>	array(
				'email'		=> $acronymOrEmail,
				'acronym'	=> $acronymOrEmail
				)
			)
		);
		
		$user = isset($user[0])?$user[0] : null;
		
		if (!empty($user))
		{
			$this->session->SetAuthenticatedUser($user);
			$this->session->AddMessage('success', "Welcome '{$user['name']}'.");
		}
		else
		{
			$this->session->AddMessage('notice', "Could not login, user does not exists or password did not match.");
		}
		return ($user != null);
	}
	
	public function Logout()
	{
		$this->session->UnsetAuthenticatedUser();
		$this->session->AddMessage('success', "You have logged out.");
	}
	
	public function IsAuthenticated()
	{
		return ($this->session->GetAuthenticatedUser() != false);
	}
	
	public function GetUserProfile()
	{
		return $this->session->GetAuthenticatedUser();
	}
}