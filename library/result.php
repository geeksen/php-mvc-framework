<?php

class result
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
		505 => 'HTTP Version Not Supported'

	// Application

		// Error
		1000 => 'Error',

		// OK
		2000 => 'OK',

		//30 : board
		3001 => 'boardseq is required',
		3002 => 'board not found',

		//35 : user
		3501 => 'boardseq is required',
		3502 => 'board not found',
	);

	function table()
	{
		$this->head();

		echo '<table border=1>';
		foreach ($this->codes as $key => $value)
		{
			echo '<tr>' . PHP_EOL;
			echo '<td>' . $key . '</td>' . PHP_EOL;
			echo '<td>' . $value . '</td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
		}
		echo '</table>';

		$this->tail();
	}

	function code($message)
	{
		$result = array_search($message, $this->codes);

		return (false === $result ? 0 : $result);
	}

	function h1_error($message)
	{
		$this->head();

		echo '<h1>' . PHP_EOL;
		echo 'Error(' . $this->code($message) . ') : ' . $message . PHP_EOL;
		echo '</h1>' . PHP_EOL;

		$this->tail();
	}

	function javascript_alert($message)
	{
		$this->head();

		echo '<script type="text/javascript">' . PHP_EOL;
		echo 'alert("' . $message . '");' . PHP_EOL;
		echo 'history.back();' . PHP_EOL;
		echo '</script>' . PHP_EOL;

		$this->tail();
	}

	function head()
	{
		echo '<!DOCTYPE html>' . PHP_EOL;
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
