<?php

class board_model extends model
{
	var $field_columns = array
	(
		1 => 'seq',
		2 => 'title',
		3 => 'content',
		4 => 'userid',
	);

	var $field_names = array
	(
		'seq' => 'Seq',
		'title' => 'Title',
		'content' => 'Content',
		'userid' => 'UserID',
	);

	function __construct(&$db, &$request)
	{
		parent::__construct($db, $request);
	}

	function select_info()
	{
		$result = $this->db->query('SELECT * FROM board WHERE seq = ' . $this->escaped['seq'] . ' LIMIT 0, 1');
		return $result->fetch_object();
	}

	function select_list()
	{
		$query = "SELECT * FROM board
		JOIN
		(
			SELECT seq FROM board ORDER BY seq DESC
			LIMIT " . (($this->escaped['page'] - 1) * $this->escaped['per_page']) . ", " . $this->escaped['per_page'] . "
		)
		T ON board.seq = T.seq ORDER BY board.seq DESC";

		if (true === array_key_exists($this->escaped['field'], $this->field_columns) && '' != $this->escaped['keyword'])
		{
			$query = "SELECT * FROM board
			JOIN
			(
				SELECT seq FROM board
				WHERE " . $this->field_columns[$this->escaped['field']] . " LIKE '%" . $this->escaped['keyword'] . "%'
				ORDER BY seq DESC
				LIMIT " . (($this->escaped['page'] - 1) * $this->escaped['per_page']) . ", " . ($this->escaped['per_page'] + 1) . "
			)
			T ON board.seq = T.seq ORDER BY board.seq DESC";
		}

		$result = $this->db->query($query);
		if (false === $result) { return array(); }

		$fetched = array();
		while ($row = $result->fetch_object())
		{
			$fetched[] = $row;
		}

		return $fetched;
	}

	function select_count()
	{
		$result = $this->db->qeury('SELECT count(*) as board_count FROM board');
		$row = $result->fetch_object();

		return $row->board_count;
	}

	function insert()
	{
		$this->db->query("INSERT INTO board (title, content, userid, inserttime, updatetime) VALUES ('" . $this->escaped['title'] . "', '" . $this->escaped['content'] . "', '" . $this->escaped['userid'] . "', NOW(), NOW())");
		return $this->db->affected_rows;
	}

	function insert_multiple()
	{
		$query = "INSERT INTO board (title, content, userid, inserttime, updatetime) VALUES";

		$count = count($this->escaped['seq']);
		for ($i = 0; $i < $count; $i++)
		{
			$query .= "('" . $this->escaped['title'][$i] . "', '" . $this->escaped['content'][$i] . "', '" . $this->escaped['userid'][$i] . "', NOW(), NOW())";

			if ($i < ($count - 1))
			{
				$query .= ",";
			}
		}

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function update()
	{
		$this->db->query("UPDATE board SET title = '" . $this->escaped['title'] . "', content = '" . $this->escaped['content'] . "', userid = '" . $this->escaped['userid'] . "', updatetime = NOW() WHERE seq = " . $this->escaped['seq']);
		return $this->db->affected_rows;
	}

	function update_multiple()
	{
		$query = "UPDATE board SET";
		$count = count($this->escaped['seq']);

		if (1)
		{
			$query .= " title = CASE";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN " . $this->escaped['seq'][$i] . " THEN '" . $this->escaped['title'][$i] . "'";
			}
			$query .= " END";
		}

		$query .= ", ";

		if (1)
		{
			$query .= " content = CASE";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN " . $this->escaped['seq'][$i] . " THEN '" . $this->escaped['content'][$i] . "'";
			}
			$query .= " END";
		}

		$query .= " WHERE seq in (" . implode(', ', $this->escaped['seq']) . ")";

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function delete()
	{
		$this->db->query("DELETE FROM board WHERE seq = " . $this->escaped['seq']);
		return $this->db->affected_rows;
	}

	function delete_mutiple()
	{
		$this->db->query("DELETE FROM board WHERE seq in (" . implode(', ', $this->escaped['seq']) . ")");
		return $this->db->affected_rows;
	}
}
