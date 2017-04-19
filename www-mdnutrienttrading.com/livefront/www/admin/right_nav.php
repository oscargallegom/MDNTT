<?php

# -----------------------------------------------------------------------------
#
# right_nav.php
#
#
# -----------------------------------------------------------------------------


# setup
# -----
include "include_path.php";
require "class_nav_mgr.php";
session_start();
if (!is_object($nav_mgr)) {
   $nav_mgr = new Navigation_Manager($include_dir);
}
session_register("nav_mgr");
include "$include_dir/nav_mgr_lock.php";

include "$include_dir/db_connect.php";
$unit = "siblings";
include "nav_mgr_top.php";
$debug = "off";


echo "<P> \n<CENTER>\n";


# generate new nav include files as needed
# ----------------------------------------
if ( ($generate != "") && ($generate >= 0) ) {
   $nav_mgr->generate_right_nav($generate);
   $nav_mgr->debug($debug);

   # success/error report
   # --------------------
   if (count($nav_mgr->error) > 0) {
      echo "<table width=300><tr><td class=text><font color=green>Navigational include files have been regenerated, with the exception of the following errors.</font></td></tr></table>";
   }
   else {
      echo "<table width=300><tr><td class=text><font color=green>The navigational include files have been regenerated successfully.</font></td></tr></table>";
   }
   $nav_mgr->show_error();
   echo "<P>";

   # broken link report
   # ------------------
   $nav_mgr->show_broken_links();


   session_unset();
   $nav_mgr = new Navigation_Manager($include_dir);
}


# edit the selected group...
# --------------------------
if (($_POST[cancel] == "") &&( ($i > 0) || (($edit == "new") && ($parent > 0))) ) {
      $included = "y";
      include "right_nav_groups.php";
      $_SESSION["nav_mgr"] = $nav_mgr;
      exit;
}
   

# or select a group to edit
# -------------------------
echo "

<div class=text>
Select a Page Group to Update or Add a New Group
</div><BR><BR>

<form method=POST action=$PHP_SELF>

";

$sql = "SELECT * FROM right_nav WHERE type = 'g' ORDER BY title";
$result = mysql_query($sql) or die(mysql_error());
if ($row = mysql_fetch_assoc($result)) {
   do {
      $group_dropdown .= "<option value=$row[id]>$row[title] [ $row[id] ]</option>\n";
   } while ($row = mysql_fetch_assoc($result));
}

echo "

<table>
 <tr>
  <td>
	<select name=i>
	$group_dropdown
	</select>
  </td>

  <td>
   <input type=submit name=select value=\" Select \">
  </td>
 </tr>
</table>

</form>

";


$nav_mgr->debug($debug);
$_SESSION["nav_mgr"] = $nav_mgr;











?>
