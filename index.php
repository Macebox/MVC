<?php

// Bootstrap
define('MVC_INSTALL_PATH', dirname(__FILE__));
define('MVC_SITE_PATH', MVC_INSTALL_PATH . '/site');

require(MVC_INSTALL_PATH.'/src/CNocturnal/bootstrap.php');

$mvc = CNocturnal::Instance();

// Front controller route
$mvc->FrontControllerRoute();

// Theme engine render
$mvc->ThemeEngineRender();

?>