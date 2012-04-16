<?php

class CCUser extends CObject implements IController
{
	private $userModel = null;
	
	public function __construct()
	{
		parent::__construct();
		$this->userModel = $this->user;
	}
	
	public function Index()
	{
		$this->views->SetTitle('User Profile');
		$this->views->AddView('User/index.tpl.php', array(
			'is_authenticated'=>$this->userModel->IsAuthenticated(),
			'user'=>$this->userModel->GetUserProfile(),
			));
	}
	
	public function Login($acronymOrEmail=null, $password=null)
	{
		$this->userModel->Login($acronymOrEmail, $password);
		$this->RedirectToController();
	}
	
	public function Logout()
	{
		$this->userModel->Logout();
		$this->RedirectToController();
	}
	
	public function Init()
	{
		$this->userModel->Init();
		$this->RedirectToController();
	}
}