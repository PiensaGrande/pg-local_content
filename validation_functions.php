<?php namespace pg_local_content; ?>
<?php 

    
// validation functions   

function text_to_filename($text) { // TODO: probably need to deal with spanish characters
  $filename = str_replace(" ", "_", $text);
  return $filename;
}    
    
function fieldname_as_text($fieldname) {
  $fieldname = str_replace("_", " ", $fieldname);
  $fieldname = ucfirst($fieldname);
  return $fieldname;
}
    
function form_errors($errors=array()) {
    global $templ;
    $output = "";
    if (!empty($errors)) {
      $output .= "<div class=\"error\">";
      //$output .= "Please fix the following errors:"; // en
      //$output .= "Arreglar los siguientes errores:"; // es
      $output .= "{$templ['fix_errors']}:";
      $output .= "<ul>";
      foreach ($errors as $key => $error) {
        $output .= "<li>";
            $output .= htmlentities($error);
            $output .= "</li>";
      }
      $output .= "</ul>";
      $output .= "</div>";
    }
    return $output;
}    

function form_messages($msgs=array()) {
    $output = "";
    if (!empty($msgs)) {
      $output .= "<div class=\"message\">";
      $output .= "<ul>";
      foreach ($msgs as $key => $msg) {
        $output .= "<li>";
        $output .= htmlentities($msg);
        $output .= "</li>";
      }
      $output .= "</ul>";
      $output .= "</div>";
    }
    return $output;
}    
    
 
// * presence
// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) {
	return isset($value) && $value !== "";
}

function validate_presences($required_fields) { // from POST
  global $errors;
  global $templ;
  foreach($required_fields as $field) {
    $value = trim($_POST[$field]);
    if (!has_presence($value)) {
        // TODO: could clean this up (and make this more general) by making a function that translates the $field.
  	//$errors[$field] = fieldname_as_text($field) . " can't be blank."; // en
        if ($field == "title") {
            $errors[$field] = "{$templ['just_title']} {$templ['required']}."; 
        } else if ($field == "description"){
            $errors[$field] = "{$templ['just_description']} {$templ['required']}."; 
        } else {
            $errors[$field] = fieldname_as_text($field) . "{$templ['required']}."; // only deals with English
        }
    }
  }
}  

function find_spec_chars($x,$excludes){
    $spec_chars = array();
    if (!empty($excludes)) {
        foreach ($excludes as $exclude) {
            if (strpos($x, $exclude) !== false) {
                $spec_chars[] = $exclude; 
            }   
        }    
    }    
    return $spec_chars;
}


function uploadCheck_spec_chars($x) {
	global $errors;
	global $templ;	
	// TODO: how restrictive should we be here? probably want to replace some chars with others. use urlencode and decode? 
	// for example: ! ? : and the quotes and ticks and . could be replaced to store as file
	//$excludes = array('.', '/', '\\', '"', "'", '#', '(', ')', '<', '>', '$', '+', '&', '*', '%', '!', '=', '{', '}', '@', ':', '|', '`', '~', '^', ';');
        $excludes = array('.', '/', '\\', '<', '>', '*', '$', '|');
	$spec_chars = find_spec_chars($x, $excludes);
	if (!empty($spec_chars)) { 
        	$errors["special_chars"] = $x . " - {$templ['special_chars']} - ";
        	if (is_array($spec_chars)) { 
			foreach ($spec_chars as $spec_char) {
                		$errors["special_chars"] = $errors["special_chars"] . $spec_char . " ";
        		}   
		} else {
			$errors["special_chars"] = $errors["special_chars"] . $spec_chars . " ";
		}
	}
}


?>

