<?php

class welcome extends controller
{
	function index()
	{
		$response = array();
		$this->load_view('index', $response);
	}
}
