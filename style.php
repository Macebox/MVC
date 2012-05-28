<?php
/**
 * Compiles a less-file to a css-file using phpless.
 *
 */
// __DIRNAME__only available from PHP 5.3 and forward
define('MVC_INSTALL_PATH', dirname(__FILE__));
define('MVC_SITE_PATH', MVC_INSTALL_PATH . '/site');
define('MVC_CORE_PATH', MVC_INSTALL_PATH . '/src/core');

require(MVC_CORE_PATH . '/bootstrap.php');

$mvc = CNocturnal::Instance();

if(!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
 
// Include the lessphp-compiler
include MVC_CORE_PATH."/lessphp/lessc.inc.php";

// Use gzip if available
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
  ob_start("ob_gzhandler"); 
else
  ob_start(); 


/**
* Compile less to css. Creates a cache-file of the last compiled less-file.
*
* This code is originally from the manual of lessphp.
*
* @param @less_fname string the filename of the less-file.
* @param @css_fname string the filename of the css-file.
* @param @cache_ext string the file-extension of the cache-file, added to the less filename. Default is '.cache'.
* @returns boolean true if the css-file was changed, else returns false.
*/
function auto_compile_less($less_fname, $css_fname, $cache_ext='.cache') {
  $cache_fname = $less_fname.$cache_ext;
  if (file_exists($cache_fname)) {
    $cache = unserialize(file_get_contents($cache_fname));
  } else {
    $cache = $less_fname;
  }

  $new_cache = lessc::cexecute($cache);
  if (!is_array($cache) || $new_cache['updated'] > $cache['updated']) {
    file_put_contents($cache_fname, serialize($new_cache));
    file_put_contents($css_fname, $new_cache['compiled']);
    return true;
  }
  return false;
}

$error = null;
$cssFiles = array();

$time = mktime(0,0,0,21,5,1980);

		
$themePath    = MVC_INSTALL_PATH . '/' . $mvc->config['theme']['path'];
		
if(isset($mvc->config['theme']['parent']))
{
	$parentPath = MVC_INSTALL_PATH . '/' . $mvc->config['theme']['parent'];
}

if (isset($parentPath) && $parentPath!=$themePath)
{
	// Compile and output the resulting css-file, use caching whenever suitable.
	$less = "{$parentPath}/style.less.css";
	$cssFiles[$parentPath]  = "{$parentPath}/style.css";
	$cache_extension = '.cache';

	$changed = auto_compile_less($less, $cssFiles[$parentPath], $cache_extension);

	if(!$changed && isset($_SERVER['If-Modified-Since']) && strtotime($_SERVER['If-Modified-Since']) >= $time)
	{
		$error = true;
	}
	else
	{
	}
}

if (isset($themePath))
{
	// Compile and output the resulting css-file, use caching whenever suitable.
	$less = "{$themePath}/style.less.css";
	$cssFiles[$themePath]  = "{$themePath}/style.css";
	$cache_extension = '.cache';

	$changed = auto_compile_less($less, $cssFiles[$themePath], $cache_extension);

	if(!$changed && isset($_SERVER['If-Modified-Since']) && strtotime($_SERVER['If-Modified-Since']) >= $time)
	{
		$error = true;
	}
	else
	{
	}
}

if ($error)
{
	header("HTTP/1.0 304 Not Modified");
}
else
{
	header('Content-type: text/css'); 
	header('Last-Modified: ' . gmdate("D, d M Y H:i:s",$time) . " GMT");
	foreach ($cssFiles as $name => $css)
	{
		readfile($css);
	}
}