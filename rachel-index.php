<?php namespace pg_local_content; ?>
<?php 
// Place module specific hints for RACHEL in template.php
// For a simple module, that will be all that is necessary.
include dirname(__FILE__) . "/template.php"; 

// Permit template.php to define whether we show anything on index.
// Remember that hiding in admin will cause rachel-admin.php to be hidden as well.
if (strtoupper($templ["hide_index"]) == "YES") { return; }

// Here we build core module structure with logo, title
// Note the availability of this data to jquery using data-
echo "
<div class='indexmodule' data-moduletype='{$templ['module_type']}' data-title='{$templ['title']}' data-img_uri='{$templ['img_uri']}' data-index_loc='{$templ['index_loc']}'>
<a href='{$templ['index_loc']}'>
<img src='{$templ['img_uri']}' alt='Your Content Logo'>
</a>
<h2><a href='{$templ['index_loc']}'>{$templ['title']}</a></h2>
";

// If you have any links or additional info to provide do it here, extend $templ in messages.php for multi-lingual.
// Comment out the description if not used.
echo "<p>{$templ["description"]}  ";

// admin has the ability to create a new lesson plan via this added link 
if (isset($_COOKIE['rachel-auth']) && $_COOKIE['rachel-auth'] == "admin") {
	echo "<small><a href=\"{$templ['web_path']}/index-edit-json.php?filename=empty\">[{$templ['create_lesson']}]</a></small>";
}
echo "</p>\n";

$jsons = glob("./{$templ["web_path"]}/*.{json}", GLOB_BRACE);
echo " <ul class=\"quad\">\n";

	// display links to each lesson
        foreach($jsons as $j) {
                $groupname = basename($j, ".json");
                $displayname = str_replace("_", " ", $groupname); // string replace for pretty display
                $displayname = ucfirst($displayname);
		if (isset($_COOKIE['rachel-auth']) && $_COOKIE['rachel-auth'] == "admin") {
			// admin can edit lessons
			echo "<li><a href=\"{$templ["web_path"]}/index-edit-json.php?filename={$groupname}\">{$displayname}</a></li>\n"; 
		} else {
			// non-admin can view lessons
			echo "<li><a href=\"{$templ["web_path"]}/index-display-json.php?filename={$groupname}\">{$displayname}</a></li>\n"; 
		}
        }

echo "</ul>\n";
echo "</div>";
?>
