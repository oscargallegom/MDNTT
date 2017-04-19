<?php

# ----------------------------------------------------------------------------- 
#
# right_nav_groups.php
#
# 1)  Add, Edit or Delete sibling groups (page groups)
#
# ----------------------------------------------------------------------------- 

include "include_path.php";
include "$include_dir/config.php";
include "$include_dir/db_connect.php";
$font = $local{"font"};
$image_dir = $local{"base_href"} . $local{"image_dir"};
//$url_base = $local{url_base_local};

$this_date = date("Y-m-d");
$error = array();
$onload = "onLoad=close_and_refresh()";
$refresh_page = "right_nav.php";

//$required = array("title");
$required = array();

if ($title != "") {
   $title = ereg_replace("\\\'", "&#039", $title);
   $title = ereg_replace('\\\"', "&#034", $title);
}
if ($subtitle != "") {
   $subtitle = ereg_replace("\\\'", "&#039", $subtitle);
   $subtitle = ereg_replace('\\\"', "&#034", $subtitle);
}



# reorder the links of a group
# --------------------------------
if ($up > 0) {
   $id = $up;
   $sql = "UPDATE right_nav SET list_order = $pos WHERE list_order = ($pos - 1) AND parent = $i";
   mysql_query($sql) or die(mysql_error());
   $sql = "UPDATE right_nav SET list_order = ($pos - 1) WHERE id = $id";
   mysql_query($sql) or die(mysql_error());
}
if ($down > 0) {
   $id = $down;
   $sql = "UPDATE right_nav SET list_order = $pos WHERE list_order = ($pos + 1) AND parent = $i";
   mysql_query($sql) or die(mysql_error());
   $sql = "UPDATE right_nav SET list_order = ($pos + 1) WHERE id = $id";
   mysql_query($sql) or die(mysql_error());
}


# cancel an update
# ----------------
if ($cancel != "") {
   //$edit = "no";
}


# perform an update or delete
# ---------------------------
if ($update != "") {

   if ($delete != "") {

      # first delete the nav files of links in the group
      # ------------------------------------------------
      $sql = "SELECT * FROM right_nav WHERE parent = $i";
      $result = mysql_query($sql) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         do {
            $nav_filename = $nav_mgr->get_right_nav_filename($row[id], "right");
            if (is_writeable($nav_filename)) { unlink($nav_filename); }
         } while ($row = mysql_fetch_assoc($result));
      }

      # then delete the links from the database
      # ---------------------------------------
      $sql = "DELETE FROM right_nav WHERE parent = $i";
      mysql_query($sql) or die(mysql_error());

      # and delete group itself from the database
      # -----------------------------------------
      $sql = "DELETE FROM right_nav WHERE id = $i";
      mysql_query($sql) or die(mysql_error());
      echo "<BR><BR><div class=text><font color=green>The page group has been deleted.</font></div><BR>";
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
		type = 'g',
		hide = '$hide', 
		default_dir = '$default_dir', 
		date = '$this_date' 
		WHERE id = '$i'";
      $result = mysql_query($sql) or die(mysql_error());

      echo "<BR><BR><div class=text><font color=green>The page group has been updated.</font></div><BR>";
      $edit = "no";
    
   }
}


# add the new element
# -------------------
if ($add != "") {
   if ($title == "") {array_push($error,"You must enter a title for the link.");}
   // if ($subtitle == "") {array_push($error,"You must enter a subtitle for the link.");}
   // if ($url == "") {array_push($error,"You must enter a URL for the link.");}
   // if (!is_numeric($parent)) {array_push($error,"No parent directory is specified.");}
   //if ($type == "") {array_push($error,"Please specify whether the link is for a directory or a page."); }

   foreach ($required as $var) {
      if ($$var == "") {
         array_push($error, "$var must not be blank");
      }
   }  

   if (!count($error) > 0) { 
      $sql = "INSERT INTO right_nav (title, subtitle, url, parent, hide, type, date, default_dir) VALUES ('$title', '$subtitle', '$url', '$parent', '$hide', 'g', '$this_date', '$default_dir')";
      mysql_query($sql) or die(mysql_error());
      $edit = "no";

      $close_and_refresh = $onload;
      $alert_msg = "The page group has been added.";

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


if ($included != "y") {
   echo <<<END
<HTML>
<HEAD>
<TITLE>MDA Site Navigation Manager - Edit Page Group Links</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="textfonts.css">
END;
   include "js.php";
   echo "</HEAD> \n";
   echo "<BODY $close_and_refresh>";
   echo "<CENTER><div class=text>";          
}


# request confirmation for an update or delete
# --------------------------------------------
if ($confirm != "") {

   //if ($title == "") 	{ array_push($error, "Title must not be blank"); }
   //if ($subtitle == "")	{ array_push($error, "Subtitle must not be blank"); }
   //if ($url  == "")	{ array_push($error, "URL must not be blank"); }


   if (!count($error) > 0) {

      # generate an appropriate message
      # -------------------------------
      if ($delete != "") {
         $confirmation_msg = "<div class=text>Are you sure you want to delete this page group?</div>";
      }
      else {
         $confirmation_msg = "<div class=text>Are you sure you want to update this page group?</div>";
         if ($hide != "yes") { 	$hide_text = "No"; }
         else { 		$hide_text = "Yes"; }
   
         $hide_text = "
 <tr>
  <td class=text>Hide </td>
  <td class=text>$hide_text </td>
 </tr>
";
      }


      if (count($error) > 0) { 
         show_error($error, $font, 177); 
         $confirm = "";
         $update  = "";
         $edit    = "yes";
      }
      else {

         $sql = "SELECT name FROM left_nav WHERE id = '$default_dir'";
         $result = mysql_query($sql) or die(mysql_error());
         $row = mysql_fetch_assoc($result);
         $default_directory = $row[name];      

         # show information being updated for confirmation
         # -----------------------------------------------
         echo "
<P>
<h4>$confirmation_msg</h4>
<form method=POST action=$PHP_SELF name=right_nav_test>
<table cellspacing=1 bgcolor=black><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>
 <tr>
  <td class=text>Title </td>
  <td class=text>$title </td>
 </tr>
 <tr>
  <td class=text>Subtitle </td>
  <td class=text>$subtitle </td>
 </tr>
 $hide_text 
 <tr>
  <td class=text>Default Directory </td>
  <td class=text>$default_directory </td>
 </tr>
 <tr>
  <td>
   <input type=hidden name=i value=$i>
   <input type=hidden name=title value=\"$title\">
   <input type=hidden name=subtitle value=\"$subtitle\">
   <input type=hidden name=delete value=$delete>
   <input type=hidden name=hide value=$hide>
   <input type=hidden name=default_dir value=$default_dir>
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
      $msg = "Add a New Page Group";
      $hidden_type = "";
      $dir_list = directory_dropdown("");
   }
   else {

      $msg = "Edit or Delete a Page Group ($i)";
      $dir_list = directory_dropdown($i);

      # retrieve information for the selected group
      # ---------------------------------------------
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

            if ($hide == "yes") { $hide_check = "checked"; }

         }
         else {
            array_push($error, "Invalid ID.  No such page group found.");
         }
      }
      
      if (count($error) > 0) { 
         show_error($error, $font, 298); 
         exit;
      }
   
      
      $group_info = "
 <tr><td colspan=2><table cellpadding=3 cellspacing=0 bgcolor=DDDDFF width=50%>
 <tr>
  <td bgcolor=EFEFEF class=text>Last Updated</td>
  <td bgcolor=EFEFEF class=text>$date</td>
 </tr>
 </table></td></tr>
	";


      # make reordering list
      # --------------------
      $group_list = reordering_list($i, $type, $font, $image_dir, $url_base);

   }  // End if edit not new
   
   
   # make the add/edit/delete form
   # -----------------------------
   echo "

<h4>$msg</h4>

<P>

<form method=POST action=$PHP_SELF name=right_nav_groups>
<table cellspacing=0 bgcolor=333333><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>

 $group_info

 <tr>
  <td class=text>Title </td>
  <td><input type=text size=40 maxlength=255 name=title value=\"$title\"></td>
 </tr>

 <tr>
  <td class=text>Subtitle </td>
  <td><input type=text size=40 maxlength=255 name=subtitle value=\"$subtitle\"></td>
 </tr>

 <tr>
  <td colspan=2 class=text>Select Default Directory: <BR>
  <select name=default_dir size=1>
  $dir_list
  </td>
 </tr>
	";

   if ($edit != "new") {
      $button = "confirm";
      echo "
 <tr>
  <td class=text>Delete </td>
  <td><input type=checkbox name=delete value=yes></td>
 </tr>
 	";
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


   # show a list of group links for reordering
   # -----------------------------------------
   if (($type == 'g') && ($edit != "new")) {
      echo "
<P>
<table width=500 bgcolor=EFEFEF>
 <tr>
  <td class=text align=center>
<a href=javascript:openWinRight('right_nav_links.php?edit=new&parent=$i');>
[ Add Link to Group ] </a>
  </td>
 </tr>
</table>
<P>
";
      if ($group_list != "") {
         echo "<div class=text>Click the up and down arrows to reorder the group's links.</div><BR><BR>\n";
         echo "<table cellpadding=4 cellspacing=1 bgcolor=333333>\n";
         echo $group_list;
         echo "</table>\n"; 
      }
   }

}  // End if edit not no


echo "</div>\n";



function show_error($error, $font, $line) {
   echo "<P><table>\n";
   echo "<tr><td class=text><font color=red><B>E R R O R :</B> ($line)</td></tr>\n";
   //foreach ($error as $msg) {
   while (!empty($error)) {
      $msg = array_shift($error);
      echo "<tr><td class=text><font color=red>$msg </font></td></tr>\n";
   }
   echo "</table>\n";
}



function reordering_list($i, $type, $font, $image_dir, $base_url) {

   # show a list of links for reordering
   # -----------------------------------
   $sql = "SELECT * FROM right_nav WHERE parent = '$i' ORDER BY list_order, title";
   $result = mysql_query($sql) or die(mysql_error());
   $submenu_count = mysql_num_rows($result);
   if ($row = mysql_fetch_assoc($result)) {
      $count = 1;
      do {
         # in case something has just been added to this group,
         # start with a proper sequential ordering to simplify reordering
         # --------------------------------------------------------------
         // NOW NOT NEEDED.  list_order SET WHEN NEW LINKS ARE ADDED.
         // $this_date = date("Y-m-d");
         // $q = "UPDATE right_nav SET list_order = $count, date = '$this_date' WHERE id=$row[id]";
         // mysql_query($q) or die(mysql_error());

         # is it hidden?
         # -------------
         if ($row[hide] == "y") {
            $hidden = "<img src=$image_dir/hidden.png border=1>";
         }  
         else {
            $hidden = "";
         }  


         if ($count != 1) {
            $up_link = "<a href=?i=$i&up=$row[id]&pos=$count> <img src=$image_dir/arrow_up_15.gif border=0> </a>";
         }
         else {
            $up_link = "";
         }
         if ($count != $submenu_count) {
            $down_link = "<a href=?i=$i&down=$row[id]&pos=$count> <img src=$image_dir/arrow_down_15.gif border=0> </a>";
         }
         else {
            $down_link = "";
         }


         $link_url = $base_url . $row[url];
         $submenu_list .= "
<tr>
  <td bgcolor=EFEFEF> $up_link </td>
  <td bgcolor=EFEFEF> $down_link </td>
  <td bgcolor=EFEFEF class=text> <a href=\"$link_url\">$row[title]</a> $hidden </td>
  <td bgcolor=EFEFEF><a href=javascript:openWinRight('right_nav_links.php?edit=yes&i=$row[id]&parent=$i');><img src=$image_dir/edit.png border=1></a></td>  
</tr>
	";

         $count = $count + 1;

      } while ($row = mysql_fetch_assoc($result));
   }

   return $submenu_list;

}


# make list of directories to put in dropdown list to allow for a 
# default directory for sibling groups
# ------------------------------------------------------------------
function directory_dropdown($i) {
   $sql = "SELECT default_dir FROM right_nav WHERE id = '$i'";
   $result = mysql_query($sql) or die(mysql_error());
   $row = mysql_fetch_assoc($result);
   $default = $row[default_dir];

   $sql = "SELECT id, name FROM left_nav WHERE type = 'd' AND hide != 'y' ORDER BY name";
   $result = mysql_query($sql) or die(mysql_error());
   if ($row = mysql_fetch_assoc($result)) {
      $dir_list = "<option value=></option>";
      $dir_list .= "<option value=0>[ Top-Level Directory ]</option>";
      do {
         // truncate directory names.  some are way too long.
         $limit = 70;
         $x = '[[:space:]][[:alnum:]]+$';
         $dd_name = $row[name];
         $selected = "";
         if ($row[id] == $default) { $selected = "selected"; }
         if (strlen($dd_name) > $limit) {
            $dd_name = substr($row[name], 0, $limit);
            $dd_name = ereg_replace($x, '...', $dd_name);
         }
   
         $dir_list .= "<option value=$row[id] $selected>[ $row[id] ] $dd_name </option>\n";
      } while ($row = mysql_fetch_assoc($result));
      $dir_list .= "</select>";
   }
   return $dir_list;
}

?>
