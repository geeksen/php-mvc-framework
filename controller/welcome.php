<?php

class welcome extends controller
{
	function index()
	{
		$response = array
		(
			'h2_title' => 'Home',
		);
	
		$this->load_view('header', $response);
		$this->load_view('index', $response);
		$this->load_view('footer', $response);
	}
}
