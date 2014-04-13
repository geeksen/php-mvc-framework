<?php

class shard
{
	var $config = array
	(
		'count' => 1,
		'format' => '%d',
	);

	function postfix($shard_key)
	{
		return sprintf($this->config['format'], ($shard_key % $this->config['count']));
	}
}
