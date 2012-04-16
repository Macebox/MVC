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
			$this->database->Insert('user', $this->config['CMUser-Admin']);
			
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
}