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
	
	public function Login()
	{
		$form = new CForm();
		$form->AddElement(new CFormElementText('acronym', array('label'=>'Acronym or email:', 'type'=>'text')));
		$form->AddElement(new CFormElementPassword('password', array('label'=>'Password:', 'type'=>'password')));
		$form->AddElement(new CFormElementSubmit('doLogin', array('value'=>'Login', 'type'=>'submit', 'callback'=>array($this, 'DoLogin'))));
		$form->Check();

		$this->views->SetTitle('Login');
		$this->views->AddView('User/login.tpl.php', array('login_form'=>$form->GetHTML()));
	}
	
	public function DoLogin($form)
	{
		if ($this->user->Login($form->GetValue('acronym'), $form->GetValue('password')))
		{
			$this->RedirectToController('profile');
		}
		else
		{
			$this->RedirectToController('login');
		}
	}
	
	public function Logout()
	{
		$this->user->Logout();
		$this->RedirectToController();
	}
	
	public function Profile()
	{
		$this->views->AddView('User/profile.tpl.php', array('user'=>$this->user->GetUserProfile()));
	}
	
	public function Init()
	{
		$this->user->Init();
		$this->RedirectToController();
	}
}