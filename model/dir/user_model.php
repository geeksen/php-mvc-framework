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

	function __construct(&$db, &$request)
	{
		parent::__construct($db, $request);
	}

	function select_info()
	{
		$query = "SELECT * FROM user WHERE userid = '" . $this->escaped['userid'] . "' LIMIT 0, 1";

		return $this->db->query($query);
	}

	function select_info_by_userid_and_passwd()
	{
		$query = "SELECT * FROM user WHERE userid = '" . $this->escaped['userid'] . "' AND passwd = '" . $this->escaped['passwd'] . "' LIMIT 0, 1";

		return $this->db->query($query);
	}

	function select_list()
	{
		$query = "SELECT * FROM user
		JOIN
		(
			SELECT userid FROM user ORDER BY userid DESC
			LIMIT " . (($this->escaped['page'] - 1) * $this->escaped['per_page']) . ", " . $this->escaped['per_page'] . "
		)
		T ON user.userid = T.userid ORDER BY user.userid DESC";

		if (true === array_key_exists($this->escaped['field'], $this->field_columns) && '' != $this->escaped['keyword'])
		{
			$query = "SELECT * FROM user
			JOIN
			(
				SELECT userid FROM user
				WHERE " . $this->field_columns[$this->escaped['field']] . " LIKE '%" . $this->escaped['keyword'] . "%'
				ORDER BY userid DESC
				LIMIT " . (($this->escaped['page'] - 1) * $this->escaped['per_page']) . ", " . ($this->escaped['per_page'] + 1) . "
			)
			T ON user.userid = T.userid ORDER BY user.userid DESC";
		}

		$result = $this->db->query($query);

		$fetched = array();
		while ($row = $result->fetch_object())
		{
			$fetched[] = $row;
		}

		return $fetched;
	}

	function select_count()
	{
		$query = "SELECT count(*) as user_count FROM user";

		if (true === array_key_exists($this->escaped['field'], $this->field_columns) && '' != $this->escaped['keyword'])
                {
                        $query = "SELECT count(*) as user_count FROM user WHERE " . $this->field_columns[$this->escaped['field']] . " LIKE '%" . $this->escaped['keyword'] . "%'";
                }

		$result = $this->db->query($query);
		$row = $result->fetch_object();

		return $row->user_count;
	}

	function insert()
	{
		$query = "INSERT INTO user (userid, passwd, inserttime, updatetime) VALUES ('" . $this->escaped['userid'] . "', '" . $this->escaped['passwd'] . "', NOW(), NOW())";

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function insert_multiple()
	{
		$query = "INSERT INTO user (userid, passwd, inserttime, updatetime) VALUES";

		$count = count($this->escaped['userids']);
		for ($i = 0; $i < $count; $i++)
		{
			$query .= "('" . $this->escaped['userids'][$i] . "', '" . $this->escaped['passwds'][$i] . "', NOW(), NOW())";

			if ($i < ($count - 1))
			{
				$query .= ", ";
			}
		}

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function update()
	{
		$query = "UPDATE user SET passwd = '" . $this->escaped['passwd'] . "', updatetime = NOW() WHERE userid = '" . $this->escaped['userid'] . "'";

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function update_multiple()
	{
		$query = "UPDATE user SET ";
		$count = count($this->escaped['userids']);

		foreach (array('passwds') as $field)
		{
			$query .= $field . " = CASE userid";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN '" . $this->escaped['userids'][$i] . "' THEN '" . $this->escaped[$field][$i] . "'";
			}
			$query .= " END, ";
		}

		$query .= "userid = '" . $this->escaped['userid'] . "', updatetime = NOW() WHERE userid in ('" . implode("', '", $this->escaped['userids']) . "')";

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function delete()
	{
		$query = "DELETE FROM user WHERE userid = '" . $this->escaped['userid'] . "'";

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function delete_multiple()
	{
		$query = "DELETE FROM user WHERE userid in ('" . implode("', '", $this->escaped['userids']) . "')";

		$this->db->query($query);
		return $this->db->affected_rows;
	}
}
