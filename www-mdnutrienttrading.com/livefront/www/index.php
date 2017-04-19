<?php
   include_once "/home/nutrientnet/md/livefront/www/admin/include_path.php";
   include_once "$include_dir/config.php";
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>

<title>Maryland Nutrient Trading Tool</title>

</HEAD>
<BODY bgcolor=FFFFFF marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>



<?php 
/* include "$include_dir/head_meta.php";
include "$include_dir/js_second_window.php"; */
?>  




<?php include "$include_dir/top.php"; ?>



<!-- END OF BANNER -->

<div id="page"> 


<!-- PUT TOP NAV BAR HERE -->
	
<table class="text" valign=top cell padding=0 border=0 width=1000px>
<tr><td>
<!-- <p align="center" class="text">Top Nav Bar HERE</p> -->
</td></tr>
</table>

<!-- END TOP NAV BAR HERE -->
	
<!-- BEGIN MAIN PAGE TABLE -->

<table class=text align=center cellpadding=0 cell spacing=0 border=0 width=1000>

<tr>



<td valign="center" valign=center width=675px class=text>






<!-- BEGIN MAIN PAGE CONTENT -->


<!-- rules="NONE" frame="BOX" -->

<!--<table width=675 cellpadding=15 border=2 >
<tr><td>
<p class="text_left_content"><b>Important Notice:  This website is currently under development by the Maryland Department of Agriculture (MDA), and some features, such as all tabs for the nitrogen and phosphorus credit trading markets, are included only for illustrative and testing purposes and are not operational at this time.  There is, however, much useful information available throughout the site, and interested parties are urged to review the material in preparation for the program's anticipated implementation.   MDA also welcomes any inquiries, comments, or suggestions, and these should be directed to Susan Payne at 410-841-5865 or <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a>.</b></p>
</td></tr>
</table> -->
<table width=675 cellpadding=15>
<tr><td>
<?php /* <h2 class="header_left_content">Welcome To Maryland's Nutrient Trading Program . . .</h2> */ ?>
<h2 class="header_left_content">Welcome to the Maryland Nutrient Trading Program . . .</h2>

<div style="padding: 1em; border: 1px solid #296AAB">
<h2 class="header_left_content">Please Read This Notice before Using the Maryland Nutrient Trading Tool</h2>
<?php /*
<p class="text_left_content">In response to feedback from users as well as to comply with recent decisions by the Maryland Nutrient Trading Advisory Committee, some enhancements and major changes will be made to the NutrientNet calculation tool.  Besides new features that will save time and make the tool perform better, trading eligibility will now be based on the whole farm rather than individual fields and farms will be able to generate nitrogen or phosphorus credits if the baseline is met in either nutrient rather than both.</p>
<p class="text_left_content">The work to effect these changes will begin January 31 and will be completed no later than March 31.  During that time, the website will continue to function as usual, and data can be entered and saved in individual accounts; however, in order to take advantage of the benefits offered by the latest version of the tool, it will be necessary to re-run all calculations after the revisions are finished.  Once this work is done, no further changes will be made to the calculation tool for six months when the new Chesapeake Bay TMDLs will be added.</p>
<p class="text_left_content">The sophistication of the Maryland NutrientNet platform, which incorporates both the Bay Model efficiencies and the national Nutrient Trading Tool (or NTT), has presented ongoing challenges for everyone involved.  The Maryland Department of Agriculture and its partners at the World Resources Institute, Drive Current, and the Texas Institute for Applied Environmental Research appreciate the many thoughtful suggestions that have not only led to the modification of the calculation tool to meet real world needs, but also prompted a re-examination of overall program principles and guidelines.  Any questions or comments should be directed to Susan Payne, Coordinator of Ecosystem Markets, at 410-841-5865 or <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a>.</p>
<p class="text_left_content">Since enhancements completed March 31 have changed baseline load calculations, all cached calculations for existing accounts have been wiped out and will need to be updated for each worksheet.  There also may be worksheets that are no longer “complete” because because of revisions in some of the possible data a user may enter.  Users should inspect their worksheets, fill in any newly missing information, and save their worksheets in order to recalculate the credits generated.  The patience of those who have been affected during this transition has been greatly appreciated, and it is anticipated that no further changes will be made for six months when the new Chesapeake Bay TMDLs will be added.  Any comments, questions, or requests for assistance should be directed to Susan Payne, Coordinator of Ecosystem Markets, at 410-841-5865 or <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a>.</p>
<p class="text_left_content">Since enhancements completed March 31 have changed baseline load calculations, all cached calculations for existing accounts have been wiped out and will need to be updated for each worksheet.  There also may be worksheets that are no longer “complete” because because of revisions in some of the possible data a user may enter.  Users should inspect their worksheets, fill in any newly missing information, and save their worksheets in order to recalculate the credits generated.  The patience of those who have been affected during this transition has been greatly appreciated, and it is anticipated that no further changes will be made for six months when the new Chesapeake Bay TMDLs will be added.  Any comments, questions, or requests for assistance should be directed to Susan Payne, Coordinator of Ecosystem Markets, at 410-841-5865 or <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a>.</p>
<p class="text_left_content">Since enhancements completed March 31, 2011, have changed baseline load calculations, all cached calculations for accounts existing at that time have been wiped out and will need to be updated for each worksheet. There also may be worksheets that are no longer &ldquo;complete&rdquo; because of revisions in some of the possible data a user may enter.  Users should inspect their worksheets, fill in any newly missing information, and save their worksheets in order to recalculate the credits generated.  No additional changes to the calculation tool are anticipated until the new Chesapeake Bay TMDL allocations are added during the second quarter of 2012.  Any comments, questions, or requests for assistance should be directed to Susan Payne, Coordinator of Ecosystem Markets, at 410-841-5865 or <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a>.</p>
<p class="text_left_content">Since enhancements completed March 31, 2011, have changed baseline load calculations, all cached calculations for accounts existing at that time have been wiped out and will need to be updated for each worksheet. There also may be worksheets that are no longer &ldquo;complete&rdquo; because of revisions in some of the possible data a user may enter.  Users should inspect their worksheets, fill in any newly missing information, and save their worksheets in order to recalculate the credits generated.  No additional changes to the calculation tool are anticipated until the new Chesapeake Bay TMDL allocations are added during the third quarter of 2012.  Any comments, questions, or requests for assistance should be directed to Susan Payne, Coordinator of Ecosystem Markets, at 410-841-5865 or <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a>.</p>
*/ ?>
<p class="text_left_content">Work to update the calculation tool has been completed as of 9:30 p.m., Monday, December 3, 2012.  The latest version of the tool incorporates Chesapeake Bay Model 5.3.2 segments, edge of segment and delivery factors, and baselines.  In addition, local TMDL tables have been updated with new baseline values and other requested modifications have been made.  While most of the revisions are behind the scenes, the names of the Bay segments will change and the baseline numbers will be different from what has appeared in the past.  All existing worksheets have been transferred to the new version and current users have been sent instructions for revising entries in order to confirm baseline compliance and re-calculate credit generation capacity. First-time users will not be affected.  Any comments, questions, or requests for assistance should be directed to Susan Payne, Coordinator of Ecosystem Markets, at 410-841-5865 or <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a>.</p>

</div>

<h2 class="header_left_content">What is Nutrient Trading?</h2>

<p class= "text_left_content">
Nutrient trading is a form of exchange (buying &amp; selling) of nutrient reduction credits.  These credits have a monetary value that may be paid to the seller for installing Best Management Practices (BMPs) to reduce nitrogen or phosphorous. In general, water quality trading utilizes a market-based approach that allows one source to maintain its regulatory obligations by using pollution reductions created by another source. As a market-based approach, increased efficiency and cost-effectiveness are achieved by letting the market determine costs. To achieve a desired load reduction, trades can take place between point sources (usually wastewater treatment plants), between point and nonpoint sources (a wastewater treatment plant and a farming operation) or between nonpoint sources (such as agriculture and urban stormwater sites or systems).
</p>

</td></tr>
</table>

<!-- <p>&nbsp;</p> -->
<table width=675 cellpadding=15>
<tr><td>

<h2 class="header_left_content">Why is there a need for a Nutrient Trading Program?</h2>

<p class="text_left_content">
Over the years, pollution levels in the Chesapeake Bay have been increasing. Chief among these pollutants are nutrients, nitrogen and phosphorus.  Much has already been done to reduce these pollutants with the development of <a href="http://www.dnr.state.md.us/bay/tribstrat/">Maryland's Tributary Strategies</a>, but more is still needed. Over the last 15 years, federal, state and local programs have been developed to assist in mitigating the impacts of pollutants in the Bay; however, the amount of  public sector funding required to achieve the desired reductions has fallen short in meeting the goals of a clean Bay.</br>
 <a href="http://www.mdnutrienttrading.com/ntneed.php">More</a>   .  . .
</p> 
</td></tr>

<tr><td>
<h2 class="header_left_content">What is Maryland's Trading Program?</h2>

<p class="text_left_content">
Maryland&rsquo;s Nutrient Trading Program is a public marketplace for the buying and selling of nutrient (nitrogen and phosphorous) credits.  The purpose of the Program ranges from being able to offset new or increased discharges to establishing economic incentives for reductions from all sources within a watershed and achieving greater environmental benefits than through,  existing regulatory programs. To facilitate trading, a web-based <a href="http://www.mdnutrienttrading.com/">Calculation Tool, Marketplace and Trading Registry</a> have been established. The Calculation Tool will assess credit generating capacity while the Market Place and Trading Registry will record approved credits and transactions and provide a means for the public to track the progress of Maryland's trading program.</br>
<a href="http://www.mdnutrienttrading.com/ntwhatis.php">More</a>  . . . 
</p> 

<?php /*
<p class="text_left_content"><a href="http://www.mda.state.md.us/nutrad/ntwhatis.php#PointSource"><b>Point Source Trading</b></a>
</p>

</td></tr>

<tr><td>

<p class="text_left_content"><a href="http://www.mda.state.md.us/nutrad/ntwhatis.php#NonPointSource"><b>Nonpoint Source Trading</b></a>
</p>
*/ ?>

</td></tr>

<tr><td>
<p class="text_left_content"><b>Learn more about Nutrient Trading here:</b></br>

&nbsp;</p>
</td></tr>

</table>

<table  width=100% border=0>
<tr>
	<!--
	<td width=50%>
	<p class="text_left_content" align=center>SLIDESHOW place holder</p>
	</td>
	-->
	<td width=50%>
		<iframe width="640" height="390" src="http://www.youtube.com/embed/66SPEdZRKBc?rel=0" frameborder="0" allowfullscreen></iframe>
	</td>
</tr>

</table>

<p>&nbsp;</p>



<!-- END MAIN PAGE CONTENT --

<!-- BEGIN RIGHT NAV -->

<td width=300 cellpadding=10 valign="top">

  <table border=0 width=100% cellpadding=0>
  <tr>
  <td>
  <a target="_BLANK" href="http://www.gov.state.md.us/announcement.html"><img src="http://www.gov.state.md.us/images/GovBox230.png" alt="Office of Governor" width="230" height="76" hspace="15" vspace="10" border="0" align="center"></a>
  </td>
  </tr>
  </table>


<p class="header_right_content" align=left>View Nitrogen and Phosphorous Credits</br>

<ul class="text_right_content">
	<li><a href="http://nutrientnet.mdnutrienttrading.com/getstarted/howto.app">How do I get started?</a></li>
	<li><a href="http://nutrientnet.mdnutrienttrading.com/tradingApplication.app">Submit Trading Application</a></li>
	<li><a href="http://nutrientnet.mdnutrienttrading.com/marketplace/list.app?nutrient_id=1">View Nitrogen Marketplace</a></li>
	<li><a href="http://nutrientnet.mdnutrienttrading.com/marketplace/list.app?nutrient_id=2">View Phosphorus Marketplace</a></li>
	<li><a href="http://nutrientnet.mdnutrienttrading.com/trade/projects.app">View Certified Credit Registry</a></li>
</ul>


<p class="header_right_content" align=left><a href="http://nutrientnet.mdnutrienttrading.com/">Login to Market</a></br>



<p class="header_right_content" align=left>Technical References & Guidelines</p>

<ul class="text_right_content">
<li><a href="http://www.mdnutrienttrading.com/docs/Phase II-A_Crdt Generation.pdf  ">Guidelines for Agricultural Credit Sellers</a>
<li><a href="http://www.mdnutrienttrading.com/docs/Phase II-B_Crdt Purchase.pdf  ">Guidelines for Agricultural Credit Buyers</a>
<?php /*<li><a href="http://www.mde.state.md.us/Water/nutrientcap.asp">Policy for Point Source Buyers & Sellers</a> */ ?>
<li><a href="http://www.mde.maryland.gov/programs/water/pages/water/nutrientcap.aspx">Policy for Point Source Buyers & Sellers</a>
<li><a href="/docs/bestmanagementpractices.pdf">NRCS BMP List (PDF)</a>
</ul>

<p class="header_right_content" align=left>Farmers . . .</p>

<ul class="text_right_content">
<li><a href="http://www.mdnutrienttrading.com/farmers">Interested in nutrient trading?</a>
</ul>

<p> &nbsp; </p>

<p class="header_right_content" align=left> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; <b><i>**NEW!**</i></b></p>

<b style="font-size:120%">Nutrient Trading Program Workshops</b><br>
<br>
<b>Wednesday, January 9, 1:00 to 4:00 p.m.</b><br>
Stevenson University, Owings Mills, Maryland<br>
<br>
<b>Monday, January 14, 1:00 to 4:00 p.m.</b><br>
Hood College, Frederick, Maryland<br>
<br>
<b>Thursday, January 17, 1:00 to 4:00 p.m.</b><br>
Washington College, Chestertown Maryland<br>
<br>
For more information and required registration, contact Susan Payne, MDA’s Coordinator of Ecosystem Markets, at <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a><br>


<?php /*
<p class="header_right_content" align=left><a href="http://www.mdnutrienttrading.com/intro">Maryland Nutrient Trading Program to be Introduced at Meetings across the State</a></p>
<p class="header_right_content" aligh=left>
	<!--Materials from Statewide Meetings in Frederick, La Plata, and Wye Mills.-->
	Informational Materials from Statewide Meetings in Frederick, La Plata, and Wye Mills held in 2010.
</p>
<ul class="text_right_content">
<li><a href="http://www.mdnutrienttrading.com/docs/mda_presentation.ppt">MDA PowerPoint Presentation</a>
<li><a href="http://www.mdnutrienttrading.com/docs/nut_trading_benefits_md_farmers.pdf">WRI Handout (PDF)</a>
<li><a href="http://www.mdnutrienttrading.com/docs/mde_overview.ppt">MDE PowerPoint Presentation</a>
<li><a href="http://www.mdnutrienttrading.com/docs/mde_rggi.ppt">MDE RGGI PowerPoint Presentation</a>
</ul>
*/ ?>

</td></tr>
</table>


<!-- END RIGHT NAV-->

</div> <!--  Ends "page" div -->

<?php include "$include_dir/bottom.php"; ?>
