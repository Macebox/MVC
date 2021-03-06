<?php

/**
* Handles the requests to the server.
*
* @package NocturnalCore
*/

class CRequest
{
	public function __construct()
	{
		$mvc = CNocturnal::Instance();
		if ($mvc->config['url_type']==1)
		{
			$this->cleanUrl = true;
		}
		else if ($mvc->config['url_type']==2)
		{
			$this->queryStringUrl = true;
		}
	}
	
	/**
	* Init method for the module.
	*
	* @param String base url
	* @param Array routing table
	*/
	
	public function Init($baseUrl = null, $routing=null)
	{
		$requestUri = $_SERVER['REQUEST_URI'];
		$scriptName = $_SERVER['SCRIPT_NAME'];
	   
		// Compare REQUEST_URI and SCRIPT_NAME as long they match, leave the rest as current request.
		if (strpos($requestUri, ".php"))
		{
			$i=0;
			$len = min(strlen($requestUri), strlen($scriptName));
			while($i<$len && $requestUri[$i] == $scriptName[$i])
			{
				$i++;
			}
			$request = trim(substr($requestUri, $i), '/');
		}
		else
		{
			$request = substr($_SERVER['REQUEST_URI'], strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'))+1);
		}
		
		if (empty($request))
		{
			$request='index';
		}
		
		// Remove the ?-part from the query when analysing controller/metod/arg1/arg2
		$queryPos = strpos($request, '?');
		if($queryPos !== false)
		{
			$request = substr($request, 0, $queryPos);
		}
		
		// Check if request is empty and querystring link is set
		if(empty($request) && isset($_GET['q']))
		{
			$request = trim($_GET['q']);
		}
		
		if (is_array($routing) && isset($routing[$request]) && $routing[$request]['enabled'])
		{
			$this->routing = $request;
			$request = $routing[$request]['url'];
		}
		
		$splits = explode('/', $request);
	   
		// Set controller, method and arguments
		$controller =  !empty($splits[0]) ? $splits[0] : 'index';
		$method       =  !empty($splits[1]) ? $splits[1] : 'index';
		$arguments = $splits;
		unset($arguments[0], $arguments[1]); // remove controller & method part from argument list
	   
		// Prepare to create current_url and base_url
		$currentUrl = $this->GetCurrentUrl();
		$parts        = parse_url($currentUrl);
		$baseUrl       = !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');
	   
		// Store it
		$this->base_url		= rtrim($baseUrl, '/') . '/';
		$this->current_url  = $currentUrl;
		$this->request_uri  = $requestUri;
		$this->script_name  = $scriptName;
		$this->routing		= isset($this->routing)?$this->routing:null;
		$this->request      = $request;
		$this->splits		= $splits;
		$this->controller	= $controller;
		$this->method		= $method;
		$this->arguments    = $arguments;
	}
	
	/**
	* Returns the current url.
	*
	*
	*/
	
	public function GetCurrentUrl()
	{
		$url = "http";
		$url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
		$url .= "://";
		$serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
		(($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
		$url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
		return $url;
	}
	
	/**
	* Returns the base-url to the site.
	*
	*
	*/
	
	public function GetBaseUrl()
	{
		return 'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'], 'index.php'));
	}
	
	/**
	* Creates(returns) an url based on the input-url.
	*
	*
	*/
	
	public function CreateUrl($url=null, $type=null)
	{
		$prepend = $this->base_url;
		if ((isset($this->cleanUrl) && $this->cleanUrl && $type==null) || $type==1)
		{
			;
		}
		else if ((isset($this->queryStringUrl) && $this->queryStringUrl && $type==null) || $type==2)
		{
			$prepend .= 'index.php?q=';
		}
		else
		{
			$prepend .= 'index.php/';
		}
		return $prepend . rtrim($url, '/');
	}
}