<?php

# ----------------------------------------------------------------------------- 
#
# left_nav_links.php
#
# 1)  Add, Edit or Delete navigational elements for the left navigation bar.
# 2)  Name (displayed text of link) and URL are updateable.
# 3)  Element can be moved to another directory by selecting new directory
# 	from a dropdown menu.
# 4)  A link may be "copied" to another directory.  This means that a hard link
#	of the original page is created in $docroot/sc/ (a designated
#	webserver-writeable directory) with a filename based on the original:
#		original -	/about/directions.php
#		first copy - 	/sc/about_directions.1.php
#		second copy - 	/sc/about_directions.2.php
#     This results in the exact same page being accessed in different 
#	directories, but with different left-side navigation relevent to the
#	directory where it was copied to.
#     Only pages may be copied, not directories.
# 5)  A link may be hidden, so that it won't appear in the site's navigation.
# 6)  A link may be designated as a directory or as a page.
# 7)  Directories may be reordered.
# 8)  'Regenerate Nav Files' will only regenerate navigational files that are
#	affected by changes to the selected item, instead of regenerating all.
#
# 20040429, ian@48thave.com, initial prototype
#
# ----------------------------------------------------------------------------- 



include "include_path.php"; 	// provides $include_dir
include "$include_dir/db_connect.php";
include "$include_dir/config.php";
require "class_nav_mgr.php";
session_start();
if (!is_object($nav_mgr)) {
   $nav_mgr = new Navigation_Manager();
}
session_register("nav_mgr");
include "$include_dir/nav_mgr_lock.php";

$font = $nav_mgr->font;
$docroot = $nav_mgr->docroot;
$url_base = $nav_mgr->url_base;
//$url_base_local = $nav_mgr->url_base_local;
$image_dir = $nav_mgr->base_href . $nav_mgr->image_dir;
$close_and_refresh = "onLoad=close_and_refresh()";
$cancel_and_close = "onLoad=cancel_and_close()";
$refresh_page = "left_nav.php";


$this_date = date("Y-m-d");
$error = array();
if ($name != "") {
   $name = ereg_replace("\\\'", "&#039", $name);
   $name = ereg_replace('\\\"', "&#034", $name);
}


echo <<<END
<html>
<head>
<title>MDA Site Navigation Manager - Edit Left Nav Link</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="textfonts.css">
</head>

<body>
END;


# generate new nav include files as needed
# ----------------------------------------
if ( ($generate != "") && ($generate >= 0) ) {
   $nav_mgr->generate_left_nav($generate);

   # success/error report
   # --------------------
   if (count($nav_mgr->error) > 0) {
      echo "<table width=300><tr><td><$nav_mgr->font color=green size=-1>Navigational include files have been regenerated, with the exception of the following errors.</font></td></tr></table>";
   }
   else {
      echo "<table width=300><tr><td><$nav_mgr->font color=green size=-1>The navigational include files have been regenerated successfully.</font></td></tr></table>";
   }
   $nav_mgr->show_error();
   echo "<P>";
   
   # broken link report
   # ------------------
   $nav_mgr->show_broken_links();
   
   session_unset();
   exit;
}



# reorder the links of a directory
# --------------------------------
if (($up > 0) && ($_GET[nb] != on)) {
   $id = $up;
   $sql = "UPDATE left_nav SET list_order = $pos WHERE list_order = ($pos - 1) AND parent = $i";
   mysql_query($sql) or die(mysql_error());
   $sql = "UPDATE left_nav SET list_order = ($pos - 1) WHERE id = $id";
   mysql_query($sql) or die(mysql_error());
}
if (($down > 0) && ($_GET[nb] != on)) {
   $id = $down;
   $sql = "UPDATE left_nav SET list_order = $pos WHERE list_order = ($pos + 1) AND parent = $i";
   mysql_query($sql) or die(mysql_error());
   $sql = "UPDATE left_nav SET list_order = ($pos + 1) WHERE id = $id";
   mysql_query($sql) or die(mysql_error());
}

# reorder the More Info blocks
# ------------------------------
if (($up > 0) && ($_GET[nb] == on)) {
   $id = $up;
   $sql = "UPDATE nav_block_assignment SET list_order = $pos WHERE list_order = ($pos - 1) AND page_id = $i";
   mysql_query($sql) or die(mysql_error());
   $sql = "UPDATE nav_block_assignment SET list_order = ($pos - 1) WHERE id = $id";
   mysql_query($sql) or die(mysql_error());
}
if (($down > 0) && ($_GET[nb] == on)) {
   $id = $down;
   $sql = "UPDATE nav_block_assignment SET list_order = $pos WHERE list_order = ($pos + 1) AND page_id = $i";
   mysql_query($sql) or die(mysql_error());
   $sql = "UPDATE nav_block_assignment SET list_order = ($pos + 1) WHERE id = $id";
   mysql_query($sql) or die(mysql_error());
}

# remove a "More Info" block
# --------------------------
if (($_GET[remove] > 0) && ($i > 0)) {
   $q = "DELETE FROM nav_block_assignment WHERE page_id = '$i' AND block_id = '$_GET[remove]'";
   mysql_query($q) or die(mysql_query());
   
   # delete the appropriate more_info nav file
   # -----------------------------------------
   $more_info_nav_file = $nav_mgr->get_nav_filename($i,"more_info","left_nav");
   if (is_file($more_info_nav_file)) {
      unlink($more_info_nav_file);
   }
}
# add a More Info block
# ---------------------
if (($_POST[add_nav_block] > 0) && ($_POST[i] > 0)) {
   $q = "INSERT INTO nav_block_assignment (block_id, page_id) VALUES ('$_POST[add_nav_block]', '$_POST[i]')";
   mysql_query($q) or die(mysql_query());
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

   $alert_msg = "";
   if ($delete != "") {
      # delete selected link
      # --------------------
      $nav_filename = $nav_mgr->get_nav_filename($i, "left", "left_nav");
      if ( (is_file($nav_filename)) && (is_writeable($nav_filename)) ) {
         unlink($nav_filename);
      }

      # and identify any links below this one that must also be deleted
      # ---------------------------------------------------------------
      $nav_mgr->prepare_nav_info();
      $nav_mgr->descendant_list = array();
      $nav_mgr->find_descendants($i);
      /*
      if (count($nav_mgr->descendant_list) > 0) {
         echo "<HR>If recursive deletions were enabled, the following links \n";
         echo "would also be deleted: <BR> \n";
         echo "<table> \n";
         foreach ($nav_mgr->descendant_list as $delete_candidate) {
            $q = "SELECT * FROM left_nav WHERE id = $delete_candidate";
            $result = mysql_query($q) or die(mysql_error());
            if ($row = mysql_fetch_assoc($result)) {
               $u = $row[url];
               echo "<tr><td><a href=\"$u\" target=_blank>$u</a></td></tr> \n";
            }
         }
         echo "</table><HR> \n";
      } 
      */

      $sql = "DELETE FROM left_nav WHERE id = $i";
      mysql_query($sql) or die("Unable to delete item: " . mysql_error());
      $alert_msg = "The navigational element (but not the actual file) has been deleted.";
      $onLoad = $close_and_refresh;
      $edit = "no";

   }

   else {

      # get pre-update info on the link
      # -------------------------------
      $sql = "SELECT * FROM left_nav WHERE id = '$i'";
      $result = mysql_query($sql) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         $link_type = $row['type'];
         $local_dir = $row['local'];
         $url_remove = $row['url'];
      }
      else { $link_type = ""; }


      # update selected link
      # --------------------
      $sql = "UPDATE left_nav SET name = '$name', url = '$url', parent = '$parent', hide = '$hide', date = '$this_date', type = '$type', local = '$local_link' WHERE id = '$i'";
      $result = mysql_query($sql) or die(mysql_error());
      $alert_msg = "The navigational element has been updated.";
      $edit = "no";


      # for copying a page/link, make a hard link in /sc/ translating slashes to
      # underscores and otherwise making a DB record just as for a real page
      # i.e., /about/directions.php  becomes  /sc/about_directions1.php
      # a second copy would be /sc/about_directions2.php, etc.
      # 
      # we need to gather/generate:
      #		original_path		i.e., "$docroot/about/directions.php"
      #		copy_filename		i.e., "about_us_directions1.php"
      #		copy_url		i.e., "/sc/about_us_directions1.php"
      # ---------------------------------------------------------------------
      if ($copy_to != "") {

         # copy: get $original_path
         # ------------------------
         $original_path = "$nav_mgr->docroot/$original_local_path";


         # copy: generate $copy_filename, $copy_url
         # ----------------------------------------
         $sql = "SELECT COUNT(id) AS copies FROM left_nav WHERE copy_of = $i";
         $result = mysql_query($sql) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            $count = $row[copies] + 1;
         }
         else {
            $count = 1;
         }
         $parts = split("\.", $original_local_path);
         $extension = array_pop($parts);
         $copy_extension = "$count.$extension";
         $original_local_path = ereg_replace("^\/+", "", $original_local_path);
         $copy_filename = ereg_replace("/", "_", $original_local_path);
         $copy_filename = ereg_replace("$extension$", "_$copy_extension", $copy_filename);
         $copy_url = "/sc/$copy_filename";


         # copy: make a hard link and register the link in the database
         # ------------------------------------------------------------
         if (!count($error) > 0) {
            if (!is_file("$nav_mgr->docroot/sc/$copy_filename")) {
               link("$original_path", "$nav_mgr->docroot/sc/$copy_filename") or die("
<table>
 <tr><td>Cannot create hard link: </td><td>$original_path -> $nav_mgr->docroot/sc/$copy_filename</td></tr>
 <tr><td>docroot: </td><td>$nav_mgr->docroot</td></tr>
 <tr><td>original_local_path: </td><td>$original_local_path</td></tr>
 <tr><td>copy_filename: </td><td>$copy_filename</td></tr>
 <tr><td>copy_url: </td><td>$copy_url</td></tr>
 </table>
");
               $alert_msg = "The page has been copied.";
            }
               $sql = "INSERT INTO left_nav (name, url, parent, hide, type, date, copy_of, local) VALUES ('$name', '$copy_url', '$copy_to', '$hide', '$type', '$this_date', '$i', 'y')";
               mysql_query($sql) or die("The new link could not be saved to the database.<BR>");
            
               $alert_msg = "The link has been registered in the navigation system.";
               $edit = "no";
         }
         else {
            show_error($error, $font, 115);   
         }
      }
   }


   # after successful update, close window and refresh main window
   # -------------------------------------------------------------
   if (!count($error) > 0) {
      $onLoad = $close_and_refresh;
   }


}


# add the new element
# -------------------
if ($add != "") {
   if ($name == "") {array_push($error,"You must enter a name for the link.");}
   if ($url == "") {array_push($error,"You must enter a URL for the link.");}
   if (!is_numeric($parent)) {array_push($error,"No parent directory is specified.");}
   if ($type == "") {array_push($error,"Please specify whether the link is for a directory or a page."); }


   if (!count($error) > 0) { 
      $sql = "INSERT INTO left_nav (name, url, parent, hide, type, date, local) VALUES ('$name', '$url', '$parent', '$hide', '$type', '$this_date', '$local_link')";
      mysql_query($sql) or die(mysql_error());
      $edit = "no";
      
      $alert_msg = "The element has been added.";
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

   if ($name == "") { array_push($error, "Name must not be blank"); }
   if ($url  == "") { array_push($error, "URL must not be blank"); }

   # if copying, check for existence of original file
   # ------------------------------------------------
   if ($copy_to != "") {

      # the url SHOULD be a full url with "http://www..."
      # -------------------------------------------------
      if (strstr($url, "$url_base")) { 
         $original_local_path = ereg_replace("$url_base", "", $url);
      }
      # but i haven't restricted the user from entering local paths, 
      # such as "/about/info.php"
      # ------------------------------------------------------------
      else if (preg_match("/^\//", $url)) {
         $original_local_path = $url;
      }
      else if (preg_match("/\/$/", $url)) {
         array_push($error, "Cannot copy a page that is an index page: $url");
      }
      else {
         array_push($error, "Cannot determine filename of file being copied.");
         unset($original_local_path);
      }


      if ( (!is_file("$nav_mgr->docroot/$local_path")) && (isset($local_path)) ) {
         array_push($error, "Original file does not exist.  Cannot make a copy of a nonexistent file."); 
      }
   }  // End if copy_to not blank


   if (!count($error) > 0) {

      # get pre-update info on the link
      # -------------------------------
      $sql = "SELECT * FROM left_nav WHERE id = '$i'";
      $result = mysql_query($sql) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         $link_type = $row['type'];
         $local_dir = $row['local'];
         $url_remove = $row['url'];
      }
      else { $link_type = ""; }
   
   
      # generate an appropriate message
      # -------------------------------
      if ($delete != "") {

         # Only allow a directory to be deleted or changed to type "p" (page)
         # if there are no subdirectories or pages under it.
         # ------------------------------------------------------------------
         if (($link_type == "d") AND ($local_dir == "y")) {
            $subdir_check = "SELECT * FROM left_nav WHERE parent = $i";
            $subdir_result = mysql_query($subdir_check) or die("Unable to check for subdirectories: <BR>" . mysql_error());
            if ($subdir_row = mysql_fetch_assoc($subdir_result)) {
               echo "<table width=400><tr><td class=text><H2><font color=red>Error: </H2></td></tr><tr><td class=text>This is a directory which contains subdirectories and/or pages.  A directory may not be deleted or changed to type \"page\" until it is empty.</td></tr></table>";
               exit;
            }
         }


         $confirmation_msg = "<div class=text>Are you sure you want to delete this navigational element?</div>";
      }
      else {
         if (($link_type == "d") AND ($type == "p") AND ($local_dir == "y")) {
            $subdir_check = "SELECT * FROM left_nav WHERE parent = $i";
            $subdir_result = mysql_query($subdir_check) or die("Unable to check for subdirectories: <BR>" . mysql_error());
            if ($subdir_row = mysql_fetch_assoc($subdir_result)) {
               echo "<table width=400><tr><td class=text><H2><font color=red>Error: </H2></td></tr><tr><td class=text>This is a directory which contains subdirectories and/or pages.  A directory may not be deleted or changed to type \"page\" until it is empty.</td></tr></table>";
               exit;
            }
         }
         $confirmation_msg = "<div class=text>Are you sure you want to update this navigational element?</div>";
      }


      # get name of parent, whether new or old
      # --------------------------------------
      if (($new_parent != "") && ($parent != $new_parent)) {
         $parent = $new_parent;
         $nav_mgr->moved = "yes";
      } 
      if ($parent == 0) { $parent_name = "Top-Level Directory"; }
      else {
         $sql = "SELECT name FROM left_nav WHERE id = '$parent'";
         $result = mysql_query($sql);
         if ($row = mysql_fetch_assoc($result)) {
            $parent_name = $row[name]; 
         }
      }

      # get name of directory being copied to
      # -------------------------------------
      if ($copy_to != "") {
         $sql = "SELECT name FROM left_nav WHERE id = '$copy_to'";
         $result = mysql_query($sql) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            $copy_dir = $row[name]; 
         }
         $copy_text = "
 <tr>
  <td><$font size=>Copy to </td>
  <td><$font size=>$copy_dir </td>
 </tr>
         ";
      }

      if ($hide != "yes") { $hide_text = "No"; }
         else { $hide_text = "Yes"; }
   
      if (count($error) > 0) { 
         show_error($error, $font, 177); 
         $confirm = "";
         $update  = "";
         $edit    = "yes";
      }
      else {
      # show information being updated for confirmation
      # -----------------------------------------------
         if ($type == "d") { $type_text = "Directory"; }
         if ($type == "p") { $type_text = "Page"; }
         if ($local_link == "y") { $local_text = "a local file"; }
         if ($local_link == "n") { $local_text = "an external link"; }
         echo "
<P>
<div class=text><h4>$confirmation_msg</h4></div>
<form method=POST action=$PHP_SELF name=update_nav_element>
<table cellspacing=1 bgcolor=black><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>
 <tr>
  <td class=text>Name </td>
  <td class=text>$name </td>
 </tr>
 <tr>
  <td class=text>URL </td>
  <td class=text>$url </td>
 </tr>
 <tr>
  <td class=text>Directory </td>
  <td class=text>$parent_name </td>
 </tr>
 $copy_text
 <tr>
  <td class=text>Hide </td>
  <td class=text>$hide_text </td>
 </tr>
 <tr>
  <td class=text>Type </td>
  <td class=text>$type_text </td>
 </tr>
 <tr>
  <td class=text>This is </td>
  <td class=text>$local_text </td>
 </tr>
 <tr>
  <td>
   <input type=hidden name=i value=$i>
   <input type=hidden name=name value=\"$name\">
   <input type=hidden name=url value=\"$url\">
   <input type=hidden name=parent value=$parent>
   <input type=hidden name=copy_to value=$copy_to>
   <input type=hidden name=delete value=$delete>
   <input type=hidden name=hide value=$hide>
   <input type=hidden name=type value=$type>
   <input type=hidden name=local_link value=$local_link>
   <input type=hidden name=original_local_path value=\"$original_local_path\">
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
      $msg = "Add a New Navigational Element";
      $hidden_type = "";
   }
   else {

      $msg = "Edit or Delete a Navigational Element";


      # retrieve information for the selected element
      # ---------------------------------------------
      if ($i > 0) {
         $sql = "SELECT * FROM left_nav WHERE id = $i";
         $result = mysql_query($sql) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            $name 	= $row[name];
            $url 	= $row[url];
            $list_order	= $row[list_order];
            $hide	= $row[hide];
            $parent 	= $row[parent];
            $date 	= $row["date"];
            $type 	= $row[type];
            $local_link	= $row[local];

            if ($hide == "yes") { $hide_check = "checked"; }

         }
         else {
            array_push($error, "Invalid ID.  No navigational element found.");
         }
      }
      
      if (count($error) > 0) { 
         show_error($error, $font, 298); 
         exit;
      }
   
      # make list of directories to put in dropdown list so that pages and 
      # directories may be moved
      # ------------------------------------------------------------------
      $sql = "SELECT id, name FROM left_nav WHERE type = 'd' AND hide != 'y' ORDER BY name";
      $result = mysql_query($sql) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         $dir_list = "<option value=></option>";
         $dir_list .= "<option value=0>[ Top-Level Directory ]</option>";
         do {
            # no need to move to the current directory or any of its 
            # subdirectories
            if ($row[name] == $name) { continue; }
            $parent_list = array();
            $parent_list = $nav_mgr->list_parents($row[id]);
            if (in_array($i, $parent_list)) { continue; }
        
            # truncate directory names.  some are way too long.
            $limit = 70;
            $x = '[[:space:]][[:alnum:]]+$';
            $dd_name = $row[name];
            if (strlen($dd_name) > $limit) {
               $dd_name = substr($row[name], 0, $limit);
               $dd_name = ereg_replace($x, '...', $dd_name);
            }

            $dir_list .= "<option value=$row[id]>[ $row[id] ] $dd_name </option>\n";
            if ($row[id] == $parent) { $parent_name = $row[name]; }
         } while ($row = mysql_fetch_assoc($result));
         $dir_list .= "</select>";
      }
      
      $element_info = "
 <tr><td colspan=2><table cellpadding=3 cellspacing=0 bgcolor=DDDDFF width=50%>
 <tr>
  <td bgcolor=EFEFEF class=text>Last Updated</td>
  <td bgcolor=EFEFEF class=text>$date</td>
 </tr>
 <tr>
  <td bgcolor=EFEFEF class=text>Parent Directory</td>
  <td bgcolor=EFEFEF class=text>$parent_name</td>
 </tr>
 </table></td></tr>
	";


      # make reordering list
      # --------------------
      $submenu_list = reordering_list($i, $type, $font, $image_dir);

   }  // End if edit not new
   
   
   # make the add/edit/delete form
   # -----------------------------
   echo "

<div class=text>
<h4>$msg</font></h4>
<a href=left_nav_links.php?generate=$i>[ Regenerate Left Nav Files ]</a>
</div>
<BR>


<P>

<form method=POST action=$PHP_SELF name=update_nav_element>
<table cellspacing=0 bgcolor=333333><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>

 $element_info

 <tr>
  <td class=text>Name </td>
  <td><input type=text size=40 maxlength=255 name=name value=\"$name\"></td>
 </tr>

 <tr>
  <td class=text>URL </td>
  <td><input type=text size=40 maxlength=255 name=url value=\"$url\"></td>
 </tr>

	";

   # dropdown menu for relocating existing links
   # -------------------------------------------
   if ($edit != "new") {
      $button = "confirm";
      if ($type == "p") {
         $copy_text = "
 <tr>
  <td colspan=2 align=center class=text>Make a copy of this link in another directory:<BR>
  <select name=copy_to size=1>
  $dir_list
  </td>
 </tr>

         ";
      } else { $copy_text = ""; }
      echo "
 <tr>
  <td colspan=2 align=center class=text>Move this link to another directory:<BR>
  <select name=new_parent size=1>
  $dir_list
  </td>
 </tr>
 $copy_text
 <tr>
  <td class=text>Hide </td>
  <td><input type=checkbox name=hide value=yes $hide_check></td>
 </tr>

 <tr>
  <td class=text>Delete </td>
  <td><input type=checkbox name=delete value=yes></td>
 </tr>
	";
   }
   else {
      # "Link Type" for new links
      # -------------------------
      $button = "add";
   }


   if ($type == "d") { $d_checked = "checked"; }
   else if ($type == "p") { $p_checked = "checked"; }

   if ($local_link == "") { $local_link = "y"; }
   if ($local_link == "y") { $y_checked = "checked"; }
   else if ($local_link == "n") { $n_checked = "checked"; }
   else if ($local_link == "s") { $s_checked = "checked"; }

   echo "
 <tr>
  <td class=text>Link Type </td>
  <td class=text>
   <input type=radio name=type value=d $d_checked> Directory &nbsp; &nbsp;
   <input type=radio name=type value=p $p_checked> Page
  </td>
 </tr>

 <tr>
  <td></td>
  <td class=text>
   <input type=radio name=local_link value=y $y_checked> Local file &nbsp; &nbsp;
   <input type=radio name=local_link value=n $n_checked> External link &nbsp; &nbsp;
   <input type=radio name=local_link value=s $s_checked> Shortcut to local page 
  </td>
 </tr>

	";

   echo "
 <tr>
  <td colspan=2>
   <table width=100%><tr>
  <td align=left>
   $hidden_type
   <input type=hidden name=parent value=$parent>
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


   # show a list of subdirectories for reordering
   # --------------------------------------------
   if ($type == 'd') {
      echo "<P><table bgcolor=EFEFEF width=500><tr><td class=text align=center><a href=?edit=new&parent=$i>[ Add a Link to this Directory ]</a></td></tr></table><P> \n";
      if ($submenu_list != "") {
         echo "<div class=text>Click the up and down arrows to reorder the <BR>elements of the directory's submenu.</div><BR><BR>";
         echo "<table cellpadding=4 cellspacing=1 bgcolor=333333>";
         echo $submenu_list;
         echo "</table><P>"; 
      }
   }


   # add, reorder, delete More Info blocks 
   # -------------------------------------
   $nav_block_list = nav_block_reordering_list($i, $font, $image_dir);
   $nav_block_dropdown = nav_block_dropdown();
   echo <<<END
<form method=POST action=$PHP_SELF>
<input type=hidden name=i value=$i>
<table>
 <tr>
  <td colspan=2 align=center><HR></td>
 </tr>
END;

   if ($nav_block_list != "") {
      echo <<<END
 <tr>
  <td colspan=2 align=center class=text><a href="nav_blocks.php?generate=0"><B>Regenerate More Info Nav Files</B><BR><BR></a></td>
 </tr>
END;
   }

   echo <<<END
 <tr>
  <td colspan=2 align=center class=text>Add a "More Info" Block</td>
 </tr>
 <tr>
  <td valign=top><select name=add_nav_block>$nav_block_dropdown</select> </td>
  <td valign=bottom> <input type=submit value=Add><BR><BR></td>
 </tr>
END;

   if ($nav_block_list != "") {
      echo <<<END
 <tr>
  <td colspan=2 align=center class=text>Click the up and down arrows to reorder the "More Info" Blocks.</td>
 </tr>
 <tr>
  <td colspan=2 align=center class=text><img src=$image_dir/removeX.png border=1> Remove</td>
 </tr>
 <tr>
  <td colspan=2 align=center>
   <table cellpadding=4 cellspacing=1 bgcolor=333333>$nav_block_list</table>
  </td>
 </tr>
END;
   }

   echo "</table></form> \n";



}  // End if edit not no



# reorder top-level links
# -----------------------
if ($reorder == "top") {
   if ($i != 0) { exit; }
   $submenu_list = reordering_list($i, "d", $font, $image_dir);

   echo "<BR><BR><div class=text>Click the up and down arrows to reorder the <BR>top-level links.</div><BR><BR>";
   echo "<table cellpadding=4 cellspacing=1 bgcolor=333333>";
   echo $submenu_list;
   echo "</table>"; 
}


function show_error($error, $font, $line) {
   echo "<P><table>";
   echo "<tr><td class=text><font color=red><B>E R R O R :</B> ($line)</td></tr>";
   while (!empty($error)) {
      $msg = array_shift($error);
      echo "<tr><td class=text><font color=red>$msg </font></td></tr>";
   }
   echo "</table>";
}



function reordering_list($i, $type, $font, $image_dir) {

   # for directories, show a list of submenu elements for reordering
   # ---------------------------------------------------------------
   if ($type == 'd') {
      $sql = "SELECT * FROM left_nav WHERE parent = '$i' ORDER BY list_order, name";
      $result = mysql_query($sql) or die(mysql_error());
      $submenu_count = mysql_num_rows($result);
      if ($row = mysql_fetch_assoc($result)) {
         $count = 1;
         if ($i == 0) { $extra = "reorder=top&edit=no"; }
         do {
            # in case something has just been moved to/from this directory,
            # start with a proper sequential ordering to simplify reordering
            # --------------------------------------------------------------
            $this_date = date("Y-m-d");
            $q = "UPDATE left_nav SET list_order = $count, date = '$this_date' WHERE id=$row[id]";
            mysql_query($q) or die(mysql_error());

            if ($count != 1) {
               $up_link = "<a href=?i=$i&up=$row[id]&pos=$count&$extra> <img src=$image_dir/arrow_up_15.gif border=0> </a>";
            }
            else {
               $up_link = "";
            }
            if ($count != $submenu_count) {
               $down_link = "<a href=?i=$i&down=$row[id]&pos=$count&$extra> <img src=$image_dir/arrow_down_15.gif border=0> </a>";
            }
            else {
               $down_link = "";
            }


   
            $submenu_list .= "
   <tr>
     <td bgcolor=EFEFEF> $up_link </td>
     <td bgcolor=EFEFEF> $down_link </td>
     <td bgcolor=EFEFEF class=text> $row[name] </td>
   </tr>
   	";
   
            $count = $count + 1;
   
         } while ($row = mysql_fetch_assoc($result));
      }
   }

   return $submenu_list;

}


function nav_block_reordering_list($i, $font, $image_dir) {

   # show a list of More Info blocks placed on this page
   # ---------------------------------------------
   $sql = "SELECT a.*, b.name FROM nav_block_assignment a, nav_blocks b WHERE a.page_id = '$i' AND a.block_id = b.id ORDER BY list_order, name";
   $result = mysql_query($sql) or die(mysql_error());
   $nav_block_count = mysql_num_rows($result);
   if ($row = mysql_fetch_assoc($result)) {
      $count = 1;
      if ($i == 0) { $extra = "reorder=top&edit=no"; }
      do {
         # start with a proper sequential ordering to simplify reordering
         # --------------------------------------------------------------
         $this_date = date("Y-m-d");
         $q = "UPDATE nav_block_assignment SET list_order = $count WHERE id=$row[id]";
//echo "sql: $q<BR>";
         mysql_query($q) or die(mysql_error());

         if ($count != 1) {
            $up_link = "<a href=?i=$i&nb=on&up=$row[id]&pos=$count&$extra> <img src=$image_dir/arrow_up_15.gif border=0> </a>";
         }
         else {
            $up_link = "";
         }
         if ($count != $nav_block_count) {
            $down_link = "<a href=?i=$i&nb=on&down=$row[id]&pos=$count&$extra> <img src=$image_dir/arrow_down_15.gif border=0> </a>";
         }
         else {
            $down_link = "";
         }

         $block_id = $row[block_id];
         $remove_link = "<a href=?i=$i&remove=$block_id><img src=$image_dir/removeX.png border=1></a>";

         if ($nav_block_count > 1) {
            $nav_block_list .= "
<tr>
  <td bgcolor=EFEFEF> $up_link </td>
  <td bgcolor=EFEFEF> $down_link </td>
  <td bgcolor=EFEFEF class=text> $row[name] </td>
  <td bgcolor=EFEFEF class=text>$remove_link</td>
</tr>
	";
         }
         else {
            $nav_block_list .= "
<tr>
  <td bgcolor=EFEFEF colspan=3 class=text> $row[name] </td>
  <td bgcolor=EFEFEF class=text>$remove_link</td>
</tr>
	";
         }

         $count++;

      } while ($row = mysql_fetch_assoc($result));
   }

   return $nav_block_list;

}


function nav_block_dropdown() {

   # make a dropdown list of all available nav blocks (those not hidden)
   # for selection for inclusion 
   # -------------------------------------------------------------------
   $q = "SELECT * FROM nav_blocks WHERE hide != 'y' ORDER BY name";
   $result = mysql_query($q) or die(mysql_error());
   if ($row = mysql_fetch_assoc($result)) {
      $block_list = "<option value=></option> \n";
      do {
         # make sure the names aren't too long
         $limit = 70;
         $x = '[[:space:]][[:alnum:]]+$';
         $dd_name = $row[name];
         if (strlen($dd_name) > $limit) {
            $dd_name = substr($row[name], 0, $limit);
            $dd_name = ereg_replace($x, '...', $dd_name);
         }

         $block_list .= "<option value=$row[id]> $dd_name </option> \n";

      } while ($row = mysql_fetch_assoc($result));
   }

   return $block_list;

}


?>
