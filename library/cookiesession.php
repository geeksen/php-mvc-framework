<?php

class cookiesession
{
	var $encrypt_key = 'too_many_secrets';
	var $cookie_name = 'pmf_session';
	var $expiry_time = 3600;
	var $update_time = 300;

	var $data = array();

	function __construct()
	{
		if ('' == $this->encrypt_key)
		{
			error_handler(1000, 'encrypt_key required');
		}

		if (!$this->load())
		{
			$this->create();
		}
		else
		{
			$this->update();
		}
	}

	function load()
	{
		$session = isset($_COOKIE[$this->cookie_name]) ? $_COOKIE[$this->cookie_name] : '';
		$http_user_agent = trim(substr(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', 0, 120));

		if ('' == $session)
		{
			return false;
		}

		$hash	 = substr($session, strlen($session) - 32);
		$session = substr($session, 0, strlen($session) - 32);

		if ($hash !== md5($session . $this->encrypt_key))
		{
			return false;
		}

		$session = unserialize($session);
		if (!is_array($session) || !isset($session['sess_id']) || !isset($session['ip_addr']) || !isset($session['u_agent']) || !isset($session['last_tm']))
		{
			$this->destroy();
			return false;
		}

		if (time() > ($session['last_tm'] + $this->expiry_time))
		{
			$this->destroy();
			return false;
		}

		if ($http_user_agent != $session['u_agent'])
		{
			$this->destroy();
			return false;
		}

		$this->data = $session;
		unset($session);

		return true;
	}

	function get($key)
	{
		return isset($this->data['usr_dat'][$key]) ? $this->data['usr_dat'][$key] : '';
	}

	function set($values)
	{
		if (!is_array($values))
		{
			error_handler(1000, 'session_set : array required');
		}

		$usr_dat = array_merge($this->data['usr_dat'], $values);
		$this->data = array_merge($this->data, array
		(
			'usr_dat' => $usr_dat,
		));

		$cookie_value = serialize($this->data);
		$cookie_value .= md5($cookie_value . $this->encrypt_key);

		setcookie($this->cookie_name, $cookie_value, 0, '/');
	}

	function create()
	{
		$sess_id = '';
		while (strlen($sess_id) < 32)
		{
			$sess_id .= mt_rand(0, mt_getrandmax());
		}

		$remote_addr = $_SERVER['REMOTE_ADDR'];
		$http_user_agent = trim(substr(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', 0, 120));

		$sess_id .= $remote_addr;
		$this->data = array
		(
			'sess_id' => md5(uniqid($sess_id, true)),
			'ip_addr' => $remote_addr,
			'u_agent' => $http_user_agent,
			'last_tm' => time(),
			'usr_dat' => array(),
		);

		$cookie_value = serialize($this->data);
		$cookie_value .= md5($cookie_value . $this->encrypt_key);

		setcookie($this->cookie_name, $cookie_value, 0, '/');
	}

	function update()
	{
		if (time() < ($this->data['last_tm'] + $this->update_time))
		{
			return;
		}

		$new_sess_id = '';
		while (strlen($new_sess_id) < 32)
		{
			$new_sess_id .= mt_rand(0, mt_getrandmax());
		}

		$remote_addr = $_SERVER['REMOTE_ADDR'];
		$http_user_agent = trim(substr(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', 0, 120));

		$new_sess_id .= $remote_addr;
		$this->data = array_merge($this->data, array
		(
			'sess_id' => md5(uniqid($new_sess_id, true)),
			'last_tm' => time(),
		));

		$cookie_value = serialize($this->data);
		$cookie_value .= md5($cookie_value . $this->encrypt_key);

		setcookie($this->cookie_name, $cookie_value, 0, '/');
	}

	function destroy()
	{
		$this->data = array();

		setcookie($this->cookie_name, '', 0, '/');
	}
}

