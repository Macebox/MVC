<?php

/*
	Site configuration file
*/

error_reporting(-1);
ini_set('display_errors', 1);


/**
 * If the framework hasn't been installed yet, set to false.
 */
$mvc->config['installed'] = false;

/**
 * The current timezone of the system.
 */
$mvc->config['timezone'] = 'Europe/Stockholm';

/**
 * The character encoding the system uses.
 */
$mvc->config['character_encoding'] = 'iso-8859-1';

/**
 * The website language.
 */
$mvc->config['language'] = 'en';

/**
 * The available controllers:
 * 	enabled			= Is the controller available?
 * 	class			= The class connected to the controller.
 */
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


/**
 * The extra routes:
 * 	enabled			= Is the route available?
 * 	url				= The url it uses.
 */
$mvc->config['routing'] = array(
	);


/**
 * The theme configuration:
 * 	path			= The local path to the theme.
 * 	parent			= The local path to the parent theme.
 * 	template_file	= The template-file used.
 * 	regions			= Regions available to output in.
 * 	data			= Website data.
 * 	themes			= List of available themes.
 */
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
		'slogan' => 'Här jobbas det..',
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


/**
 * Set a base_url to use another than the default calculated.
 */
$mvc->config['base_url'] = '';

/**
 * The url-type that will be created with all the create_url()-functions.
 * 	0				= default: index.php/controller/method/arg1/arg2/arg3
 * 	1				= clean: controller/method/arg1/arg2/arg3
 * 	2				= q-string: index.php?q=controller/method/arg1/arg2/arg3
 */
$mvc->config['url_type'] = 2;

/**
 * Database settings
 * 	active		- Is the connection active?
 * 	dsn			- Unavaliable
 * 	host		- hostname
 * 	user		- username for db
 * 	password	- password for user
 * 	db			- database to access
 * 	dbDriver	- Driver for sql
 * 		- available drivers:
 * 			Mysqli
 */
$mvc->config['database'] = array(
	'active' => true,
	'dsn' => '',
	'host' => '',
	'user' => '',
	'password' => '',
	'db' => '',
	'dbDriver' => 'Mysqli',
	'drivers' => array(
		'Mysqli',
		),
	);


/**
 * Debug information messages:
 * 	false = don't show.
 * 	true = show.
 */
$mvc->config['debugEnabled'] = true;
$mvc->config['debug'] = array(
	'mvc' => false,
	'db-num-queries' => true,
	'db-queries' => true,
	);


/**
 * The content of the navbar.
 */
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
	'page' => array(
		'text' => 'Pages',
		'url' => 'page',
		),
	);


/**
 * The session-variables
 */
$mvc->config['session_key'] = 'Nocturnal';
$mvc->config['session_name'] = 'localhost';

/**
 * CMUser-Admin
 * 	name		- Admin name
 * 	password	- Admin password
 */
$mvc->config['CMUser-Admin'] = array(
	'name' => 'root',
	'password' => 'root',
	'email' => 'test@example.com',
	'acronym' => 'root',
	);


/**
 * CMUser-Groups
 * 	Do not touch unless you know what you are doing.
 */
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


/**
 * Allow or disallow creation of new user accounts.
 */
$mvc->config['create_new_users'] = true;

/**
 * The content settings.
 * 	types			= The types of content.
 * 	filter			= The filter.
 */
$mvc->config['content'] = array(
	'types' => array(
		'page' => 'page',
		'post' => 'post',
		),
	'filter' => array(
		'plain' => 'plain',
		'html' => 'html',
		'bbcode' => 'bbcode',
		),
	);


/**
 * Mainly comments for the config file.
 * 	config.comments.php			<- The one updated by the creator(do not edit, please ^^)
 * 	site.config.comments.php	<- The user comment file
 */
$mvc->config['commentfiles'] = array(
	'/config.comments.php',
	'/site.config.comments.php',
	);

