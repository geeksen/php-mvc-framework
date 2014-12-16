
<form id='form_search' action='/dir/zipcode/index'>
<div class='nav-top'>
  <select name='field'>
    <option value='0' <?php if (0 == $field) { echo ' selected'; } ?>><?php echo 'House Number' ?></option>
    <option value='1' <?php if (1 == $field) { echo ' selected'; } ?>><?php echo 'Road Name' ?></option>
  </select>
  <input type='text' name='keyword' value='<?php echo $keyword ?>'>
  <input type='submit' value='Search'>
</div>
</form>

<table class='hover'>
<?php
if (0 == $field)
{
?>
<tr>
        <th>Zipcode</td>
        <th>Si-Do</th>
        <th>Gu-Gun</th>
        <th>Dong</th>
        <th>Ri</th>
        <th>Number</th>
        <th>Building</th>
        <th>Select</th>
</tr>
<?php
}
else if (1 == $field)
{
?>
<tr>
        <th>Si-Do</th>
        <th>Gu-Gun</th>
        <th>Road Name</th>
        <th>(Dong)</th>
        <th>Select</th>
</tr>
<?php
}

$file = $this->upload_path . '/zipcode/zipcode_old.csv';
if (1 == $field) { $file = $this->upload_path . '/zipcode/zipcode_new.csv'; }

$zipcode_count = 0;
$display_count = 0;
if ('' != $keyword && false !== ($handle = fopen($file, 'r')))
{
        while (false !== ($line = fgets($handle, 1024)))
        {
                if (false === strpos($line, $keyword))
                {
                        continue;
                }

                ++$zipcode_count;
                $address = explode(',', $line);

                if ($zipcode_count > ($per_page * ($page - 1)) && ++$display_count <= $per_page)
                {
                        if (0 == $field)
                        {
?>
<tr>
        <td><?php echo $address[0] ?></td>
        <td><?php echo $address[1] ?></td>
        <td><?php echo $address[2] ?></td>
        <td><?php echo $address[3] ?></td>
        <td><?php echo $address[4] ?></td>
        <td><?php echo $address[5] ?></td>
        <td><?php echo $address[6] ?></td>
        <td>Select</td>
</tr>
<?php
                        }
                        else if (1 == $field)
                        {
?>
<tr>
        <td><?php echo $address[0] ?></td>
        <td><?php echo $address[1] ?></td>
        <td><?php echo $address[2] ?></td>
        <td><?php if ('' != $address[3]) { echo '(' . $address[3] . ')'; } ?></td>
        <td>Select</td>
</tr>
<?php
                        }
                }
        }
        fclose($handle);
}
?>
</table>

<div>
  <div class='pagination'></div>
</div>

<div class='nav-bottom'>
  <span class='right'>

<?php
$page_count = (intval(($zipcode_count - 1) / $per_page) + 1);
?>

<?php if ($page > 1): ?>
    <a href='/dir/zipcode/index/<?php echo ($page - 1) . '/' . $field . '/' . $keyword ?>'>Prev</a> |
<?php else: ?>
    Prev |
<?php endif ?>

<?php if ($page < $page_count): ?>
    <a href='/dir/zipcode/index/<?php echo ($page + 1) . '/' . $field . '/' . $keyword ?>'>Next</a>
<?php else: ?>
    Next
<?php endif ?>

  </span>
</div>

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
    window.location.href = '/dir/zipcode/index/1/' + this.field.value + '/' + this.keyword.value;
    return false;
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
      a_first.setAttribute('href', '/dir/zipcode/index/1/' + field + '/' + keyword);
      a_first.appendChild(document.createTextNode('1'));
      pagination.appendChild(a_first);
      pagination.appendChild(document.createTextNode(' \u00a0'));

      var a_prev = document.createElement('a');
      a_prev.setAttribute('href', '/dir/zipcode/index/' + (i - 1) + '/' + field + '/' + keyword);
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
        a_page.setAttribute('href', '/dir/zipcode/index/' + i + '/' + field + '/' + keyword);
        a_page.appendChild(document.createTextNode(i));
        pagination.appendChild(a_page);
        pagination.appendChild(document.createTextNode(' \u00a0'));
      }
    }

    if (i <= page_count) {
      var a_next = document.createElement('a');
      a_next.setAttribute('href', '/dir/zipcode/index/' + i + '/' + field + '/' + keyword);
      a_next.appendChild(document.createTextNode('>>'));
      pagination.appendChild(a_next);
      pagination.appendChild(document.createTextNode(' \u00a0'));

      var a_last = document.createElement('a');
      a_last.setAttribute('href', '/dir/zipcode/index/' + page_count + '/' + field + '/' + keyword);
      a_last.appendChild(document.createTextNode(page_count));
      pagination.appendChild(a_last);
      pagination.appendChild(document.createTextNode(' \u00a0'));
    }
  }

  Pagination();
}
</script>

