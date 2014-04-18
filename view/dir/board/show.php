
<span class='right'><a href='/dir/user/signout'>SignOut</a></span>

<form method='post'>
<table>
  <tr>
    <th class='th-fixed-width'>Title</th>
    <td><?php echo $title ?></td>
  </tr>
  <tr>
    <td colspan='2'><?php echo $content ?></td>
  </tr>
  <tr>
    <th>File1</th>
    <td><?php echo $file1 ?></td>
  </tr>
  <tr>
    <th>File2</th>
    <td><?php echo $file2 ?></td>
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
  <span class='right'>
    <input type='button' name='list' class='history_go' value='List'>
  </span>
</div>

</form>
</div>

<script type='text/javascript'>
window.onload = function() {
  var list_buttons = document.getElementsByName('list');
  for (var i = 0; i < list_buttons.length; i++) {
    list_buttons[i].onclick = function() {
      history.go(-1);
    }
  }
}
</script>
