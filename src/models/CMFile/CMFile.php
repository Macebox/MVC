<?php

/**
* File model
*
* @package NocturnalCore
*/

class CMFile extends CObject implements ArrayAccess,IModule
{
	public function __construct($id=null)
	{
		parent::__construct();
		if ($id!=null)
		{
			$this->LoadById($id);
		}
		else
		{
			$this['id']			= 0;
			$this['filename']	= "";
			$this['content']	= "";
			$this['created']	= "";
			$this['updated']	= "";
			$this['deleted']	= "";
			$this['idMap']		= 0;
			$this['physical']	= false;
		}
	}
	
	/**
	* Manage method.
	*
	* @param String action to be run
	*/
	
	public function Manage($action=null)
	{
		switch($action)
		{
			case 'install':
			{
				if ($this->Init())
				{
					return array('success', 'Successfully created the database tables (or left them untouched if they already existed).');
				}
				else
				{
					return array('success', 'Unable to create the database-tables.');
				}
			}break;
			default:
				throw new Exception('Unsupported action for this module.');
				break;
		}
	}
	
	/**
	* Init method which creates database tables.
	*
	*
	*/
	
	private function Init()
	{
		$this->database->RunQuery(
"CREATE TABLE IF NOT EXISTS `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `deleted` datetime,
  `name` varchar(30) NOT NULL,
  `physical` tinyint(1) NOT NULL,
  `idMap` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idMap` (`idMap`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;", true);

	return $this->database->RunQuery(
"ALTER TABLE `file`
  ADD CONSTRAINT `idMap` FOREIGN KEY (`idMap`) REFERENCES `map` (`id`);", true);
	}
	
	public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
	public function offsetExists($offset) { return isset($this->data[$offset]); }
	public function offsetUnset($offset) { unset($this->data[$offset]); }
	public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }
	
	public function ReadAll($mapId)
	{
		return $this->database->Get('file', array(), array('idMap' => $mapId, 'deleted' => null));
	}
	
	public function UploadFile($filename, $idMap)
	{
		$name		= basename( $_FILES[$filename]['name']);
		$fileExists = $this->database->Get('file', array(), array('idMap' => $idMap, 'name' => $name));
		if (empty($fileExists))
		{
			$tmpFile	= $_FILES[$filename]['tmp_name'];
			$dateTime	= date('o-m-d H:i:s');
			$targetPath = $this->GetPhysicalPath($name, $idMap);
			
			$insertArray = array(
				'content'	=> "physical",
				'created'	=> $dateTime,
				'updated'	=> $dateTime,
				'name'		=> $name,
				'physical'	=> 1,
				'idMap'		=> $idMap,
			);
			
			if ($this->database->Insert('file', $insertArray))
			{
				if (move_uploaded_file($tmpFile, $targetPath))
				{
					return true;
				}
			}
		}
		else
		{
			$this->session->AddMessage('error', 'File already exists.');
			return false;
		}
		
		return false;
	}
	
	public function CreateFile($name, $content, $idMap)
	{
		$filename = preg_replace("/\//", "", $name);
		$fileExists = $this->database->Get('file', array(), array('idMap' => $idMap, 'name' => $filename));
		if (empty($fileExists))
		{
			$dateTime	= date('o-m-d H:i:s');
			
			$insertArray = array(
				'content'	=> $content,
				'created'	=> $dateTime,
				'updated'	=> $dateTime,
				'name'		=> $filename,
				'physical'	=> 0,
				'idMap'		=> $idMap,
			);
			
			return $this->database->Insert('file', $insertArray);
		}
		else
		{
			$this->session->AddMessage('error', 'File already exists.');
			return false;
		}
	}
	
	public function ReadContent()
	{
		$ret = null;
		if ($this['id']!=0 && $this['deleted']==null)
		{
			if ($this['physical'])
			{
				$path = $this->GetPhysicalPath($this['filename'], $this['idMap']);
				$this['type'] = $this->GetFileType($this['filename']);
				if ($this['type']=='text')
				{
					$file = fopen($path,'r');
					$ret = fread($file, filesize($path));
					fclose($file);
				}
				else if ($this['type']=='image')
				{
					$ret = $this->GetRelativePath($this['filename'], $this['idMap']);
				}
			}
			else
			{
				$this['type']='text';
				$ret = $this['content'];
			}
		}
		
		return $ret;
	}
	
	public function GetPhysicalPath($name, $idMap, $bRemoved=true)
	{
		return MVC_INSTALL_PATH.'/'.$this->GetRelativePath($name, $idMap, $bRemoved);
	}
	
	public function GetRelativePath($name, $idMap, $bRemoved=true)
	{
		$file = pathinfo($name);
		$removed = $this->database->Get('file', array('id', 'deleted'), array('name' => $name, 'idMap' => $idMap));
		$removed = (!empty($removed)&&$removed[0]['deleted']!=null&&$bRemoved)?$removed[0]['id'].'/':null;
		return 'site/data/'.md5($removed.$idMap.'/'.$name).'.'.$file['extension'];
	}
	
	public function LoadById($id)
	{
		$file = $this->database->Get('file', array(), array('id' => $id));
		if (!empty($file))
		{
			$file = $file[0];
			$this['id']			= $file['id'];
			$this['filename']	= $file['name'];
			$this['content']	= $file['content'];
			$this['created']	= $file['created'];
			$this['updated']	= $file['updated'];
			$this['deleted']	= $file['deleted'];
			$this['idMap']		= $file['idMap'];
			$this['physical']	= $file['physical'];
		}
		else
		{
			$this['id']			= 0;
			$this['filename']	= "";
			$this['content']	= "";
			$this['created']	= "";
			$this['updated']	= "";
			$this['deleted']	= "";
			$this['idMap']		= 0;
			$this['physical']	= false;
		}
	}
	
	public function EditFile($name, $content)
	{
		$ret = true;
		if ($this['id']!=0)
		{
			$filename = preg_replace("/\//", "", $name);
			$fileExists = $this->database->Get('file', array(), array('idMap' => $this['idMap'], 'name' => $filename));
			if (empty($fileExists) || (!empty($fileExists) && $fileExists[0]['id']==$this['id']))
			{
				$updateArray = array(
					'name'		=> $filename,
					'content'	=> $content,
					'deleted'	=> null,
				);
				if ($this['physical'])
				{
					unset($updateArray['content']);
					
					$path = $this->GetPhysicalPath($this['filename'], $this['idMap']);
					$file = fopen($path, 'w');
					$ret = fwrite($file, $content)!=FALSE;
					fclose($file);
				}
				
				if ($ret)
				{
					$this->database->Update('file', $updateArray, array('id'=>$this['id']));
				}
			}
			else
			{
				$this->session->AddMessage('error', 'A file with that name already exists.');
				$ret = false;
			}
		}
		else
		{
			$ret = false;
		}
		
		return $ret;
	}
	
	public function RemoveFile()
	{
		$fileExists = $this->database->Get('file', array(), array('idMap' => $this['idMap'], 'id' => $this['id']));
		if (!empty($fileExists) && $fileExists[0]['deleted']==null)
		{
			$this->database->Update('file', array('deleted' => date('o-m-d H:i:s')), array('idMap' => $this['idMap'], 'id' => $this['id']));
			if ($fileExists[0]['physical'])
			{
				$oldFile = $this->GetPhysicalPath($this['filename'], $this['idMap'], false);
				$newFile = $this->GetPhysicalPath($this['filename'], $this['idMap']);
				
				return rename($oldFile, $newFile);
			}
			return true;
		}
		return false;
	}
	
	public function GetFileType($filename)
	{
		$ret = 'text';
		$file = pathinfo($filename);
		switch($file['extension'])
		{
			case 'bmp':
			case 'gif':
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'jpe':
			case 'jfif':
			case 'tif':
			case 'tiff':
				{
					$ret='image';
				} break;
			default:
				{
					$ret = 'text';
				} break;
		}
		return $ret;
	}
}