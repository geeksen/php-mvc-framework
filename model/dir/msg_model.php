<?php

class msg_model extends model
{
	var $fields = array
	(
		1 => 'msgseq',
		2 => 'msgtext',
	);

	function __construct(&$db, &$request)
	{
		parent::__construct($db, $request);
	}

	function select_info()
	{
		$result = $this->db->query('SELECT * FROM msg WHERE msgseq = ' . $this->escaped['msgseq'] . ' LIMIT 0, 1');
		return $result->fetch_object();
	}

	function select_list()
	{
		$query = "SELECT * FROM msg JOIN (SELECT msgseq FROM msg ORDER BY msgseq DESC LIMIT " . (($this->escaped['page'] - 1) * $this->escaped['per_page']) . ", " . $this->escaped['per_page'] . ") T ON msg.msgseq = T.msgseq ORDER BY msg.msgseq DESC";
		if (true === array_key_exists($this->escaped['field'], $this->fields) && '' != $this->escaped['keyword'])
		{
			$query = "SELECT * FROM msg JOIN (SELECT msgseq FROM msg WHERE " . $this->fields[$this->escaped['field']] . " LIKE '%" . $this->escaped['keyword'] . "%'  ORDER BY msgseq DESC LIMIT " . (($this->escaped['page'] - 1) * $this->escaped['per_page']) . ", " . $this->escaped['per_page'] . ") T ON msg.msgseq = T.msgseq ORDER BY msg.msgseq DESC";
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
		$query = 'SELECT count(*) as msg_count FROM msg';
		if (true === array_key_exists($this->escaped['field'], $this->fields) && '' != $this->escaped['keyword'])
		{
			$query = "SELECT count(*) as msg_count FROM msg WHERE " . $this->fields[$this->escaped['field']] . " LIKE '%" . $this->escaped['keyword'] . "'";
		}

		$result = $this->db->query($query);
		$row = $result->fetch_object();

		return $row->msg_count;
	}

	function insert()
	{
		$this->db->query("INSERT INTO msg (msgtext, inserttime, updatetime) VALUES ('" . $this->escaped['msgtext'] . "', NOW(), NOW())");
		return $this->db->affected_rows;
	}



	function update()
	{
		$this->db->query("UPDATE msg SET msgtext = '" . $this->escaped['msgtext'] . "', updatetime = NOW() WHERE msgseq = " . $this->escaped['msgseq']);
		return $this->db->affected_rows;
	}

	function delete()
	{
		$this->db->query("DELETE FROM msg WHERE msgseq = " . $this->escaped['msgseq']);
		return $this->db->affected_rows;
	}
}
