<?php namespace pg_local_content; ?>
<?php include(dirname(__FILE__) . "/template.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title><?php echo $templ["title"]; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="js/jquery_1_9_1.js"></script>
    <!--<link rel="stylesheet" href="js/magnific-popup.css" />-->
    
  </head>
    
  <body> 
    <a id="pg-rachel" href="/"></a>

<?php
    $dir = dirname(__FILE__);  
    exec("df {$dir}", $exec_out, $exec_err);
    $str = rtrim($exec_out[1]);
    $pieces = preg_split('/\s+/', $str);
    $avail = $pieces[sizeof($pieces) - 3]; // in KB   
    $availBytes = $avail * 1000;
?>      
<?php
	$delete_link = "";
	if (admin_logged_in()) {
	$delete_link = "<a href='#' class='pglc_delete'>[{$templ['delete']}]</a>";
        echo "                 
            <img src='images/film.png' id='logo' alt=''>
            <h1 style='padding-top: 4px;'>{$templ['title']}</h1>
            <p style='margin-top:-16px;'>{$templ['upload_your_content']}</p>

            <form action='upload.php' method='post' enctype='multipart/form-data'>
                {$templ['file_to_upload']}:<input type='file' name='filename' id='fileToUpload'>
            <br>{$templ['content_type']}:
            <select name='type' id='typeToUpload'>
            <option value='interactive'>{$templ['dynamic_type']}</option>
            <option value='static' selected>{$templ['static_type']}</option>
            </select>
                <br><br><input type='submit' value='{$templ['upload']}' name='submit'>
            </form>
            <div style='clear:both'></div>
            ";
	} else {
        echo "<div id='info'>
            <img src='{$templ["img_uri"]}' alt='comisariada' />
            <h1>{$templ["title"]}</h1>
            <p>{$templ["description"]}</p>  
            </div>";
	}   
	echo "<div style='clear:both'></div>";
    
    echo "<div class=\"section\">";
    echo "<h2 id=\"interactive\">{$templ['dynamic_types']}</h2>";
    echo "<table>";
	$list = glob("./lc-interactive/*", GLOB_NOSORT);
    foreach($list as $item) {
            $name = pathinfo($item, PATHINFO_BASENAME);
            echo "<tr><td><a href='{$item}' class='pglc_filename'>{$name}</td>\n"; 
            echo "<td style='text-align:right'>[" . filesize($item)/1000 . " KB] {$delete_link}</td>\n";
            echo "</tr>\n";
    }    
	echo "</table>";
	echo "</div>";


	echo "<div class=\"section\">";
    echo "<h2 id=\"static\">{$templ['static_types']}</h2>";
    echo "<table>";
    $list = glob("./lc-static/*", GLOB_NOSORT);
    foreach($list as $item) {
            $name = pathinfo($item, PATHINFO_BASENAME);
            echo "<tr><td><a href='{$item}' class='pglc_filename'>{$name}</td>\n"; 
         echo "<td style='text-align:right'>[" . filesize($item)/1000 . " KB] {$delete_link}</td>\n";
            echo "</tr>\n";
    } 
    echo "</table>";
    echo "</div>";
    echo "<div style='text-align:center;'>";
    echo "<button id='pglc_finished'>{$templ['finished']}</button>";
    echo "</div>";

?>      
<?php
function admin_logged_in() {
	if(isset($_COOKIE['rachel-auth']) && $_COOKIE['rachel-auth'] == "admin") { return true; }
	    else { return false; }
}
?>
      
</body>
</html>

<script>

$(document).ready(function() {
    //binds to onchange event of your input field
    $('#fileToUpload').bind('change', function() {
      //this.files[0].size gets the size of your file in bytes.
         if (this.files[0].size > 32000000) { // in Bytes // 32MB
             alert('<?php echo "{$templ['jq_filesize_p1']} "; ?>' + " 32 MB)");
             $(this).wrap('<form>').closest('form').get(0).reset();
             $(this).unwrap();
         } else if ( (<?php echo $availBytes; ?> - this.files[0].size) < 50000000) { // Make sure to leave 50MB available
             alert('<?php echo "{$templ['no_upload_space']}"; ?>');
             $(this).wrap('<form>').closest('form').get(0).reset();
             $(this).unwrap();
         }
      
    });
    
    $('.pglc_delete').on('click', function(e) {
      e.preventDefault();
      var fullname = $(this).closest('tr').find('.pglc_filename').attr('href');
      var filename= fullname.split('/').pop()
      if (confirm('<?php echo "{$templ['delete']}? "; ?>' + filename)) {
          var row = $(this).closest('tr');
           $.ajax({
                url: "remove.php?name="+fullname,
                success: function(data){
                    //alert("Delete success!");
                    row.hide();
                },
                error: function(){
                    alert('<?php echo "{$templ['error']}"; ?>');
                }
            });
        }
    });

   $('#pglc_finished').on('click', function(e) {
      e.preventDefault();
      window.location.replace("/");
   });

});    
    
</script>
