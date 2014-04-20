<?php

class model
{
	var $db;
	var $escaped;

	function __construct(&$db, &$request)
	{
		$this->db = $db;
		$this->db->set_charset('utf8');

		$this->escape_request($request);
	}

	function escape_request(&$request)
	{
		$this->escaped = $this->real_escape_array($request);
	}

	function real_escape_array(&$array)
	{
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$this->real_escape_array($value);
				continue;
			}

			# for hander_socket
			$value = str_replace("\t", ' ', $value);

			$value = str_replace('<', '&lt;', $value);
			$value = str_replace('>', '&gt;', $value);

			$array[$key] = $this->db->real_escape_string($value);
		}

		return $array;
	}
}
