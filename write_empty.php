<?php namespace pg_local_content; ?>
<?php
   $json = $_REQUEST['json'];

   /* sanity check */
   if (json_decode($json) != null)
   {
     $file = fopen('emptyjson.txt','w+');
     $json_pretty = json_encode(json_decode($json), JSON_PRETTY_PRINT);
     fwrite($file, $json_pretty);
     fclose($file);
   }
   else
   {
     // user has posted invalid JSON, handle the error 
   }
?>
