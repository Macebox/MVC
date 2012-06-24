<?php

/**
* Controller for the Admin Control Panel
*
* @package NocturnalCMF
*/

class CCAdminControlPanel extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	*	Index page for the module.
	*
	*/
	
	public function Index()
	{
		$this->views->SetTitle('Admin Control Panel');
		$this->views->AddView('acp/index.tpl.php', array(
			'user'	=> $this->user,
			),
			'primary');
	}
	
	public function User($id=null)
	{
		$users = null;
		$groups = null;
		$user = $this->user->GetUser($id);
		if ($user==null)
		{
			$users = $this->user->ReadAll();
		}
		else
		{
			$model = new CMGroup();
			$groups = $model->ReadAll();
		}
		
		$this->views->SetTitle('ACP - Users');
		$this->views->AddView('acp/user.tpl.php', array(
			'admin'		=> $this->user->InGroup('admin'),
			'users'		=> $users,
			'user'		=> $user,
			'groups'	=> $groups,
			),
			'primary'
			);
	}
	
	public function AddGroup($user, $group)
	{
		if ($this->user->InGroup('admin'))
		{
			if ($this->user->AddGroup($user, $group))
			{
				$this->session->AddMessage('success', 'Succesfully added user to group');
			}
		}
		$this->RedirectToController('user/'.$user);
	}
	
	public function RemoveGroup($user, $group)
	{
		if ($this->user->InGroup('admin'))
		{
			if ($this->user->RemoveGroup($user, $group))
			{
				$this->session->AddMessage('success', 'Succesfully removed user from group');
			}
		}
		$this->RedirectToController('user/'.$user);
	}
	
	public function Group()
	{
		$groups = null;
		$model = new CMGroup();
		$groups = $model->ReadAll();
		$this->views->SetTitle('ACP - Groups');
		$this->views->AddView('acp/group.tpl.php', array(
			'user'		=> $this->user,
			'groups'	=> $groups,
			),
			'primary'
			);
	}
	
	public function EditGroup($id = null)
	{
		$label = empty($id)?'Add':'Edit';
		$model = new CMGroup($id);
		$form = new CForm();
		
		$form->AddElement(new CFormElementText('acronym', array('value' => $model['acronym'])));
		$form->AddElement(new CFormElementText('name', array('value' => $model['name'])));
		$form->AddElement(new CFormElementSubmit($label, array('callback' => array($this, 'DoEditGroup'))));
		if (!empty($id))
		{
			$form->AddElement(new CFormElementSubmit('remove', array('callback' => array($this, 'DoRemoveGroup'))));
		}
		$form->AddElement(new CFormElementHidden('id', array('value' => $id)));
		
		$form->SetValidation('acronym', array('not_empty'));
		$form->SetValidation('name', array('not_empty'));
		
		if ($this->user->InGroup('admin'))
		{
			$form->Check();
		}
		
		$this->views->SetTitle("ACP - {$label} Group");
		$this->views->AddView('acp/editgroup.tpl.php', array(
			'label'		=> $label,
			'form'		=> $form->GetHTML(),
			),
			'primary');
	}
	
	public function DoEditGroup($form)
	{
		$model = new CMGroup($form['id']['value']);
		$name = $form['name']['value'];
		$acronym = $form['acronym']['value'];
		if ($model->Edit($acronym, $name))
		{
			$this->session->AddMessage('success', 'Succesfully edited the group');
		}
		else
		{
			$this->session->AddMessage('error', 'Unable to edit group');
		}
		$this->RedirectToController('editgroup/'.$form['id']['value']);
	}
	
	public function DoRemoveGroup($form)
	{
		$model = new CMGroup($form['id']['value']);
		if ($model->Remove())
		{
			$this->session->AddMessage('success', 'Succesfully remove the group');
			$this->RedirectToController('group');
		}
		else
		{
			$this->session->AddMessage('error', 'Unable to remove group');
			$this->RedirectToController('editgroup/'.$form['id']['value']);
		}
	}
	
	public function Route()
	{
		$groups = null;
		$model = new CMRoute();
		$routes = $model->ReadAll();
		
		$form = new CFormRoutes();
		
		$form->AddElement(new CFormElementHeading('Current routes:'));
		
		$i=0;
		foreach($routes as $route => $array)
		{
			$form->AddElement(new CFormElementCheckbox('route-'.$i.'-enabled', array(
				'checked'	=> $array['enabled'],
				'label'		=> '',
				)
			));
			$form->AddElement(new CFormElementText('route-'.$i.'-trigger', array(
				'value'		=> $route,
				'label'		=> '',
				)
			));
			$form->AddElement(new CFormElementText('route-'.$i.'-route', array(
				'value'		=> $array['url'],
				'label'		=> '',
				)
			));
			$form->AddElement(new CFormElementHidden('route-'.$i.'-old', array(
				'value'		=> $route,
				)
			));
			$i++;
		}
		
		$form->AddElement(new CFormElementHeading('New route:'));
		
		$form->AddElement(new CFormElementCheckbox('route-new-enabled', array(
				'label'		=> '',
				)
			));
			$form->AddElement(new CFormElementText('route-new-trigger', array(
				'label'		=> '',
				)
			));
			$form->AddElement(new CFormElementText('route-new-route', array(
				'label'		=> '',
				)
			));
		
		$form->AddElement(new CFormElementSubmit('Apply', array('callback' => array($this, 'DoApplyRoutes'))));
		
		$form->Check();
		
		$this->views->SetTitle('ACP - Routes');
		$this->views->AddView('acp/route.tpl.php', array(
			'user'		=> $this->user,
			'form'	=> $form->GetHTML(),
			),
			'primary'
			);
	}
	
	public function DoApplyRoutes($form)
	{
		if (!$this->user->InGroup('admin'))
		{
			$this->RedirectToController('index');
		}
		$aRoutes = array();
		for ($nRoutes=0;isset($form['route-'.$nRoutes.'-trigger']); $nRoutes++)
		{
			$aRoutes[$nRoutes] = array(
				'trigger'		=> $form['route-'.$nRoutes.'-trigger']	['value'],
				'route'			=> $form['route-'.$nRoutes.'-route']	['value'],
				'enabled'		=> isset($form['route-'.$nRoutes.'-enabled']['value']),
				'old'			=> $form['route-'.$nRoutes.'-old']		['value'],
				);
		}
		
		if (!empty($form['route-new-trigger']['value']))
		{
			$aRoutes[$nRoutes] = array(
				'trigger'	=> $form['route-new-trigger']	['value'],
				'route'		=> $form['route-new-route']		['value'],
				'enabled'	=> isset($form['route-new-enabled']['value']),
				'old'		=> '',
			);
			$nRoutes++;
		}
		
		$model = new CMRoute();
		
		if ($model->ApplyRoutes($aRoutes))
		{
			$this->session->AddMessage('success', 'Succesfully applied route settings');
		}
		else
		{
			$this->session->AddMessage('error', 'Unable to apply route settings');
		}
		$this->RedirectToController('route');
	}
}