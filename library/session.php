<?php

class session
{
	var $expiry_time = 3600;
	var $update_time = 300;

	function __construct()
	{
		session_cache_limiter('');
		session_start();

		$remote_addr = $_SERVER['REMOTE_ADDR'];
		$http_user_agent = trim(substr(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', 0, 120));

		if (!isset($_SESSION['ip_addr']) || !isset($_SESSION['u_agent']) || !isset($_SESSION['last_tm']))
		{
			$_SESSION = array_merge($_SESSION, array
			(
				'ip_addr' => $remote_addr,
				'u_agent' => $http_user_agent,
				'last_tm' => time(),
			));
		}

		/*
		if ($remote_addr != $_SESSION['ip_addr'])
		{
			$this->destroy();
			return;
		}
		*/
	
		if (time() > ($_SESSION['last_tm'] + $this->expiry_time))
		{
			$this->destroy();
			return;
		}

		if ($http_user_agent != $_SESSION['u_agent'])
		{
			$this->destroy();
			return;
		}

		if (time() < ($_SESSION['last_tm'] + $this->update_time))
		{
			session_regenerate_id();
		}
	}

	function destory()
	{
		$_SESSION = array();

		if (isset($_COOKIE[session_name()]))
		{
			setcookie(session_name(), '', 0, '/');
		}
		session_destroy();
	}

	function set($data)
	{
		if (!is_array($data))
		{
			error_handler(1, 'session_set : array required');
		}

		$_SESSION = array_merge($_SESSION, $data);
	}

	function data($key)
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
	}
}
