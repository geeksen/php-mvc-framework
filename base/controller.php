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

<?php
//엠비즈 for utf-8 detection

if ($f_mode == 'insert' || $f_mode == 'update') {
	if ($f_mode == 'insert') { $v_filepath = $v_filepath . substr(date('Y-m-d'), 0, 7) . '/'; }
	if ($f_mode == 'update') { $v_filepath = $v_filepath . substr($d_insertdate    , 0, 7) . '/'; }
	if (!is_dir($v_filepath) && !mkdir($v_filepath)) { mysqli_close($dbconn_mbiz); echo '<font size=4 face=tahoma color=#ff0000>could not make directory</font>'; exit; }

	$f_filename = $_FILES['file']['name'];
	if ($f_filename != '') {
		if ($f_mode == 'update' && $d_filename != '' && file_exists($v_filepath . $d_filename) && !unlink($v_filepath . $d_filename)) { mysqli_close($dbconn_mbiz); print 'could not remove</font>'; exit; }

		$f_filename = str_replace('.htm'  , '.txt', $f_filename);
		$f_filename = str_replace('.html' , '.txt', $f_filename);
		$f_filename = str_replace('.shtml', '.txt', $f_filename);
		$f_filename = str_replace('.php'  , '.txt', $f_filename);
		$f_filename = str_replace('.php3' , '.txt', $f_filename);
		$f_filename = str_replace('.php4' , '.txt', $f_filename);
		$f_filename = str_replace('.phtml', '.txt', $f_filename);
		$f_filename = str_replace('.inc'  , '.txt', $f_filename);
		$f_filename = str_replace('.pl'   , '.txt', $f_filename);
		$f_filename = str_replace('.cgi'  , '.txt', $f_filename);
		$f_filename = str_replace(' '     , '_'   , $f_filename);

		$i = 0;
		$v_filename = $f_filename;
		 while (file_exists($v_filepath . $f_filename)) {
			$f_filename = $i . $v_filename;
			$i++;
		}

		if (is_uploaded_file($_FILES['file']['tmp_name']) && !move_uploaded_file($_FILES['file']['tmp_name'], $v_filepath . $f_filename)) { mysqli_close($dbconn_mbiz); print 'could not upload'; exit; }
	}
	else {
		if ($f_mode == 'update') { $f_filename = $d_filename; }
	}
}

if ($s_userid == 'Steve' && $f_mode == 'delete' && $d_filename != '') {
	$v_filepath = $v_filepath . substr($d_insertdate    , 0, 7) . '/';
	if (file_exists($v_filepath . $d_filename) && !unlink($v_filepath . $d_filename)) { mysqli_close($dbconn_mbiz); echo '<font size=4 face=tahoma color=#ff0000>could not remove</font>'; exit; }
}
?>
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
