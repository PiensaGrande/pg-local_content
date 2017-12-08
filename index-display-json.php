<?php namespace pg_local_content; ?>
<?php include(dirname(__FILE__) . "/template.php") ?>
<?php
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
        
        if (isset($_REQUEST["newjson"])) {
           $str = $_REQUEST["newjson"];
            
        } else if (isset($_REQUEST["filename"])) {// DISPLAY JSON FROM FILE
	       $filename = $_REQUEST["filename"];
           $str = file_get_contents($filename . '.json');
        }
        $jsonInfo = json_decode($str, true);
    
        
      if (isset($_REQUEST["preview"])) {
          echo "<div id=\"lc-content\" class=\"white-popup\">";
      } else {
          echo "<div id=\"lc-content\">";
      }
      
?>    
        <div id="info">
	<img src="<?php echo $templ["img_uri"] ?>" alt="comisariada">   
        <h1><?php echo $templ["title"] ?></h1>
        <p><?php echo $templ["description"] ?><p>
        </div>
        <div style="clear:both;"> </div>
      
<?php      
        
        // ---------------------------------------------------------------------------------------------
        // ----------                          PRETTY DISPLAY                               ------------
        // ---------------------------------------------------------------------------------------------
           echo "<div class=\"section\">";
           echo "<h2>{$jsonInfo['title']}</h2>";
           if ($jsonInfo['objective'] != '') {
                echo "<div class=\"segment\"><h3 style=\"display:inline\">{$templ['objective']}:</h3><p style=\"display:inline\">{$jsonInfo['objective']}</p></div>";  
            }
           if (count($jsonInfo['criticalPoints'])) {
                echo "<div class=\"segment\"><h3>{$templ['critical_points']}:</h3>";
                echo "<ul id=\"criticalPoints\">";
                if (count($jsonInfo['criticalPoints'])) {
                   foreach($jsonInfo['criticalPoints'] as $cp => $point) {
                        echo "<li>{$point}</li>"; 
                    } 
                }
                echo "</ul></div>";
            }
            if (count($jsonInfo['ideas'])) {
               echo "<div class=\"segment\"><h3>{$templ['teaching_tips']}:</h3>";
               echo "<ul id=\"ideas\">";
                if (count($jsonInfo['ideas'])) {
                   foreach($jsonInfo['ideas'] as $i => $idea) {
                       echo "<li>{$idea}</li>";  
                    } 
                }
                echo "</ul></div>";
            }  

           echo "<table>";
           foreach ($jsonInfo['groups'] as $idx => $group) {
               foreach ($group['items'] as $jdx => $item) {
                   echo "<tr>";
                   echo "<td>";
                   echo "<img src=\"{$jsonInfo['groups'][$idx]['logoUri']}\" style=\"max-height:30px; max-width:30px; padding:4px; vertical-align:bottom;\">";
                   echo "<a href=\"{$item['uri']}\">{$item['title']}</a></td>";
                   echo "<td>";
                   if ($item['headphone'] == "yes") {
                       echo "<img  src=\"images/headphones.png\" alt=\"Usa Audífonos\" style=\"max-height:20px; padding-right: 5px;\">";
                   } 
                   echo "{$item['description']}</td>";
                   echo "<td>";
                   if ($item['testUri'] != "") {
                       echo "<a href=\"{$item['testUri']}\" ><img src=\"images/testMe.png\" alt=\"Prueba\" style=\"max-height:30px;\" /></a>";
                   } 
                   echo "</td>";
                   echo "</tr>";
               }
           }
            echo "</table>";
            echo "</div>"; //end section
       
?>         
      </div> <!-- end content -->
  </body>
</html>




<script src="js/jquery.magnific-popup.js"></script>
<!--<script src="js/magnific/jquery.magnific-popup.min.js"></script> -->   

<script> 
     
function createJSON($submit) {
    $no_error = "true";
    jsonObj = {};
    
    jsonObj['title'] = $('#title').val();
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
            if (item['title'] != "" || item['description'] != "" || item['uri'] != "" || item['testUri'] != "") {
                items.push(item);
                $(this).css('background-color', 'white');
                if (($submit == "submit" || $submit == "preview") && (item['title'] == "" || item['uri'] == "")) {
                    $no_error = "false";
                    $(this).css('background-color', 'yellow');
                } 
            }
            
            
        });      
        // push items to group
        group_info['items'] = items;
        // push group
        groups.push(group_info);
    });  
    jsonObj['groups'] = groups;
    
    //alert(JSON.stringify(jsonObj));
    $('#newjson').val(JSON.stringify(jsonObj));
    
    if ($no_error == "false") {
        alert("Título y URI necesitan valores.");
    }
    return $no_error;
    
}
    
    


$(document).ready(function() {
    
  createJSON();
       
});   
    

</script>
