
<span class='right'><a href='/dir/user/signout'>SignOut</a></span>

<form id='form_search' action='/dir/board/index'>
<div class='nav-top'>
  <select name='field'>
<?php foreach ($field_columns as $field_key => $field_column): ?>
    <option value='<?php echo $field_key ?>' <?php if ($field == $field_key) { echo ' selected'; } ?>><?php echo $field_names[$field_column] ?></option>
<?php endforeach ?>
  </select>
  <input type='text' name='keyword' value='<?php echo $keyword ?>'>
  <input type='submit' value='Search'>
  <span class='right'>
    <a href='/dir/board/form'>New</a>
  </span>
</div>
</form>

<form id='form_batch' method='post' action='/dir/board/batch'>
<table class='hover'>
  <tr>
    <th>
      <input type='checkbox' name='check_all'>
      Seq.
    </th>
    <th>Title</th>
    <th>UserID</th>
    <th>UpdateTime</th>
    <th>Show | Update | Delete</th>
  </tr>

<?php foreach ($board_list as $row): ?>
  <tr>
    <td>
      <input type='checkbox' name='seqs[]' value='<?php echo $row->seq ?>'>
      <?php echo $row->seq ?>
    </td>
    <td><?php echo $row->title ?></td>
    <td><?php echo $row->userid ?></td>
    <td><?php echo $row->updatetime ?></td>
    <td>
      <a href='/dir/board/show/<?php echo $page ?>/<?php echo $row->seq ?>'>Show</a> |
      <a href='/dir/board/form/<?php echo $page ?>/<?php echo $row->seq ?>'>Update</a> |
      <a href='/dir/board/delete/<?php echo $page ?>/<?php echo $row->seq ?>'>Delete</a>
    </td>
  </tr>
<?php endforeach ?>

</table>

<div>
  <span class='left'><input type='checkbox' name='check_all'></span>
  <div class='pagination'></div>
</div>

<div class='nav-bottom'>
  <input type='submit' name='delete_multiple' value='Delete'>
  <span class='right'>

<?php if ($page > 1): ?>
    <a href='/dir/board/index/<?php echo ($page - 1) . '/' . $field . '/' . $keyword ?>'>Prev</a> |
<?php else: ?>
    Prev |
<?php endif ?>

<?php if ($page < $page_count): ?>
    <a href='/dir/board/index/<?php echo ($page + 1) . '/' . $field . '/' . $keyword ?>'>Next</a>
<?php else: ?>
    Next
<?php endif ?>

  </span>
</div>
</form>

</div>

<script type='text/javascript'>
window.onload = function() {
  if ('function' !== typeof document.getElementsByClassName) {
    document.getElementsByClassName = function(class_name) {
      var elems = new Array();

      var tags = document.getElementsByTagName('*');
      for (var i = 0; i < tags.length; ++i) {
        var classes = ' ' + tags[i].getAttribute('class') + ' ';
        if (-1 != classes.indexOf(class_name)) {
          elems.push(tags[i]);
        }
      }

      return elems;
    }
  }

  document.getElementById('form_search').onsubmit = function() {
    window.location.href = '/dir/board/index/1/' + this.field.value + '/' + this.keyword.value;
    return false;
  }

  var CheckAll = function(checked) {
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; ++i) {
      if ('checkbox' != inputs[i].type) continue;
      inputs[i].checked = checked;
    }
  }

  var checkall_checkboxes = document.getElementsByName('check_all');
  for (var i = 0; i < checkall_checkboxes.length; ++i) {
    checkall_checkboxes[i].onclick = function() {
      CheckAll(this.checked);
    }
  }

  var Pagination = function() {
    var paginations = document.getElementsByClassName('pagination');
    var pagination = paginations[0];

    var form_search = document.getElementById('form_search');
    var field = form_search.field.value;
    var keyword = form_search.keyword.value;

    var page = <?php echo $page ?>;
    var page_count = <?php echo $page_count ?>;
    var link_count = <?php echo $link_count ?>;
    var i = parseInt((page - 1) / link_count) * link_count + 1;

    if (i > link_count) {
      var a_first = document.createElement('a');
      a_first.setAttribute('href', '/dir/board/index/1/' + field + '/' + keyword);
      a_first.appendChild(document.createTextNode('1'));
      pagination.appendChild(a_first);
      pagination.appendChild(document.createTextNode(' \u00a0'));

      var a_prev = document.createElement('a');
      a_prev.setAttribute('href', '/dir/board/index/' + (i - 1) + '/' + field + '/' + keyword);
      a_prev.appendChild(document.createTextNode('<<'));
      pagination.appendChild(a_prev);
      pagination.appendChild(document.createTextNode(' \u00a0'));
    }

    for (var j = 0; i <= page_count && j < link_count; ++i, ++j) {
      if (i == page) {
        var b_page = document.createElement('b');
        b_page.appendChild(document.createTextNode(i));
        pagination.appendChild(b_page);
        pagination.appendChild(document.createTextNode(' \u00a0'));
      }
      else {
        var a_page = document.createElement('a');
        a_page.setAttribute('href', '/dir/board/index/' + i + '/' + field + '/' + keyword);
        a_page.appendChild(document.createTextNode(i));
        pagination.appendChild(a_page);
        pagination.appendChild(document.createTextNode(' \u00a0'));
      }
    }

    if (i <= page_count) {
      var a_next = document.createElement('a');
      a_next.setAttribute('href', '/dir/board/index/' + i + '/' + field + '/' + keyword);
      a_next.appendChild(document.createTextNode('>>'));
      pagination.appendChild(a_next);
      pagination.appendChild(document.createTextNode(' \u00a0'));

      var a_last = document.createElement('a');
      a_last.setAttribute('href', '/dir/board/index/' + page_count + '/' + field + '/' + keyword);
      a_last.appendChild(document.createTextNode(page_count));
      pagination.appendChild(a_last);
      pagination.appendChild(document.createTextNode(' \u00a0'));
    }
  }

  Pagination();
}
</script>
