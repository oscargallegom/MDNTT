<?php
   include_once "/var/www/mdnt/public/admin/include_path.php";
   include_once "$include_dir/config.php";
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>

<title>Maryland Nutrient Trading Tool</title>
<style type="text/css">

#water_container {
  position: relative;
}

#water_quality_advisory,
#disabled {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
}
#disabled {
  z-index: 10;
}

.disabled {
    background: red;
    width: 400px;
    height: 90px;
    line-height: 30px;
    text-align: center;
    color: #fff;
    font-weight: bold
}

.disabled {     
    width:             400px;     
    height:            90px;     
    -moz-transform:    rotate(20deg);     
    -o-transform:      rotate(20deg);     
    -webkit-transform: rotate(20deg);     
    transform:         rotate(20deg);
}

#water_container2 {
  position: relative;
}

#water_quality_advisory2,
#disabled2 {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
}

#disabled2 {
  z-index: 20;
}

.disabled2 {
    background: red;
    width: 400px;
    height: 90px;
    line-height: 30px;
    text-align: center;
    color: #fff;
    font-weight: bold
}

.cancelled {
    width: 600px;
    height: 90px;
    line-height: 30px;
    font-size: 20px;
    text-align: center;
    color: red;
    font-weight: bold
}

.disabled2 {     
    width:             400px;     
    height:            90px;     
    -moz-transform:    rotate(20deg);     
    -o-transform:      rotate(20deg);     
    -webkit-transform: rotate(20deg);     
    transform:         rotate(20deg);
}

.disabled3 {     
    width:             600px;     
    height:            90px;     
    -moz-transform:    rotate(20deg);     
    -o-transform:      rotate(20deg);     
    -webkit-transform: rotate(20deg);     
    transform:         rotate(20deg);
}
</style>

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
<h2 class="header_left_content">Welcome to the Maryland Nutrient Trading Program . . . </h2>

<div style="padding: 1em; border: 1px solid #296AAB">

<div id="water_container">
<div id="water_quality_advisory">
<h2 class="header_left_content"><center>Trainings in Use of Online Calculation Tool (MNTT) March 14 in Frederick and March 15 in Wye Mills</center></h2>
<p class="text_left_content">
MDA has just completed a re-calibration of the MNTT to reflect the updated APEX model and some changes in the way grazing rotations are entered in the tool. Individuals who would like to learn how to use the calculation tool, as well as those who would like to become or remain Certified Verifiers under the education requirement for both programs, can gain the necessary proficiency (and credits) in the use of the MNTT by attending <b><u>one</u></b> of two training sessions.<br><br>
<b>The workshops will be held on Tuesday, March 14, from 1:00 to 4:00 p.m. at Hood College in Frederick and Wednesday, March 15, from 1:00 to 4:00 p.m. at Chesapeake College in Wye Mills.</b>  The three-hour session will consist of MNTT training, individual practice with the tool, and Certainty Program training (only for those wishing to become Certified Verifiers).  More information and directions will be forwarded in advance of the workshops.<br><br>

Participants should bring a current Nutrient Management Plan, and updated Soil Conservation and Water Quality Plan, and if applicable, a Waste Storage Management Plan for the property to entered into the calculation tool (note that these documents are confidential and will require permission from the farmer or landowner to use them).  Those without access to these materials will be provided with inputs for a fictional farm so that they can learn how the tool works.  <b>Everyone planning to participate in the workshops will need to have an online account established in advance of the training dates.</b>  Accounts can be opened by clicking on the "Login to CBNTT," and following the instructions found there. <br><br>
The workshops are open to Soil Conservation District personnel, farmers, landowners, and others with an interest in one or both programs.  <b>Since space is limited to only twenty participants, those who would like to attend should contact Susan Payne, MDA's Coordinator of the Ecosystem Markets and Certainty Programs, at <a href="mailto:susan.payne@maryland.gov">susan.payne@maryland.gov</a> ASAP but no later than noon on Friday, March 10.</b>
</p>
</div><br><br><br><br><br>
<center><div class="disabled disabled3">These events have been cancelled because of the expected blizzard.  New dates will be posted as soon as possible.</div></center>
</div>
<br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>


<div id="water_container">
<div id="water_quality_advisory">
<h2 class="header_left_content"><center>The Maryland Water Quality Trading Advisory Committee to Meet on March 20</center></h2>
<p class="text_left_content">
<b>The next meeting of the Trading Advisory Committee will be held on Monday, March 20, from 1:00 to 4:00 p.m., at MDA’s headquarters, 50 Harry S. Truman Parkway, Annapolis, MD.</b>  The Committee acts as an ongoing consultative group to provide direction to the overall trading program and oversee further enhancement of the trading infrastructure.
</p>
</div><br><br><br>
<center><div class="disabled">This meeting has been cancelled.  The next meeting has been tentatively scheduled for May 1.</div></center>
</div>
<br>
<br><br>
<div id="water_container2">
<div id="water_quality_advisory2">
<h2 class="header_left_content"><center>Healthy Soils Workshops and $100,000 Grant Opportunity</center></h2>
<p class="text_left_content">
The Adaptation and Response Work Group of the Maryland Commission on Climate Change is sponsoring regional workshops focusing on soil science and soil health on March 14 at Hood College in Frederick and March 15 at Chesapeake College in Wye Mills.  <b>The workshops also include a presentation on the guidelines and application process for a $100,000 Innovation Technology grant that will be awarded for projects dealing with the integration of nutrient reductions with climate change adaptation.</b> <a href="/docs/Healthy_Soils_Workshops_Flyer_update.pdf">Click here</a> to view the event flyer with more details and the agenda. 
</p>
</div><br><br><br>
<center><div class="disabled2">These events have been cancelled because of the expected blizzard.  New dates will be posted as soon as possible.</div></center>
</div>
<br>





<h2 class="header_left_content"><center><br><br> </center></h2>

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
<li><a href="/docs/bestmanagementpractices.pdf">NRCS BMP List (PDF)</a>
</ul>
<?php
/*<p class="header_right_content" align=left>Farmers . . .</p>

<ul class="text_right_content">
<li><a href="http://www.mdnutrienttrading.com/farmers">Interested in nutrient trading?</a>
</ul>*/
?>

<p class="header_right_content" align=left>What's New</p>
<ul class="text_right_content">
<p><li><a href="http://www.mdnutrienttrading.com/docs/12-17-15_nutrient_trading_symposium_pr.doc">Press Release for Trading Symposium</a></p>
<p><li><a href="http://www.mdnutrienttrading.com/docs/10-23-15_nutrient_trading_policy.doc">Press Release for Trading Policy Announcement</a></p>
<p><li><a href="http://www.mdnutrienttrading.com/docs/Nutrient-Trading-Policy-3-Pager-10-23-15.pdf">Maryland Nutrient Trading Policy Statement</a></p>
</ul><p class="header_right_content" align=left>National Network on Water Quality Trading</p>
<table class="text">
<td>
<p class="text_right_content" align="justify">
The Maryland Department of Agriculture is a member of the National Network on Water Quality Trading. In June 2015, that group published a comprehensive reference providing the essential tools for new and evolving water quality trading programs. “Building a Water Quality Trading Program: Options and Considerations” identifies common trading issues and the options, considerations, and examples important to building a trading program. It captures several decades of experience in trading programs and is the product of a dialogue between National Network participants who represent agriculture, wastewater utilities, environmental groups, regulatory agencies, and practitioners. To learn more about the National Network and its activities, go to <a href="http://willamettepartnership.org/nn-wqt">willamettepartnership.org/nn-wqt</a>; to download the full publication, go to <a href="http://wri.org/nn-wqt">wri.org/nn-wqt</a>. 
</p></td><td></td><td></td><td></td></table></p></div><p> &nbsp; </p></font>
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
<a target="_BLANK" href="http://www.mde.state.md.us"><img border=0 align=right src="/images/page/MDELogo_Symbol.png"></a>
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
