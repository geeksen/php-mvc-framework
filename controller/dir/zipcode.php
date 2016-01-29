<?php

class zipcode extends controller
{
	var $session;

	var $files = array
	(
		'file1',
		'file2',
	);

	function __construct(&$request_uri)
	{
		parent::__construct($request_uri);
	}

	function index()
	{
		$request = $this->input_get(array
		(
			'page' => 1,
			'field' => 0,
			'keyword' => '',
			'per_page' => 10,
			'link_count' => 10,
		));

		$response = array
		(
			'h2_title' => 'Zipcode :: Index',

			'page' => $request['page'],
			'field' => $request['field'],
			'keyword' => $request['keyword'],
			'per_page' => $request['per_page'],

			'link_count' => $request['link_count'],
		);

		$this->load_view('header', $response);
		$this->load_view('dir/zipcode/index', $response);
		$this->load_view('footer', $response);
	}
}

