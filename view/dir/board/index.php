
<span class='right'><a href='/dir/sign/out'>SignOut</a></span>

<form id='form_search' action='/dir/board/index'>
<div class='nav-top'>
  <select name='field'>
<?php foreach ($field_columns as $field_key => $field_column): ?>
    <option value='<?php echo $field_key ?>' <?php if ($field == $field_key) { echo ' selected'; } ?>><?php echo $field_names[$field_column] ?></option>
<?php endforeach ?>
  </select>
  <input type='text' name='keyword'>
  <input type='button' value='Search'>
  <span class='right'>
    <a href='/dir/board/form'>New</a>
  </span>
</div>
</form>

<form id='form_batch' method='post' action='/dir/board/exec_batch'>
<table class='hover'>
  <tr>
    <th>
      <input type='checkbox' name='check_all'>
      Seq.
    </th>
    <th>Title</th>
    <th>UserID</th>
    <th>UpdateTime</th>
  </tr>

<?php foreach ($board_list as $row): ?>
  <tr>
    <td><?php echo $row->seq ?></td>
    <td><?php echo $row->title ?></td>
    <td><?php echo $row->userid ?></td>
    <td><?php echo $row->updatetime ?></td>
  </tr>
<?php endforeach ?>

</table>

<div>
  <span class='left'><input type='checkbox' name='check_all'></span>
  <div class='pagination'>
pagination
<!--
EOT

    $i = int(($page - 1) / $PAGELINK) * $PAGELINK + 1;
    if ($i > $PAGELINK) {
        print " <a href='$script_name?mode=list'>1</a> &nbsp;\n";
        printf " <a href='$script_name?mode=list&amp;page=%d'>", $i - 1;
        print "&lt;&lt;</a> &nbsp;\n";
    }
    for ($j = 0; $i <= $totalpage && $j < $PAGELINK; $i++, $j++) {
        if ($i == $page) {
            print " <b>$i</b> &nbsp;\n";
        }
        else {
            print " <a href='$script_name?mode=list&amp;page=$i'>";
            print "$i</a> &nbsp;\n";
        }
    }
    if ($i <= $totalpage) {
        print " <a href='$script_name?mode=list&amp;page=$i'>";
        print "&gt;&gt;</a> &nbsp;\n";
        print " <a href='$script_name?mode=list&amp;page=$totalpage'>";
        print "$totalpage</a> &nbsp;\n";
    }

    print <<EOT;
-->
  </div>
</div>

<div class='nav-bottom'>
  <input type='submit' value='Delete'>
  <span class='right'>
<!--
EOT

    print " <a href='$script_name?mode=list&amp;page=$page&amp;time=$time'>";
    print "$l{'reload'}</a> |\n";

    if ($page > 1) {
        printf " <a href='$script_name?mode=list&amp;page=%d'>", $page - 1;
        print "$l{'prev'}</a> |\n";
    }
    else { print " $l{'prev'} |\n"; }

    if ($page < $totalpage) {
        printf " <a href='$script_name?mode=list&amp;page=%d'>", $page + 1;
        print "$l{'next'}</a>\n";
    }
    else { print " $l{'next'}\n"; }

    print <<EOT;
-->
  </span>
</div>

</div>

<script type='text/javascript'>
window.onload = function() {
  var CheckAll = function(checked) {
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
      if ('checkbox' != inputs[i].type) continue;
      inputs[i].checked = checked;
    }
  }

  var checkall_checkboxes = document.getElementsByName('check_all');
  for (var i = 0; i < checkall_checkboxes.length; i++) {
    checkall_checkboxes[i].onclick = function() {
      CheckAll(this.checked);
    }
  }
}
</script>
