<?php
include("pushy_constants.inc");
$mid=$_REQUEST["mid"];
$sid=$_REQUEST["sid"];
//  header("HTTP/1.1 302 Found");
$headerLocation=DOMAIN."/members/frame_header.php?mid=$mid&sid=$sid";
$mainLocation=DOMAIN."/members/index.php?mid=$mid&sid=$sid";
if (isset($_REQUEST["_tab_"]) && strlen($_REQUEST["_tab_"])>0)
  {
    $_tab_=$_REQUEST["_tab_"];
    $mainLocation.="&_tab_=$_tab_";
  }
?>
<html>
<head>
<title>PushyAds</title>
</head>
<frameset rows="0%,*" frameborder=0 framespacing=0 border=0>
  <frame name="FRAME_PUSHY_HEADER" id="FRAME_PUSHY_HEADER" src="<?php echo $headerLocation?>">
  <frameset>
      <frame src="<?php echo $mainLocation?>">
  </frameset>

<noframes>Your Browser does not support frames</NOFRAMES>
</frames>
</html>
