<?php

class database extends mysqli
{
	var $config = array
	(
		'pmf' => array
		(
			'server' => '127.0.0.1',
			'userid' => 'root',
			'passwd' => '1235',
			'dbname' => 'pmf',
			'dbport' => 3306,
		),
	);

	function __construct(&$db)
	{
		if (!array_key_exists($db, $this->config))
		{
			error_handler(1, 'database config not found');
		}

		parent::__construct($this->config[$db]['server'], $this->config[$db]['userid'], $this->config[$db]['passwd'], $this->config[$db]['dbname'], $this->config[$db]['dbport']);
	}
}
