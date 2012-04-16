<?php
/**
* Helpers for the template file.
*/
$mvc->data['header'] = '<h1>MVC</h1>';
$mvc->data['footer'] = '<p>&copy; Nocturnal by Marcus Olsson</p>';


/**
* Print debuginformation from the framework.
*/
function get_debug()
{
	$mvc = CNocturnal::Instance();
	$html = "";
	if (isset($mvc->config['debugEnabled']) && $mvc->config['debugEnabled'])
	{
		$html = "<h2>Debug:</h2>";
		if (isset($mvc->config['debug']['mvc']) && $mvc->config['debug']['mvc'])
		{
			$html .= "<h3>MVC:</h3><hr><p>The content of the config array:</p><pre>" . htmlentities(print_r($mvc->config, true)) . "</pre>";
			$html .= "<hr><p>The content of the data array:</p><pre>" . htmlentities(print_r($mvc->data, true)) . "</pre>";
			$html .= "<hr><p>The content of the request array:</p><pre>" . htmlentities(print_r($mvc->request, true)) . "</pre>";
		}
		if (isset($mvc->config['debug']['db-num-queries']) && $mvc->config['debug']['db-num-queries'])
		{
			$html .= "<h3>Database:</h3><hr><p>Database made " . $mvc->database->GetNumQueries() . " queries.</p><p>Queries:<br />";
			foreach($mvc->database->GetQueries() as $key => $val)
			{
				$html .= $val."<br />";
			}
			$html .= "</p>";
		}
		
		$html .= $mvc->GetExceptionMessages();
	}
	
	return $html;
}

function render_views()
{
	return CNocturnal::Instance()->views->Render();
}

/**
* Create a url by prepending the base_url.
*/
function base_url($url)
{
	return CNocturnal::Instance()->request->base_url . trim($url, '/');
}

/**
* Return the current url.
*/
function current_url()
{
	return CNocturnal::Instance()->request->current_url;
}

/**
*	Create an url
*/

function create_url($url)
{
	$mvc = CNocturnal::Instance();
	return $mvc->request->CreateUrl($url);
}

/**
 * Create HTML for a navbar.
 */
function getHTMLForNavigation($id)
{
	$mvc = CNocturnal::Instance();
	$p = $mvc->request->controller;
	foreach($mvc->config['navbar'] as $key => $item)
	{
		$selected = ($p == $item['url']) ? " class='selected'" : null;
		@$html .= "<a href='{$mvc->request->CreateUrl($item['url'])}'{$selected}>{$item['text']}</a>\n";
	}
	return "<nav id='$id'>\n{$html}</nav>\n";
}


/**
 * Create HTML for navigation links among kmoms.
 */
function getHTMLForKmomNavlinks($id)
{
	$mvc = CNocturnal::Instance();
	foreach($mvc->config['navkmom'] as $key => $item)
	{
		@$html .= empty($item['url']) ? $item['text'] : "<a href='{$mvc->request->CreateUrl($item['url'])}'{$selected}>{$item['text']}</a>\n" ;
	}
	return "<nav id='$id'>\n{$html}</nav>\n";
}

/*
*	
*
*
*/

function get_messages_from_session()
{
	$messages = CNocturnal::Instance()->session->GetMessages();
	$html = null;
	if(!empty($messages))
	{
		foreach($messages as $val)
		{
			$valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
			$class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
			$html .= "<div class='$class'>{$val['message']}</div>\n";
		}
	}
	return $html;
}