<?php

//Bootstrapper

/*
	Autoload function
*/
function autoload($aClassName)
{
	$classFile = "/src/{$aClassName}/{$aClassName}.php";
	$file1 = MVC_INSTALL_PATH . $classFile;
	$file2 = MVC_SITE_PATH . $classFile;
	if (is_file($file1))
	{
		require_once($file1);
	}
	else if (is_file($file2))
	{
		require_once($file2);
	}
}
spl_autoload_register('autoload');

?>