<?php

class shard
{
	var $shard_count = 1;
	var $postfix_format = '%d';

	function postfix($shard_key)
	{
		return sprintf($this->postfix_format, ($shard_key % $this->shard_count));
	}
}
