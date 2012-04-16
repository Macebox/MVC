<?php

class CCUser extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		$this->views->SetTitle('User Profile');
		$this->views->AddView('User/index.tpl.php', array(
			'is_authenticated'	=> $this->user->IsAuthenticated(),
			'user'				=> $this->user->GetUserProfile(),
			)
		);
	}
	
	public function Login($acronymOrEmail=null, $password=null)
	{
		$this->user->Login($acronymOrEmail, $password);
		$this->RedirectToController("profile");
	}
	
	public function Logout()
	{
		$this->user->Logout();
		$this->RedirectToController();
	}
	
	public function Init()
	{
		$this->user->Init();
		$this->RedirectToController();
	}
}