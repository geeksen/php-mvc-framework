<?php

class controller
{
	var $upload_path = '/home/ubuntu/github/php-mvc-framework/upload/';
	var $upload_allowed = array
	(
		'image/png',
		'image/gif',
		'image/jpeg',
	);

	var $img_mimes = array('image/png', 'image/gif', 'image/jpeg');
	var $png_mimes = array('image/x-png');
	var $jpeg_mimes = array('image/jpe', 'image/jpg', 'image/pjpeg');

	var $request_uri = array();

	function __construct(&$request_uri)
	{
		$this->request_uri = $request_uri;
	}

	function input_get($request)
	{
		$i = 0;
		foreach ($request as $key => $value)
		{
			$request[$key] = isset($this->request_uri[$i]) ? urldecode($this->request_uri[$i]) : $value;
			++$i;
		}

		return $request;
	}

	function input_post($request)
	{
		foreach ($request as $key => $value)
		{
			$request[$key] = isset($_POST[$key]) ? $_POST[$key] : $value;
		}

		return $request;
	}

	function input_upload($path, $files)
	{
		$upload = array();
		foreach ($files as $file)
		{
			$upload[$file] = '';

			if (!isset($_FILES[$file]['name']) || '' == $_FILES[$file]['name'] || 0 == $_FILES[$file]['size'])
			{
				continue;
			}

			$filename = $_FILES[$file]['name'];
			$tmp_name = $_FILES[$file]['tmp_name'];

			//$finfo = new finfo(FILEINFO_MIME);
			//$mime = $finfo->file($file);

			if (!function_exists('finfo_open'))
			{
				error_handler(1000, 'finfo_open not found');
			}

			$finfo = finfo_open();
			$mime = finfo_file($finfo, $tmp_name, FILEINFO_MIME);
			finfo_close($finfo);
			
			$regexp = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';
			if (is_string($mime) && preg_match($regexp, $mime, $matches))
			{
				$mime = $matches[1];
				if (in_array($mime, $this->png_mimes))
				{
					$mime = 'image/png';
				}

				if (in_array($mime, $this->jpeg_mimes))
				{
					$mime = 'image/jpeg';
				}

				if (!in_array($mime, $this->upload_allowed))
				{
					error_handler(1000, 'upload not allowed');
				}

				if (in_array($mime, $this->img_mimes))
				{
					if (!function_exists('getimagesize'))
					{
						error_handler(1000, 'getimagesize not found');
					}

					if (false === getimagesize($tmp_name))
					{
						error_handler(1000, 'getimagesize failed');
					}
					
					if (false === ($handle = fopen($_FILES[$file]['tmp_name'], 'rb')))
					{
						error_handler(1000, 'fopen tmp_file failed');
					}

					$opening_bytes = fread($handle, 256);
					fclose($handle);

					if (preg_match('/<(a|body|head|html|img|plaintext|pre|script|table|title)[\s>]/i', $opening_bytes))
					{
						error_handler(1000, 'not an image');
					}
				}
			}

			$ext = substr(strrchr($filename, '.'), 0);
			$filename = substr($filename, 0, strrpos($filename, '.'));

			$i = 1;
			$new_filename = $filename . $ext;
		 	while ($i < 100 && file_exists($path . $new_filename))
			{
				$new_filename = $filename . '(' . $i . ')' . $ext;
				++$i;
			}

			if (100 == $i)
			{
				error_handler(1000, 'upload failed');
			}

			if (!file_exists($path) && !mkdir($path, 0755))
			{
				error_handler(1000, 'mkdir failed');
			}

			if (is_uploaded_file($_FILES[$file]['tmp_name']) && !move_uploaded_file($_FILES[$file]['tmp_name'], $path . $new_filename))
			{
				error_handler(1000, 'upload failed');
			}

			$upload[$file] = $new_filename;
		}

		return $upload;
	}

	function load_database($db)
	{
		return new database($db);
	}

	function load_library($library)
	{
		if (!file_exists('library/' . $library . '.php'))
		{
			error_handler(1000, 'library not found');
		}
		require_once 'library/' . $library . '.php';

		$library = basename($library);
		return new $library();
	}

	function load_model(&$db, $model)
	{
		if (!file_exists('model/' . $model . '.php'))
		{
			error_handler(1000, 'model not found');
		}
		require_once 'model/' . $model . '.php';

		$model = basename($model);
		return new $model($db);
	}

	function load_view($view, &$response)
	{
		if (!file_exists('view/' . $view . '.php'))
		{
			error_handler(1000, 'view not found');
		}

		foreach ($response as $key => $value)
		{
			$$key = $value;
		}

		require_once 'view/' . $view . '.php';
	}

	function redirect_to($uri)
	{
		header('Refresh:0; url=' . $uri);

		exit;
	}
}
