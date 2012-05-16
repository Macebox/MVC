<?php

// Bootstrap
define('MVC_INSTALL_PATH', dirname(__FILE__));
define('MVC_SITE_PATH', MVC_INSTALL_PATH . '/site');
define('MVC_CORE_PATH', MVC_INSTALL_PATH . '/src/core');

require(MVC_CORE_PATH.'/bootstrap.php');

$mvc = CNocturnal::Instance();

// Front controller route
$mvc->FrontControllerRoute();

// Theme engine render
$mvc->ThemeEngineRender();

?>