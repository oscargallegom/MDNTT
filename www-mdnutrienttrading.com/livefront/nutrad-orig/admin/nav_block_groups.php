<?php

# ----------------------------------------------------------------------------- 
#
# nav_block_groups.php
#
# 	Add, Edit or Delete nav blocks (More Info blocks)
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
$refresh_page = "nav_blocks.php";


if ($name != "") {
   $name = ereg_replace("\\\'", "&#039", $name);
   $name = ereg_replace('\\\"', "&#034", $name);
}


# reorder the links of a group
# --------------------------------
if ($up > 0) {
   $id = $up;
   $sql = "UPDATE nav_block_links SET list_order = $pos WHERE list_order = ($pos - 1) AND parent = $i";
   mysql_query($sql) or die(mysql_error());
   $sql = "UPDATE nav_block_links SET list_order = ($pos - 1) WHERE id = $id";
   mysql_query($sql) or die(mysql_error());
}
if ($down > 0) {
   $id = $down;
   $sql = "UPDATE nav_block_links SET list_order = $pos WHERE list_order = ($pos + 1) AND parent = $i";
   mysql_query($sql) or die(mysql_error());
   $sql = "UPDATE nav_block_links SET list_order = ($pos + 1) WHERE id = $id";
   mysql_query($sql) or die(mysql_error());
}


# cancel an update
# ----------------
if ($cancel != "") {
}


# perform an update or delete
# ---------------------------
if ($update != "") {

   if ($delete != "") {

      # first delete the nav files for pages using this nav block
      # ---------------------------------------------------------
      $sql = "SELECT * FROM nav_block_assignment WHERE block_id = $i";
      $result = mysql_query($sql) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         do {
            //$nav_filename = $nav_mgr->get_nav_block_filename($row[id]);
            $nav_filename = $nav_mgr->get_nav_filename($row[page_id], "more_info", "nav_block_links");
            if (is_writeable($nav_filename)) { unlink($nav_filename); }
         } while ($row = mysql_fetch_assoc($result));
      }

      # then delete the links from the database
      # ---------------------------------------
      $sql = "DELETE FROM nav_block_links WHERE parent = $i";
      mysql_query($sql) or die(mysql_error());

      $sql = "DELETE FROM nav_block_assignment WHERE block_id = $i";
      mysql_query($sql) or die(mysql_error());


      # and delete group itself from the database
      # -----------------------------------------
      $sql = "DELETE FROM nav_blocks WHERE id = $i";
      mysql_query($sql) or die(mysql_error());
      echo "<BR><BR><div class=text><font color=green>The More Info block has been deleted.</font></div><BR>";
      $edit = "no";

   }

   else {
      # update selected group
      # --------------------
      $sql = "UPDATE nav_blocks SET 
		name = '$name', 
		type = '$type', 
		hide = '$hide', 
		default_dir = '$default_dir', 
		date = '$this_date' 
		WHERE id = '$i'";
      $result = mysql_query($sql) or die(mysql_error());

      echo "<BR><BR><div class=text><font color=green>The More Info block has been updated.</font><BR>";
      $edit = "no";
    
   }
}


# add the new element
# -------------------
if ($add != "") {
   if ($name == "") {array_push($error,"You must enter a name for the More Info block.");}
   // if ($subtitle == "") {array_push($error,"You must enter a subtitle for the link.");}
   // if ($url == "") {array_push($error,"You must enter a URL for the link.");}
   // if (!is_numeric($parent)) {array_push($error,"No parent directory is specified.");}
   //if ($type == "") {array_push($error,"Please specify whether the link is for a directory or a page."); }

   //foreach ($required as $var) {
      //if ($$var == "") {
         //array_push($error, "$var must not be blank");
      //}
   //}  

   if (!count($error) > 0) { 
      $sql = "INSERT INTO nav_blocks (name, type, hide, date, default_dir) VALUES ('$name', '$type', '$hide', '$this_date', '$default_dir')";
      mysql_query($sql) or die(mysql_error());
      $edit = "no";

      $close_and_refresh = $onload;
      $alert_msg = "The More Info block has been added.";

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
<TITLE>MDA Site Navigation Manager - Edit More Info Blocks</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="textfonts.css">
END;
   include "js.php";
   echo "</HEAD> \n";
   echo "<BODY $close_and_refresh>";
   echo "<CENTER>";
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
         $confirmation_msg = "<div class=text>Are you sure you want to delete this More Info block?</div>";
      }
      else {
         $confirmation_msg = "<div class=text>Are you sure you want to update this More Info block?</div>";
         if ($hide != "y") { 	$hide_text = "No"; }
         else { 		$hide_text = "Yes"; }
   
         $hide_text = "
 <tr>
  <td class=text>Hide </td>
  <td class=text>$hide_text </td>
 </tr>
";
         if ($default_dir > 0) {
            $ddq = "SELECT name FROM left_nav WHERE id = $default_dir";
            $ddq_result = mysql_query($ddq);
            if ($ddq_row = mysql_fetch_assoc($ddq_result)) {
               $default_dir_name = $ddq_row[name];
            }
            else { $default_dir_name = ""; }
         }
         else { $default_dir_name = ""; }
 
         if      ($type == "mda") { $type_text = "MDA"; }
         else if ($type == "www") { $type_text = "WWW"; }
         else if ($type == "pdf") { $type_text = "PDF"; }
      }


      if (count($error) > 0) { 
         show_error($error, $font, 177); 
         $confirm = "";
         $update  = "";
         $edit    = "yes";
      }
      else {

         # show information being updated for confirmation
         # -----------------------------------------------
         echo "
<P>
<div class=text><h4>$confirmation_msg</h4></div>
<form method=POST action=$PHP_SELF name=right_nav_test>
<table cellspacing=1 bgcolor=black><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>
 <tr>
  <td class=text>Name </td>
  <td class=text>$name </td>
 </tr>
 <tr>
  <td class=text>Default Directory </td>
  <td class=text>$default_dir_name </td>
 </tr>
 <tr>
  <td class=text>Type </td>
  <td class=text>$type_text </td>
 </tr>
 $hide_text 
 <tr>
  <td>
   <input type=hidden name=i value=$i>
   <input type=hidden name=name value=\"$name\">
   <input type=hidden name=delete value=$delete>
   <input type=hidden name=hide value=$hide>
   <input type=hidden name=type value=$type>
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
      $msg = "Add a New More Info Block";
      $hidden_type = "";
      $dir_list = directory_dropdown("");
   }
   else {

      $msg = "Edit or Delete a More Info Block";
      $dir_list = directory_dropdown($i);

      # retrieve information for the selected group
      # ---------------------------------------------
      if ($i > 0) {
         $sql = "SELECT * FROM nav_blocks WHERE id = $i";
         $result = mysql_query($sql) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            $name 	= $row[name];
            $type 	= $row[type];
            $hide	= $row[hide];
            $date 	= $row["date"];

            if ($hide == "y") { $hide_check = "checked"; }

            if ($type == "mda") { $mda_checked = "checked"; }
            else if ($type == "www") { $www_checked = "checked"; }
            else if ($type == "pdf") { $pdf_checked = "checked"; }
            else { $mda_checked = "checked"; }

         }
         else {
            array_push($error, "Invalid ID.  No such More Info block found.");
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

<div class=text><h4><$font>$msg</font></h4></div>

<P>

<form method=POST action=$PHP_SELF name=right_nav_groups>
<table cellspacing=0 bgcolor=333333><tr><td>
<table cellpadding=3 cellspacing=3 bgcolor=EFEFEF>

 $group_info

 <tr>
  <td class=text>Name </td>
  <td><input type=text size=40 maxlength=255 name=name value=\"$name\"></td>
 </tr>
 <tr>
  <td colspan=2 class=text>Select Default Directory: <BR>
  <select name=default_dir size=1>
  $dir_list
  </td>
 </tr>
 <tr>
  <td class=text>Type </td>
  <td class=text>
   <input type=radio name=type value=mda $mda_checked> MDA &nbsp; &nbsp; 
   <input type=radio name=type value=www $www_checked> WWW &nbsp; &nbsp; 
   <input type=radio name=type value=pdf $pdf_checked> PDF &nbsp; &nbsp; 
  </td>
 </tr>
 <tr>
  <td class=text>Hide </td>
  <td><input type=checkbox name=hide value=y $hide_check></td>
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


   # "On Which Pages is this Block?" and "Add Link to Block" links
   # -------------------------------------------------------------
   echo "<P>
<table width=500 bgcolor=EFEFEF>
 <tr>
  <td class=text align=left>
<a href=javascript:openWinRight('nav_block_show.php?bid=$i');>
[ Pages Where This Block Has Been Placed ] </a>
  </td>

  <td class=text align=right>
<a href=javascript:openWinRight('nav_block_links.php?edit=new&parent=$i');>
[ Add Link to Block ] </a>
  </td>
 
 </tr>
</table>
<P>
";


   # Reorder links in group
   # ----------------------
   if ($group_list != "") {
      echo "<div class=text>Click the up and down arrows to reorder the block's links.</div><BR><BR>\n";
      echo "<table cellpadding=4 cellspacing=1 bgcolor=333333>\n";
      echo $group_list;
      echo "</table>\n"; 
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
   $sql = "SELECT * FROM nav_block_links WHERE parent = '$i' ORDER BY list_order, title";
   $result = mysql_query($sql) or die(mysql_error());
   $submenu_count = mysql_num_rows($result);
   if ($row = mysql_fetch_assoc($result)) {
      $count = 1;
      do {
         # in case something has just been added to this group,
         # start with a proper sequential ordering to simplify reordering
         # --------------------------------------------------------------
         // NOW NOT NEEDED.  list_order SET WHEN NEW LINKS ARE ADDED.
         $this_date = date("Y-m-d");
         $q = "UPDATE nav_block_links SET list_order = $count, date = '$this_date' WHERE id=$row[id]";
         mysql_query($q) or die(mysql_error());

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
  <td bgcolor=EFEFEF>$up_link</td>
  <td bgcolor=EFEFEF>$down_link</td>
  <td bgcolor=EFEFEF class=text><a href=\"$link_url\">$row[title]</a> $hidden </td>
  <td bgcolor=EFEFEF><a href=javascript:openWinMoreInfo('nav_block_links.php?edit=yes&i=$row[id]');><img src=$image_dir/edit.png border=1></a></td>  
</tr>
	";

         $count = $count + 1;

      } while ($row = mysql_fetch_assoc($result));
   }

   return $submenu_list;

}


# make list of directories to put in dropdown list to allow for a 
# default directory for nav blocks
# ---------------------------------------------------------------
function directory_dropdown($i) {
   $sql = "SELECT default_dir FROM nav_blocks WHERE id = '$i'";
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
