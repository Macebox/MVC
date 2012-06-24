<?php

/**
* File controller
*
* @package NocturnalCore
*/

class CCFile extends CObject implements IController
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		$this->View('map',1);
	}
	
	public function View($type, $id=1)
	{
		$this->views->SetTitle('Files');
		$admin = $this->user->InGroup('admin')||$this->user->InGroup('filemanager');
		if ($type=='file')
		{
			$file = new CMFile($id);
			$this->views->AddView('file/file.tpl.php',array(
				'admin'		=> $admin,
				'file'		=> $file,
				'content'	=> $file->ReadContent(),
				),'primary'
			);
		}
		else
		{
			$map = new CMFolder();
			$file = new CMFile();
			$this->views->AddView('file/map.tpl.php',array(
				'admin'		=> $admin,
				'map'		=> $id,
				'maps'		=> $map->ReadAll($id),
				'files'		=> $file->ReadAll($id),
				'root'		=> $map->GetRoot($id),
				),'primary'
			);
		}
	}
	
	public function Upload($mapId=1)
	{
		$admin = $this->user->InGroup('admin')||$this->user->InGroup('filemanager');
		
		$form = new CForm(array('enctype' => "multipart/form-data"));
		$form->AddElement(new CFormElementFile('uploadfile', array('label' => 'File:')));
		$form->AddElement(new CFormElementSubmit('Upload', array('callback' => array($this, 'DoUpload'))));
		$form->AddElement(new CFormElementHidden('idmap', array('value' => $mapId)));
		
		$form->SetValidation('uploadfile', array('not_empty'));
		
		if ($admin)
		{
			$form->Check();
		}
		
		$this->views->SetTitle('Upload file');
		$this->views->AddView('file/upload.tpl.php',array(
			'admin'		=> $admin,
			'form'		=> $form->GetHTML(),
			),'primary'
		);
	}
	
	public function DoUpload($form)
	{
		$fileName	= 'uploadfile';
		$idMap		= $form['idmap']['value'];
		
		$model = new CMFile();
		if ($model->UploadFile($fileName, $idMap))
		{
			$this->session->AddMessage('success','Successfully uploaded the file.');
			$this->RedirectToController('view/map/'.$idMap);
		}
		else
		{
			$this->session->AddMessage('error','Unable to upload the file.');
		}
	}
	
	public function Create($type='file', $mapid=1)
	{
		$form = new CForm();
		if ($type=='file')
		{
			$form->AddElement(new CFormElementText('filename'));
			$form->AddElement(new CFormElementTextArea('content'));
			$form->AddElement(new CFormElementSubmit('Create', array(
				'callback'	=> array($this, 'DoCreateFile'),
				)
			));
			$form->SetValidation('filename', array('not_empty'));
		}
		else if ($type=='map')
		{
			$form->AddElement(new CFormElementText('mapname'));
			$form->AddElement(new CFormElementSubmit('Create', array(
				'callback'	=> array($this, 'DoCreateMap'),
				)
			));
			$form->SetValidation('mapname', array('not_empty'));
		}
		
		$form->AddElement(new CFormElementHidden('idMap', array(
				'value'		=> $mapid,
				)
			));
		
		$admin = $this->user->InGroup('admin')||$this->user->InGroup('filemanager');
		
		if ($admin)
		{
			$form->Check();
		}
		
		$this->views->SetTitle('Create new '.$type);
		$this->views->AddView('file/create.tpl.php', array(
			'form'	=> $form->GetHTML(),
			'type'	=> $type,
			'admin'	=> $admin,
			),
			'primary'
		);
	}
	
	public function DoCreateFile($form)
	{
		$filename	= $form['filename']	['value'];
		$content	= $form['content']	['value'];
		$idMap		= $form['idMap']	['value'];
		
		$model = new CMFile();
		if ($model->CreateFile($filename, $content, $idMap))
		{
			$this->session->AddMessage('success', 'Successfully created the file.');
			$this->RedirectToController('view/map/'.$idMap);
		}
		else
		{
			$this->session->AddMessage('error','Unable to create the file.');
		}
	}
	
	public function DoCreateMap($form)
	{
		$mapname	= $form['mapname']	['value'];
		$idMap		= $form['idMap']	['value'];
		
		$model = new CMFolder();
		if ($model->CreateFolder($mapname, $idMap))
		{
			$this->session->AddMessage('success', 'Successfully created the map.');
			$this->RedirectToController('view/map/'.$idMap);
		}
		else
		{
			$this->session->AddMessage('error','Unable to create the map.');
		}
	}
	
	public function Remove($type, $id)
	{
		$mapid = null;
		if ($id!=null && ($this->user->InGroup('admin')||$this->user->InGroup('filemanager')))
		{
			if ($type=='file')
			{
				$model = new CMFile($id);
				$mapid = $model['idMap'];
				if ($model->RemoveFile())
				{
					$this->session->AddMessage('success', 'Removed the file.');
				}
				else
				{
					$this->session->AddMessage('error', 'Unable to remove the file.');
				}
			}
			else if ($type=='map')
			{
				$model = new CMFolder();
				$mapid = $model->GetRoot($id);
				if ($model->RemoveFolder($id))
				{
					$this->session->AddMessage('success', 'Removed the map.');
				}
				else
				{
					$this->session->AddMessage('error', 'Unable to remove the map.');
				}
			}
		}
		$this->RedirectToController('view/map/'.$mapid);
	}
	
	public function Edit($type, $id)
	{
		$form = new CForm();
		if ($type=='file')
		{
			$file = new CMFile($id);
			$form->AddElement(new CFormElementText('filename', array('value'=>$file['filename'])));
			$form->AddElement(new CFormElementTextArea('content',array('value'=>$file->ReadContent())));
			$form->AddElement(new CFormElementSubmit('Edit', array(
				'callback'	=> array($this, 'DoEditFile'),
				)
			));
			$form->AddElement(new CFormElementHidden('id', array('value'=>$file['id'])));
			$form->SetValidation('filename', array('not_empty'));
		}
		else if ($type=='map')
		{
			$model = new CMFolder();
			$folder = $model->GetFolder($id);
			$form->AddElement(new CFormElementText('mapname',array('value'=>$folder['path'])));
			$form->AddElement(new CFormElementSubmit('Edit', array(
				'callback'	=> array($this, 'DoEditMap'),
				)
			));
			$form->AddElement(new CFormElementHidden('id', array('value'=>$folder['id'])));
			$form->SetValidation('mapname', array('not_empty'));
		}
		
		$admin = $this->user->InGroup('admin')||$this->user->InGroup('filemanager');
		
		if ($admin)
		{
			$form->Check();
		}
		
		$this->views->SetTitle('Edit '.$type);
		$this->views->AddView('file/edit.tpl.php', array(
			'form'	=> $form->GetHTML(),
			'type'	=> $type,
			'admin'	=> $admin,
			),
			'primary'
		);
	}
	
	public function DoEditFile($form)
	{
		$filename	= $form['filename']	['value'];
		$content	= $form['content']	['value'];
		$id			= $form['id']		['value'];
		
		$model = new CMFile($id);
		if ($model->EditFile($filename, $content))
		{
			$this->session->AddMessage('success', 'Successfully edited the file.');
			$this->RedirectToController('view/file/'.$id);
		}
		else
		{
			$this->session->AddMessage('error','Unable to edit the file.');
		}
	}
	
	public function DoEditMap($form)
	{
		$mapname	= $form['mapname']	['value'];
		$id			= $form['id']		['value'];
		
		$model = new CMFolder();
		$folder = $model->GetFolder($id);
		if ($model->EditFolder($id, $mapname))
		{
			$this->session->AddMessage('success', 'Successfully edited the map.');
			$this->RedirectToController('view/map/'.$folder['idRoot']);
		}
		else
		{
			$this->session->AddMessage('error','Unable to edit the map.');
		}
	}
}