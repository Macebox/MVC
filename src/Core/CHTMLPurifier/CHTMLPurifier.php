<?php

/**
* Wrapper class for HTMLPurifier
*
* @package NocturnalCMF
*/

class CHTMLPurifier
{
	public static $instance = null;
	
	/**
	* Returns a singleton instance of HTMLPurify.
	*
	*
	*/
	
	public static function Purify()
	{
		if(!self::$instance)
		{
			require_once(__DIR__.'/htmlpurifier-4.4.0-standalone/HTMLPurifier.standalone.php');
			$config = HTMLPurifier_Config::createDefault();
			$config->set('Cache.DefinitionImpl', null);
			self::$instance = new HTMLPurifier($config);
		}
		return self::$instance->purify($text);
	}
}