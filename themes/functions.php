<?php
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
			$html .= "<h3>MVC:</h3><p>The content of the config array:</p><pre>" . htmlentities(print_r($mvc->config, true)) . "</pre>";
			$html .= "<h3>The content of the data array:</h3><pre>" . htmlentities(print_r($mvc->data, true)) . "</pre>";
			$html .= "<h3>The content of the request array:</h3><pre>" . htmlentities(print_r($mvc->request, true)) . "</pre>";
		}
		if (isset($mvc->config['debug']['db-num-queries']) && $mvc->config['debug']['db-num-queries'])
		{
			$html .= "<h3>Database:</h3><p>Database made " . $mvc->database->GetNumQueries() . " queries.</p>";
			
			if (isset($mvc->config['debug']['db-queries']) && $mvc->config['debug']['db-queries'])
			{
				$html .= '<p>Queries:<br />';
				foreach($mvc->database->GetQueries() as $key => $val)
				{
					$html .= $val."<br />";
				}
				$html .= "</p>";
			}
		}
		
		$html .= $mvc->GetExceptionMessages();
	}
	
	return $html;
}

function render_views($region='default')
{
	return CNocturnal::Instance()->views->Render($region);
}

function region_has_content($region='default' /*...*/) {
  return CNocturnal::Instance()->views->RegionHasView(func_get_args());
}

/**
* Create a url by prepending the base_url.
*/
function base_url($url=null)
{
	return CNocturnal::Instance()->request->base_url . trim($url, '/');
}

function theme_url($url=null)
{
	return CNocturnal::Instance()->data['themeUrl'] . $url;
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

function create_url($url, $type=null)
{
	$mvc = CNocturnal::Instance();
	return $mvc->request->CreateUrl($url, $type);
}

/**
 * Create HTML for a navbar.
 */
function getHTMLForNavigation($id)
{
	$mvc = CNocturnal::Instance();
	$p = $mvc->request->controller;
	$m = $mvc->request->method;
	$a = $mvc->request->arguments;
	foreach($mvc->config['navbar'] as $key => $item)
	{
		$selected = ($p == $item['url'] || $mvc->request->routing==$item['url']) ? " class='selected'" : null;
		@$html .= "<a href='{$mvc->request->CreateUrl($item['url'])}'{$selected}>{$item['text']}</a>\n";
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
			$html .= "<div class='$class'>{$class}:<br />{$val['message']}</div>\n";
		}
	}
	return $html;
}

/*
* Login menu
*
**/


function get_gravatar($size=null)
{
	$user = CNocturnal::Instance()->user->GetUserProfile();
	return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '.jpg?' . ($size ? "s=$size" : null);
}

function login_menu()
{
	$mvc = CNocturnal::Instance();
	if($mvc->user->IsAuthenticated())
	{
		$items = "<img src='".get_gravatar(15)."'>";
		$items .= "<a href='" . create_url('user/profile') . "'>" . $mvc->user->GetAcronym() . "</a> ";
		if($mvc->user->InGroup($mvc->config['CMUser-Groups']['admin']['acronym']))
		{
			$items .= "<a href='" . create_url('acp') . "'>acp</a> ";
		}
		$items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
	}
	else
	{
		$items = "<a href='" . create_url('user/login') . "'>login</a> ";
	}
	return "<nav class=\"right\">$items</nav>";
}

function esc($str) {
  return htmlEnt($str);
}

function filter_data($data, $filter) {
  return CMContent::Filter($data, $filter);
}