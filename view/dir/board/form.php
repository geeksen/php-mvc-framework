
<span class='right'><a href='/dir/user/signout'>SignOut</a></span>

<form method='post' action='/dir/board/post' enctype='multipart/form-data'>
<table>
  <tr>
    <th class='th-fixed-width'>Title</th>
    <td>
      <input type='hidden' name='seq' value='<?php echo $seq ?>'>
      <input type='hidden' name='page' value='<?php echo $page ?>'>
      <input type='text' name='title' class='input-text' value='<?php echo $title ?>'>
    </td>
  </tr>
  <tr>
    <td colspan='2'><textarea name='content' rows='20' cols='80'><?php echo $content ?></textarea>
    </td>
  </tr>
  <tr>
    <th>File1</th>
    <td>
      <input type='file' name='file1'>
<?php if ('' != $file1): ?>
        <a href='/upload/<?php echo substr($inserttime, 0, 7) ?>/<?php echo $file1 ?>'><?php echo $file1 ?></a>
<?php endif ?>
    </td>
  </tr>
  <tr>
    <th>File2</th>
    <td>
      <input type='file' name='file2'>
<?php if ('' != $file2): ?>
        <a href='/upload/<?php echo substr($inserttime, 0, 7) ?>/<?php echo $file2 ?>'><?php echo $file2 ?></a>
<?php endif ?>
    </td>
  </tr>
  <tr>
    <th>UserID</th>
    <td><?php echo $userid ?></td>
  </tr>
  <tr>
    <th>InsertTime</th>
    <td><?php echo $inserttime ?></td>
  </tr>
  <tr>
    <th>UpdateTime</th>
    <td><?php echo $updatetime ?></td>
  </tr>
</table>

<div class='nav-bottom'>
  <input type='submit' value='Submit'>
  <span class='right'>
    <input type='button' name='cancel' class='history_go' value='Cancel'>
  </span>
</div>

</form>
</div>

<script type='text/javascript'>
window.onload = function() {
  var cancel_buttons = document.getElementsByName('cancel');
  for (var i = 0; i < cancel_buttons.length; i++) {
    cancel_buttons[i].onclick = function() {
      history.go(-1);
    }
  }
}
</script>
