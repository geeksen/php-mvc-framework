<?php

require 'base/error.php';
require 'base/session.php';
require 'base/config.php';
require 'base/router.php';
require 'base/database.php';

require 'base/model.php';
require 'base/view.php';
require 'base/controller.php';

$router = new router();
$class = $router->fetch_class();
$method = $router->fetch_method();

$view = new view();

require 'controller/' . $class . '.php';
if (!class_exists($class))
{
	echo 'error1';
	exit;
}
$controller = new $class();

if (!method_exists($controller, $method))
{
	echo 'error2';
	exit;
}

$controller->$method();
$view->display();
