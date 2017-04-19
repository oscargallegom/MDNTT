<?php

# ------------------------------------------------------------------------------
# config.php
#
# All site-specific configuration is set in this file.
# Also update /admin/include_path.php to reflect the path specified here for
# $include_dir.
# ------------------------------------------------------------------------------

$local = array(


################################################################################
#
# Update the following values for site config and the MySQL database
# according to your server's configuration.
#
################################################################################


# site config
# -----------
   # include_dir is directory containing this file, the 'nav' directory, etc. 
   "include_dir"	=> "/home/mda_http/mda/nutrad/includes",

   # your domain name for the site
   "url_base" 		=> "http://mdnutrienttrading.org",

   # optional.  you may use this if you were to set up the site in a 
   # subdirectory of another site, or, for example, under a /test 
   # directory.  leave blank otherwise.
   "url_base_local"	=> "http://mda.state.md.us/nutrad",

   # base_href should be url_base + url_base_local
   "base_href"		=> "http://mdnutrienttrading.org",

   # directory of the Document Root, the top level of the website
   "docroot"		=> "/home/mda_http/mda/nutrad",



# MySQL database
# --------------
   "dbhost"	=> "",   	// hostname - leave blank for localhost
   "dbname"	=> "mda",   	// name of database
   "dbuser"	=> "mda_user",  // name of database user
   "dbpass"	=> "Pge4B7q", 	// database user's password 




################################################################################
#
# No need to configure anything below here.
#
################################################################################

# look and feel
# -------------
   "font" => 'font face="arial, arial narrow, helvetica, sans serif, verdana, tahoma"',
   "left_nav_width" 	=> "140",
   "right_nav_width" 	=> "140",
   "right_nav_bgcolor"	=> "7D8EBA",
   "nav_block_width" 	=> "140",


# navigation manager
# ------------------
   # lock file for when navigational include files are being regenerated
   "lockfile" => "/tmp/nav_mgr.lock",	// used only when generating nav files

   # used by the navigation manager 
   "index_page"		=> "index.php",

   # directory off of document root containing general site image files
   "image_dir"		=> "imgs"	// i.e., $docroot/imgs


);


?>
