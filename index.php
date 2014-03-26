<?php

require 'class/config.php';
require 'class/error.php';
require 'class/router.php';
require 'class/model.php';
require 'class/view.php';

$router = new router();
$class = $router->fetch_class();
$method = $router->fetch_method();

$view = new view();

require 'class/controller.php';
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
