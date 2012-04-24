<?php

class CMUser extends CObject
{
	public function __construct($mvc)
	{
		parent::__construct($mvc);
	}
	
	public function Init()
	{
		$dateTime = date('o-m-d H:i:s');
		if (isset($this->config['CMUser-Groups']))
		{
			foreach($this->config['CMUser-Groups'] as $key => $val)
			{
				$exists = $this->database->Get('group','',array('acronym'=>$val['acronym']));
				
				if (empty($exists))
				{
					/*Create groups*/
					$val['created'] = $dateTime;
					$this->database->Insert('group',$val);
					$this->session->AddMessage('notice', 'Successfully created a group: '.$val['acronym'].'.');
				}
			}
		}
		
		$userExist = $this->database->Get('user','',array(
			'acronym'	=>	$this->config['CMUser-Admin']['acronym'],
			)
		);
		if (empty($userExist))
		{
			/*Create Admin*/
			$this->config['CMUser-Admin']['created'] = $dateTime;
			
			$tmpArray = $this->CreatePassword($this->config['CMUser-Admin']['password']);
			
			$userArray = $this->config['CMUser-Admin'];
			
			$userArray['password']	= $tmpArray['password'];
			$userArray['salt']		= $tmpArray['salt'];
			
			$this->database->Insert('user', $userArray);
			
			$userId = $this->database->Get('user', array('id'), array(
				'acronym'	=> $this->config['CMUser-Admin']['acronym']
				)
			);
			$userId = $userId[0]['id'];
			
			/*Admin group*/
			
			$groupId = $this->database->Get('group',array('id'),array(
				'acronym'	=> $this->config['CMUser-Groups']['admin']['acronym']
				)
			);
			$groupId = $groupId[0]['id'];
			
			$this->database->Insert('user2group',array(
				'idUser'	=> $userId,
				'idGroup'	=> $groupId,
				'created'	=> $dateTime
				)
			);
			
			/*User group*/
			
			$groupId = $this->database->Get('group',array('id'),array(
				'acronym'	=> $this->config['CMUser-Groups']['user']['acronym']
				)
			);
			$groupId = $groupId[0]['id'];
			
			$this->database->Insert('user2group',array(
				'idUser'	=> $userId,
				'idGroup'	=> $groupId,
				'created'	=> $dateTime
				)
			);
			
			$this->session->AddMessage('notice', 'Successfully created a default admin user as root:root.');
		}
	}
	
	public function Login($acronymOrEmail, $password)
	{
		$user	= $this->database->Get('user','',array(
			'userAuth'	=>	array(
				'email'		=> $acronymOrEmail,
				'acronym'	=> $acronymOrEmail
				)
			)
		);
		
		$user = isset($user[0])?$user[0] : null;
		
		if (!empty($user) && $this->CheckPassword($password, $user['salt'], $user['password']))
		{
			$this->RefreshUserProfile($user['acronym']);
			$this->session->AddMessage('success', "Welcome '{$user['name']}'.");
		}
		else
		{
			$this->session->AddMessage('notice', "Could not login, user does not exists or password did not match.");
		}
		return ($user != null);
	}
	
	public function Create($acronym, $password, $name, $email)
	{
		$dateTime = date('o-m-d H:i:s');
		$tmpU = $this->database->Get('user','',array(
			array(
				'acronym'		=> $acronym,
				'email'			=> $email,
				)
			)
		);
		
		$tmpU = isset($tmpU[0])?$tmpU[0] : null;
		
		if (empty($tmpU))
		{
			$userArray = array(
				'acronym'		=> $acronym,
				'name'			=> $name,
				'email'			=> $email,
				'created'		=> $dateTime);
			$hPwd = $this->CreatePassword($password);
			
			$userArray['password']	= $hPwd['password'];
			$userArray['salt']		= $hPwd['salt'];
			
			$this->database->Insert('user', $userArray);
			
			$user = $this->database->Get('user', array('id'), $userArray);
			
			$userId = $user[0]['id'];
			
			$groupId = $this->database->Get('group',array('id'),array(
				'acronym'	=> $this->config['CMUser-Groups']['user']['acronym']
				)
			);
			$groupId = $groupId[0]['id'];
			
			$this->database->Insert('user2group',array(
				'idUser'	=> $userId,
				'idGroup'	=> $groupId,
				'created'	=> $dateTime
				)
			);
			
			return TRUE;
		}
		else
		{
			$this->session->AddMessage('error', "Acronym or email already exists.");
			return FALSE;
		}
	}
	
	public function Logout()
	{
		$this->session->UnsetAuthenticatedUser();
		$this->session->AddMessage('success', "You have logged out.");
	}
	
	public function ChangePassword($oldPassword, $newPassword)
	{
		$user	= $this->database->Get('user','',array(
			'acronym'		=> $this->user->GetAcronym(),
			)
		);
		
		$user = isset($user[0])?$user[0] : null;
		
		if ($this->CheckPassword($oldPassword,$user['salt'],$user['password']))
		{
			$pwArray = $this->CreatePassword($newPassword);
			$this->database->Update('user',array(
				'password'	=> $pwArray['password'],
				'salt'		=> $pwArray['salt'],
				),
			array(
				'acronym'	=> $this->user->GetAcronym(),
				)
			);
			$this->session->AddMessage('success', 'Password updated');
			
			$this->Login($this->user->GetAcronym(), $newPassword);
			return TRUE;
		}
		else
		{
			$this->session->AddMessage('error', 'Old password not correct.');
			return FALSE;
		}
	}
	
	public function SaveProfile($userInfo)
	{
		if (is_array($userInfo))
		{
			$this->database->Update('user', $userInfo, array(
				'acronym'=>$this->user->GetAcronym()
				)
			);
			
			$this->RefreshUserProfile();
			
			$this->session->AddMessage('success', "User information has been changed.");
		}
	}
	
	public function IsAuthenticated()
	{
		return ($this->session->GetAuthenticatedUser() != false);
	}
	
	public function GetUserProfile()
	{
		return $this->session->GetAuthenticatedUser();
	}
	
	public function RefreshUserProfile($acronym=null)
	{
		if ($acronym==null)
		{
			$acronym = $this->GetAcronym();
		}
		
		$user = $this->database->Get('user','',array('acronym'=>$acronym));
		
		$user = isset($user[0])?$user[0] : null;
		
		if (!empty($user))
		{
			$tmpArray = $this->database->Get('user2group', array('idGroup'), array('idUser'=>$user['id']));
				
			$user['groups'] = array();
			
			foreach($tmpArray as $key => $val)
			{
				$tmp = $this->database->Get('group','',array(
					'id'	=> $val['idGroup'],
					)
				);
				
				$user['groups'][$tmp[0]['acronym']] = $tmp[0];
			}
			
			$this->session->SetAuthenticatedUser($user);
		}
	}
	
	public function InGroup($groupAcronym)
	{
		if ($this->IsAuthenticated())
		{
			$user = $this->session->GetAuthenticatedUser();
			
			return isset($user['groups'][$groupAcronym]);
		}
		
		return FALSE;
	}
	
	public function GetAcronym()
	{
		if ($this->IsAuthenticated())
		{
			$user = $this->session->GetAuthenticatedUser();
			
			return $user['acronym'];
		}
		
		return null;
	}
	
	public function CreatePassword($plain, $salt=true)
	{
		if($salt)
		{
			$salt = md5(microtime());
			$password = md5($salt . $plain);
		}
		else
		{
			$salt = null;
			$password = md5($plain);
		}
		return array('salt'=>$salt, 'password'=>$password);
	}
	
	public function CheckPassword($plain, $salt, $password)
	{
		if($salt)
		{
			return $password === md5($salt . $plain);
		}
		else
		{
			return $password === md5($plain);
		}
	}
}