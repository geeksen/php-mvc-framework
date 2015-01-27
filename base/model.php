<?php

class model
{
	var $db;
	var $result;
	var $num_rows;
	var $affected_rows;

	function __construct(&$db)
	{
		$this->db = $db;
		$this->db->set_charset('utf8');
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

			// for hander_socket
			$value = str_replace("\t", ' ', $value);

			$value = str_replace('<', '&lt;', $value);
			$value = str_replace('>', '&gt;', $value);

			$array[$key] = $this->db->real_escape_string($value);
		}

		return $array;
	}

	function query($query, $params = array())
	{
		if (false === strpos($query, '?'))
		{
			$this->result = $this->db->query($query);
			$this->affected_rows = $this->db->affected_rows;
			return;
		}

		$exploded = explode('?', $query);
		if (count($params) != (count($exploded) - 1))
		{
			error_handler(1000, 'invalid query params');
		}

		$i = 0;
		$query = $exploded[0];
		foreach ($params as $param)
		{
			if (is_array($param))
			{
				error_handler(1000, 'invalid query params');
			}

			if (is_string($param))
			{
				$param = "'" . $param . "'";
			}
 
			$query .= $param;
			$query .= $exploded[++$i];
		}

		$this->result = $this->db->query($query);
		$this->affected_rows = $this->db->affected_rows;
	}
}
