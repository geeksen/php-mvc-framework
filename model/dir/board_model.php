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

	function __construct(&$db)
	{
		parent::__construct($db);
	}

	function select_info(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('SELECT * FROM board WHERE seq = ? LIMIT 0, 1', array
		(
			intval($escaped['seq']),
		));

		$this->num_rows = $this->result->num_rows;
		return $this->result->fetch_object();
	}

	function select_list(&$request)
	{
		$escaped = $this->real_escape_array($request);

		if (true === array_key_exists($escaped['field'], $this->field_columns) && '' != $escaped['keyword'])
		{
			$this->query('SELECT * FROM board JOIN (SELECT seq FROM board WHERE ' . $this->field_columns[$escaped['field']] . ' LIKE ? ORDER BY seq DESC LIMIT ?, ?) T ON board.seq = T.seq ORDER BY board.seq DESC', array
			(
				'%' . $escaped['keyword'] . '%',
                                ($escaped['page'] - 1) * $escaped['per_page'],
                                intval($escaped['per_page']),
			));
		}
		else
		{
			$this->query('SELECT * FROM board JOIN (SELECT seq FROM board ORDER BY seq DESC LIMIT ?, ?) T ON board.seq = T.seq ORDER BY board.seq DESC', array
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
			$this->query('SELECT count(*) as board_count FROM board WHERE ' . $this->field_columns[$escaped['field']] . ' LIKE ?', array
			(
				'%' . $escaped['keyword'] . '%',
			));
		}
		else
		{
			$this->query('SELECT count(*) as board_count FROM board', array
			(

			));
		}

		$row = $this->result->fetch_object();
		return $row->board_count;
	}

	function insert_info(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('INSERT INTO board (title, content, file1, file2, userid, inserttime, updatetime) VALUES (?, ?, ?, ?, ?, NOW(), NOW())', array
		(
			$escaped['title'],
			$escaped['content'],
			$escaped['file1'],
			$escaped['file2'],
			$escaped['sess_userid'],
		));

		return $this->affected_rows;
	}

	function insert_list(&$request)
	{
		$escaped = $this->real_escape_array($request);
		$query = "INSERT INTO board (title, content, file1, file2, userid, inserttime, updatetime) VALUES";

		$count = count($escaped['seqs']);
		for ($i = 0; $i < $count; $i++)
		{
			$query .= "('" . $escaped['titles'][$i] . "', '" . $escaped['contents'][$i] . "', '" . $escaped['file1s'][$i] . "', '" . $escaped['file2s'][$i] . "', '" . $escaped['sess_userids'][$i] . "', NOW(), NOW())";

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

		$this->query('UPDATE board SET title = ?, content = ?, file1 = ?, file2 = ?, userid = ?, updatetime = NOW() WHERE seq = ?', array
		(
			$escaped['title'],
			$escaped['content'],
			$escaped['file1'],
			$escaped['file2'],
			$escaped['sess_userid'],
			intval($escaped['seq']),
		));

		return $this->affected_rows;
	}

	function update_list(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$query = "UPDATE board SET ";
		$count = count($escaped['seqs']);

		foreach (array('titles', 'contents', 'file1s', 'file2s') as $field)
		{
			$query .= $field . " = CASE seq";
			for ($i = 0; $i < $count; $i++)
			{
				$query .= " WHEN " . $escaped['seqs'][$i] . " THEN '" . $escaped[$field][$i] . "'";
			}
			$query .= " END, ";
		}

		$query .= "userid = '" . $escaped['sess_userid'] . "', updatetime = NOW() WHERE seq in (" . implode(', ', $escaped['seqs']) . ")";

		$this->query($query);
		return $this->affected_rows;
	}

	function delete_info(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$this->query('DELETE FROM board WHERE seq = ? AND userid = ?', array
		(
			$escaped['seq'],
			$escaped['sess_userid'],
		));

		return $this->affected_rows;
	}

	function delete_list(&$request)
	{
		$escaped = $this->real_escape_array($request);

		$query = "DELETE FROM board WHERE seq in (" . implode(', ', $escaped['seqs']) . ") AND userid = '" . $escaped['sess_userid'] . "'";

		$this->query($query);
		return $this->affected_rows;
	}
}
