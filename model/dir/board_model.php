<?php

class board_model extends model
{
	var $field_columns = array
	(
		//1 => 'seq',
		2 => 'title',
		3 => 'content',
		4 => 'file1',
		5 => 'file2',
		6 => 'userid',
	);

	var $field_names = array
	(
		//'seq' => 'Seq',
		'title' => 'Title',
		'content' => 'Content',
		'file1' => 'File1',
		'file2' => 'File2',
		'userid' => 'UserID',
	);

	function __construct(&$db, &$request)
	{
		parent::__construct($db, $request);
	}

	function select_info()
	{
		$query = "SELECT * FROM board WHERE seq = " . $this->escaped['seq'] . " LIMIT 0, 1";

		$result = $this->db->query($query);
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

		$fetched = array();
		while ($row = $result->fetch_object())
		{
			$fetched[] = $row;
		}

		return $fetched;
	}

	function select_count()
	{
		$query = "SELECT count(*) as board_count FROM board";

		if (true === array_key_exists($this->escaped['field'], $this->field_columns) && '' != $this->escaped['keyword'])
                {
                        $query = "SELECT count(*) as board_count FROM board WHERE " . $this->field_columns[$this->escaped['field']] . " LIKE '%" . $this->escaped['keyword'] . "%'";
                }

		$result = $this->db->query($query);
		$row = $result->fetch_object();

		return $row->board_count;
	}

	function insert()
	{
		$query = "INSERT INTO board (title, content, userid, file1, file2, inserttime, updatetime) VALUES ('" . $this->escaped['title'] . "', '" . $this->escaped['content'] . "', '" . $this->escaped['userid'] . "', '" . $this->escaped['file1'] . "', '" . $this->escaped['file2'] . "', NOW(), NOW())";

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function insert_multiple()
	{
		$query = "INSERT INTO board (title, content, userid, file1, file2, inserttime, updatetime) VALUES";

		$count = count($this->escaped['seqs']);
		for ($i = 0; $i < $count; $i++)
		{
			$query .= "('" . $this->escaped['title'][$i] . "', '" . $this->escaped['content'][$i] . "', '" . $this->escaped['userid'][$i] . "', '" . $this->escaped['file1'][$i] . "', '" . $this->escaped['file2'][$i] . "', NOW(), NOW())";

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
		$query = "UPDATE board SET title = '" . $this->escaped['title'] . "', content = '" . $this->escaped['content'] . "', userid = '" . $this->escaped['userid'] . "', file1 = '" . $this->escaped['file1'] . "', file2 = '" . $this->escaped['file2'] . "', updatetime = NOW() WHERE seq = " . $this->escaped['seq'];

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function update_multiple()
	{
		$query = "UPDATE board SET";
		$count = count($this->escaped['seqs']);

		/* title */ if (1)
		{
			$query .= " title = CASE";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN " . $this->escaped['seqs'][$i] . " THEN '" . $this->escaped['title'][$i] . "'";
			}
			$query .= " END, ";
		}

		/* content */ if (1)
		{
			$query .= " content = CASE";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN " . $this->escaped['seqs'][$i] . " THEN '" . $this->escaped['content'][$i] . "'";
			}
			$query .= " END, ";
		}

		/* file1 */ if (1)
		{
			$query .= " file1 = CASE";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN " . $this->escaped['seqs'][$i] . " THEN '" . $this->escaped['file1'][$i] . "'";
			}
			$query .= " END, ";
		}

		/* file2 */ if (1)
		{
			$query .= " file2 = CASE";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN " . $this->escaped['seqs'][$i] . " THEN '" . $this->escaped['file2'][$i] . "'";
			}
			$query .= " END, ";
		}

		$query .= "userid = '" . $this->escaped['userid'] . "', updatetime = NOW() WHERE seq in (" . implode(', ', $this->escaped['seqs']) . ")";

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function delete()
	{
		$query = "DELETE FROM board WHERE seq = " . $this->escaped['seq'];

		$this->db->query($query);
		return $this->db->affected_rows;
	}

	function delete_multiple()
	{
		$query = "DELETE FROM board WHERE seq in (" . implode(', ', $this->escaped['seqs']) . ")";

		$this->db->query($query);
		return $this->db->affected_rows;
	}
}
