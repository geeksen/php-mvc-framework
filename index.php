<?php

require_once 'base/error.php';

class application
{
	var $request_uri = array();

	var $directory = '';
	var $controller = '';
	var $method = '';

	function __construct()
	{
		date_default_timezone_set('Asia/Seoul');

		require_once 'base/database.php';
		require_once 'base/model.php';
		require_once 'base/controller.php';

		$this->controller = 'welcome';
		$this->method = 'index';
	}

	function parse()
	{
		$REQUEST_URI = '';
		if ('cli' == php_sapi_name())
		{
			$args = array_slice($_SERVER['argv'], 1);
			$REQUEST_URI = $args ? '/' . implode('/', $args) : '';
		}
		else if (isset($_SERVER['REQUEST_URI']))
		{
			$REQUEST_URI = $_SERVER['REQUEST_URI'];
		}

		$slice_offset = 0;
		$this->request_uri = explode('/', rtrim($REQUEST_URI, '/'));
		if (isset($this->request_uri[0])) { $this->directory  = $this->request_uri[0]; $slice_offset++; }
		if (isset($this->request_uri[1])) { $this->controller = $this->request_uri[1]; $slice_offset++; }
		if (isset($this->request_uri[2])) { $this->method     = $this->request_uri[2]; $slice_offset++; }

		$path = 'controller/' . $this->directory . '/' . $this->controller;
		if (file_exists($path) && is_dir($path))
		{
			$this->directory = $this->controller;
			$this->controller = $this->method;
			$this->method = 'index';

			if (isset($this->request_uri[3]) && '' != $this->request_uri[3])
			{
				$this->method = $this->request_uri[3];
				$slice_offset++;
			}
		}

		$this->request_uri = array_slice($this->request_uri, $slice_offset);
	}

	function load()
	{
		$path = 'controller/' . $this->directory . '/' . $this->controller;
		if (!file_exists($path . '.php'))
		{
			error_handler(1000, 'file not found');
		}

		require_once $path . '.php';
	}

	function main()
	{
		$this->parse();
		$this->load();

		$directory = $this->directory;
		$controller = $this->controller;
		$method = $this->method;

		if (!class_exists($controller))
		{
			error_handler(1000, 'class not found');
		}
		$class = new $controller($this->request_uri);

		if (!method_exists($controller, $method))
		{
			error_handler(1000, 'method not found');
		}
		$class->$method();
	}
}

$application = new application();
$application->main();
