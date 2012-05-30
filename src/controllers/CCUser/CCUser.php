<?php

/**
* Controller for the User
*
* @package NocturnalCMF
*/

class CCUser extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	* Index-page for the module.
	*
	*
	*/
	
	public function Index()
	{
		$this->views->SetTitle('User Profile');
		$this->views->AddView('user/index.tpl.php', array(
			'is_authenticated'	=> $this->user['isAuthenticated'],
			'user'				=> $this->user,
			'allow_create_user'	=> $this->config['create_new_users'],
			),
		'primary'
		);
	}
	
	/**
	* Login-page for the users.
	*
	*
	*/
	
	public function Login()
	{
		$form = new CForm(array('name'=>'loginForm', 'action'=>$this->request->CreateUrl('user/login')), array(
			'acronym' 		=> new CFormElementText('acronym', array(
				'label'		=> 'Acronym or email:',
				'type'		=> 'text',
				)
			),
			'password'		=> new CFormElementPassword('password', array(
				'label'		=> 'Password:',
				'type'		=> 'password',
				)
			),
			new CFormElementSubmit('doLogin', array(
				'value'		=> 'Login',
				'type'		=> 'submit',
				'callback'	=> array($this, 'DoLogin')
				)
			),
		));
		
		$form->SetValidation('acronym',array('not_empty'));
		$form->SetValidation('password',array('not_empty'));
		
		$form->Check();

		$this->views->SetTitle('Login');
		$this->views->AddView('user/login.tpl.php', array(
			'login_form'		=> $form->GetHTML(),
			'allow_create_user'	=> $this->config['create_new_users'],
			),
		'primary'
		);
	}
	
	/**
	* Login-method for the module
	*
	* @param CForm form with login information inserted
	*/
	
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
	
	/**
	* Logout-method for the module.
	*
	*
	*/
	
	public function Logout()
	{
		$this->user->Logout();
		$this->RedirectToController();
	}
	
	/**
	* Profile-page for the module.
	*
	*
	*/
	
	public function Profile()
	{
		$user = $this->user;
		$profileForm = new CForm(array(
			'action'	=> $this->request->CreateUrl('user/profile'),
			),
		array(
			'acronym'	=> new CFormElementText('Acronym', array(
				'readonly'	=> true,
				'value'		=> $this->user->GetAcronym(),
				)
			),
			'OldPw'		=> new CFormElementPassword('OldPw',array(
				'label'		=> 'Old Password',
				)
			),
			'Pw'		=> new CFormElementPassword('Pw', array(
				'label'		=> 'Password',
				)
			),
			'Pw2'		=> new CFormElementPassword('Pw2', array(
				'label'		=> 'Password again',
				)
			),
			new CFormElementSubmit('doChangePassword', array(
				'value'		=> 'Change password',
				'callback'	=> array($this, 'DoChangePassword'),
				)
			),
		));
		
		$profileForm->SetValidation('OldPw',array('not_empty'));
		$profileForm->SetValidation('Pw',array('not_empty'));
		$profileForm->SetValidation('Pw2',array('not_empty'));
		
		$profileForm->Check();
		
		$userProfileForm = new CForm(array(
			'action'	=> $this->request->CreateUrl('user/profile')), array(
			'Name'		=> new CFormElementText('Name', array(
				'label'		=> 'Name:*',
				'value'		=> $user['name'],
				)
			),
			'Email'		=> new CFormElementText('Email', array(
				'label'		=> 'Email:*',
				'value'		=> $user['email'],
				)
			),
			new CFormElementSubmit('doSaveProfile', array(
				'value'		=> 'Save',
				'callback'	=> array($this, 'DoSaveProfile'),
				)
			),
		));
		
		$userProfileForm->SetValidation('Name',array('not_empty'));
		$userProfileForm->SetValidation('Email',array('not_empty'));
		
		$userProfileForm->Check();
		
		$this->views->SetTitle('Profile');
		$this->views->AddView('user/profile.tpl.php', array(
			'profileForm'		=> $profileForm->GetHTML().$userProfileForm->GetHTMl(),
			'user'				=> $this->user,
			'is_authenticated'	=> $this->user['isAuthenticated'],
			),
		'primary'
		);
	}
	
	/**
	* Create user-page for the module.
	*
	*
	*/
	
	public function Create()
	{
		$form = new CForm(array('name'=>'createUserForm', 'action'=>$this->request->CreateUrl('user/create')), array(
			'acronym' 		=> new CFormElementText('acronym', array(
				'label'		=> 'Acronym:',
				'type'		=> 'text',
				)
			),
			'password'		=> new CFormElementPassword('password', array(
				'label'		=> 'Password:',
				'type'		=> 'password',
				)
			),
			'password2'		=> new CFormElementPassword('password', array(
				'label'		=> 'Password again:',
				'type'		=> 'password',
				)
			),
			'name' 		=> new CFormElementText('name', array(
				'label'		=> 'Name:',
				'type'		=> 'text',
				)
			),
			'email' 		=> new CFormElementText('email', array(
				'label'		=> 'Email:',
				'type'		=> 'text',
				)
			),
			new CFormElementSubmit('doCreate', array(
				'value'		=> 'Create',
				'type'		=> 'submit',
				'callback'	=> array($this, 'DoCreate')
				)
			),
		));
		
		$form->SetValidation('acronym',array('not_empty'));
		$form->SetValidation('password',array('not_empty'));
		$form->SetValidation('password2',array('not_empty'));
		$form->SetValidation('name',array('not_empty'));
		$form->SetValidation('email',array('not_empty'));
		
		$form->Check();

		$this->views->SetTitle('Create user');
		$this->views->AddView('user/create.tpl.php', array(
			'register_form'		=> $form->GetHTML(),
			'allow_create_user'	=> $this->config['create_new_users'],
			),
		'primary'
		);
	}
	
	/**
	* User creation-method for the module.
	*
	*
	*/
	
	public function DoCreate($form)
	{
		if ($form['password']['value']!=$form['password2']['value'])
		{
			$this->session->AddMessage('error', 'Password did not match.');
			$this->RedirectToController('create');
		}
		else if($this->user->Create($form['acronym']['value'],
                           $form['password']['value'],
                           $form['name']['value'],
                           $form['email']['value']
                           ))
		{
			$this->user->Login($form['acronym']['value'], $form['password']['value']);
			$this->session->AddMessage('success', "Welcome {$this->user->GetAcronym()}. Your have successfully created a new account.");
			$this->RedirectToController('profile');
		}
		else
		{
			$this->session->AddMessage('notice', "Failed to create an account.");
			$this->RedirectToController('create');
		}
	}
	
	/**
	* Password change-method for the module.
	*
	*
	*/
	
	public function DoChangePassword($form)
	{
		if ($form->GetValue('Pw') == $form->GetValue('Pw2'))
		{
			$this->user->ChangePassword($form->GetValue('OldPw'), $form->GetValue('Pw'));
		}
		else
		{
			$this->session->AddMessage('info', 'Password didn\'t match.');
		}
		
		$this->RedirectToController('profile');
	}
	
	/**
	* Profile save-method for the module.
	*
	*
	*/
	
	public function DoSaveProfile($form)
	{
		$userInfo = array('name'=>$form->GetValue('Name'), 'email'=>$form->GetValue('Email'));
		
		$this->user->SaveProfile($userInfo);
		
		$this->RedirectToController('profile');
	}
}