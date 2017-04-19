<?php

# ----------------------------------------------------------------------------- 
#
# right_nav_links.php
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
$refresh_page = "right_nav.php";
$pi = $_POST[parent];


$this_date = date("Y-m-d");
$error = array();

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
      $sql = "SELECT * FROM right_nav WHERE id = $i";
      $result = mysql_query($sql) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         do {
            $nav_filename = $nav_mgr->get_right_nav_filename($row[id], "right");
            if (is_writeable($nav_filename)) { unlink($nav_filename); }
         } while ($row = mysql_fetch_assoc($result));
      }

      # then delete the links from the database
      # ---------------------------------------
      $sql = "DELETE FROM right_nav WHERE id = $i";
      mysql_query($sql) or die(mysql_error());

      $alert_msg = "The page group link has been deleted.";
      $onLoad = $close_and_refresh;
      $edit = "no";

   }

   else {
      # update selected link
      # --------------------
      $sql = "UPDATE right_nav SET 
		title = '$title', 
		subtitle = '$subtitle', 
		url = '$url', 
		parent = '$parent', 
		type = 'p',
		hide = '$hide', 
		date = '$this_date' 
		WHERE id = '$i'";
      $result = mysql_query($sql) or die(mysql_error());

      $alert_msg = "The page group link has been updated.";
      $onLoad = $close_and_refresh;
      $edit = "no";
    
   }
}


# add the new element
# -------------------
if ($add != "") {

   # check for required fields
   # -------------------------
   foreach ($required as $var) {
      if ($$var == "") {
         array_push($error, "$var must not be blank");
      }
   }  

   # get the MAX list_order for this block and add the new link to the end
   # ---------------------------------------------------------------------
   $q = "SELECT MAX(list_order) AS max FROM right_nav WHERE parent = $parent";
   $result = mysql_query($q) or die(mysql_error());
   if ($r = mysql_fetch_assoc($result)) {
      $list_order = $r['max'] + 1;
   }     


   if (!count($error) > 0) { 
      $sql = "INSERT INTO right_nav (title, subtitle, url, parent, hide, type, date, list_order) VALUES ('$title', '$subtitle', '$url', '$parent', '$hide', 'p', '$this_date', '$list_order')";
      mysql_query($sql) or die(mysql_error());
      $i = mysql_insert_id();

      $alert_msg = "The page group link has been updated.";
      $onLoad = $close_and_refresh;
      $edit = "no";
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


echo <<<END
<HTML>
<HEAD>
<TITLE>MDA Site Navigation Manager - Edit Page Group Links</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="textfonts.css">
END;
include "js.php";
echo "</HEAD> \n";
echo "<BODY $onLoad>";
echo "<CENTER><div class=text>";



# request confirmation for an update or delete
# --------------------------------------------
if ($confirm != "") {

   //if ($title == "") 	{ array_push($error, "Title must not be blank"); }

   if (!count($error) > 0) {

      # generate an appropriate message
      # -------------------------------
      if ($delete != "") {
         $confirmation_msg = "<div class=text>Are you sure you want to delete this page group link?</div>";
      }
      else {
         $confirmation_msg = "<div class=text>Are you sure you want to update this page group link?</div>";
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

         $q = "SELECT title FROM right_nav WHERE id = $parent";
         $result = mysql_query($q);
         if ($row = mysql_fetch_assoc($result)) {
            $parent_title = $row[title];
         }
         else {
            array_push($error, "Cannot determine corresponding Page Group for $title.");
         }


      # show information being updated for confirmation
      # -----------------------------------------------
         echo "
<P>
<h4>$confirmation_msg</h4>
<form method=POST action=right_nav_links.php>
<table cellspacing=1 bgcolor=black><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>
 <tr>
  <td class=text><B>Title </td>
  <td class=text>$title </td>
 </tr>
 <tr>
  <td class=text><B>Subtitle </td>
  <td class=text>$subtitle </td>
 </tr>
 <tr>
  <td class=text><B>URL </td>
  <td class=text>$url </td>
 </tr>
 <tr>
  <td class=text><B>Page Group </td>
  <td class=text>$parent_title </td>
 </tr>
 $copy_text
 <tr>
  <td class=text><B>Hide </td>
  <td class=text>$hide_text </td>
 </tr>
 <tr>
  <td class=text><B>Type </td>
  <td class=text>Page Group Link</td>
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
      $msg = "Add a Link to a Page Group";
      $hidden_type = "";
   }
   else {

      $msg = "Edit or Delete a Page Group Link";

      # retrieve information for the selected link
      # ------------------------------------------
      if ($i > 0) {
         $sql = "SELECT * FROM right_nav WHERE id = $i";
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
            $type 	= $row[type];

            if ($hide == "y") { $hide_check = "checked"; }

            $qq = "SELECT title FROM right_nav WHERE id = $parent";
            $result = mysql_query($qq);
            if ($qqrow = mysql_fetch_assoc($result)) {
               $parent_title = $qqrow[title];
            }
            else {
               array_push($error, "Cannot determine corresponding Page Group for $title.");
            }
         }
         else {
            array_push($error, "Invalid ID.  No such page group link found.");
         }
      }
      
      if (count($error) > 0) { 
         //show_error($error, $font, 298); 
         exit;
      }
   
      
      $link_info = "
 <tr><td colspan=2><table cellpadding=3 cellspacing=0 bgcolor=DDDDFF width=50%>
 <tr>
  <td bgcolor=EFEFEF class=text>Last Updated</td>
  <td bgcolor=EFEFEF class=text>$date</td>
 </tr>
 <tr>
  <td bgcolor=EFEFEF class=text>Page Group</td>
  <td bgcolor=EFEFEF class=text>$parent_title</td>
 </tr>
 </table></td></tr>
	";


   }  // End if edit not new
   
   
   # make the add/edit/delete form
   # -----------------------------
   echo "

<h4>$msg</h4>

<P>

<form method=POST action=$PHP_SELF>
<table cellspacing=0 bgcolor=333333><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>

 $link_info

 <tr>
  <td class=text>Title </td>
  <td class=text><input type=text size=40 maxlength=255 name=title value=\"$title\"></td>
 </tr>

 <tr>
  <td class=text>Subtitle </td>
  <td class=text><input type=text size=40 maxlength=255 name=subtitle value=\"$subtitle\"></td>
 </tr>

 <tr>
  <td class=text>URL </td>
  <td class=text><input type=text size=40 maxlength=255 name=url value=\"$url\"></td>
 </tr>

 <tr>
  <td class=text>Hide </td>
  <td class=text><input type=checkbox name=hide value=y $hide_check></td>
 </tr>

 <tr>
  <td class=text>Delete </td>
  <td class=text><input type=checkbox name=delete value=yes></td>
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
   echo "<tr><td class=text><font color=red><B>E R R O R :</B> ($line)</td></tr>";
   while (!empty($error)) {
      $msg = array_shift($error);
      echo "<tr><td class=text><font color=red>$msg </font></td></tr>";
   }
   echo "</table>";
}



?>
