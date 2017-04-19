<?php

# ----------------------------------------------------------------------------- 
#
# nav_block_show.php
#
# Show all the pages on which a particular More Info block has been placed.
#
# ----------------------------------------------------------------------------- 

include "include_path.php";
include "$include_dir/config.php";
include "$include_dir/db_connect.php";

require "class_nav_mgr.php";
session_start();
if (!is_object($nav_mgr)) {
   $nav_mgr = new Navigation_Manager($include_dir);
}
session_register("nav_mgr");

$font = $local{"font"};
$image_dir = $nav_mgr->base_href . $nav_mgr->image_dir;
$bid = $_GET['bid'];


echo <<<END

<HTML>
<HEAD>
<TITLE>MDA Site Navigation Manager</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="textfonts.css">

END;

include "js.php";
echo "</HEAD> \n";
echo "<BODY>";
echo "<CENTER>";


# More Info block info
# --------------
$q = "SELECT * FROM nav_blocks WHERE id = '$bid'";
$result = mysql_query($q) or die(mysql_error());
if ($row = mysql_fetch_assoc($result)) {
   $block_name = $row['name'];
}
else {
   $block_name = "More Info block ID $bid";
}


# Find out on which pages this More Info block has been placed
# ------------------------------------------------------------
$count = 1;
$q = "SELECT l.id, l.name, l.url, l.type FROM left_nav l, nav_block_assignment a WHERE a.block_id = '$bid' AND a.page_id = l.id";
$result = mysql_query($q) or die (mysql_error());
if ($row = mysql_fetch_assoc($result)) {
   $page_list = "<tr><td colspan=2><HR></td></tr>";
   do {
      $name = $row['name'];
      $url = $row['url'];
      $id = $row['id'];
      $type = $row['type'];

      $full_url = $nav_mgr->{base_href} . $url;

      $page_list .= "<tr><td valign=top class=text><B> $count. </B></font></td><td class=text><a href=\"left_nav_links.php?i=$id&t=$type\" target=_blank><B>$name</B></a><BR><a href=\"$full_url\" target=_blank>$url</a></td></tr> \n";
      $count++;
   } while ($row = mysql_fetch_assoc($result));
}
else {
   $page_list = "<tr><td><$font size=-1 color=red>This More Info block has not yet been placed on any pages.</font></td></tr>";
}


echo <<<EOF

<div class=text>
<h3>More Info Block Placement for</h3>
<h4>"$block_name"</h4>
</div>
<P>

<table cellspacing=3 cellpadding=3>
$page_list
</table>

EOF;


?>
