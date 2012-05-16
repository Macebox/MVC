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
		MVC_SITE_PATH	. "/Controllers/{$classFile}",
		MVC_SITE_PATH	. "/Models/{$classFile}",
		$srcPath		. "/Controllers/{$classFile}",
		$srcPath		. "/Models/{$classFile}",
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

function makeClickable($text)
{
	return preg_replace_callback(
		'#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
		create_function(
			'$matches',
			'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'
			),
		$text
	);
}

function bbcode2html($text) {
  $search = array(
    '/\[b\](.*?)\[\/b\]/is',
    '/\[i\](.*?)\[\/i\]/is',
    '/\[u\](.*?)\[\/u\]/is',
    '/\[img\](https?.*?)\[\/img\]/is',
    '/\[url\](https?.*?)\[\/url\]/is',
    '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
    );   
  $replace = array(
    '<strong>$1</strong>',
    '<em>$1</em>',
    '<u>$1</u>',
    '<img src="$1" />',
    '<a href="$1">$1</a>',
    '<a href="$1">$2</a>'
    );     
  return preg_replace($search, $replace, $text);
}