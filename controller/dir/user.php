<?php

class user extends controller
{
	var $session;

	function __construct()
	{
		$this->session = $this->load_library('session');
	}

	function signin()
	{
		$response = array
		(
			'h2_title' => 'Sign In',
		);

		$this->load_view('header', $response);
		$this->load_view('dir/user/signin', $response);
		$this->load_view('footer', $response);
	}

	function signup()
	{
		$response = array
		(
			'h2_title' => 'Sign Up',
		);

		$this->load_view('header', $response);
		$this->load_view('dir/user/signup', $response);
		$this->load_view('footer', $response);
	}

	function signout()
	{
		$this->session->destroy();

		$this->redirect_to('/dir/board/index');
	}

	function auth()
	{
		$request = $this->input_post(array
		(
			'userid' => '',
			'passwd' => '',
		));

		if ('' == $request['userid'])
		{
			error_handler(1, 'userid required');
		}

		if ('' == $request['passwd'])
		{
			error_handler(1, 'passwd required');
		}

		$request['passwd'] = md5($request['passwd']);

		$db = $this->load_database('pmf');
		$user_model = $this->load_model($db, $request, 'dir/user_model');
		$result = $user_model->select_info_by_userid_and_passwd();

		if (0 == $result->num_rows)
		{
			error_handler(1, 'auth failed');
		}

		$user_info = $result->fetch_object();

		$this->session->set(array
		(
			'userid' => $user_info->userid,
		));

		$this->redirect_to('/dir/board/index');
	}

	function post()
	{
		$request = $this->input_post(array
		(
			'userid' => '',
			'passwd' => '',
			'retypepasswd' => '',
		));

		if ('' == $request['userid'])
		{
			error_handler(1, 'userid required');
		}

		if ($request['passwd'] != $request['retypepasswd'])
		{
			error_handler(1, 'retype passwd');
		}

		$request['passwd'] = md5($request['passwd']);

		$db = $this->load_database('pmf');
		$user_model = $this->load_model($db, $request, 'dir/user_model');

		$affected_rows = $user_model->insert();

		if (1 != $affected_rows)
		{
			error_handler(1, 'insufficient affected_rows');
		}

		$this->redirect_to('/dir/user/signin');
	}
}
