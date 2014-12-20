<?php

class user_model extends model
{
	var $field_columns = array
	(
		1 => 'userid',
	);

	var $field_names = array
	(
		'userid' => 'UserID',
	);

	function __construct(&$db)
	{
		parent::__construct($db);
	}

	function select_info(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('SELECT * FROM user WHERE userid = ? LIMIT 0, 1', array
		(
			$escaped['userid'],
		));

		$this->num_rows = $this->result->num_rows;
		return $this->result->fetch_object();
	}

	function select_info_by_userid_and_passwd(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('SELECT * FROM user WHERE userid = ? AND passwd = ? LIMIT 0, 1', array
		(
			$escaped['userid'],
			$escaped['passwd'],
		));

		$this->num_rows = $this->result->num_rows;
		return $this->result->fetch_object();
	}

	function select_list(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('SELECT * FROM user JOIN (SELECT userid FROM user ORDER BY userid DESC LIMIT ?, ?) T ON user.userid = T.userid ORDER BY user.userid DESC', array
		(
			($escaped['page'] - 1) * $escaped['per_page'],
			intval($escaped['per_page']),
		));

		if (true === array_key_exists($escaped['field'], $this->field_columns) && '' != $escaped['keyword'])
		{
			$this->query('SELECT * FROM user JOIN (SELECT userid FROM user WHERE ' . $this->field_columns[$escaped['field']] . ' LIKE ? ORDER BY userid DESC LIMIT ?, ?) T ON user.userid = T.userid ORDER BY user.userid DESC', array
			(
				'%' . $escaped['keyword'] . '%',
				($escaped['page'] - 1) * $escaped['per_page'],
				intval($escaped['per_page']),
			));
		}
		else
		{
			$this->query('SELECT * FROM user JOIN (SELECT userid FROM user ORDER BY userid DESC LIMIT ?, ?) T ON user.userid = T.userid ORDER BY user.userid DESC', array
			(
				($escaped['page'] - 1) * $escaped['per_page'],
				intval($escaped['per_page']),
			));
		}

		$this->num_rows = $this->result->num_rows;

		$fetched = array();
		while ($row = $this->result->fetch_object())
		{
			$fetched[] = $row;
		}

		return $fetched;
	}

	function select_count(&$request)
	{
		$escaped = $this->real_escape_array($request);

		if (true === array_key_exists($escaped['field'], $this->field_columns) && '' != $escaped['keyword'])
                {
                        $this->query('SELECT count(*) as user_count FROM user WHERE ' . $this->field_columns[$escaped['field']] . ' LIKE ?', array
			(
				'%' . $escaped['keyword'] . '%',
			));
                }
		else
		{
			$this->query('SELECT count(*) as user_count FROM user', array
			(
	
			));
		}

		$row = $this->result->fetch_object();
		return $row->user_count;
	}

	function insert_info(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('INSERT INTO user (userid, passwd, inserttime, updatetime) VALUES (?, ?, NOW(), NOW())', array
		(
			$escaped['userid'],
			$escaped['passwd'],
		));

		return $this->affected_rows;
	}

	function insert_list(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$query = "INSERT INTO user (userid, passwd, inserttime, updatetime) VALUES";

		$count = count($escaped['userids']);
		for ($i = 0; $i < $count; $i++)
		{
			$query .= "('" . $escaped['userids'][$i] . "', '" . $escaped['passwds'][$i] . "', NOW(), NOW())";

			if ($i < ($count - 1))
			{
				$query .= ", ";
			}
		}

		$this->query($query);
		return $this->affected_rows;
	}

	function update_info(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('UPDATE user SET passwd = ?, updatetime = NOW() WHERE userid = ?', array
		(
			$escaped['passwd'],
			$escaped['userid'],
		));

		return $this->affected_rows;
	}

	function update_list(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$query = "UPDATE user SET ";
		$count = count($escaped['userids']);

		foreach (array('passwds') as $field)
		{
			$query .= $field . " = CASE userid";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN '" . $escaped['userids'][$i] . "' THEN '" . $escaped[$field][$i] . "'";
			}
			$query .= " END, ";
		}

		$query .= "userid = '" . $escaped['userid'] . "', updatetime = NOW() WHERE userid in ('" . implode("', '", $escaped['userids']) . "')";

		$this->query($query);
		return $this->affected_rows;
	}

	function delete_info(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('DELETE FROM user WHERE userid = ?', array
		(
			$escaped['userid'],
		));

		return $this->affected_rows;
	}

	function delete_list(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$query = "DELETE FROM user WHERE userid in ('" . implode("', '", $escaped['userids']) . "')";

		$this->query($query);
		return $this->affected_rows;
	}
}
