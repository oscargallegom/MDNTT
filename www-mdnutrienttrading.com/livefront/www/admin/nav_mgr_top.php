<?php


include "js.php";
$image_dir = $nav_mgr->base_href . $nav_mgr->image_dir;



if ($unit == "siblings") {
   $page_title = "Page Groups (Sibling Pages)";
   $description = "Add, edit or delete pages of the right-side page groups (sibling pages).";
   $local_links = <<<END

<table bgcolor=EFEFEF width=500>

<!-- LINKS -->
 <tr>
  <td align=left class=text width=50%>
<a href=javascript:openWinRight('right_nav_groups.php?edit=new');>
[ Add New Group ] </a>
  </td>
  <td align=right class=text width=50%>
<a href=right_nav.php?generate=0>
[ Regenerate Page Group Nav Files ] </a>
  </td>
 </tr>
  
</table>
<BR>

  
END;

}
else if ($unit == "blocks") {
   $page_title = "More Info Blocks";
   $description = "Manage supplementary navigational blocks.";
   $local_links = <<<END

<table bgcolor=EFEFEF width=500>

<!-- LINKS -->
 <tr> 
  <td align=center class=text>
<a href=javascript:openWinMoreInfo('nav_block_groups.php?edit=new');>
[ Add New Block ] </a>
  </td>
  <td align=center class=text>
<a href=nav_blocks.php?generate=0> 
[ Regenerate More Info Nav Files ] </a>
  </td>
 </tr>

</table>
<BR>


END;

}
else {
   $page_title = "Left-Side Nav Bar";
   $description = "Add, edit or delete directories and pages of the left-side navigation bar.";
   $local_links = <<<END

<table width=600 cellspacing=0>

<!-- LINKS -->
 <tr>
  <td align=left width=33% class=text bgcolor=EFEFEF>
<a href=javascript:openWinLeft('left_nav_links.php?edit=new&parent=0');>
[ Add a New Top-Level Link ] </a>
  </td>
  <td align=center class=text width=33% bgcolor=EFEFEF>
<a href=javascript:openWinLeft('left_nav_links.php?i=0&reorder=top&edit=no');>
[ Reorder Top-Level Links ]
  </td>
  <td align=right class=text width=33% bgcolor=EFEFEF>
<a href=left_nav.php?generate=0>
[ Regenerate Left Nav Files ]
  </td>
 </tr>

 <tr><td colspan=3><BR></td></tr>
 <tr>
  <td colspan=3 align=center>
   <img src=$image_dir/edit.png border=1> <$nav_mgr->font size=-1>Edit
    &nbsp; &nbsp; &nbsp;
   <img src=$image_dir/hidden.png border=1> <$nav_mgr->font size=-1>Hidden
    &nbsp; &nbsp; &nbsp;
   <img src=$image_dir/copy.png border=1> <$nav_mgr->font size=-1>Copy
  </td>
 </tr> 
</table>
<BR>
  
  
END;

}


# preparation complete - display the page top
# -------------------------------------------
echo <<<END
<html>
<head>
<title>MDA Site Navigation Manager - $page_title</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="textfonts.css">
</head>

<body>

<CENTER>
<div class=text>

<!-- TITLE -->
<h2><$nav_mgr->font>MDA Navigation Manager</font></h2>
<table cellpadding=3 cellspacing=3>
 <tr>
  <td nowrap class=text>[ <a href=left_nav.php>Left-Side Nav Bar</a> ]</td>
  <td align=center nowrap class=text>[ <a href=right_nav.php>Page Groups</a> ]</td>
  <td align=right nowrap class=text>[ <a href=nav_blocks.php>More Info Blocks</a> ]</td>
  <td align=right nowrap class=text>[ <a href=navigation_manager_help.pdf target=_blank>Help</a> ]</td>
</table>
<h4>$page_title</h4>


<table cellpadding=3 cellspacing=3>

<!-- DESCRIPTION -->
<tr><td align=center class=text>$description</td></tr>
</table>
<BR>

$local_links

END;




?>
