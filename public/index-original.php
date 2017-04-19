<?php
   include_once "/var/www/mdnt/public/admin/include_path.php";
   include_once "$include_dir/config.php";
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>

<title>Maryland Nutrient Trading Tool</title>

</HEAD>
<BODY bgcolor=FFFFFF marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>



<!-- BEGIN TOP LEVEL BANNER TABLE -->

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="Maryland Nutrient Trading"/>
<meta name="keywords" content="Maryland Nutrient Trading"/>
<title>Maryland Nutrient Trading</title>
<script type="text/javascript" src="http://www.maryland.gov/branding/statewideNavigation.js"></script>
<link href="/style/guideline.css" rel="stylesheet" type="text/css" />
<!-- ANY OTHER SCRIPTS, STLYES HERE -->

<style type="text/css">
body {
  background: #2A579C;
}
</style>


<body>
<div id="container" align="center">
<div id="banner">
<div id="mdlogo"><a target="_BLANK" href="http://www.maryland.gov"><img src="/images/page/MDlogo.gif" alt="maryland.gov" width="168" height="90" border="0" /></a>
</div>
<!-- [Begin] Global Links -->
<div id="global">
<div align="right">
<script type="text/javascript">showStatewideNavigation("white");</script>
</div>
<div class="hide">
<h1>Maryland Nutrient Trading</h1> <!--  banner text alt for accessibility -->
<a href="#content" title="skip to content" accesskey="1">skip to content </a> <!--  change if using other anchor name -->
</div>
</div>
<!-- [End] Global Links -->
<!-- [Begin] Search Form -->
<div id="global2">
<div align="right">
  	<form name="gs" method="get" Target="_Blank" action="http://search.maryland.gov/search" id="Form2">
      	<label for="q" style="display:none">Search:</label>
      	<input type="text" class="searchTextBox" accesskey="4" name="q" id="q" size="30" maxlength="256" value="Search" onfocus="if(this.value=='Search'){this.value='';this.style.color='#000';}else{this.select();}" />
      	<!-- site collection name -->
      	<input type="hidden" name="site" value="Agriculture" id="site" />
      	<input type="image" class="searchButton" name="btnG" id="search" title="Search" alt="Search" src="images/page/searchButton.gif" />
      	<!-- gsa variables -->
      	<input type="hidden" name="entqr" value="0" id="entqr" />
      	<input type="hidden" name="ud" value="1" id="ud" />
      	<input type="hidden" name="sort" value="date:D:L:d1" id="sort" />
      	<input type="hidden" name="output" value="xml_no_dtd" id="output" />
      	<input type="hidden" name="oe" value="UTF-8" id="oe" />
      	<input type="hidden" name="ie" value="UTF-8" id="ie" />
      	<input type="hidden" name="client" value="search_md_1" id="client" />
      	<input type="hidden" name="proxystylesheet" value="search_md_1" id="proxystylesheet" />
  	</form>
</div>
</div>
<!-- [End] Search Form -->
<div id="tools">
	<ul>
	<li class="mail"><a href="mailto:?subject=Maryland Nutrient Trading &amp;body=http://www.mdnutrienttrading.com">Email Friend</a> </li>
	</ul>
	</div>
</div>



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
 
<p class="text_left_content">Work to update the calculation tool has been completed as of 9:30 p.m., Monday, December 3, 2012.  The latest version of the tool incorporates Chesapeake Bay Model 5.3.2 segments, edge of segment and delivery factors, and baselines.  In addition, local TMDL tables have been updated with new baseline values and other requested modifications have been made.  While most of the revisions are behind the scenes, the names of the Bay segments will change and the baseline numbers will be different from what has appeared in the past.  All existing worksheets have been transferred to the new version and current users have been sent instructions for revising entries in order to confirm baseline compliance and re-calculate credit generation capacity. First-time users will not be affected.  Any comments, questions, or requests for assistance should be directed to Susan Payne, Coordinator of Ecosystem Markets, at 410-841-5865 or <a href="mailto:NuTrade.mda@maryland.gov">NuTrade.mda@maryland.gov</a>.</p>
*/?>
<p class="text_left_content">
As of May 1, 2014, the Maryland Nutrient Trading Program launched the latest version of its online tools. The World Resources Institute (WRI), which partners with the Maryland Department of Agriculture (MDA) and the Texas Institute for Applied Environmental Research (TIAER), has utilized the Maryland trading platform as the template for a new multistate platform that can be accessed by users in Maryland, Pennsylvania, and Virginia. WRI and TIAER have transferred all existing accounts from the Maryland platform to the new multistate platform. They are also in the process of transferring the 253 farm worksheets for users who met the April 16 deadline for transfer requests.
<br /><br />
To enter the new site, you can continue to use <a href="http://www.mdnutrienttrading.com">www.mdnutrienttrading.com</a> or alternately you can switch to <a href="http://www.cbntt.org">www.cbntt.org</a>. However, it is recommended that you continue to use the former web address so that you can find specific information and/or learn about activities related to Maryland’s program. <b>For those who had an existing account established before May 1, your account can be found under your current username, but you will have to change your password in order to access your worksheets.</b> 
<br /><br />
Because the latest version of the calculation tool incorporates any needed modifications since the last update, as well as changes required by current Maryland Nutrient Management regulations, there have also been some changes to the format for entries. You will have to make a few revisions to your worksheets to reflect the data entries for meeting the new setback, stream fencing, and manure incorporation requirements. When these have been completed, you can re-run your worksheets to re-confirm baseline compliance and re-calculate potential nitrogen, phosphorus, and sediment credits.<b> First-time users will not be affected.</b>
<br /><br />
Any comments, questions, or requests for assistance should be directed to Susan Payne, Coordinator of Ecosystem Markets, at 410-841-5865 or <a href="mailto:Nutrade.mda@maryland.gov">Nutrade.mda@maryland.gov</a>. In addition, MDA plans to hold training sessions in the use of the latest version of the tool in the coming months. If you are interested in participating, watch the website or contact Susan Payne to be notified of dates and locations.
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

<td width=300 cellpadding=10 valign="top" align="left">

  <table border=0 width=100% cellpadding=0>
  <tr>
  <td>
  <a target="_BLANK" href="http://www.gov.state.md.us/announcement.html"><img src="http://www.gov.state.md.us/images/GovBox230.png" alt="Office of Governor" width="230" height="76" hspace="15" vspace="10" border="0" align="center"></a>
  </td>
  </tr>
  </table>


<p class="header_right_content" align=left>View Nitrogen and Phosphorous Credits</br>

<?php
/*<ul class="text_right_content">
	<li><a href="http://nutrientnet.mdnutrienttrading.com/getstarted/howto.app">How do I get started?</a></li>
	<li><a href="http://nutrientnet.mdnutrienttrading.com/tradingApplication.app">Submit Trading Application</a></li>
	<li><a href="http://nutrientnet.mdnutrienttrading.com/marketplace/list.app?nutrient_id=1">View Nitrogen Marketplace</a></li>
	<li><a href="http://nutrientnet.mdnutrienttrading.com/marketplace/list.app?nutrient_id=2">View Phosphorus Marketplace</a></li>
	<li><a href="http://nutrientnet.mdnutrienttrading.com/trade/projects.app">View Certified Credit Registry</a></li>
</ul>*/
?>

<p class="header_right_content" align=left>
<a href="http://www.cbntt.org">Login to CBNTT</a><br>
<a href="">Login to Market (Under Construction)</a></br>



<p class="header_right_content" align=left>Technical References & Guidelines</p>

<ul class="text_right_content">
<li><a href="http://www.mdnutrienttrading.com/docs/Phase II-A_Crdt Generation.pdf  ">Guidelines for Agricultural Credit Sellers</a>
<li><a href="http://www.mdnutrienttrading.com/docs/Phase II-B_Crdt Purchase.pdf  ">Guidelines for Agricultural Credit Buyers</a>
<?php /*<li><a href="http://www.mde.state.md.us/Water/nutrientcap.asp">Policy for Point Source Buyers & Sellers</a> */ ?>
<li><a href="http://www.mde.maryland.gov/programs/water/pages/water/nutrientcap.aspx">Policy for Point Source Buyers & Sellers</a>
<li><a href="/docs/bestmanagementpractices.pdf">NRCS BMP List (PDF)</a>
</ul>
<?php
/*<p class="header_right_content" align=left>Farmers . . .</p>

<ul class="text_right_content">
<li><a href="http://www.mdnutrienttrading.com/farmers">Interested in nutrient trading?</a>
</ul>*/
?>

<p class="header_right_content" align=left>What's New</p>
<table class="text">
<td>
<p class="text_right_content" align="justify">
The Maryland Department of Agriculture is a member of the National Network on Water Quality Trading.  That group has just published a comprehensive reference providing the essential tools for new and evolving water quality trading programs.  “Building a Water Quality Trading Program: Options and Considerations” identifies common trading issues and the options, considerations, and examples important to building a trading program.  It captures several decades of experience in trading programs and is the product of a dialogue between National Network participants who represent agriculture, wastewater utilities, environmental groups, regulatory agencies, and practitioners.  To learn more about the National Network, go to <a href="http://willamettepartnership.org/nn-wqt">willamettepartnership.org/nn-wqt</a>; to download the publication, go to <a href="http://wri.org/nn-wqt">wri.org/nn-wqt</a>.
</p></td><td></td><td></td><td></td></table>
</p></div>
<p> &nbsp; </p></font>

<img src="/images/page/growing_blue_award.png" width="311" height="311" border="0">
<br>
For more information about the nomination of the Maryland Nutrient Trading Program for the Growing Blue Award, see <a href="http://www.mdnutrienttrading.com/docs/11-6-13_Nutrient_Trading_Award.pdf">press release</a> put out by the Maryland Department of Agriculture.
<br>

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
<!-- [Begin] Footer Information -->
<div id="footer">
<p>
<a href="http://www.mdnutrienttrading.com/">Home </a>
&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.mdnutrienttrading.com/contactus">Contact Us </a>
<!--
&nbsp;&nbsp;|&nbsp;&nbsp;<a href="AccessibilityNoticeURLhere">Accessibility</a>
&nbsp;&nbsp;|&nbsp;&nbsp;<a href="PrivacyNoticeURLhere">Privacy Notice</a>
&nbsp;&nbsp;|&nbsp;&nbsp;<a href="TermsOfUsePolicyURLHere">Terms of Use</a>
-->
</p>

<table class="footer" font="80%" text="#ffffff" width="100%">
<tr>
<td width="35%">
<a target="_BLANK" href="http://www.mde.state.md.us"><img border=0 align=right src="/images/page/mdelogo.gif"></a>
<p><font size="2" color="#ffffff"><a target="_BLANK" href="http://www.mde.state.md.us">Maryland Department of the Environment</a></br>
1800 Washington Blvd., Baltimore, MD 21230</br>
Phone: 410-537-3000</font></p>
</td>
<td width="28%">
&nbsp;
</td>
<td width="37%">
<a target="_BLANK" href="http://www.mda.state.md.us"><img border=0 align=left src="/images/page/mdalogo.gif"></a>
  <p align=right><font size="2" color="#ffffff"><a target="_BLANK" href="http://www.mda.state.md.us">Maryland Department of Agriculture </a></br>
50 Harry S Truman Pkwy, Annapolis, MD 21401</br>
     Phone:  410-841-5700</font></p>
 </td>
</tr>
</table> 
</div>
<!-- [End] Footer Information -->
