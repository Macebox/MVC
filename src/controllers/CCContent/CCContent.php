<?php

/**
* Controller for the Content
*
* @package NocturnalCMF
*/

class CCContent extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	*  Index page for the module.
	*
	*/
	
	public function Index()
	{
		$content = new CMContent();
		$this->views->SetTitle('Content Controller');
		$admin = $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym'])?true:false;
		$this->views->AddView('content/index.tpl.php', array(
			'contents'	=> $content->ListAll(null),
			'admin'		=> $admin,
			'user'		=> $this->user->GetUserProfile(), 
			),
		'primary'
		);
	}
	
	/**
	*  Redirects to edit-method with a blank id.
	*
	*/
	
	public function Create()
	{
		$this->Edit();
	}
	
	/**
	* Displays the form for the content-creation/editing.
	*
	* @param integer id, content id
	*/
	
	public function Edit($id=null)
	{
		$content	= new CMContent($id);
		$save = isset($content['id'])? 'save' : 'create';
		$enableRemove = (!isset($content['deleted']));
		
		$typeA = $this->config['content']['types'];
		
		if ($id!=null)
		{
			$typeA[$content['type']] = array('value' => $content['type'], 'selected' => 'selected');
		}
		
		$filterA = $this->config['content']['filter'];
		
		if ($id!=null)
		{
			$filterA[$content['filter']] = array('value' => $content['filter'], 'selected' => 'selected');
		}
		
		$form = new CForm(array('name'=>'editForm', 'action'=>$this->request->CreateUrl("content/edit/{$id}")),array(
			'title'		=> new CFormElementText('title', array(
				'value'		=> $content['title'],
				)),
			'key'		=> new CFormElementText('key', array(
				'label'		=> 'Key:*',
				'value'		=> $content['key'],
				)),
			'data'		=> new CFormElementTextArea('data', array(
				'label'		=> 'Content:',
				'value'		=> $content['data'],
				)),
			'type'		=> new CFormElementSelect('type', array(
				'options'	=> $typeA,
				)),
			'filter'	=> new CFormElementSelect('filter', array(
				'options'	=> $filterA,
				)),
			$save		=> new CFormElementSubmit($save, array(
				'callback'		=> array($this, 'DoSave'),
				'callback-args'	=> array($content),
				)),
			'remove'	=> (($save=='save')?new CFormElementSubmit('Remove', array('callback' => array($this, 'DoRemove'),'callback-args' => array($content),'disabled'=>($enableRemove?null:'disabled'))):null),
			'id'		=> new CFormElementHidden('id', array(
				'value'		=> $content['id'],
				)),
			)
		);
		
		$form->SetValidation('title', array('not_empty'));
		$form->SetValidation('key', array('not_empty'));
		
		$status = $form->Check();
		if ($status === false)
		{
			$this->session->AddMessage('notice', 'The form could not be processed.');
			$this->RedirectToController("edit/{$id}");
		}
		else if ($status === true)
		{
			$this->RedirectToController("edit/{$id}");
		}
		
		$title = isset($id) ? 'Edit' : 'Create';
		
		$admin = $this->user->InGroup($this->config['CMUser-Groups']['admin']['acronym'])?true:false;
		$this->views->SetTitle("{$title} content: {$id}");
		$this->views->AddView('content/edit.tpl.php', array(
			'user'		=> $this->user,
			'content'	=> $content,
			'form'		=> $form,
			'admin'		=> $admin,
			),
		'primary'
		);
	}
	
	/**
	* Callback-method for the Edit-page, saves the content.
	*
	* @param CForm form with inserted data
	* @param CMContent content to be edited
	*/
	
	public function DoSave($form, $content)
	{
		$content['id']    	= $form['id']		['value'];
		$content['title'] 	= $form['title']	['value'];
		$content['key']   	= $form['key']		['value'];
		$content['data']  	= $form['data']		['value'];
		$content['type']  	= $form['type']		['value'];
		$content['filter']	= $form['filter']	['value'];
		return $content->Save();
	}
	
	/**
	* Callback-method for the Edit-page, "removes" the content.
	*
	* @param CForm form holding the id
	* @param CMContent content to be removed
	*/
	
	public function DoRemove($form, $content)
	{
		$content['id'] = $form['id']['value'];
		if ($content->Remove())
		{
			$this->session->AddMessage('success', 'Succesfully removed the content.');
			$this->RedirectToController('index');
		}
		else
		{
			$this->session->AddMessage('error', 'Unable to remove content.');
			$this->RedirectToController("edit/{$form[$id]}");
		}
	}
}