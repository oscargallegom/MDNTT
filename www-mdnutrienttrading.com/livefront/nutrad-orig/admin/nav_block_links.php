<?php

# ----------------------------------------------------------------------------- 
#
# nav_block_links.php
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
$close_and_refresh = "onLoad=close_and_refresh()";
$cancel_and_close = "onLoad=cancel_and_close()";
$refresh_page = "nav_blocks.php";
$pi = $_POST[parent];

$this_date = date("Y-m-d");
$error = array();
$update_msg = "";
$image_dir = $nav_mgr->base_href . $nav_mgr->image_dir;

$required = array();
//$required = array("title");
if ($title != "") {
   $title = ereg_replace("\\\'", "&#039", $title);
   $title = ereg_replace('\\\"', "&#034", $title);
}
if ($subtitle != "") {
   $subtitle = ereg_replace("\\\'", "&#039", $subtitle);
   $subtitle = ereg_replace('\\\"', "&#034", $subtitle);
}



# cancel an update
# ----------------
if ($cancel != "") {
   $onLoad = $cancel_and_close;
   $edit = "no";
}


# perform an update or delete
# ---------------------------
if ($update != "") {

   if (!$i > 0) {
      echo "Error: Missing ID <BR>";
      exit;
   }

   if ($delete != "") {

      # first, delete the nav files of the link
      # ---------------------------------------
      $nav_filename = $nav_mgr->get_nav_filename($i, "more_info", "nav_block_links");
      if (is_writeable($nav_filename)) { unlink($nav_filename); }

      # then delete the links from the database
      # ---------------------------------------
      $sql = "DELETE FROM nav_block_links WHERE id = $i";
      mysql_query($sql) or die(mysql_error());

      //echo "<BR><BR><$font size=-1 color=green>The More Info block link has been deleted.<BR>";
      $alert_msg = "The More Info block link has been deleted.";
      $onLoad = $close_and_refresh;
      $edit = "no";

   }

   else {
      # update selected link
      # --------------------
      $sql = "UPDATE nav_block_links SET 
		title = '$title', 
		subtitle = '$subtitle', 
		url = '$url', 
		parent = '$parent', 
		hide = '$hide', 
		date = '$this_date' 
		WHERE id = '$i'";
      $result = mysql_query($sql) or die(mysql_error());

      //$update_msg = "<$font size=-1 color=green>The nav block link has been updated.<BR><BR><a href=nav_blocks.php?generate=0&sw=y><img src=$image_dir/regenerate.png border=0></a><BR>";
      $alert_msg = "The More Info block link has been updated.";
      $onLoad = $close_and_refresh;
      $edit = "no";
    
   }
}


# add the new element
# -------------------
if ($add != "") {

   # check for required fields
   # -------------------------
   //foreach ($required as $var) {
      //if ($$var == "") {
         //array_push($error, "$var must not be blank");
      //}
   //}  

   # get the MAX list_order for this block and add the new link to the end
   # ---------------------------------------------------------------------
   $q = "SELECT MAX(list_order) AS max FROM nav_block_links WHERE parent = $parent";
   $result = mysql_query($q) or die(mysql_error());
   if ($r = mysql_fetch_assoc($result)) {
      $list_order = $r['max'] + 1;
   }

   if (!count($error) > 0) { 
      $sql = "INSERT INTO nav_block_links (title, subtitle, url, parent, list_order, hide, date) VALUES ('$title', '$subtitle', '$url', '$parent', '$list_order', '$hide', '$this_date')";
      mysql_query($sql) or die(mysql_error());
      $i = mysql_insert_id();
      $edit = "no";

      $alert_msg = "The nav block link has been updated.";
      $onLoad = $close_and_refresh;
   }
   else {
      show_error($error, $font, 251);
      $edit = "new";
   }
}
else if (count($error) > 0) { 
   show_error($error, $font, 256); 
   $confirm = "";
   $update  = "";
   $edit    = "yes";
}


echo "<HEAD> \n";
include "js.php";
echo "</HEAD> \n";
echo "<BODY $onLoad>";
echo "<CENTER>";



# request confirmation for an update or delete
# --------------------------------------------
if ($confirm != "") {

   if ($title == "") 	{ array_push($error, "Title must not be blank"); }

   if (!count($error) > 0) {

      # generate an appropriate message
      # -------------------------------
      if ($delete != "") {
         $confirmation_msg = "<div class=text>Are you sure you want to delete this More Info block link?</div>";
      }
      else {
         $confirmation_msg = "<div class=text>Are you sure you want to update this More Info block link?</div>";
      }


      if ($hide != "y") { 	$hide_text = "No"; }
      else { 			$hide_text = "Yes"; }
   
      if (count($error) > 0) { 
         show_error($error, $font, 177); 
         $confirm = "";
         $update  = "";
         $edit    = "yes";
      }
      else {

         $q = "SELECT name FROM nav_blocks WHERE id = $parent";
         $result = mysql_query($q);
         if ($row = mysql_fetch_assoc($result)) {
            $parent_title = $row[name];
         }
         else {
            array_push($error, "Cannot determine corresponding More Info Block for $title.");
         }


      # show information being updated for confirmation
      # -----------------------------------------------
         echo "
<P>
<div class=text><h4>$confirmation_msg</h4></div>
<form method=POST action=$PHP_SELF>
<table cellspacing=1 bgcolor=black><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>
 <tr>
  <td><$font size=-1 color=black><B>Title </td>
  <td><$font size=-1>$title </td>
 </tr>
 <tr>
  <td><$font size=-1 color=black><B>Subtitle </td>
  <td><$font size=-1>$subtitle </td>
 </tr>
 <tr>
  <td><$font size=-1 color=black><B>URL </td>
  <td><$font size=-1>$url </td>
 </tr>
 <tr>
  <td><$font size=-1 color=black><B>More Info Block </td>
  <td><$font size=-1>$parent_title </td>
 </tr>
 $copy_text
 <tr>
  <td><$font size=-1 color=black><B>Hide </td>
  <td><$font size=-1>$hide_text </td>
 </tr>
 <tr>
  <td>
   <input type=hidden name=i value=$i>
   <input type=hidden name=title value=\"$title\">
   <input type=hidden name=subtitle value=\"$subtitle\">
   <input type=hidden name=url value=\"$url\">
   <input type=hidden name=parent value=\"$parent\">
   <input type=hidden name=delete value=$delete>
   <input type=hidden name=hide value=$hide>
   <input type=submit name=update value=Submit>
  </td>
  <td align=right>
   <input type=submit name=cancel value=Cancel>
  </td>
</table>
</td></tr></table>

</form>

	";
         $edit = "no";
         $update = "no";
      }
   }
}


# show initial pop-up window with information for editing
# -------------------------------------------------------
if ($edit != "no") {

   if ($edit == "new") {
      $msg = "Add a Link to a More Info Block";
      $hidden_type = "";
   }
   else {

      $msg = "Edit or Delete a More Info Block Link";

      # retrieve information for the selected link
      # ------------------------------------------
      if ($i > 0) {
         $sql = "SELECT * FROM nav_block_links WHERE id = $i";
         //echo "SQL: $sql<BR>";
         $result = mysql_query($sql) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            $title 	= $row[title];
            $subtitle 	= $row[subtitle];
            $url 	= $row[url];
            $list_order	= $row[list_order];
            $hide	= $row[hide];
            $parent 	= $row[parent];
            $date 	= $row["date"];

            if ($hide == "y") { $hide_check = "checked"; }

            $qq = "SELECT name FROM nav_blocks WHERE id = $parent";
            $result = mysql_query($qq);
            if ($qqrow = mysql_fetch_assoc($result)) {
               $parent_name = $qqrow[name];
            }
            else {
               array_push($error, "Cannot determine corresponding More Info Block for $title.");
            }
         }
         else {
            array_push($error, "Invalid ID.  No such page group link found.");
         }
      }
      
      if (count($error) > 0) { 
         show_error($error, $font, 298); 
         //exit;
      }
   
      
      $link_info = "
 <tr><td colspan=2><table cellpadding=3 cellspacing=0 bgcolor=DDDDFF width=50%>
 <tr>
  <td bgcolor=EFEFEF><$font size=-1>Last Updated</td>
  <td bgcolor=EFEFEF><$font size=-1>$date</td>
 </tr>
 <tr>
  <td bgcolor=EFEFEF><$font size=-1>More Info Block</td>
  <td bgcolor=EFEFEF><$font size=-1>$parent_name</td>
 </tr>
 </table></td></tr>
	";


   }  // End if edit not new
   
   
   # make the add/edit/delete form
   # -----------------------------
   echo "

<div class=text><h4><$font>$msg</font></h4></div>

<P>

$update_msg

<form method=POST action=$PHP_SELF>
<table cellspacing=0 bgcolor=333333><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>

 $link_info

 <tr>
  <td><$font size=-1>Title </td>
  <td><input type=text size=40 maxlength=255 name=title value=\"$title\"></td>
 </tr>

 <tr>
  <td><$font size=-1>Subtitle </td>
  <td><input type=text size=40 maxlength=255 name=subtitle value=\"$subtitle\"></td>
 </tr>

 <tr>
  <td><$font size=-1>URL </td>
  <td><input type=text size=40 maxlength=255 name=url value=\"$url\"></td>
 </tr>

 <tr>
  <td><$font size=-1>Hide </td>
  <td><input type=checkbox name=hide value=y $hide_check></td>
 </tr>

 <tr>
  <td><$font size=-1>Delete </td>
  <td><input type=checkbox name=delete value=yes></td>
 </tr>

	";


   if ($edit != "new") {
      $button = "confirm";
   }
   else {
      $button = "add";
   }


   echo "
 <tr>
  <td colspan=2>
   <table width=100%><tr>
  <td align=left>
   $hidden_type
   <input type=hidden name=i value=$i>
   <input type=hidden name=parent value=$parent>
   <input type=submit name=$button value=Submit>
  </td>
  <td align=right><input type=submit name=cancel value=Cancel></td>
 </tr>
   </table>
  </td>
 </tr>

</table>
</td></tr></table>

</form>

	";


}  // End if edit not no


echo "</div>";



function show_error($error, $font, $line) {
   echo "<P><table>";
   echo "<tr><td><$font size=0 color=red><B>E R R O R :</B> ($line)</td></tr>";
   //foreach ($error as $msg) {
   while (!empty($error)) {
      $msg = array_shift($error);
      echo "<tr><td><$font size=-1 color=red>$msg </font></td></tr>";
   }
   echo "</table>";
}



?>
