<?php

class database extends mysqli
{
	var $config = array
	(
		'msgdb' => array
		(
			'server' => '127.0.0.1',
			'userid' => 'root',
			'passwd' => '1235',
			'dbname' => 'msgdb',
			'dbport' => 3306,
		),
	);

	function __construct(&$db)
	{
		parent::__construct($this->config[$db]['server'], $this->config[$db]['userid'], $this->config[$db]['passwd'], $this->config[$db]['dbname'], $this->config[$db]['dbport']);
	}
}
