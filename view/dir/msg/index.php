<!DOCTYPE html>

<meta charset='utf-8'>

<p><a href='/dir/msg/form'>New Message</a></p>

<p><a href='/dir/msg/index/1/2/keyword'>Search</a></p>

<p>
<?php foreach ($msg_list as $row):?>

	<?php echo $row->msgseq ?>
	|
	<?php echo $row->msgtext ?>
	|
	<a href='/dir/msg/view/<?php echo $row->msgseq?>'>View</a>
	|
	<a href='/dir/msg/form/<?php echo $row->msgseq?>'>Update</a>
	|
	<a href='/dir/msg/exec_get/<?php echo $row->msgseq?>'>Delete</a>
	<br>

<?php endforeach ?>
</p>

Pagination..
