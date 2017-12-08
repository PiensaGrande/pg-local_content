<?php namespace pg_local_content; ?>
<?php include(dirname(__FILE__) . "/template.php") ?>
<?php require_once(dirname(__FILE__) . "/validation_functions.php") ?>
<?php
	if (isset($_REQUEST["ajaxFilenames"])) {
		$jsons = glob('./*.{json}', GLOB_BRACE);
		$forbiddenFiles = array();
		foreach($jsons as $j) {
			$forbiddenFiles['files'][] = basename($j,'.json') ;
		}
	header('Content-Type: application/json');
	echo json_encode($forbiddenFiles);
	exit;
	}

$header = "
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html>
  <head>
    <meta content='text/html; charset=UTF-8' http-equiv='content-type'>
    <title>{$templ['title']}</title>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <script src='js/jquery_1_9_1.js'></script>
    <link rel='stylesheet' href='js/magnific-popup.css' />
  </head>
    
  <body> 
      
      <style>
          .highlight {background-color:yellow;}
      </style>
      
    <a id='pg-rachel' href='/'></a> <!-- Rachel top bar to Rachel Home -->
           
";
if (!isset($_REQUEST["preview"])) {
          echo $header;
      } 

        // TODO: Features
        // special glob for type flashcards (tarjetas) to show available card sets in select box
        // make flashcards (tarjetas) automatically give testUri on checked
        // make error specific to title or uri, if missing place rule in placeholder for that field
      
	// TODO: Critical
        // Add function to clear contents and give admin a button to do so
      
        // TODO: non critical
        // move styling into stylesheet #form etc {}
        
        
    $errors = array();
    $messages = array();
    
    if (isset($_REQUEST["newjson"])) {// LIKELY POST SUBMIT
        $str = $_REQUEST["newjson"];
        if (isset($_REQUEST["writejson"])) {
            if (file_exists(text_to_filename($_REQUEST["group-title"]) . ".json") && (text_to_filename($_REQUEST["group-title"]) != $_REQUEST["filename"])) {
                $errors["group_title"] = "{$templ['lesson_title_unique']}.";
            } else {
                if(file_exists($_REQUEST["filename"].".json")) { unlink($_REQUEST["filename"] . ".json"); }
                $write_file = text_to_filename($_REQUEST["group-title"]);
                // write to file
                $fp = fopen($write_file . ".json", 'w');
                fwrite($fp, $str);
                fclose($fp);
		$_REQUEST["filename"] = $write_file;
                $messages["save"] = $templ["saved"]; 
            }
        }
    } else if (isset($_REQUEST["filename"])) {// DISPLAY JSON FROM FILE
        $filename = $_REQUEST["filename"];
        if ($filename == "empty") {
            $filename = "emptyjson.txt"; 
        } else {
            $filename = $filename . '.json'; 
        }
	if(!(file_exists($filename))) { $filename = "emptyjson.bak.txt"; }
       $str = file_get_contents($filename); 
    }
    $jsonInfo = json_decode($str, true);
    
        
      if (isset($_REQUEST["preview"])) {
          echo "<div id=\"lc-content\" class=\"white-popup\">";
      } else {
          echo "<div id=\"lc-content\">";
      }
      
?>    
        
        <div id="info">
        <img src="<?php echo $templ["img_uri"] ?>" alt="comisariada" />
        <h1><a href="index.php"><?php echo $templ["title"] ?></a></h1>
        <p><?php echo $templ["description"] ?></p>
        </div>
        <?php echo form_messages($messages); ?>
        <?php echo form_errors($errors); ?>
        <div style="clear:both;"> </div>
         
<?php      
        // ---------------------------------------------------------------------------------------------
        // ----------                              FORM (EDIT)                              ------------
        // ---------------------------------------------------------------------------------------------
        echo "<form id=\"plan\" method=\"post\" action=\"index-edit-json.php\" style=\"width:80%\"; >";
        echo "<p>{$templ['lesson_title']}:<input id=\"title\" type=\"text\" size=\"35%\" name=\"group-title\" value=\"{$jsonInfo['title']}\"></p>";
        echo "{$templ['objective']}:<p><textarea id=\"objective\" name=\"objective\" placeholder=\"({$templ['optional']})\" rows=\"8\" cols=\"120\">{$jsonInfo['objective']}</textarea></p>";  
        //if (count($jsonInfo['criticalPoints'])) {
            echo "<p>{$templ['critical_points']}:";
            echo "<button type=\"button\" id=\"addCP\" style=\"margin-left:4px; padding:2px;\">+ {$templ['new']}</button>"; 
            echo "<ul id=\"criticalPoints\" style='display:inline-block;'>";
            if (count($jsonInfo['criticalPoints'])) {
               foreach($jsonInfo['criticalPoints'] as $cp => $point) {
                    echo "<li style='padding:5px;'><input type=\"text\" size=\"100%\" name=\"criticalPoints\" value=\"{$point}\"></li>"; 
                } 
            }
            echo "</ul></p>";
        //}
        //if (count($jsonInfo['ideas'])) {
           echo "<p>{$templ['teaching_tips']}:";
           echo "<button type=\"button\" id=\"addIdea\" style=\"margin-left:4px; padding:2px;\">+ {$templ['new']}</button>"; 
           echo "<ul id=\"ideas\" style='display:inline-block;'>";
            if (count($jsonInfo['ideas'])) {
               foreach($jsonInfo['ideas'] as $i => $idea) {
                   echo "<li style='padding:5px;'><input type=\"text\" size=\"100%\" name=\"ideas\" value=\"{$idea}\"></li>";  
                } 
            }
            echo "</ul></p>";
       // }
        foreach ($jsonInfo['groups'] as $idx => $group) {
               echo "<div class=\"group {$group['type']}\" style=\"border: 2px solid #990033\">"; 
               echo "<img src=\"{$group['logoUri']}\" style=\"max-height:60px; max-width:80px; padding:6px; float:left;\"><span style='line-height:60px;'>{$group['name']}</span>";

               echo "<input type=\"hidden\" value=\"{$group['type']}\" name=\"type\" />";
               echo "<input type=\"hidden\" value=\"{$group['name']}\" name=\"name\" />";
               echo "<input type=\"hidden\" value=\"{$group['logoUri']}\" name=\"logoUri\" />";
            
            
                // build select box options for group type local content (static and interactive)
                $selbox = "<option value=''></option>";
                if ($group['type'] == "lc-static" || $group['type'] == "lc-interactive") { 
                    $options = glob("./{$group['type']}/*");
                    foreach($options as $option) {
                          $option = rtrim($option);
                          $pieces = explode('/', $option);
                          $piece = $pieces[sizeof($pieces) - 1]; // just the filename 
                          $selbox .= "<option value='" . $option . "'>" . $piece . "</option>"; 
                    }
                } 
            
               echo "<button type=\"button\" id=\"button-{$group['type']}\" class=\"addItem\" style=\"float:right; margin-top:24px; margin-right:10px;\">+ {$templ['new']}</button>"; 
               echo "<div class=\"endgroupinfo\" style=\"clear:both;\"></div>";

               // hidden template for a new item of this group type
               echo "<div class=\"itemTemplate\" style=\"display:none;\">";
	       echo "<ul style='display:inline-block; width:98%; margin:0px; margin-bottom:5px;'>";
               echo print_item($group['type'], $selbox, null);
	       echo "</ul>";
               echo "</div>"; // end itemTemplate
            
                // print items if there are some
                if (count($group['items'])) {
		    echo "<ul style='display:inline-block; width:98%; margin:0px; margin-bottom:5px;'>";
                    foreach ($group['items'] as $jdx => $item) {
                        echo print_item($group['type'], $selbox, $item);
                   } 
		   echo "</ul>";
                } 
            
                echo "</div>"; // end group
            
        } // end for each group

       // jquery event buttons and hidden form fields
        echo "<input id=\"preview\" style=\"padding:5px; margin:15px;\" type=\"button\" name=\"preview\" value=\"{$templ['preview']}\" />";
        echo "<input id=\"newjson\" type=\"hidden\" value=\"\" name=\"newjson\" />";
        if (isset($_REQUEST["filename"])) {
		  echo "<input id=\"filename\" type=\"hidden\" value=\"{$_REQUEST["filename"]}\" name=\"filename\" />"; 
	    } else {
          echo "<input id=\"filename\" type=\"hidden\" value=\"\" name=\"filename\" />"; 
        }
        echo "<input id=\"writejson\" type=\"hidden\" value=\"1\" name=\"writejson\" />";
        echo "<input style=\"padding:5px; margin:15px;\" type=\"submit\" name=\"submit\" value=\"{$templ['save']}\" />";
        echo "</form>";      
?>         
      
      </div> <!-- end content -->
  </body>
</html>

<?php


function print_item($group_type, $selbox, $item) {
    global $templ;
    $output = "";
    
    $output .= "<li style='padding:2px; line-height:30px;'><div class=\"item\" style=\"padding:5px; border:1px solid grey;\">";
    $output .= "{$templ['just_title']}:<input type=\"text\" size=\"30%\" name=\"title\" value=\"{$item['title']}\">";  
    $output .= "<button type=\"button\" class=\"removeItem\" style=\"margin-left:5px;float:right;\">{$templ['delete_element']}</button></br>"; 
    if ($group_type == "lc-static" || $group_type == "lc-interactive") { 
        $output .= "<select name='lcSelect' class='lcSelect' style=''>$selbox</select>    ";
    }   
    $output .= "{$templ['just_link']}:<input type=\"text\" size=\"50%\" name=\"uri\" value=\"{$item['uri']}\"></br>";
    $output .= "{$templ['just_description']}:<input type=\"text\" size=\"60%\" name=\"description\" value=\"{$item['description']}\">";
    $output .= "<img  src=\"images/headphones.png\" alt=\"Usa Audífonos\" style=\"max-height:20px; padding-left:5px;margin-top:3px;\">";
    if ($item['headphone'] == "yes") {
        $output .= "<input class=\"headphoneButton\" type=\"checkbox\" name=\"headphone\"  value=\"yes\" checked=\"checked\" />";
   } else {
        $output .= "<input class=\"headphoneButton\" type=\"checkbox\" name=\"headphone\"  value=\"no\" />";
   }
   $output .= "<img src=\"images/testMe.png\" alt=\"Prueba\" style=\"max-height:24px; padding-left:5px;margin-top:3px;\" />";
   // TODO: type flashcards could get special case that autofills test uri with a link to the corresponding test set 
   if ($item['testUri'] != "") { // show uri field
       $output .= "<input class=\"testUriButton\" type=\"checkbox\" name=\"hasTest\"  value=\"yes\" checked=\"checked\"/>";
       $output .= "<div  class=\"testUri\" style=\"display:inline-block;\">Enlace de Prueba:<input type=\"text\" size=\"50%\" name=\"testUri\" value=\"{$item['testUri']}\"></div>";
   } else { // hide uri field
       $output .= "<input class=\"testUriButton\" type=\"checkbox\" name=\"hasTest\"  value=\"no\"/>";
       $output .= "<div class=\"testUri\" style=\"display:none;\" >URI de Prueba:<input type=\"text\" size=\"50%\" name=\"testUri\" value=\"{$item['testUri']}\"></div>"; 
   } 
   
   $output .= "</div></li>"; // end item
    
   return $output;    
}



?>


<!-- ---------------------------------------------------------------------------------------------
// ----------                               SCRIPTS                                   ------------
// --------------------------------------------------------------------------------------------- -->


<script src="js/jquery.magnific-popup.js"></script>
<!--<script src="js/magnific/jquery.magnific-popup.min.js"></script> -->   

<script> 
     
function createJSON(submit) {
    var no_error = "true";
    jsonObj = {};
    jsonObj['title'] = $('#title').val();
    if (submit == "submit" || submit == "preview") {
        if (jsonObj['title'] == "") { // title required
            no_error = "false";
            $("#title").addClass("highlight");
            alert('<?php echo "{$templ['lesson_title_required']}.";?>');
        } else if ((jQuery.inArray(jsonObj['title'], forbiddenFiles)!==-1) && (jsonObj['title'] !== filename)){
            // TODO: This jquery check isn't working. Want to give error alert if a lesson with this title already exists.
	    no_error = "false";
            $("#title").addClass("highlight");
            alert('<?php echo "{$templ['lesson_title_unique']}.";?>');
	}       
    }
    
    jsonObj['objective'] = $('#objective').val();
    
    cpoints = [];
    $("#criticalPoints").children().find('input[name="criticalPoints"]').each(function() {
        var cp = $(this).val();
        if (cp != "") {
            cpoints.push(cp);
        }
    });      
    jsonObj['criticalPoints'] = cpoints;
    
    ideas = [];
    $("#ideas").children().find('input[name="ideas"]').each(function() {
        var idea = $(this).val();
        if (idea != "") {
            ideas.push(idea);
        }
    });      
    jsonObj['ideas'] = ideas;
    
    groups = [];
    $(".group").each(function() {
        // get info for group
        group_info = {};
        group_info['type'] = $(this).find('input[name="type"]').val();
        group_info['name'] = $(this).find('input[name="name"]').val();
        group_info['logoUri'] = $(this).find('input[name="logoUri"]').val();
        
        // get items for group
        items = [];
        $(this).find(".item").each(function() {
            item = {};
            item['title'] = $(this).find('input[name="title"]').val();
            item['description'] = $(this).find('input[name="description"]').val();
            item['uri'] = $(this).find('input[name="uri"]').val();
            item['headphone'] = $(this).find('input[name="headphone"]').val();
            item['testUri'] = $(this).find('input[name="testUri"]').val();
            $(this).removeClass("highlight");
            if (item['title'] != "" || item['description'] != "" || item['uri'] != "" || item['testUri'] != "") {
                items.push(item);
                if ((submit == "submit" || submit == "preview") && ((item['title'] == "" && item['uri'] != "") || (item['title'] != "" && item['uri'] == ""))) {
                    no_error = "false";
                    alert('<?php echo "{$templ['title_uri_required']}."; ?>');
                    $(this).addClass("highlight");
                }
            }
            
            
        });      
        // push items to group
        group_info['items'] = items;
        // push group
        groups.push(group_info);
    });  
    jsonObj['groups'] = groups;
    
    $('#newjson').val(JSON.stringify(jsonObj));
    
    if (no_error == "false") {
        var errHere = $(".highlight").first().offset();
        $("html, body").animate({scrollTop: errHere.top}, "slow");
    }
    
    return no_error;
    
}
    
    
    
function registerItemEvents() {
    
    $(".testUriButton").on('click', function() {
      if ($(this).is(':checked')) {
          $(this).parent().find('div[class="testUri"]').css('display', 'inline-block');
      } else {
          $(this).parent().find('div[class="testUri"]').css('display', 'none');
          $(this).parent().find('input[name="testUri"]').val("");
      }
    });  
    
    $(".headphoneButton").on('click', function() {
        if ($(this).is(':checked')) {
          $(this).val("yes");
        } else {
          $(this).val("no");
        }
    }); 
    
    $( ".lcSelect" ).change(function() {
        $(this).parent().find('input[name="uri"]').val($(this).val());
    });
    
    $(".removeItem").on('click', function() {
        if (confirm('<?php echo "{$templ['are_you_sure']}";?>')) {
            $(this).parent().remove();
        }
    }); 
    
}
    
function checkSelectBoxes(){
    
    //$(".lcSelect option[value = ]")
    
}
    

$(document).ready(function() {
    
  filename = $('#filename').val();
    
  checkSelectBoxes();
    
  createJSON();
    
  $(".addItem").on('click', function() {
        $newItem = $(this).parent().find('div[class="itemTemplate"]').html();
        $(this).parent().append($newItem);
        registerItemEvents(); // for newly added items
  });  
    
  registerItemEvents(); // any existing items
    
  $("#addIdea").on('click', function() {
      $("#ideas").append("<li><input type=\"text\" size=\"100%\" name=\"ideas\" value=\"\"></li>");
  });  
    
  $("#addCP").off('click').on('click', function() {
      $("#criticalPoints").append("<li><input type=\"text\" size=\"100%\" name=\"criticalPoints\" value=\"\"></li>");
  });  
    
  $("#preview").on('click', function() {
        //createJSON("preview"); // updates json with current form values $('#newjson').val() 
	json_checker = createJSON("preview");
        if(json_checker == "true"){ // no errors
            $(".highlight").removeClass("highlight");
            $srcPopup = 'index-display-json.php?preview=1';
            $.magnificPopup.open({
                    items: {
                        src: $srcPopup
                    },
                    type: 'ajax',
                    ajax: {
                        settings: {
                            type: 'POST',
                            data: { 
                                newjson: $('#newjson').val()
                            }
                        }
                    }


            });
            
        } else { // yes errors
            
        }
            
  });  

  $.getJSON( "index-edit-json.php?ajaxFilenames=1", function( data ) {
     forbiddenFiles = data['files'];
  });
    
$("#plan").submit(function(event) {
    // update json
    if(createJSON("submit") == "false"){ //prevent submit with form errors
        event.preventDefault();
    }
});
});   
    
</script>
