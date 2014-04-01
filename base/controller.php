<?php

class controller
{
	var $path_info_exploded = array();

	function __construct(&$path_info_exploded)
	{
		$this->path_info_exploded = $path_info_exploded;
	}

	function input_get($request)
	{
		$i = 0;
		foreach ($request as $key => $value)
		{
			$request[$key] = isset($this->path_info_exploded[$i]) ? $this->path_info_exploded[$i] : $value;

			if (0 === $value)
			{
				$request[$key] = intval($request[$key]);
			}

			$i++;
		}

		return $request;
	}

	function input_post($request)
	{
		foreach ($request as $key => $value)
		{
			$request[$key] = isset($_POST[$key]) ? $_POST[$key] : $value;

			if (0 === $value)
			{
				$request[$key] = intval($request[$key]);
			}
		}

		return $request;
	}

	function input_upload()
	{
	}

	function load_database($db)
	{
		return new database($db);
	}

	function load_library($library)
	{
		require_once 'library/' . $library . '.php';

		$library = basename($path);
		return new $library();
	}

	function load_model(&$db, &$request, $model)
	{
		require_once 'model/' . $model . '.php';

		$model = basename($model);
		return new $model($db, $request);
	}

	function load_view($view, &$response)
	{
		foreach ($response as $key => $value)
		{
			$$key = $value;
		}

		require_once 'view/' . $view . '.php';
	}

	function redirect_to($uri)
	{
		header('Refresh:0; url=' . $uri);
	}
}
