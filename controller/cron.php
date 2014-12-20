<?php

class cron extends controller
{
	function __construct()
	{
		if ('cli' != php_sapi_name())
		{
			error_handler(1000, 'not cli mode');	
		}
	}

	function index()
	{
		echo 'index';
	}
}

