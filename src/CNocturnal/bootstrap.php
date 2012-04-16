<?php

//Bootstrapper

/*
	Autoload function
*/
function autoload($aClassName)
{
	$classFile = "/src/{$aClassName}/{$aClassName}.php";
	$file1 = MVC_SITE_PATH . $classFile;
	$file2 = MVC_INSTALL_PATH . "/models/{$aClassName}/{$aClassName}.php";
	$file3 = MVC_INSTALL_PATH . $classFile;
	if (is_file($file1))
	{
		require_once($file1);
	}
	else if (is_file($file2))
	{
		require_once($file2);
	}
	else if (is_file($file3))
	{
		require_once($file3);
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