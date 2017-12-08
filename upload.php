<?php namespace pg_local_content; ?>
<?php ob_start(); ?>
<?php include(dirname(__FILE__) . "/template.php"); ?>
<?php

// TODO: make explicit allowed file types?
// allowable static files: pdf, png, jpg, svg
// allowable interactive files: swf, mp4

// TODO: disallow dangerous file types and names

// TODO: improve error reporting

if (isset($_FILES['filename']['name'])) {

     $filename = $_FILES['filename']['name'];
     $tmp_filename = $_FILES['filename']['tmp_name'];
     $file_extn = pathinfo($filename,PATHINFO_EXTENSION);
     $type=$_POST["type"];
     if ($type=="interactive") {
	   $target_dir = "lc-interactive";
     } elseif($type=="static") {
	   $target_dir = "lc-static";
     }
     $new_filename = str_replace(" ", "_", $filename);
     $target_file = $target_dir . "/" . $new_filename;
    
    // check for safe file types and names
    // TODO: make this more sophisticated
     if ($file_extn == "php") {
	   $errors["file_type"] = $filename . " - " . $templ["forbidden_type"];
     } else if ((substr($new_filename, 0, 1) == ".") || (substr($new_filename, 0, 1) == ' ') || 
               (substr($new_filename, 0, 1) == "-") || (substr($new_filename, 0, 1) == '_')
               ) {
        $errors["file_type"] = $filename . " - " . $templ["forbidden_first_char"];
     } 
    
    // Check if file already exists
    if (file_exists($target_file)) {
        $errors["exists"] = $filename . " - " . $templ["forbidden_same_name"];
    }

    // Check file size
    /*  $filesize = $_FILES["filename"]["size"];
    if ($filesize > 24000) { // in KB (24MB)
        $errors["img_size"] = $filename . " " . $templ['large_file'] ;
    }*/
    
     if (empty($errors)) {
         exec("mv ". $tmp_filename . " " . $target_file);
     }
    
}

redirect("index.php");
ob_end_flush();


function redirect( $redirect ) {
    header( 'Location: ' . $redirect );
    die;
}     

?>
