<?php
/**
* Holding a instance of Cmvcdia to enable use of $this in subclasses.
*
* @package NocturnalCore
*/
class CObject
{
	/**
	 * Constructor
	 */
	protected function __construct($mvc = null)
	{
		$mvc = !$mvc?CNocturnal::Instance():$mvc;
		
		$this->config   = &$mvc->config;
		$this->request  = &$mvc->request;
		$this->data     = &$mvc->data;
		$this->database = &$mvc->database;
		$this->views	= &$mvc->views;
		$this->session	= &$mvc->session;
		$this->user		= &$mvc->user;
	}
	
	/**
	* Redirects to url/controller
	*
	* @param String url or controller
	* @param String method
	*/
	
	protected function RedirectTo($urlOrController=null, $method=null)
	{
		$mvc = CNocturnal::Instance();
		if(isset($mvc->config['debug']['db-num-queries']) && $mvc->config['debug']['db-num-queries'] && isset($mvc->db))
		{
			$this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
		}
		if(isset($mvc->config['debug']['db-queries']) && $mvc->config['debug']['db-queries'] && isset($mvc->db))
		{
			$this->session->SetFlash('database_queries', $this->db->GetQueries());
		}
		$this->session->SetFlash('pageTimeStart', $this->session->__get('pageTimeStart'));
		$this->session->StoreInSession();
		header('Location: ' . $this->request->CreateUrl($urlOrController.'/'.$method));
		exit;
	}


	/**
	* Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
	*
	* @param string method name the method, default is index method.
	*/
	protected function RedirectToController($method=null)
	{
		$this->RedirectTo($this->request->controller, $method);
	}


	/**
	* Redirect to a controller and method. Uses RedirectTo().
	*
	* @param string controller name the controller or null for current controller.
	* @param string method name the method, default is current method.
	*/
	protected function RedirectToControllerMethod($controller=null, $method=null)
	{
		$controller = is_null($controller) ? $this->request->controller : null;
		$method = is_null($method) ? $this->request->method : null;
		$this->RedirectTo($this->request->CreateUrl($controller, $method));
	}

}