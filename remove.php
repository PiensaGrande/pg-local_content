<?php namespace pg_local_content; ?>
<?php
  if(isset($_REQUEST['name'])) {
    $name = $_REQUEST['name'];
    return unlink($name);
  }
?>
