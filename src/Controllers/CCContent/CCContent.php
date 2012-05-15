<?php

class CCContent extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		$content = new CMContent();
		$this->views->SetTitle('Content Controller');
		$this->views->AddView('Content/index.tpl.php', array(
			'contents'	=> $content->ListAll(null),
			),
		'primary'
		);
	}
	
	public function Create()
	{
		$this->Edit();
	}
	
	public function Edit($id=null)
	{
		$content	= new CMContent($id);
		$save = isset($content['id'])? 'save' : 'create';
		$enableRemove = (!isset($content['deleted']));
		$form = new CForm(array('name'=>'editForm', 'action'=>$this->request->CreateUrl("content/edit/{$id}")),array(
			'title'		=> new CFormElementText('title', array(
				'value'		=> $content['title'],
				)),
			'key'		=> new CFormElementText('key', array(
				'value'		=> $content['key'],
				)),
			'data'		=> new CFormElementTextArea('data', array(
				'label'		=> 'Content:',
				'value'		=> $content['data'],
				)),
			'type'		=> new CFormElementText('type', array(
				'value'		=> $content['type'],
				)),
			'filter'	=> new CFormElementText('filter', array(
				'value'		=> $content['filter'],
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
		$this->views->SetTitle("{$title} content: {$id}");
		$this->views->AddView('Content/edit.tpl.php', array(
			'user'		=> $this->user,
			'content'	=> $content,
			'form'		=> $form,
			),
		'primary'
		);
	}
	
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