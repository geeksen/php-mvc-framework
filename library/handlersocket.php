<?php

class handlersocket
{
	var $config = array
	(
		'pmf' => array
		(
			'server' => '127.0.0.1',
			'portwr' => 9999,
			'port'   => 9998,
		),
	);

	var $wr = array
	(
		'select_info' => '',
		'select_list' => '',
		'insert_info' => 'wr',
		'insert_info' => 'wr',
		'update_info' => 'wr',
		'update_list' => 'wr',
		'delete_info' => 'wr',
		'delete_list' => 'wr',
	);

	var $socket;

	function __construct(&$command, &$db, &$param)
	{
		if (!array_key_exists($db, $this->config))
		{
			error_handler(1000, 'handersocket config not found');
		}

		if (!array_key_exists($command, $this->wr))
		{
			error_handler(1000, 'handersocket config not found');
		}

		$wr = $this->wr[$command];
		$this->socket = stream_socket_client('tcp://' . $this->config[$db]['server'] . ':' . $this->config[$db]['port' . $wr], $errno, $errstr);
	}

	function __destruct()
	{
		if (null !== $this->socket)
		{
			fclose($this->socket);
			$this->socket = null;
		}

        	$this->indexes = array();
        	$this->index = 1;
	}

	function get_index()
	{
	}

	function select_info()
	{
	}

	function select_list()
	{
	}

	function insert_info()
	{
	}

	function insert_list()
	{
	}

	function update_info()
	{
	}

	function update_list()
	{
	}

	function delete_info()
	{
	}

	function delete_list()
	{
	}
}
