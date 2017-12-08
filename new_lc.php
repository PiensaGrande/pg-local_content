<?php namespace pg_local_content; ?>
<?php include dirname(__FILE__) . "/template.php"; ?>
<?php require_once(dirname(__FILE__) . "/validation_functions.php"); ?>
<?php

$errors = array();

// disk space check
$dir = dirname(__FILE__);  
exec("df {$dir}", $exec_out, $exec_err);
$str = rtrim($exec_out[1]);
$pieces = preg_split('/\s+/', $str);
$avail = $pieces[sizeof($pieces) - 3]; // in KB   
$availBytes = $avail * 1000; // in bytes
$title = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Process the form  
    $hasUpload = 0;
    $img_uri = "";
    if(isset($_REQUEST['image_sel'])) {
	   $img_uri = $_REQUEST['image_sel'];
    } elseif (isset($_FILES['filename']['name']) && !empty($_FILES['filename']['name']) ) {
        
        // php.ini variables of interest:
        // upload_max_filesize >= 30M
        // post_max_size >= 30M
        
        // Deal with uploaded file if there is one
        $hasUpload = 1;
        
        $filename = $_FILES['filename']['name'];
        $tmp_filename = $_FILES['filename']['tmp_name'];
        $file_extn = pathinfo($filename,PATHINFO_EXTENSION);
        
        // Check if image file is an actual image or fake image
        $check = getimagesize($tmp_filename);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . "."; // debug
        } else {
            //$errors["img_file"] = $filename . " is not an image."; // en
	    //$errors["img_file"] = $filename . " no es un imagen."; // es
            $errors["img_file"] = "{$filename} {$templ['not_image']}."; 
        }

        // Allow certain file formats
        if($file_extn != "jpg" && $file_extn != "png" && $file_extn != "jpeg"
        && $file_extn != "gif" && $file_extn != "svg" ) {
            //$errors["img_type"] = $filename . " is not JPG, JPEG, PNG, SVG or GIF. Only these file types are allowed"; // en
            //$errors["img_type"] = $filename . " no es de tipo JPG, JPEG, PNG, SVG o GIF"; // es
	    $errors["img_type"] = "{$filename} {$templ['not_img_type']}.";
        }
        
        // TODO: check for disallowed characters in uploaded filename
        // uploadCheck_spec_chars($filename);
        
        // PHP check file size (also done in jquery before submit)
        $filesize = $_FILES["filename"]["size"];
        if ($filesize > 500000) { // in bytes (500KB)
            //$errors["img_size"] = $filename . " is too large."; // en 
            //$errors["img_size"] = $filename . " es demasiado grande."; // es
            $errors["img_size"] = "{$filename} {$templ['large_file']}.";
	}
          
    }
    
    if(isset($_REQUEST['title'])) {
           $title = $_REQUEST['title'];
    }
    if(isset($_REQUEST['description'])) {
           $description = $_REQUEST['description'];
    }
    
    // validations
    $required_fields = array("title", "description"); 
    validate_presences($required_fields);
    
    // escape quotation marks in the description (saved in json file)
    $description = str_replace('"', '\"', $description);
    
    uploadCheck_spec_chars($title);
    $dirname = text_to_filename($title); // replace spaces with underscores
    $dir = dirname(__FILE__) . "/../" . $dirname; // to create module directory
    
    // Check if directory already exists
    // TODO: would be nice to do an ealier check in jquery as well
    if ( (is_dir($dir) || file_exists($dir)) && ($title != "") ) {
        $errors["unique_title"] = "{$templ['just_module']} {$title} {$templ['unique_title']}."; // es
    }
    
    if (empty($errors)) {
        // if no errors, proceed to create new module
        
        // TODO: maybe use php functions instead
        exec("mkdir " . $dir);
        exec("mkdir " . $dir . "/lc-static");
        exec("mkdir " . $dir . "/lc-interactive");
        exec("mkdir " . $dir . "/images");
        exec("mkdir " . $dir . "/js");
        exec("mkdir " . $dir . "/uploads");
        exec("cp *.php " . $dir . "/.");
	exec("cp *.png " . $dir . "/.");
	exec("cp *.css " . $dir . "/.");
	exec("cp *.txt " . $dir . "/.");
        exec("cp -r images/* " . $dir . "/images/.");
        exec("cp -r js/* " . $dir . "/js/.");
        exec("rm " . $dir . "/new_lc.php");
	exec("rm " . $dir . "/rachel-admin.php");
	exec("rm " . $dir . "/template.php"); // we will create a new template.php file for this new module
        
        if ($hasUpload == 1) {
            $target_file = $dir . "/uploads/" . basename($filename);
            exec("mv ". $tmp_filename . " " . $target_file);   
            // TODO maybe need to check success of upload. What to do in this case?
//            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
//            } else {
//                echo "Sorry, there was an error uploading your file.";
//            } 
              $img_uri = "uploads/" .  basename($filename);
        }

        if ($img_uri == "") { $img_uri = "logo.png"; }

        // open and write to template.php
        $fp = fopen($dir . "/template.php", 'w');
        fwrite($fp, '<?php namespace pg_local_content; ?>'.PHP_EOL);
        fwrite($fp, '<?php require_once($_SERVER["DOCUMENT_ROOT"] .  "/admin/common.php"); ?>'.PHP_EOL); // currently needed for getlang
	fwrite($fp, '<?php $lang1 = getlang();'.PHP_EOL);
	fwrite($fp, '$templ = array();'.PHP_EOL);
	fwrite($fp, '$tmpl_dir = str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__FILE__));'.PHP_EOL);
	fwrite($fp, 'include dirname(__FILE__) . "/messages.php";'.PHP_EOL);
	fwrite($fp, '$templ["title"] = "' . $title . '";'.PHP_EOL);
	fwrite($fp, '$templ["description"] = "' . $description . '";'.PHP_EOL);
	fwrite($fp, '$templ["img_uri"] = "{$tmpl_dir}/' . $img_uri . '";'.PHP_EOL);
	fwrite($fp, '$templ["index_loc"] = "{$tmpl_dir}/index.php";'.PHP_EOL);
//	fwrite($fp, '$templ["dirname"] = "' . $dirname . '";'.PHP_EOL);
	fwrite($fp, '$templ["dirname"] = basename(__DIR__);'.PHP_EOL);
	fwrite($fp, '$templ["web_path"] = $tmpl_dir;'.PHP_EOL);
	fwrite($fp, '$templ["module_type"] = "local_content";'.PHP_EOL);
	fwrite($fp, '$templ["hide_index"] = "no";'.PHP_EOL);
        fwrite($fp, " ?>" . PHP_EOL);
        fclose($fp);
     
        redirect_to("{$templ['web_path']}/../{$dirname}/index.php");
    }
     
    
} else {
  // This is probably a GET request
} // end: if (isset($_POST['submit']))


function redirect_to($new_location) {
    header("Location: " . $new_location);
    exit;
}

?>

<!-- ---------------------------------------
---- ------------- FORM DISPLAY ------------
---- --------------------------------------- -->


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title><?php echo "{$templ['title']}"?></title>
    <script src="js/jquery_1_9_1.js"></script>
    <link rel="stylesheet" href="js/magnific-popup.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
    
  <body> 
      
    <a id="pg-rachel" href="/"></a> <!-- Rachel top bar to Rachel Home -->
       
      
      
    <div id="content">    
        <div id="lc-preview-module" class="indexmodule">
        <a href="#"><img id="pglc_preview_image" src="logo.png" alt="Your Content Logo"></a>
        <!-- Title and link to your module's index.html -->
        <h2><a href="#" id="pglc_preview_title"><?php echo $templ["pglc_preview_title"];?></a></h2>
        <!-- Description of your module -->
        <p><span id="pglc_preview_description"><?php echo $templ["pglc_preview_description"];?></span>
	<small><a href="#">[<?php echo $templ['create_lesson'];?>]</a></small>
	</p>
        <ul class="quad"> 
            <li><a href="#"><?php echo $templ["lesson"];?> 1</a></li>
            <li><a href="#"><?php echo $templ["lesson"];?> 2</a></li>
            <li><a href="#"><?php echo $templ["lesson"];?> 3</a></li>
            <li><a href="#"><?php echo $templ["lesson"];?> 4</a></li>
            <li><a href="#"><?php echo $templ["lesson"];?> 5</a></li>
            <li><a href="#"><?php echo $templ["lesson"];?> 6</a></li>
            <li><a href="#"><?php echo $templ["lesson"];?> 7</a></li>
            <li><a href="#"><?php echo $templ["lesson"];?> 8</a></li>
        </ul>
        </div>  
    <!--</div>-->
    <div style="clear:both"></div>         
        
    <div class="thumblist" style="width:960px">   
    <h1 style="padding-left:22px;"><?php echo $templ["your_content"]; ?></h1> 
        
        <ol type="1">   
        <li><?php echo $templ["step1"]; ?></li>
        <li><?php echo $templ["step2"]; ?></li>
        <li><?php echo $templ["step3"]; ?></li>
        </ol> 
        
        <?php //echo message(); ?>
        <?php echo form_errors($errors); ?>

        <form id="pg_new_lc_form" action="new_lc.php" method="post" enctype="multipart/form-data">
        <?php echo $templ["just_image"];?>: 
	    <select id="pglc_preview_pic" name="image_sel">
    			<option value="" disabled selected><?php echo $templ["select_sample"];?></option>
    			<option value="images/preview/atom.png">Atom</option>
    			<option value="images/preview/binary.jpg">Binary</option>
                <option value="images/preview/blocks.png">Blocks</option>
    			<option value="images/preview/brain.jpg">Brain</option>
                <option value="images/preview/dog.jpg">Dog</option>
                <option value="images/preview/earth.png">Earth</option>
    			<option value="images/preview/einstein.jpg">Einstein</option>
                <option value="images/preview/fibonacci.png">Fibonacci</option>
                <option value="images/preview/kids.png">Kids</option>
                <option value="images/preview/kitten.jpg">Kitten</option>
                <option value="images/preview/life.png">Life</option>
                <option value="images/preview/math.png">Math</option>
    			<option value="images/preview/origami.png">Origami</option>
                <option value="images/preview/paint.png">Paint</option>
                <option value="images/preview/rubiks.png">Rubiks</option> 
           		<option value="images/preview/school.png">School</option>
    			<option value="images/preview/study.jpg">Study</option>
                <option value="images/preview/teacher.png">Teacher</option>
    			<option value="images/preview/tree.jpg">Tree</option>
                <option value="images/preview/writing.png">Writing</option>
             </select>
		<?php echo $templ["choose_image"];?>:<input type="file" name="filename" id="pglc_upload-img">
            <p><?php echo $templ["just_title"];?>:<input id="title" type="text" size="35%" name="title" placeholder="<?php echo $templ['pglc_preview_title'];?>" value="<?php echo $title; ?>"></p>
            <p><?php echo $templ["just_description"];?>:<input id="description" type="text" size="85%" name="description" placeholder="<?php echo $templ['pglc_preview_description']; ?>" value="<?php echo $description; ?>" ></p>
            <input id="makeButton" type="submit" name="submitButton" value="<?php echo $templ['make_module'];?>" />
        </form>
    </div> 
    </div>   <!-- end content -->
    </body>
    
    
<script>
$( document ).ready(function (){
	$('#pglc_preview_pic').change(function () {
        	var sel_val = $('#pglc_preview_pic').val();
        	sel_val = "<?php echo $templ['web_path']; ?>/" + sel_val;
        	$('#pglc_preview_image').attr("src", sel_val);
        	$('#pglc_upload-img').val("");
	});

	$('#description').change(function () {
        	$('#pglc_preview_description').html($(this).val());
	});

	$('#title').change(function () {
            var str = $('#title').val();
            if(/^[a-zA-Z0-9-_ ]*$/.test(str) == false) {
                // we create a directory with the same name as the module title, so disallow dangerous characters
                alert('<?php echo "{$templ['allowed_chars_title']}."; ?>'); 
                $('#title').focus().val("");
                $('#pglc_preview_title').text("<?php echo $templ['pglc_preview_title']; ?>");
            } else {
                $('#pglc_preview_title').text($(this).val());
            }
	});

	$('#pglc_upload-img').change(function () {
        
	var maxfilesize = 500000; // in Bytes // 500 KB
        if (this.files[0].size > maxfilesize) { // in Bytes // 500 KB
             alert('<?php echo "{$templ['jq_filesize_p1']} "; ?>' + (maxfilesize / 1000) + '<?php echo " {$templ['jq_filesize_p2']} "; ?>' + (this.files[0].size / 1000) + " KB.)");
             $(this).wrap('<form>').closest('form').get(0).reset();
             $(this).unwrap();
             $('#pglc_preview_image').attr("src", "<?php echo $templ['web_path']; ?>/logo.png");
             $('#pglc_preview_pic').val("");
         } else if ( (<?php echo $availBytes; ?> - this.files[0].size) < 50000000) { // in Bytes // 50MB
             alert('<?php echo "{$templ['no_upload_space']}."; ?>');
             $(this).wrap('<form>').closest('form').get(0).reset();
             $(this).unwrap();
         } else {
            var reader = new FileReader();
            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("pglc_preview_image").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
		    $('#pglc_preview_pic').val("");
         }
	});

	function writeEmptyHandler(data) {
       	 	$('#pg_new_lc_form').submit();
	}

	function buildEmptyHandler(data) {
        		$.ajax({
			  	type: "POST",
  				url: "write_empty.php",
 				data: { json: JSON.stringify(data) },
            			success: writeEmptyHandler
        		});
	}

	$('#makeButton').on('click', function (e) {
    		e.preventDefault();
		if (!$('#title').val() || !$('#description').val()) {
			var alert_str = "";
			if (!$('#title').val()) {
				alert_str += '<?php echo "{$templ['just_title']} {$templ['required']}. "; ?>'; 
			}
			if (!$('#description').val()) {
                                alert_str += '<?php echo "{$templ['just_description']} {$templ['required']}. "; ?>'; 
                        }
			alert(alert_str);
		} else { // form looks ok to continue

			// sadly jquery generates error messages by trying to load the images from index.php as well.
			// a solution is to write the parser in php and return just json data here. 
			// since the images don't load, just the requests pass the network, this is being tabled.
			$.get('/index.php', function(modules){
				var data = {title:'', objective:'', criticalPoints:[], ideas:[], groups:[{
        				type:"lc-interactive",
        				name:'<?php echo "{$templ['local_content']} - {$templ['dynamic_types']}"; ?>',
        				logoUri:"<?php echo $templ['web_path']; ?>/images/clapper.png",
        				items:[]},
        				{
        				type:"lc-static",
        				name:'<?php echo "{$templ['local_content']} - {$templ['static_types']}"; ?>',
        				logoUri:"<?php echo $templ['web_path']; ?>/images/printer.png",
        				items:[]}]}; 
				var html = $(modules);  // if logoUri were a data element we would be able to string replace all img tags before loading here.
    				$('.indexmodule', html).each(function() {
					var element = {};
					var moduletype = $(this).data('moduletype');
					var lessondisplay = $(this).data('lessondisplay');
					if (lessondisplay !=  "hidden") {
						if (moduletype == null || moduletype == "" || typeof(moduletype) == 'undefined') {
                                                	element.type = "module";
                                        	} else {
                                                	element.type = moduletype;
                                        	}
                                        	element.name = $(this).find('h2 a:first').text();
                                        	element.logoUri = "/" + $(this).find('img').attr('src');
                                        	element.items = [];
                                        	data.groups.push(element);
					}
					
				});
				buildEmptyHandler(data);
			
			});
		
		}
	});
    
});
</script>
</html>
