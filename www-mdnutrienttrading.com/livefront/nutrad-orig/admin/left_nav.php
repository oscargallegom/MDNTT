<?php

# -----------------------------------------------------------------------------
#
# left_nav.php
#
# 1)  Display formatted view of all navigational elements, i.e. any links that
# are registered with the main left-side navigation bar, whether they are 
# hidden or not.  The view of directories and subdirectories may be expanded
# by clicking the arrow next to the directory name.  All directories may be
# expanded by selecting 'Expand All'.
#
# 2)  Allow the administrator to edit the information pertaining to each element
# and reorder the listing of the elements via a popup window.
#
# 3)  Require that any element have a target URL (page) or index page (dir).
#
# 20040428 - ?, initial development
#
# -----------------------------------------------------------------------------


include "include_path.php";
require "class_nav_mgr.php";
session_start();
if (!is_object($nav_mgr)) {
   $nav_mgr = new Navigation_Manager($include_dir);
}
session_register("nav_mgr");

$width = $nav_mgr->left_nav_width;

include "$include_dir/db_connect.php";
include "$include_dir/nav_mgr_lock.php";
$unit = "left_nav";
include "nav_mgr_top.php";
$debug = "off";
set_time_limit(180);	// override the normal 30-second exec. time limit
			// because it can take longer than that to generate
			// the hundreds of navigational files.



# generate new nav include files as needed
# ----------------------------------------
if ( ($generate != "") && ($generate >= 0) ) {
   $nav_mgr->generate_left_nav($generate);
   $nav_mgr->debug($debug);

   # success/error report
   # --------------------
   if (count($nav_mgr->error) > 0) {
      echo "<table width=300><tr><td align=center><$nav_mgr->font color=green size=-1>Navigational include files have been regenerated, with the exception of the following errors.</font></td></tr></table>";
   }
   else {
      echo "<table width=300><tr><td align=center class=text><font color=greenThe navigational include files have been regenerated successfully.</font></td></tr></table>";
   }
   $nav_mgr->show_error();
   echo "<P>";

   # broken link report
   # ------------------
   $nav_mgr->show_broken_links();


   session_unset();
   $nav_mgr = new Navigation_Manager($include_dir);
}



# expand or collapse a directory with c=id o=id
# ---------------------------------------------
if ($o > 0) { 
   $nav_mgr->opened{"$o"} = "yes"; 
   $nav_mgr->closed{"$o"} = "no"; 
}
if ($c > 0) { 
   $nav_mgr->closed{"$c"} = "yes"; 
   $nav_mgr->opened{"$c"} = "no"; 
}



# get info for any elements that will be displayed
# ------------------------------------------------
$nav_mgr->get_info($view);
$image_dir = $nav_mgr->base_href . $nav_mgr->image_dir;

echo "<table width=$width><tr> \n";
echo "<td><a href=?view=open_all><img src=$image_dir/open_all.png border=0></a></td> \n";
echo "<td align=right><a href=?view=close_all><img src=$image_dir/close_all.png border=0></a></td> \n";
echo "</tr></table> \n";


# show the navigational elements selected for display
# ---------------------------------------------------
$nav_mgr->level = 0;
$nav_mgr->left_nav = "";
$nav_mgr->show_submenus(0, 0, "nav_bar_admin");  // (level, element_id, mode)
echo "$nav_mgr->left_nav \n";

$nav_mgr->debug($debug);



$_SESSION["nav_mgr"] = $nav_mgr;











?>
