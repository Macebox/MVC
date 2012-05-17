<?php

/*
	Site configuration file
*/

error_reporting(-1);
ini_set('display_errors', 1);

$mvc->config['installed'] = false;
$mvc->config['timezone'] = 'Europe/Stockholm';
$mvc->config['character_encoding'] = 'iso-8859-1';
$mvc->config['language'] = 'en';
$mvc->config['controllers'] = array(
	'index' => array(
		'enabled' => true,
		'class' => 'CCIndex',
		),
	'me' => array(
		'enabled' => true,
		'class' => 'CCMe',
		),
	'guestbook' => array(
		'enabled' => true,
		'class' => 'CCGuestbook',
		),
	'presentation' => array(
		'enabled' => true,
		'class' => 'CCPresentation',
		),
	'blog' => array(
		'enabled' => true,
		'class' => 'CCBlog',
		),
	'user' => array(
		'enabled' => true,
		'class' => 'CCUser',
		),
	'configure' => array(
		'enabled' => true,
		'class' => 'CCConfigure',
		),
	'content' => array(
		'enabled' => true,
		'class' => 'CCContent',
		),
	'page' => array(
		'enabled' => true,
		'class' => 'CCPage',
		),
	'theme' => array(
		'enabled' => true,
		'class' => 'CCTheme',
		),
	'modules' => array(
		'enabled' => true,
		'class' => 'CCModules',
		),
	'acp' => array(
		'enabled' => true,
		'class' => 'CCAdminControlPanel',
		),
	);

$mvc->config['routing'] = array(
	);

$mvc->config['theme'] = array(
	'path' => 'themes/grid',
	'parent' => 'themes/grid',
	'template_file' => 'index.tpl.php',
	'regions' => array(
		'flash',
		'featured-first',
		'featured-middle',
		'featured-last',
		'primary',
		'sidebar',
		'triptych-first',
		'triptych-middle',
		'triptych-last',
		'footer-column-one',
		'footer-column-two',
		'footer-column-three',
		'footer-column-four',
		'footer',
		),
	'data' => array(
		'header' => 'Nocturnal',
		'slogan' => 'H�r jobbas det..',
		'favicon' => '/img/trollface.jpg',
		'logo' => '/img/trollface.jpg',
		'logo_width' => 80,
		'logo_height' => 80,
		'footer' => '&copy;Nocturnal by Marcus Olsson',
		),
	'themes' => array(
		'core' => array(
			'name' => 'core',
			'file' => 'default.tpl.php',
			'path' => 'themes/core',
			'parent' => 'themes/core',
			),
		'metal' => array(
			'name' => 'metal',
			'file' => 'default.tpl.php',
			'path' => 'themes/metal',
			'parent' => 'themes/metal',
			),
		'grid' => array(
			'name' => 'grid',
			'file' => 'index.tpl.php',
			'path' => 'themes/grid',
			'parent' => 'themes/grid',
			),
		),
	);

$mvc->config['base_url'] = '';
$mvc->config['url_type'] = 1;
$mvc->config['database'] = array(
	'active' => true,
	'dsn' => '',
	'host' => '',
	'user' => '',
	'password' => '',
	'db' => '',
	'dbDriver' => '',
	'drivers' => array(
		'Mysqli',
		),
	);

$mvc->config['debugEnabled'] = true;
$mvc->config['debug'] = array(
	'mvc' => false,
	'db-num-queries' => true,
	'db-queries' => true,
	);

$mvc->config['navbar'] = array(
	'me' => array(
		'text' => 'Me',
		'url' => 'me',
		),
	'guestbook' => array(
		'text' => 'Guestbook',
		'url' => 'guestbook',
		),
	'blog' => array(
		'text' => 'Blogg',
		'url' => 'blog',
		),
	);

$mvc->config['session_key'] = 'Nocturnal';
$mvc->config['session_name'] = 'localhost';
$mvc->config['CMUser-Admin'] = array(
	'name' => 'root',
	'password' => 'root',
	'email' => 'test@example.com',
	'acronym' => 'root',
	);

$mvc->config['CMUser-Groups'] = array(
	'admin' => array(
		'acronym' => 'admin',
		'name' => 'The Administrator Group',
		),
	'user' => array(
		'acronym' => 'user',
		'name' => 'The User Group',
		),
	);

$mvc->config['create_new_users'] = true;
