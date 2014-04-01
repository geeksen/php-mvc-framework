<?php

class model
{
	var $db;
	var $escaped;

	function __construct(&$db, &$request)
	{
		$this->db = $db;

		$this->db->set_charset('utf8');
		foreach ($request as $key => $value)
		{
			$this->escaped[$key] = $this->db->real_escape_string($value);
		}
	}
}
