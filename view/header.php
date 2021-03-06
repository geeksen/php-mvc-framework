<!DOCTYPE html>

<html>
<head>
  <title>php-mvc-framework</title>
  <meta charset='utf-8'>
  <meta name='viewport' content='initial-scale=1'>
  <style type='text/css'>
    body {
      background-color:#fff; color:#666;
      font-size:.8em; font-family:tahoma, geneva, sans-serif;
    }

    .root { width:770px }

    .left { float:left }
    .right { float:right }
    .clear { clear:both }

    a { color:#06c; text-decoration:none }
    a:hover { color:#06c; text-decoration:underline }

    h2 { color:#666; font-weight:normal }

    form { clear:both; margin:0 }
    fieldset {
      border:1px solid #eee; padding-left:1.5em; padding-bottom:1.5em
    }
    label {
      margin-top:1.2em; margin-bottom:.2em; display:block; font-weight:bold
    }

    input { font-family:tahoma, geneva, sans-serif }
    .input-text { width:400px; }
    select { font-family:tahoma, geneva, sans-serif }

    textarea {
      width:740px; height:300px; font-size:1em;
      font-family:tahoma, geneva, sans-serif
    }

    .nav-top { margin-bottom:.5em }
    .nav-bottom { margin-top:.5em }

    table { width:100%; border:1px solid #ddd; border-collapse:collapse }
    .hover tr:hover { background-color:#eee }

    th {
      background-color:#eee;
      border:1px solid #ddd; border-collapse:collapse; padding:.5em
    }
    td { border:1px solid #ddd; border-collapse:collapse; padding:.5em }
    .th-fixed-width { width:150px }

    .pagination {
       border-bottom:1px solid #ddd;
       margin-top:.5em; padding-bottom:.8em; text-align:center
    }
    .history_go { border:0; background-color:#fff; color:#06c }
  </style>
</head>

<body>
<div class='root'>

<h2 class='left'><?php echo $h2_title ?></h2>
