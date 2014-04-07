<?php

//error_reporting(E_ALL);
//set_error_handler('error_handler');
date_default_timezone_set('Asia/Seoul');

function error_handler($errno, $errstr)
{
	echo json_encode(array
	(
		'errno' => $errno,
		'errstr' => $errstr,
	));

	exit;
}

class main
{
	var $path_info_exploded = array();

	var $directory = '';
	var $controller = '';
	var $method = '';

	function init()
	{
		require_once 'base/database.php';
		require_once 'base/model.php';
		require_once 'base/controller.php';

		$this->controller = 'welcome';
		$this->method = 'index';
	}

	function parse()
	{
		$path_info = '';
		if ('cli' == php_sapi_name())
		{
			$args = array_slice($_SERVER['argv'], 1);
			$path_info = $args ? '/' . implode('/', $args) : '';
		}
		else if (isset($_SERVER['PATH_INFO']))
		{
			$path_info = $_SERVER['PATH_INFO'];
		}

		$slice_offset = 0;
		$this->path_info_exploded = explode('/', rtrim($path_info, '/'));
		if (isset($this->path_info_exploded[0])) { $this->directory  = $this->path_info_exploded[0]; $slice_offset++; }
		if (isset($this->path_info_exploded[1])) { $this->controller = $this->path_info_exploded[1]; $slice_offset++; }
		if (isset($this->path_info_exploded[2])) { $this->method     = $this->path_info_exploded[2]; $slice_offset++; }

		$path = 'controller/' . $this->directory . '/' . $this->controller;
		if (file_exists($path) && is_dir($path))
		{
			$this->directory = $this->controller;
			$this->controller = $this->method;
			$this->method = 'index';

			if (isset($this->path_info_exploded[3]) && '' != $this->path_info_exploded[3])
			{
				$this->method = $this->path_info_exploded[3];
				$slice_offset++;
			}
		}

		$this->path_info_exploded = array_slice($this->path_info_exploded, $slice_offset);
	}

	function load()
	{
		$path = 'controller/' . $this->directory . '/' . $this->controller;
		if (false === file_exists($path . '.php'))
		{
			error_handler(1, 'file not found');
		}

		require_once $path . '.php';
	}

	function exec()
	{
		$this->init();
		$this->parse();
		$this->load();

		$directory = $this->directory;
		$controller = $this->controller;
		$method = $this->method;

		if (false === class_exists($controller))
		{
			error_handler(1, 'class not found');
		}
		$class = new $controller($this->path_info_exploded);

		if (false === method_exists($controller, $method))
		{
			error_handler(1, 'method not found');
		}
		$class->$method();
	}
}

$main = new main();
$main->exec();
