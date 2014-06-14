<?php
$dlg=0;
if (isset($_REQUEST["dlg"]) && $_REQUEST["dlg"]=="1")
  $dlg=1;
?>
<html>
<head>
<title>PushyAds Copyright Notice</title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css">
<script type="text/javascript" src="/local-js/modal/subModal.js"></script>

<script type="text/javascript">
function closeWindow()
 {
   <?php
      if ($dlg==1)
         echo "window.top.hidePopWin(false);";
      else
         echo "window.close();";
   ?>
 }
</script>

</head>

<body class="background text">

  <div align=center style="margin: 12px 0;">

      <table width=590 cellspacing=0 cellpadding=0 style="border: 1px solid #666666;" class=text>
        <tr height=25>
          <td align="left" bgcolor=#F9FA7C valign="middle" height="25" style="background-image: url('http://pds1106.s3.amazonaws.com/images/compare-bar.jpg'); background-repeat: repeat-y; border-bottom: 1px solid #000000; padding-left: 16px;" class=size15>

            <table width=100% border=0 cellspacing=0 cellpadding=0>
              <tr>
                <td width=50% class=white><b>Pushyads.com</b></td>
                <td width=50% align=right valign=middle><img src="http://pds1106.s3.amazonaws.com/images/favicon.ico"> &nbsp;</td>
              </tr>
            </table>

          </td>
        </tr>

        <tr>
          <td><img src="http://pds1106.s3.amazonaws.com/images/menu-shadow2.jpg" width="620" height="6"></td>
        </tr>
        <tr height=18 valign=bottom bgcolor="#FFFFFF">
          <td align=right class="smalltext" style="padding-right: 20px"><a href="javascript:closeWindow()">Close Window</a></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF" style="padding: 5px 20px 15px 20px">

<center> <span class="largetext bold darkred">PushyAds Copyright Notice</span></center>

        <p>All material on PushyAds.com domains is copyrighted and cannot be reproduced, stored in a
    retrieval system, or transmitted, in any form or by any means, electronic, mechanical, photocopying,
    recording, or otherwise without the prior written permission of PushyAds.com.

    <p>No material copyrighted by PushyAds.com can be sold or used by anyone in any way. All
    copyrighted material, except creatives, provided on PushyAds.com list of domains is provided
    to clients who purchase services from PushyAds.com only for the purpose of helping them become
    proficient in their business.

        <p>Copyrighted creatives, which include graphics, ad copy, banners, or any and all manner of media
    used for advertising or generating leads for clients or that make up the creative content of the
    PushyAds.com websites, cannot be used for any purpose without the prior written permission of
    PushyAds.com.

        <p>Affiliates of PushyAds.com may use selected ad copy, banners, graphics, videos, audio, and email teasers
    located inside the Affiliate backoffice under "Marketing Materials" ONLY for the express purpose of
    advertising PushyAds.com as specified in the policies & procedures in the PushyAds.com
    <a href="/pop-aff-agmt.php?dlg=<?php echo $dlg?>">Affiliate Agreement</a>.

      </td>
    </tr>
    <tr height=25 valign=top>
      <td bgcolor="#FFFFFF" >

        <div align=center class="tahoma size10">
          <a href="/pop-terms.php?dlg=<?php echo $dlg?>">Terms of Use</a> ||
          <a href="/pop-tos.php?dlg=<?php echo $dlg?>">Terms of Service</a> ||
          <a href="/pop-aff-agmt.php?dlg=<?php echo $dlg?>">Affiliate Agreement</a> ||
          <a href="/pop-disclaimer.php?dlg=<?php echo $dlg?>">Earnings Disclaimer</a> ||
          <a href="/pop-privacy.php?dlg=<?php echo $dlg?>">Privacy Notice</a> ||
          <a href="/pop-comp-plan.php?dlg=<?php echo $dlg?>">Compensation Plan</a>
        </div>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
