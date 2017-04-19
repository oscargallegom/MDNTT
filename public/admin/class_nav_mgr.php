<?php


################################################################################
################################################################################

class Navigation_Manager {

   var $view;
   var $closed;
   var $opened;
   var $ofi = array();			// "ofi" = "open for include"
   var $cfi = array();			// "cfi" = "closed for include"
   var $level;
   var $left_nav;
   var $descendant_list = array();
   var $hidden = array();
   var $ih = array();
   var $error = array();
   var $broken_links = array();
   var $delete_copies = array();

   # set in config.php
   # -----------------
   var $url_base;
   var $base_href;
   var $docroot;
   var $index_page;
   var $image_dir;
   var $font;


   #############################################################################
   function Navigation_Manager() {

      # set up configurable parameters
      # ------------------------------
      include "include_path.php";
      include "$include_dir/config.php";
      foreach ($local as $key => $val) {
         $this->$key = $val;
      }
      $this->hidden 		= array();

   }



   #############################################################################
   function get_info($view) {

      if ($view == "close_all") {
         # only show the top-level elements
         # --------------------------------
         $this->opened = array();
         $this->closed = array();
         $sql = "SELECT * FROM left_nav WHERE parent = 0 ORDER BY list_order";
      }
      else if ($view == "open_all") { 
         # expand all: get info for all navigational elements
         # --------------------------------------------------
         $sql = "SELECT * FROM left_nav";
         $this->opened = array();
         $this->closed = array();
      }
      else if ((count($this->closed) > 0) || (count($this->opened) > 0)) {
         # drill down: get info for specific directories that have been "opened"
         # ---------------------------------------------------------------------
         $sql = "SELECT * FROM left_nav WHERE parent IN ('0',";
         foreach ($this->opened as $k => $v) {
            $sql .= "'$k',";
         }
         $sql = ereg_replace(",$", "", $sql);
         $sql .= ")";
      }
      else {
         # default: only show the top-level elements
         # -----------------------------------------
         $sql = "SELECT * FROM left_nav WHERE parent = 0 ORDER BY list_order";
         $this->view = "default";
      }


      $result = mysql_query($sql) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         do {
      
            # get info for each selected navigational element
            # -----------------------------------------------
            $this->{"element_$row[id]"}{name} 		= $row[name];
            $this->{"element_$row[id]"}{filename}	= $row[filename];
            $this->{"element_$row[id]"}{url} 		= $row[url];
            $this->{"element_$row[id]"}{list_order} 	= $row[list_order];
            $this->{"element_$row[id]"}{parent} 	= $row[parent];
            $this->{"element_$row[id]"}{hide} 		= $row[hide];
            $this->{"element_$row[id]"}{"date"} 	= $row["date"];
            $this->{"element_$row[id]"}{type} 		= $row[type];
            $this->{"element_$row[id]"}{copy_of}	= $row[copy_of];
            $this->{"element_$row[id]"}{local}		= $row[local];

            if ($row[hide] == "yes") {
               array_push($this->hidden, $id);
            }
      
            # put the element into the appropriate submenu
            # --------------------------------------------
            $this->{"submenu_$row[parent]"}{$row[id]} 	= $row[list_order];
      
            # reset the closed and opened parameters for close_all and open_all
            # -----------------------------------------------------------------
            if ($view == "close_all") { $this->closed{"$row[id]"} = "yes"; }
            if ($view == "open_all")  { $this->opened{"$row[id]"} = "yes"; }
   
         } while ($row = mysql_fetch_array($result));
      }
      
   }
   
   
   
   #############################################################################
   function show_submenus($level, $id, $mode) {

      # '$mode' is for "nav_bar_admin" or "nav_bar_include", depending on 
      # whether we're generating nav include files or the nav admin page
      # -----------------------------------------------------------------

      $submenu_temp = $this->{"submenu_$id"};
      $this->mode = $mode;

      # display link for directory and any submenus
      # -------------------------------------------
      if (is_array($submenu_temp)) { 
         asort($submenu_temp); # key is element id, val is list_order
         reset($submenu_temp);

         $this->$mode($level, $id, $dir_link);


         # for generating nav includes, set closed if not open
         # ---------------------------------------------------
         if (($mode == "nav_bar_include") && ($this->opened{"$id"} != "yes")) {
            $this->closed{"$id"} = "yes";
         }

         # create another nested table for a submenu?
         # ------------------------------------------
         if ((!empty($submenu_temp)) && ($this->closed{"$id"} != "yes")) {
            $this->begin_table($level, "");
            $close_table = "yes";
         }
         else {
            $close_table = "no";
         }

         # expand a directory if it has a submenu and the dir is not "closed"
         # ------------------------------------------------------------------
         $submenu_ids = array_keys($submenu_temp);
         $submenu_temp = array();
         while((!empty($submenu_ids))&&($this->closed{"$id"}!="yes")){
            $next_element = array_shift($submenu_ids);

            $last_child = "last_child_in_level_" . $level;
            if (empty($submenu_ids)) { 
               $this->$last_child = "yes"; 
            }
            else { 
               $this->$last_child = "no"; 
            }

            # recursively show any additional submenus
            # ----------------------------------------
            if (is_array($this->{"submenu_$next_element"})) {
               $this->level = $level;
               $this->show_submenus($level + 1, $next_element, $mode);
            }
            else {
               $this->$mode($level, $next_element, "");
            }

         }

         # don't close out a table if the submenu was not displayed
         # --------------------------------------------------------
         if ($close_table == "yes") {
            $this->end_table($level);
         }

      }

      # display link for a page
      # -----------------------
      else {
         $this->$mode($level, $id, "");
      }

      # clear the submenu after displaying it
      # -------------------------------------
      if ($mode != "nav_bar_include") {
         $this->{"submenu_$id"} = array();
         $this->{"submenu_0"} = array();
      }


   }
   
   
   #############################################################################
   function nav_bar_admin($level, $id, $dir_link) {

      $image_dir = $this->base_href . $this->image_dir;

      if ($id == 0) { return; }

      $name 		= $this->{"element_$id"}{name};
      $url 		= $this->{"element_$id"}{url};
      $list_order	= $this->{"element_$id"}{list_order};
      $parent		= $this->{"element_$id"}{parent};
      $hide		= $this->{"element_$id"}{"hide"};
      $date 		= $this->{"element_$id"}{"date"};
      $type 		= $this->{"element_$id"}{type};
      $copy_of 		= $this->{"element_$id"}{copy_of};
      $local 		= $this->{"element_$id"}{local};

      if ($hide == "yes") { 
         $hidden = "<img src=$image_dir/hidden.png border=1>"; 
      }
      if ($copy_of > 0) { 
         $q = "SELECT url FROM left_nav WHERE id = $copy_of";
         $result = mysql_query($q) or die(mysql_error());
         $row = mysql_fetch_assoc($result);
         $copied_from_url = $this->url_base_local . $row[url];
         $copy = "<a href=\"$copied_from_url\" target=_blank><img src=$image_dir/copy.png border=1></a>"; 
      }
      if ($url == "") { $url = "#$id"; }

      $following_tr = "
  <tr>
   <td colspan=4 background=$image_dir/dotline2.gif>
    <img src=$image_dir/pix.gif width=1 height=10>
   </td>
  </tr>
	";

      if((!empty($this->{"submenu_$id"}))&&($this->closed{"$id"} != "yes")){
         $dir_link = "<a name=$id><img src=$image_dir/pix.gif width=1></a><a href=$PHP_SELF?c=$id#$id><img src=$image_dir/arrow_nav_down.gif border=0></a>";
         $following_tr = "
  <tr>
   <td colspan=4><img src=$image_dir/pix.gif width=1 height=6></td>
  </tr>
	";
      }
      else if (($type == "d") AND ($this->opened{"$id"} == "yes")) {
         $dir_link = "<a name=$id><img src=$image_dir/pix.gif width=1></a><a href=$PHP_SELF?c=$id#$id><img src=$image_dir/arrow_nav_down.gif border=0></a>";
      }
      else if ($type == "d") {
         $dir_link = "<a name=$id><img src=$image_dir/pix.gif width=1></a><a href=$PHP_SELF?o=$id#$id><img src=$image_dir/arrow_nav_up.gif border=0></a>";
      }

      $font = $this->font;
      $size = "-1";
      if ($local != 'n') { $url = $this->url_base_local . $url; }

      $this->left_nav .= "
  <tr>
    <td>$dir_link</td>
    <td><img src=$image_dir/pix.gif width=2></td>
    <td><a href=\"$url\" class=$this->font_class target=_blank>$name</a> $hidden $copy </td>
    <td><a href=javascript:openWinLeft('left_nav_links.php?i=$id&t=$type');><img src=$image_dir/edit.png width=11 border=1></a></td>
  </tr>
  $following_tr

	";


      # clear this array once we've displayed it
      # ----------------------------------------
      $this->{"element_$id"} = array();

   }

   
   #############################################################################
   function nav_bar_include($level, $id, $start) {

      $image_dir = $this->image_dir;
      $element_id = $this->{"element_$id"};

      if ($id == 0) { return; }

      $name 		= $this->{"ie_$id"}{name};
      $url 		= $this->{"ie_$id"}{url};
      $list_order	= $this->{"ie_$id"}{list_order};
      $parent		= $this->{"ie_$id"}{parent};
      $hide		= $this->{"ie_$id"}{hide};
      $date 		= $this->{"ie_$id"}{"date"};
      $type 		= $this->{"ie_$id"}{type};
      $local 		= $this->{"ie_$id"}{local};

      if ($hide == "yes") { return; }

      # open a new browser window for external links.  local links should just
      # begin with a "/".
      # ----------------------------------------------------------------------
      if (preg_match('/^http/', $url)) {
         if (!preg_match('/.pdf$/', $links[url])) {
            $target = "target=_blank";
         }
         else { $target = ""; }
      }
      else if (preg_match('/^\//', $url)) {
         $url = preg_replace("|^/|", "", $url);
         $target = "";
      }
      else {
         $target = "";
      }

      if (($this->ofi{"$id"} == "yes") AND ($type == "d")) {
         $pointer = "<img src=$image_dir/arrow_nav_up.gif border=0>";
         if (count($this->{"is_$id"}) > 0) { 
            $following_tr = "
  <tr>
   <td colspan=4><img src=$image_dir/pix.gif width=1 height=6></td>
  </tr>
           ";
         }
         else {
            $following_tr = "
  <tr>
   <td colspan=4 background=$image_dir/dotline2.gif><img src=$image_dir/pix.gif width=1 height=10></td>
  </tr>
           ";
         }
      }
      else if (($this->ofi{"$id"} == "yes") AND ($type == "p")) {
         $pointer = "<img src=$image_dir/arrow_nav_up.gif border=0>";
         $following_tr = "
  <tr>
   <td colspan=4 background=$image_dir/dotline2.gif><img src=$image_dir/pix.gif width=1 height=10></td>
  </tr>
        ";
      }
      else {
         $pointer = "<img src=$image_dir/pix.gif height=1 width=11>";
         $following_tr = "
  <tr>
   <td colspan=4 background=$image_dir/dotline2.gif><img src=$image_dir/pix.gif width=1 height=10></td>
  </tr>
        ";
      }

      $last_child = "last_child_in_level_" . $level;
      if (($this->$last_child == "yes") && ($type == "p")) { 
         $following_tr = "
  <tr>
   <td colspan=4 background=$image_dir/dotline2.gif><img src=$image_dir/pix.gif width=1 height=7></td>
  </tr>
        ";
      }

      if (($this->{"last_menu_item_$level"} == "open")&&($level > 0)) {
         $extra_space = "
  <!-- level: $level -->
  <tr>
   <td colspan=4><img src=$image_dir/pix.gif width=1 height=6></td>
  </tr>
        ";
      }

      $background = "background=$image_dir/dotline.gif";
      if (($local != 'n') AND (!preg_match("/^http/", $url))) { 
         $url = "$this->url_base_local/$url";
      }

      if ($start == "start") {
         $bgcolor = "bgcolor=666633";
         $background = "";
         $valign = "valign=middle";
         $align = "align=center";
         $name = "<B>$name</B>";
         $code_block = "
  <tr>
   <td rowspan=5><img src=$image_dir/pix.gif></td>
   <td><img src=$image_dir/pix.gif width=2></td>
   <td rowspan=5><img src=$image_dir/pix.gif></td>
   <td></td>
  </tr>
  <tr>
    <td colspan=4 $align $valign>&nbsp;<a href=\"$url\" class=$this->font_class $target>$name</a></td>
  </tr>
  <tr>
   <td colspan=4><img src=$image_dir/pix.gif height=2></td>
  </tr>
	";
      }
      else {
         $code_block = $extra_space . "
  <tr>
   <td valign=top>$pointer</td>
   <td><img src=$image_dir/pix.gif width=2></td>
   <td><a href=\"$url\" class=$this->font_class $target>$name</a></td>
   <td><img src=$image_dir/pix.gif width=1></td>
  </tr>
  $following_tr

	";
      }

      $this->left_nav .= $code_block;

   }

   

   #############################################################################
   function begin_table($level, $nav_head) {

      $width = ($this->left_nav_width) - ($level * 11);
      if ($nav_head == "nav_head") { $color = 0; $width = "100%"; }
      else if ($this->mode == "nav_bar_admin") { $color = $level + 1; }
      else { $color = $level + 1; }
      $image_dir = $this->image_dir;

      # loop through only the last 3 colors listed below
      # ------------------------------------------------
      while ($color > 7) { $color = $color - 5; }  # silly, but scales
      if ($color > 4) { $color = $color - 3; }

      if      ($color == 0) { $table_color = "666633";$this->font_class = "link_top"; }
      else if ($color == 1) { $table_color = "CCCC99";$this->font_class = "link_1"; }
      else if ($color == 2) { $table_color = "E8E8BB";$this->font_class = "link_2"; }
      else if ($color == 3) { $table_color = "F5F5D7";$this->font_class = "link_2"; }
      else if ($color == 4) { $table_color = "EDE3B9";$this->font_class = "link_2"; }

      if ($level > $this->level) {
         $this->left_nav .= "
 <tr>
  <td></td>
  <td colspan=3 align=right>
	";
      }
      
      $this->left_nav .= "
<!-- BEGIN NAV LEVEL $level -->
<table cellpadding=0 cellspacing=0 border=0 bgcolor=$table_color width=$width>

	";
      if ($nav_head != "nav_head") {
         $this->left_nav .= "
 <tr>
  <td colspan=4 background=$image_dir/dotline_top2.gif><img src=$image_dir/pix.gif width=1 height=6></td>
 </tr>
	";
      }


   }  // End function begin_table



   #############################################################################
   function end_table($level) {

      if ($level == 1) { $this->font_class = "link_1"; }
      $image_dir = $this->image_dir;

      $last_child = "last_child_in_level_" . $level;
      if ($this->$last_child == "no") { 
         $this->left_nav .= "
  <tr>
    <td colspan=4 background=$image_dir/dotline2.gif><img src=$image_dir/pix.gif width=1></td>
  </tr>
	";
      }
      $this->left_nav .= "\n</table>\n";
      $this->left_nav .= "<!-- END NAV LEVEL $level --> \n\n";
      if (($level > 0) && ($this->$last_child != "yes")) {
         $this->left_nav .= "
</td></tr>
<tr>
 <td colspan=4><img src=$image_dir/pix.gif width=1 height=6></td>
</tr>
	";
      }


   }  // End function end_table




   #############################################################################
   function generate_left_nav($id) {
  
      $ids_to_generate	= array();
      $this->hidden	= array();
      $family		= array();
      $include_dir	= $this->include_dir;

      # in order to ensure that we hide any links under a hidden directory, 
      # always start at the top and work our way down, skipping anything 
      # hidden and therefor not looking up the IDs of its submenus.
      # same technique allows us to generate navigation for just a submenu
      # and its underlings.
      # -------------------------------------------------------------------
      if ( ($id == "0") || ($id >= 0) ) {

         # make a list of just those IDs that are affected by a change to 
         # this $id and not hidden.  a value of 0 will generate all navigation.
         # check all the way up to make sure no upstream dirs are hidden.
         # however, if any links have been moved to other directories, 
         # regenerate all.
         # ---------------------------------------------------------------------


         # 1. touch a lock file before we start
         # ------------------------------------
         include "$this->include_dir/nav_mgr_lock.php";
         touch($this->lockfile);

         # 2. check up to top. make sure we're not in a hidden dir 
         # -------------------------------------------------------
         $tt = $id;
         while ($tt != 0) {
            $sql = "SELECT id, parent, hide FROM left_nav WHERE id = $tt";
            $result = mysql_query($sql) or die(mysql_error());
            $row = mysql_fetch_assoc($result);
            if ($row[hide] == "yes") {
               array_push($this->ih, $row[id]);
               
               # since we're working up from the bottom, if we find a dir that
               # is hidden, we should put all dirs under it into array $hidden
               array_push($this->ih, $family);
               $this->ih = array_unique($this->ih);
            }
            else {
               array_push($family, $row[parent]);
            }
            $tt = $row[parent];
         }


         # 3. this link's parent
         # ---------------------
         if ($id != 0) {
            $sql = "SELECT parent FROM left_nav WHERE id = $id";
            $result = mysql_query($sql) or die(mysql_error());
            $row = mysql_fetch_assoc($result);
            if ($row[parent] != 0) {
               array_push($ids_to_generate, $row[parent]);
            }
            $parent = $row[parent];
         }
         else {
            $parent = $id;
            array_push($ids_to_generate, $id);
         }


         # 4. other links in this directory
         # --------------------------------
         $sql = "SELECT id, hide FROM left_nav WHERE parent = $parent";
         $result = mysql_query($sql) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            do {
               if ($row[hide] == "yes") {  
                  array_push($this->ih, $row[id]);
               }
               array_push($ids_to_generate, $row[id]);
            } while ($row = mysql_fetch_assoc($result));
         }


         # 5. find all submenus
         # --------------------
         $this->prepare_nav_info();	
         $this->descendant_list = array();
         $this->find_descendants($id);
         $ids_to_generate = array_merge($ids_to_generate, $this->descendant_list);



         # 6. generate or delete 
         # ---------------------
         $ids_to_generate = array_unique($ids_to_generate);
         sort($ids_to_generate);
         reset($ids_to_generate);
         $this->ih = array_unique($this->ih);
         foreach ($ids_to_generate as $id2g) {

            # make default.nav.html
            # ---------------------
            if (!$this->is_local($id2g)) { 
               continue; 
            }
            else if ($this->top_page($id2g)) { 
               continue; 
            }

            # determine filename for nav file
            # nav file for page:	/about/contact_us.php
            # would be:			/includes/nav/about_contact_us.php
            # ------------------------------------------------------------
            if ($nav_filename = $this->get_nav_filename($id2g, "left", "left_nav")) {

            }
            else {
               array_push($this->error, "Could not determine filename for id:  $id2g");
               continue;
            }


            # delete nav files for hidden pages/dirs
            # --------------------------------------
            if (in_array($id2g, $this->ih)) {
               if (strlen($nav_filename) < 288) {
                  if (is_file($nav_filename)) {
                     unlink($nav_filename);
                  }
               }
            }
            # or generate new nav file
            # ------------------------
            else {
               $this->ofi = array();
               $this->cfi = array();

               # get a list of parents up to the top.  these will be "open".
               # -----------------------------------------------------------
               $tt = $id2g;
               $this->ofi{"$id2g"} = "yes";
               $temp_parents = array();
               while ($tt != 0) {
                  $sql = "SELECT parent FROM left_nav WHERE id = $tt";
                  $result = mysql_query($sql);
                  $row = mysql_fetch_assoc($result);
                  if ($row[parent] == 0) { break; }
                  $tt = $row[parent];
                  $this->ofi{"$row[parent]"} = "yes";
               }
               $topmost_parent = $tt;
           
               # contents of nav file goes into $this->left_nav
               # ----------------------------------------------
               $this->left_nav = "";
               $this->selected_link = $id2g;
               $this->start_nav_file($topmost_parent); 
               $this->prepare_nav_file(0, $topmost_parent, "start"); 
               $this->finish_nav_file(); 

               # and write the file
               # ------------------
               if (strlen($nav_filename) < 288) { # found out limit of is_file!
                  if ((!is_file($nav_filename))||(is_writeable($nav_filename))){
                     if (is_file($nav_filename)) { unlink($nav_filename); }
                     $fp = fopen("$nav_filename", "w");
                     fputs($fp, $this->left_nav);
                     fclose($fp); 
                  }
               }
               else {
                  array_push($this->error, "Filename is too long: <$this->font color=black size=-1>$nav_filename</font>");
               }
            }
         }  // end foreach ids to generate


         # remove lock file
         # ----------------
         unlink($this->lockfile);

 
      } 
   }  // End function generate_left_nav



   #############################################################################
   function generate_right_nav($id) {
   
      $ids_to_generate	= array();
      $this->hidden	= array();
      $include_dir	= $this->include_dir;

      if ($id >= 0) {

         # make a list of just those IDs that are affected by a change to this 
         # $id and not hidden.  a value of 0 will generate all navigation.
         #
         # 1. touch a lock file before we start
         # ------------------------------------
         include "$this->include_dir/nav_mgr_lock.php";
         touch($this->lockfile);

         # 2. figure out which files to regenerate
         # ---------------------------------------
         if ($id == 0) {
            $sql = "SELECT * FROM right_nav WHERE type = 'p' AND hide != 'y' AND url != ''";
            $result = mysql_query($sql);
            if ($row = mysql_fetch_assoc($result)) {
               do {
                  if ($row[hide] == "yes") {
                     array_push($this->hidden, $row[id]);
                  }
                  else if (preg_match('/pdf/i', $row[url])) {
                     next;
                  }
                  else {
                     array_push($ids_to_generate, $row[id]);
                  }
               } while ($row = mysql_fetch_assoc($result));
            }
            else {
               return;
            }
         }
         else {
            array_push($ids_to_generate, $id);
         }

 

         # 3. generate or delete 
         # ---------------------
         $ids_to_generate = array_unique($ids_to_generate);
         $this->hidden = array_unique($this->hidden);
         foreach ($ids_to_generate as $id2g) {

            # determine filename for nav file
            # nav file for page:	/about/contact_us.php
            # would be:			/includes/nav/about_contact_us.php
            # ------------------------------------------------------------
            if (!$nav_filename = $this->get_right_nav_filename($id2g, "right")) {
               continue; 
            }


            # delete nav files for hidden pages/dirs
            # --------------------------------------
            if (in_array($id2g, $this->ih)) {
               if (strlen($nav_filename) < 288) {
                  if (is_file($nav_filename)) {
                     unlink($nav_filename);
                  }
               }
            }

            # or generate new nav file
            # ------------------------
            else {
               $this->right_nav = "";
               $this->selected_link = $id2g;
               $this->prepare_right_nav_file($id2g);

               if (strlen($nav_filename) < 288) {
                  if ((!is_file($nav_filename)) || (is_writeable($nav_filename))) {
                     $fp = fopen("$nav_filename", "w");
                     fputs($fp, $this->right_nav);
                     fclose($fp); 
                  }
                  else {
                     array_push($this->error, "File is not writeable: <$this->font color=black size=-1>$nav_filename</font>");
                  }
               }
               else { 
                  array_push($this->error, "Filename is too long: <$this->font color=black size=-1>$nav_filename</font>");
               }
            }
         }


         # remove lock file
         # ----------------
         unlink($this->lockfile);

 
      } 
   }  // End function generate_right_nav


   #############################################################################
   function generate_nav_blocks($id) {
   
      $ids_to_generate	= array();
      $this->hidden	= array();
      $include_dir	= $this->include_dir;

      if ($id >= 0) {

         # make a list of just those IDs that are affected by a change to this 
         # $id and not hidden.  a value of 0 will generate all navigation.
         #
         # 1. touch a lock file before we start
         # ------------------------------------
         include "$this->include_dir/nav_mgr_lock.php";
         touch($this->lockfile);

         # 2. figure out which files to regenerate
         #    these are pages that are all registered in the left nav
         # ----------------------------------------------------------
         $sql = "SELECT * FROM nav_block_assignment";
         $result = mysql_query($sql) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            do {
               array_push($ids_to_generate, $row[page_id]);
            } while ($row = mysql_fetch_assoc($result));
         }
         else {
            # then no nav files to generate
            unlink($this->lockfile);
            return;
         }

 

         # 3. generate or delete 
         # ---------------------
         $ids_to_generate = array_unique($ids_to_generate);
         $this->hidden = array_unique($this->hidden);
         foreach ($ids_to_generate as $id2g) {

            # determine filename for nav file
            # nav file for:	/about/contact_us.php
            # would be:		/includes/nav/more_info/about_contact_us.php
            # ------------------------------------------------------------
            if ($nav_filename = $this->get_nav_filename($id2g, "more_info", "left_nav")) {

            }
            else {
               continue;
            }


            # delete nav files for hidden pages/dirs
            # --------------------------------------
            if (in_array($id2g, $this->ih)) {
               /*
               if (strlen($nav_filename) < 288) {
                  if (is_file($nav_filename)) {
                     unlink($nav_filename);
                  }
               }
               */
            } 

            # or generate new nav file
            # ------------------------
            else {
               $this->nav_block = "";
               $this->selected_link = $id2g;
               $this->prepare_nav_block_file($id2g);

               if (strlen($nav_filename) < 288) {
                  if ((!is_file($nav_filename)) || (is_writeable($nav_filename))) {
                     if ($fp = fopen("$nav_filename", "w")) {
                        fputs($fp, $this->nav_block);
                        fclose($fp); 
                     }
                     else {
                        array_push($this->error, "File is not writeable: <$this->font color=black size=-1>$nav_filename</font>");
                     }
                  }
                  else {
                     array_push($this->error, "File is not writeable: <$this->font color=black size=-1>$nav_filename</font>");
                  }
               }
               else { 
                  array_push($this->error, "Filename is too long: <$this->font color=black size=-1>$nav_filename</font>");
               }
            }
         }


         # then generate default left nav for any pages that are not linked
         # into the left nav system that appear to be local
         # ----------------------------------------------------------------
         $q = "SELECT * FROM nav_blocks";
         $result = mysql_query($q) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            do {
               $bi = $row[id];
               ${"default_dir_$bi"} = $row[default_dir];
            } while ($row = mysql_fetch_assoc($result));
         }

         $q = "SELECT * FROM nav_block_links";
         $result = mysql_query($q) or die(mysql_error());
         if ($row = mysql_fetch_assoc($result)) {
            do {
               $lurl = $row[url];
               $default_dir = ${"default_dir_$row[parent]"};
               if ($default_dir_nav = $this->get_nav_filename($default_dir, "left", "left_nav")) {
                  if (!is_file($default_dir_nav)) {
                     # no nav file to link to; continue
                     continue;
                  }
               }
               else {
                  continue;
               }
               $block_id = $row[parent];
               if (!preg_match('/.pdf/i', $lurl)) {
                  # make_filename will return false if it considers the url to
                  # be non-local
                  # ----------------------------------------------------------
                  if ($left_nav_filename = 
			$this->make_filename($lurl, "left", "full")) {
                     # replace a symlink'ed nav file with a new symlink to the 
                     # default dir nav file using relative paths
                     $lnf = array();
                     $lnf = split("/", $left_nav_filename);
                     $lnf_relative = array_pop($lnf);
                     $ddf = array();
                     $ddf = split("/", $default_dir_nav);
                     $ddf_relative = array_pop($ddf);
                     chdir("$this->include_dir/nav/left/");

                     if (is_file($lnf_relative)) {
                        if (is_link($lnf_relative)) {
                           unlink($lnf_relative);
                           symlink($ddf_relative, $lnf_relative);
                        }
                     }
                     else if (!is_file($lnf_relative)) {
                        symlink($ddf_relative, $lnf_relative);
                     }
                  }
               }
            } while ($row = mysql_fetch_assoc($result));
         }



         # remove lock file
         # ----------------
         unlink($this->lockfile);

 
      } 
   }  // End function generate_nav_blocks




   #############################################################################
   function start_nav_file($id) {

      $width = $this->left_nav_width;
      $this->left_nav .= "
<!-- BEGIN LEFT NAV -->
<table cellpadding=0 cellspacing=1 border=0 width=$width bgcolor=666633>
 <tr>
  <td>
		";
      $this->begin_table(0, "nav_head");
      $this->nav_bar_include(0, $id, "start");
      $this->end_table(0);

   }
   #############################################################################
   function finish_nav_file() {

      $this->left_nav .= "
  </td>
 </tr>
</table>
<!-- END LEFT NAV -->
		\n";

   }


   #############################################################################
   function prepare_nav_file($level, $id, $start) {

      # display link for directory and any submenus
      # -------------------------------------------
      $submenu_temp = $this->{"is_$id"};
      if (is_array($submenu_temp)) { 
         asort($submenu_temp); 	# key is element id, val is list_order - 
         reset($submenu_temp); 	# sort them by list_order
         $submenu_ids = array_keys($submenu_temp);

         $this->level = $level;
         if ($start != "start") {
            $next_level = $level + 1;
            $this->nav_bar_include($level, $id, "");
         }
         else { $next_level = $level; }
      

         # for generating nav includes, set closed if not open
         # ---------------------------------------------------
         if ($this->ofi{"$id"} != "yes") {
            $this->cfi{"$id"} = "yes";
         }

         # create another nested table for a submenu?
         # ------------------------------------------
         if ((!empty($submenu_temp)) AND ($this->cfi{"$id"} != "yes")) {
            $this->begin_table($next_level, "");
            $close_table = "yes";
         }
         else {
            $close_table = "no";
         }

         # expand a directory if it has a submenu and the dir is not "closed"
         # ------------------------------------------------------------------
         $submenu_temp = array();
         while((!empty($submenu_ids))&&($this->ofi{"$id"}=="yes")){
            $this->{"last_menu_item_$level"} = "closed";
            $next_element = array_shift($submenu_ids);

            # we need some extra HTML code at the end of a submenu
            # ----------------------------------------------------
            $last_child = "last_child_in_level_" . $next_level;
            if (empty($submenu_ids)) { 
               $this->$last_child = "yes"; 
            }
            else { 
               $this->$last_child = "no"; 
            }

            # recursively show any additional submenus
            # ----------------------------------------
            if (is_array($this->{"is_$next_element"})) {
               $this->prepare_nav_file($next_level, $next_element, "");
               if ($this->ofi{"$next_element"} == "yes") {
                  $this->{"last_menu_item_$level"} = "open";
               }
            }
            else {
               $this->nav_bar_include($next_level, $next_element, "");
            }
         }

         # don't close out a table if the submenu was not displayed
         # --------------------------------------------------------
         if ($close_table == "yes") {
            $this->end_table($next_level);
         }

      }

      # display link for a page
      # -----------------------
      else {
         $this->nav_bar_include($level, $id, "");
      }

      # clear the submenu after displaying it
      # -------------------------------------
      $this->{"submenu_$id"} = array();
      $this->{"submenu_0"} = array();


   }



   #############################################################################
   function prepare_nav_info() {


      $this->ofi = array();  // ofi = open for include
      $this->cfi = array();  // cfi = close for include
      $sql = "SELECT * FROM left_nav";

      $result = mysql_query($sql) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         do {
            # get info for each link (ie = include element)
            # ---------------------------------------------
            $this->{"ie_$row[id]"}{name} 	= $row[name];
            $this->{"ie_$row[id]"}{filename} 	= $row[filename];
            $this->{"ie_$row[id]"}{url} 	= $row[url];
            $this->{"ie_$row[id]"}{list_order} 	= $row[list_order];
            $this->{"ie_$row[id]"}{parent} 	= $row[parent];
            $this->{"ie_$row[id]"}{hide} 	= $row[hide];
            $this->{"ie_$row[id]"}{"date"} 	= $row["date"];
            $this->{"ie_$row[id]"}{type} 	= $row[type];
            $this->{"ie_$row[id]"}{copy_of}	= $row[copy_of];
            $this->{"ie_$row[id]"}{local}	= $row[local];

            if ($row[hide] == "yes") {
               array_push($this->ih, $row[id]);
            }
      
            # put the element into appropriate submenu (is = include submenu)
            # ---------------------------------------------------------------
            $this->{"is_$row[parent]"}{$row[id]} = $row[list_order];
      
         } while ($row = mysql_fetch_array($result));
      }
      
   }



   #############################################################################
   function prepare_right_nav_file($id) {

      # configurable settings (config.php)
      # ----------------------------------
      $width = $this->right_nav_width;
      $bgcolor = $this->right_nav_bgcolor;

      # get ID of this group
      # --------------------
      $q = "SELECT parent FROM right_nav WHERE id = '$id'"; 
      $result = mysql_query($q);
      $row = mysql_fetch_assoc($result);
      $parent = $row{parent};

      # get group info
      # --------------
      $q = "SELECT * FROM right_nav WHERE id = '$parent'"; 
      $result = mysql_query($q);
      $group_info = array();
      $group_info = mysql_fetch_assoc($result);
      if ($group_info{hide} == 'y') { return; }
      $title = $group_info{title};
      $subtitle = $group_info{subtitle};
 
      # make left nav file for the page a symlink to the default dir nav file
      # if it doesn't already have a left nav file
      # ---------------------------------------------------------------------
      $default_dir = $group_info{default_dir};
      $default_dir_nav=$this->get_nav_filename($default_dir,"left","left_nav");
      $this_nav = $this->get_right_nav_filename($id, "left");
      if (($default_dir_nav != "") && ($default_dir_nav != $this_nav) && ($this_nav != "")) {
         # make symlinks using relative paths for portability
         # --------------------------------------------------
         $tn = array();
         $tn = split("/", $this_nav);
         $tn_relative = array_pop($tn);

         $ddf = array();
         $ddf = split("/", $default_dir_nav);
         $ddf_relative = array_pop($ddf);
         chdir("$this->include_dir/nav/left/");

         if (is_file($tn_relative)) {
            if ( (is_link($tn_relative)) && (is_writeable($tn_relative)) ) {
               if (is_file($ddf_relative)) {
                  unlink($tn_relative);
                  symlink($ddf_relative, $tn_relative);
               }
               else {
                  array_push($this->error,"Default directory nav file $ddf_relative does not exist.");
               }
            }
         }
         else if (!is_file($tn_relative)) {
            if (is_file($ddf_relative)) {
               symlink($ddf_relative, $tn_relative);
            }
            else {
               array_push($this->error,"Default directory nav file $ddf_relative does not exist.");
            }
               
         }
      }

      $image_dir = $this->image_dir;

      if ($subtitle != "") {
         $subtitle_html = "
<tr>
 <td><img src=$image_dir/pix.gif width=6 height=1></td>
 <td class=text>$subtitle</td>
 <td><img src=$image_dir/pix.gif width=6 height=1></td>
</tr>
<tr>
 <td colspan=3><img src=$image_dir/pix.gif height=6></td>
</tr>
	";
      }

      $this->right_nav = <<<EOF
<!-- BEGIN SIBLING TABLE -->

<table cellpadding=0 cellspacing=0 border=0 bgcolor=$bgcolor width=$width>
<tr>
  <td rowspan=6 bgcolor=$bgcolor><img src=$image_dir/pix.gif></td>
         
  <td bgcolor=$bgcolor><img src=$image_dir/pix.gif height=2></td>
  <td rowspan=6 bgcolor=$bgcolor><img src=$image_dir/pix.gif></td>
</tr>    
<tr>
  <td align=center class=header_white>$title</td>
</tr>
<tr>
  <td><img src=$image_dir/pix.gif height=2></td>
</tr>
<tr>
  <td bgcolor=DDE2EF><img src=$image_dir/pix.gif height=4></td>
   
</tr>
<tr>
  <td align=center bgcolor=DDE2EF>
  
   <table cellpadding=0 cellspacing=0 border=0 bgcolor=DDE2EF width=138>
    $subtitle_html
EOF;


      # get each sibling page's info
      # ----------------------------
      $q = "SELECT * FROM right_nav WHERE parent = '$parent' AND type = 'p' AND hide != 'y' ORDER BY list_order";
      $sibling_count = 0;
      $result = mysql_query($q);
      $row = array();
      if ($row = mysql_fetch_assoc($result)) {
         do {
            $sibling_count++;
            $title = $row{title}; 
            $subtitle = $row{subtitle}; 
            $url = $row{url};
            if (!preg_match("/^http/", $url)) {
               if (preg_match("/^\//", $url)) {
                  $url = $this->url_base_local . $url;
               }
               else {
                  $url = "$this->url_base_local/$url";
               }
            }
            
            if ($row[id] == $id) { 
               $pointer = "<a href=\"$url\"><img src=$image_dir/arrow_sibling.gif border=0></a>"; 
            }
            else { $pointer = ""; }
               

            if ($title != "") {
               $html_title = <<<EOF
<a href="$url" class=link_sibling_title>$title</a><BR>
EOF;
            }
            else { $html_title = ""; }
            if ($subtitle != "") {
               if ($title != "") { $html_subtitle = "<BR>"; }
               $html_subtitle .= <<<EOF
<a href="$url" class=link_sibling_subtitle>$subtitle</a><BR>
EOF;
            }
            else { $html_subtitle = ""; }

            $this->right_nav .= <<<EOF
<!-- LINK $sibling_count -->
   <tr>
     <td valign=top>$pointer</a></td>
     <td valign=top>$html_title $html_subtitle</td>
     <td><img src=$image_dir/pix.gif width=6 height=1></td>
   </tr>
   <tr>
     <td colspan=3><img src=$image_dir/pix.gif height=6></td>
   </tr>
EOF;


         } while ($row = mysql_fetch_assoc($result));
      }
      else { $this->right_nav = ""; return; }

      $this->right_nav .= <<<EOF
   </table>
  </td>
 </tr>
<tr>
  <td bgcolor=DDE2EF><img src=$image_dir/pix.gif height=4></td>
</tr>
<tr>
  <td bgcolor=$bgcolor><img src=$image_dir/pix.gif></td>
</tr>
</table>

<!-- END SIBLING TABLE -->

<p>

EOF;


   }  // End function prepare_right_nav_file


   #############################################################################
   function prepare_nav_block_file($id) {

      # configurable settings (config.php)
      # ----------------------------------
      $width = $this->nav_block_width;
      $width2 = $width - 2;


      # loop through nav block placed on this page
      # ------------------------------------------
      $q = "SELECT block_id, list_order FROM nav_block_assignment WHERE page_id = '$id' ORDER BY list_order";
      $result = mysql_query($q);
      if ($row = mysql_fetch_assoc($result)) {
         $this->nav_block = "";
         $block_count = 0;
         do {
            $block_count++;

            # get nav block info
            # ------------------
            $q2 = "SELECT * FROM nav_blocks WHERE id = '$row[block_id]'"; 
            $result2 = mysql_query($q2);
            $block_info = array();
            $block_info = mysql_fetch_assoc($result2);
            if ($block_info{hide} == 'y') { continue; }

            if ($block_info{type} == 'mda') { 
               $title = "MDA"; 
               $bgcolor = "FFFCDF"; 
               $tab = "tab_more_info_mda.gif"; 
            }
            else if ($block_info{type} == 'www') {
               $title = "WWW";
               $bgcolor = "F0EEE3";
               $tab = "tab_more_info_www.gif"; 
            }
            else if ($block_info{type} == 'pdf') {
               $title = "PDF";
               $bgcolor = "F9E0D1";
               $tab = "tab_more_info_pdf.gif"; 
            }

            $name = $group_info{name};

            $image_dir = $this->image_dir;

            # start the nav block's table
            # ---------------------------
            $this->nav_block .= <<<EOF
<!-- BEGIN NAV BLOCK TABLE $block_count -->

<table cellpadding=0 cellspacing=0 border=0 bgcolor=white width=$width>
<tr>
  <td width=1 bgcolor=white><img src=$image_dir/pix.gif></td>
  <td width=$width2><img src=$image_dir/$tab></td>
  <td width=1 bgcolor=959595><img src=$image_dir/pix.gif></td>
</tr>    
<tr>
  <td width=1 bgcolor=959595><img src=$image_dir/pix.gif></td>
  <td width=$width2 bgcolor=$bgcolor>
  
   <table cellpadding=0 cellspacing=0 border=0 bgcolor=$bgcolor width=$width2>
   <tr>
    <td colspan=3><img src=$image_dir/pix.gif height=6></td>
   </tr>

EOF;

            # get info on individual links for each nav block on the page
            # -----------------------------------------------------------
            $q3 = "SELECT * FROM nav_block_links WHERE parent = '$row[block_id]' ORDER BY list_order";
            $result3 = mysql_query($q3);
            if ($links = mysql_fetch_assoc($result3)) {
               $link_count = 0;
               do {
                  if ($links[hide] == "yes") { next; }
                  $link_count = $link_count + 1;
                  $title = $links[title];
                  $subtitle = $links[subtitle];
                  $url = $links[url];

                  # we have allowed entries to have no URL, so that they may
                  # appear as headers, addl info, etc.  do not create an href
                  # tag around these or put an arrow next to them.
                  # ---------------------------------------------------------
                  if (preg_match('/^http/', $links[url])) { 
                     if (!preg_match('/.pdf$/', $links[url])) {
                        $target = "target=_blank";
                     }
                     else { $target = ""; }
                  }
                  else { 
                     if (preg_match("/^\//", $url)) {
                        $url = $this->url_base_local . $url;
                     }
                     else {
                        $url = "$this->url_base_local/$url";
                     }

                     $target = "";
                  }
                  if ($links[url] == "") {
                     $href = "<span class=text_caption>";
                     $end_href = "</span>";
                     $pointer = "<img src=$image_dir/pix.gif width=11 height=1>";
                     if ($subtitle != "") {
                        $subtitle_html = "<span class=text_caption>\n$subtitle<BR></span>\n";
                     }
                     else { $subtitle_html = ""; }
                  }
                  else {
                     $pointer = "<a href=\"$url\" $target><img src=$image_dir/arrow_file.gif border=0></a>";
                     $href ="<a href=\"$url\" class=link_folder_title $target>";
                     $end_href = "</a>";
                     if ($subtitle != "") {
                        $subtitle_html = "\n<a href=\"$url\" class=link_folder_subtitle $target>$subtitle</a><BR>\n";
                     }
                     else { $subtitle_html = ""; }
                  }


                  $this->nav_block .= <<<END

<!-- LINK $link_count -->
<tr>
 <td valign=top>$pointer</td>
 <td valign=top>$href $title $end_href<BR> $subtitle_html</td>
 <td><img src=$image_dir/pix.gif width=6 height=1></td>
</tr>
<tr>
 <td colspan=3><img src=$image_dir/pix.gif height=6></td>
</tr>

END;

               } while ($links = mysql_fetch_assoc($result3));

            } 

            $this->nav_block .= <<<EOF

   </table>
  </td>
  <td width=1 bgcolor=959595><img src=$image_dir/pix.gif></td>
 </tr>
<tr>
  <td width=1 bgcolor=959595 colspan=3><img src=$image_dir/pix.gif></td>
</tr>
</table>

<!-- END NAV BLOCK TABLE $block_count -->

<p>

EOF;


         } while ($row = mysql_fetch_assoc($result)); 
      }




   }  // End function prepare_nav_block_file


   #############################################################################
   function find_descendants($id) {

      # we want to actually keep track of hidden IDs so we can remove
      # their nav files
      # prepare an array of IDs that are downstream of $id
      # requires that prepare_nav_info() was called
      # --------------------------------------------------
      if ( (is_array($this->{"is_$id"})) && (!empty($this->{"is_$id"})) ) {
         $submenu_ids = array_keys($this->{"is_$id"});
         while (!empty($submenu_ids)) {
            $next_element = array_shift($submenu_ids);

            if ($this->{"ie_$next_element"}{hide} == "yes") { 
               $func = "find_hidden_descendants";
               $list = "ih";
            }
            else {
               $func = "find_descendants";
               $list = "descendant_list";
            }
  
            if ( (is_array($this->{"is_$next_element"})) && (!empty($this->{"is_$next_element"})) )  {
               array_push($this->{"$list"}, $next_element);
               $this->{"$func"}($next_element);
            }
            else {
               array_push($this->{"$list"}, $next_element);
            }
         }
      }
      else {
      }

   }  // End function find_descendants

   #############################################################################
   function find_hidden_descendants($id) {

      if ( (is_array($this->{"is_$id"})) && (!empty($this->{"is_$id"})) ) {
         $submenu_ids = array_keys($this->{"is_$id"});
         while (!empty($submenu_ids)) {
            $next_element = array_shift($submenu_ids);
            if ( (is_array($this->{"is_$next_element"})) && (!empty($this->{"is_$next_element"})) )  {
               $this->find_hidden_descendants($next_element);
            }
            else {
               array_push($this->ih, $next_element);
            }
         }
      }
      else {
      }

   }  // End function find_hidden_descendants




   #############################################################################
   function get_nav_filename($id, $type, $table) {

      # determine filename for nav file
      # $type -> left/right/more_info
      # $table -> left_nav/right_nav/nav_block_links
      #
      # nav file for page: /about/contact_us.php
      # would be:          $this->include_dir/nav/$type/about_contact_us.php
      # --------------------------------------------------------------------

      if ($id == 0) {
         # top-level/default nav include file
         # ----------------------------------
         $nav_filename = "$this->include_dir/nav/$type/default.nav.html";
         return $nav_filename;
      }

      else {
         if ($url = $this->get_url($id, $table)) {
            if ($url != "") {
               //echo "$id: $url <BR> \n";
               $nav_filename = $this->make_filename($url, $type, "full");
               //echo "$id: nav_filename: $nav_filename <BR> \n";
               return $nav_filename;
            }
            else { return false; }
         }
         else {
            return false;
         }
    
      }

   }
   #############################################################################
   function make_filename($url, $type, $path) {

      # --------------------------------------------------------
      # determine a nav include file's filename based on the URL 
      # $type may be: left, right, more_info
      # --------------------------------------------------------

      # URLs in the DB could be full URLs, such as "http://..."
      # -------------------------------------------------------
      if (strstr($url, "$this->url_base")) {
         $local_path = ereg_replace("^$this->url_base", "", $url);
      }

      # or local URLs, such as "/about/contact_us.php"
      # ----------------------------------------------
      else if (preg_match("/^\//", $url)) {
         if ($this->url_base_local != "") {
            $local_path = ereg_replace("^$this->url_base_local", "", $url);
         }
         else {
            $local_path = $url; 
         }
      }

      # otherwise, we can't know what the filename should be
      # ----------------------------------------------------
      else {
         return false;
      }

      # URLs ending in "/" should link to a directory index page
      # sitewide standard index page name is set in $this->index_page
      # -------------------------------------------------------------
      if (preg_match("/\/$/", $local_path)) {
         $local_path = "$local_path/$this->index_page";
      }

      # remove the leading slash and replace other slashes with underscores
      # see example above
      # -------------------------------------------------------------------
      $local_path = ereg_replace("^\/", "", $local_path);
      $nav_filename = ereg_replace("/+", "_", $local_path);
      if ($path == "full") {
         $nav_filename = "$this->include_dir/nav/$type/$nav_filename.nav.html";
      }

      return $nav_filename;
 
   }
   #############################################################################
   function get_url($id, $table) {
      
      # get a url from the given table based on the unique ID
      # $table may be: left_nav, right_nav, nav_block_links
      # -----------------------------------------------------
      $q = "SELECT url FROM $table WHERE id = '$id'";
      $result = mysql_query($q) or die(mysql_error());
      $row = mysql_fetch_assoc($result);
      $url = $row[url];

      return $url; 

   }


   #############################################################################
   function get_right_nav_filename($id, $type) {

      # determine filename for page group nav file
      # page group nav file for:  /about/contact_us.php
      # would be:     $this->include_dir/nav/$type/about_contact_us.php
      # ---------------------------------------------------------------------

      if (!$id > 0) {
         return;
      }

      else {
         $sql = "SELECT url FROM right_nav WHERE id = $id";
         $result = mysql_query($sql) or die(mysql_error());
         $row = mysql_fetch_assoc($result);
         $url = $row[url];

         # URLs in the DB could be full URLs, such as "http://..."
         # -------------------------------------------------------
         if (strstr($url, "$this->url_base")) {
            $local_path = ereg_replace("$this->url_base", "", $url);
         }

         # or local URLs, such as "/about/contact_us.php"
         # ----------------------------------------------
         else if (preg_match("/^\//", $url)) {
            if ($this->url_base_local != "") { 
               $local_path = ereg_replace("$this->url_base_local", "", $url);
            }
            else {
               $local_path = $url;
            }
         }

         # otherwise, we can't know what the filename should be
         # ----------------------------------------------------
         else {
            return;
         }

         # URLs ending in "/" should link to a directory index page
         # sitewide standard index page name is set in $this->index_page
         # -------------------------------------------------------------
         if (preg_match("/\/$/", $local_path)) {
            $local_path = "$local_path/$this->index_page";
         }

         # remove the leading slash and replace other slashes with underscores
         # see example above
         # -------------------------------------------------------------------
         $local_path = ereg_replace("^\/", "", $local_path);
         $nav_filename = ereg_replace("/+", "_", $local_path);
         $nav_filename = "$this->include_dir/nav/$type/$nav_filename.nav.html";
      }

      return $nav_filename;

   }


   #############################################################################
   function check_url($id) {

      # this function not currently used
      # --------------------------------
      if ($id == 0) { return; }
      $sql = "SELECT url FROM left_nav WHERE id = $id";
      $result = mysql_query($sql) or die(mysql_error());     
      $row = mysql_fetch_assoc($result);
      $url = $row[url]; 
      if (preg_match("/^\/sc\//", $url)) { 
         $url = "$this->url_base/$url"; 
      }
      else if ((preg_match("/^\//", $url)) AND ($this->url_base_local != "")) {
         $url = ereg_replace("$this->url_base_local", "$this->url_base", $url);
      }

   }



   #############################################################################
   function show_broken_links() {

      $font = "font face=\"arial\" color=red size=-1";

      if (count($this->broken_links) > 0) {
         echo "<table>";
         echo "<tr><td align=center><$font><B>The following links appear to be broken:</B></td></tr>";
         foreach ($this->broken_links as $bl) {
            $sql = "SELECT url,type FROM left_nav WHERE id = $bl";
            $result = mysql_query($sql) or die(mysql_error());
            if ($row = mysql_fetch_assoc($result)) {
               echo "<tr><td><$this->font color=red size=-1>
<a href=javascript:openWinLeft('left_nav_links.php?i=$bl&t=$row[type]');>$row[url]</a>
			</td></tr>";
            }
         }
         echo "</table><P>";
      }

   }



   #############################################################################
   function show_error() {

      $font = "font face=\"arial\" color=red size=-1";

      if (count($this->error) > 0) {
         echo "<table>";
         echo "<tr><td align=center><$font><B>Error:</B></td></tr>";
         foreach ($this->error as $e) {
            echo "<tr><td><$this->font color=red size=-1>$e</td></tr>";
         }
         echo "</table>";
      }

   }



   #############################################################################
   function debug($flag) {
   
      # write object to a file for debugging purposes
      # ---------------------------------------------
      if ($flag == "on") {
         $snav_mgr = serialize($this);
         $object_file = "/tmp/nav_mgr_object_debug.txt";
         $fp = fopen($object_file, "w");
         fputs($fp, $snav_mgr);
         fclose($fp);
         chmod($object_file, 0600);
      }

   }



   #############################################################################
   function is_local($id) {
   
      # determine if a nav element is a local page
      # if yes, return true; if not, return false
      # ------------------------------------------
      $q = "SELECT local FROM left_nav WHERE id = $id";
      $result = mysql_query($q) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         $local = $row{local};
      }
      if ($local == "y")	{ return true; }
      else 			{ return false; }

   }


   #############################################################################
   function top_page($id) {
   
      # return true if id is for a top-level link that is not a directory
      # -----------------------------------------------------------------
      $q = "SELECT parent,type,url FROM left_nav WHERE id = $id";
      $result = mysql_query($q) or die(mysql_error());
      if ($row = mysql_fetch_assoc($result)) {
         $parent = $row{parent};
         $type = $row{type};
         $url = $row{url};
      }
      if (($parent == 0) AND ($type == "p"))	{ return true; }
      else 					{ return false; }

   }



   #############################################################################
   function get_path($id, $type) {
   
      # figure out the path of a file within the web root or from /
      # -----------------------------------------------------------
      $q = "SELECT url,local,filename FROM left_nav WHERE id = $id";
      $result = mysql_query($q) or die(mysql_error());
      $row = mysql_fetch_assoc($result);
      $url = $row[url];
      $filename = $row[filename];
      
      # skip non-local links
      # --------------------
      if ($row[local] != "y") {
         return;
      }

      # URLs in the DB should be full URLs, such as "http://..."
      # --------------------------------------------------------
      $https = str_replace("http://", "https://", $this->url_base);
      if ( (strstr($url, "$this->url_base")) OR (strstr($url, "$https")) ) {
         $local_path = ereg_replace("$this->url_base", "", $url);
      }

      # but try to accomodate local URLs, such as "/about/contact_us.php"
      # -----------------------------------------------------------------
      else if (preg_match("/^\//", $url)) {
      }

      # otherwise, we can't know what the filename should be
      # ----------------------------------------------------
      else {
         array_push($this->error, "Unable to determine path for URL at: 
<BR>$url [$id]");
      }

      # URLs ending in "/" should link to a directory index page
      # sitewide standard index page name is set in $this->index_page
      # -------------------------------------------------------------
      if (preg_match("/\/$/", $local_path)) {
         $local_path = "$local_path/$filename";
      }

      # remove the leading slash and replace other slashes with underscores
      # see example above
      # -------------------------------------------------------------------
      if ($type == "nav") { 
      }
      else if ($type == "dummy") { 
         $local_path = "$this->docroot/" . $local_path;
      }
      
      $local_path = preg_replace("/\/+/", "/", $local_path);
      $local_path = preg_replace("/^\//", "", $local_path);
      $path = split("/", $local_path);
      return $path;      
 
   }


   #############################################################################
   function list_parents($id) {
   
      # make an array of all the IDs of directories that a given directory or
      # page is in and return it
      # ---------------------------------------------------------------------     
      $tt = $id;
      $parents = array();

      if ($id > 0) {
         while ($tt > 0) {
            $q = "SELECT parent FROM left_nav WHERE id = $tt";
            $result = mysql_query($q) or die(mysql_error());
            if ($row = mysql_fetch_assoc($result)) {
               $tt = $row[parent];
               array_push($parents, $tt);
            }
            else {
               $tt = 0;
            }
         }
      }

      return $parents;

   }


}  
// ----------------------------------------------------------------
// End class Navigation_Manager
// ----------------------------------------------------------------



?>
