<?php

error_reporting(E_ALL);
set_error_handler('error_handler');

function error_handler($errno, $errstr, $errfile = '', $errline = 0, $errcontext = array())
{
	$error = new error();
	$errno = (1000 == $errno) ? $error->code($errstr) : $errno;

	$error->show('html', $errno, $errstr, $errfile, $errline, $errcontext);

	exit;
}

class error
{
	var $codes = array
	(

	// HTTP

		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',

		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',

		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',

		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',

	// APPLICATION

		// ERROR
		1000 => 'ERROR',

		// OKAY
		2000 => 'OKAY',

		//30 : board
		3001 => 'boardseq is required',
		3002 => 'board not found',

		//35 : user
		3501 => 'userid is required',
		3502 => 'board not found',
	);

	function code($message)
	{
		$result = array_search($message, $this->codes);

		return (false === $result ? 1000 : $result);
	}

	function show($format, $code, $message, $file, $line, $context)
	{
		if (!method_exists($this, $format))
		{
			$format = 'html';
		}

		$this->$format($code, $message, $file, $line, $context);
	}

	function json($code, $message, $file, $line, $context)
	{
		header('Content-type: application/json; charset=utf-8');
		echo json_encode(array
		(
			'code' => $code,
			'message' => $message,
		));
	}

	function html($code, $message, $file, $line, $context)
	{
		$this->head();

		echo '<h1>Error(' . $code . ') : ' . $message . '</h1>' . PHP_EOL;

		$this->tail();
	}

	function alert($code, $message, $file, $line, $context)
	{
		$this->head();

		echo '<script type="text/javascript">' . PHP_EOL;
		echo 'alert("' . $message . '");' . PHP_EOL;
		echo 'history.back();' . PHP_EOL;
		echo '</script>' . PHP_EOL;

		$this->tail();
	}

	function table()
	{
		$this->head();

		echo '<table border=1>' . PHP_EOL;
		foreach ($this->codes as $key => $value)
		{
			echo '<tr>' . PHP_EOL;
			echo '<td>' . $key . '</td>' . PHP_EOL;
			echo '<td>' . $value . '</td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
		}
		echo '</table>' . PHP_EOL;

		$this->tail();
	}

	function head()
	{
		echo '<!DOCTYPE html>' . PHP_EOL . PHP_EOL;
		echo '<html lang="en">' . PHP_EOL;
		echo '<head>' . PHP_EOL;
		echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">' . PHP_EOL;
		echo '<meta charset="utf-8">' . PHP_EOL;
		echo '<title>Result</title>' . PHP_EOL;
		echo '</head>' . PHP_EOL;
		echo '<body>' . PHP_EOL;
	}

	function tail()
	{
		echo '</body>' . PHP_EOL;
		echo '</html>' . PHP_EOL;
	}
}