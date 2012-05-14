<?php

//Bootstrapper

/*
	Autoload function
*/
function autoload($aClassName)
{
	$classFile = "{$aClassName}/{$aClassName}.php";
	$srcPath = MVC_INSTALL_PATH . "/src/";
	
	$files = array(
		MVC_SITE_PATH	. "/controllers/{$classFile}",
		MVC_SITE_PATH	. "/models/{$classFile}",
		$srcPath		. "/controllers/{$classFile}",
		$srcPath		. "/models/{$classFile}",
		MVC_CORE_PATH	. "/{$classFile}",
	);
	
	foreach($files as $file)
	{
		if (is_file($file))
		{
			require_once($file);
			break;
		}
	}
}
spl_autoload_register('autoload');

/*
	Exception handler
*/

function exception_handler($e)
{
	$mvc = CNocturnal::Instance();
	$mvc->AddExceptionMessage("MVC: Uncaught exception: <p>" . $e->getMessage() . "</p><pre>" . $e->getTraceAsString(), "</pre>");
}
set_exception_handler('exception_handler');

/*
	Html-ents function
*/

function htmlent($str, $flags = ENT_COMPAT)
{
	return htmlentities($str, $flags, CNocturnal::Instance()->config['character_encoding']);
}