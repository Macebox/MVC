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
    '/\[url=(https?.*?)\](.*?)\[\/url\]/is',
	'/\[quote\](.*?)\[\/quote\]/is',
	"/\[code\](.*?)\[\/code\]/es",
	'/\[size=(.*?)\](.*?)\[\/size\]/is',
	'/\[color=(.*?)\](.*?)\[\/color\]/is',
    );   
  $replace = array(
    '<strong>$1</strong>',
    '<em>$1</em>',
    '<u>$1</u>',
    '<img src="$1" />',
    '<a href="$1">$1</a>',
    '<a href="$1">$2</a>',
	'<blockquote>$1</blockquote>',
	"code2html('$1')",
	'<span style="font-size: $1px;">$2</span>',
	'<span style="color: $1;">$2</span>',
    );     
  return preg_replace($search, $replace, $text);
}

function code2html($text)
{
	$search = array(
	'/\&amp\;lt\;\?php/',
	'/\&amp\;lt\;\?/',
	'/\?\&amp\;gt\;/',
	'/\&amp\;#36\;([a-zA-Z0-9]*)/',
	'/\/\/(.*\\n)/',
	'/\'(.*?)\'/',
	'/([0-9]+)/',
	'/\/\*/',
	'/\*\//',
	'/function/',
	'/(\\r\\n|\\r|\\n)/',
	'/\\t/',
	);
	$replace = array(
	'<span class="codeTag">&lt;?php</span>',
	'<span class="codeTag">&lt;?</span>',
	'<span class="codeTag">?&gt;</span>',
	'<span class="codeVariable">\$$1</span>',
	'<span class="codeComment">//$1</span>',
	'<span class="codeQuote">\'$1\'</span>',
	'<span class="codeNumber">$1</span>',
	'<span class="codeComment">/*',
	'*/</span>',
	'<span class="codeFunction">function</span>',
	'<br/>',
	'&nbsp; &nbsp;',
	);
	
	return "<div class='codeWindow'><dt>Kod:</dt><code class='code'>".preg_replace($search, $replace, $text)."</code></div>";
}