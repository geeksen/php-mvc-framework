<?php

class model
{
	var $db;
	var $escaped;

	function __construct(&$db, &$request)
	{
		$this->db = $db;
		$this->db->set_charset('utf8');

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
			
			$array[$key] = $this->db->real_escape_string($value);
		}

		return $array;
	}
}
