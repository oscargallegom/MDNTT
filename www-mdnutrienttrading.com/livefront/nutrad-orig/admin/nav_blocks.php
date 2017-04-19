<?php

# -----------------------------------------------------------------------------
#
# nav_blocks.php
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
$unit = "blocks";


if ($_GET['sw'] != 'y') {
   # nav files were regenerated from the nav links pop-up window;
   # don't go to main nav block mgmt page.
   # ------------------------------------------------------------
   include "nav_mgr_top.php";
}


$debug = "off";


echo "<P> \n<CENTER>\n";


# generate new "More Info" include files as needed
# ------------------------------------------------
if ( ($generate != "") && ($generate >= 0) ) {
   $nav_mgr->generate_nav_blocks($generate);
   $nav_mgr->debug($debug);

   # success/error report
   # --------------------
   if (count($nav_mgr->error) > 0) {
      echo "<table width=300><tr><td class=text><font color=greenNavigational include files have been regenerated, with the exception of the following errors.</font></td></tr></table>";
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

   if ($_GET['sw'] == 'y') {
      # nav files were regenerated from the nav links pop-up window;
      # don't go to main nav block mgmt page.
      # ------------------------------------------------------------
      exit;
   }

}


# edit the selected block
# -----------------------
if (($_POST[cancel] == "") && (($i > 0) || (($edit == "new") && ($parent > 0))) ) {
   $included = "y";
   include "nav_block_groups.php";
   $_SESSION["nav_mgr"] = $nav_mgr;
   exit;
}
   

# or select a group to edit
# -------------------------
echo "

<div class=text>
Select a More Info Block to Update or Add a New More Info Block
</div><BR><BR>

<form method=POST action=$PHP_SELF>

";

$sql = "SELECT * FROM nav_blocks ORDER BY name";
$result = mysql_query($sql) or die(mysql_error());
if ($row = mysql_fetch_assoc($result)) {
   do {
      $group_dropdown .= "<option value=$row[id]>$row[name] [ $row[id] ]</option>\n";
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
