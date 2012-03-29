<?php
/**
* Helpers for the template file.
*/
$mvc->data['header'] = '<h1>Header: MVC</h1>';
$mvc->data['footer'] = '<p>Footer: &copy; MVC by Marcus Olsson</h1>';


/**
* Print debuginformation from the framework.
*/
function get_debug() {
  $mvc = CMVC::Instance();
  $html = "<h2>Debuginformation</h2><hr><p>The content of the config array:</p><pre>" . htmlentities(print_r($mvc->config, true)) . "</pre>";
  $html .= "<hr><p>The content of the data array:</p><pre>" . htmlentities(print_r($mvc->data, true)) . "</pre>";
  $html .= "<hr><p>The content of the request array:</p><pre>" . htmlentities(print_r($mvc->request, true)) . "</pre>";
  return $html;
}

/**
* Create a url by prepending the base_url.
*/
function base_url($url)
{
	return CMVC::Instance()->request->base_url . trim($url, '/');
}

/**
* Return the current url.
*/
function current_url()
{
	return CMVC::Instance()->request->current_url;
}

?>