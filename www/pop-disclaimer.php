<?php
$dlg=0;
if (isset($_REQUEST["dlg"]) && $_REQUEST["dlg"]=="1")
  $dlg=1;
?>
<html>
<head>
<title>PushyAds Earnings Disclaimer</title>
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

<center> <span class="largetext bold darkred">PushyAds Earnings Disclaimer</span></center>

<p>Every effort has been made to accurately represent the products and their potential. Even though this industry is one of the few where one can write their own check in terms of earnings, there is NO GUARANTEE that you will earn any money (I suppose if you sat on your thumbs and did nothing, this might apply to you).

<p>Examples in these materials are not to be interpreted as a promise or guarantee of earnings. Earning potential is entirely dependent on the person using these products, services, ideas, and techniques. We do not purport this to be a "Get Rich Quick Scheme".

<p>Your level of success in attaining the results claimed in our materials depends upon the time you devote to the program, ideas and techniques mentioned, your finances, knowledge and various skills. Since these factors differ according to individuals, we cannot guarantee your success or income level, nor are we responsible for any of your actions ('course, I don't think you expect us to be, right?).

<p>Materials in our product(s) and our website may contain information that includes, or is based upon forward-looking statements within the meaning of the Securities Litigation Reform Act of 1995. Forward-looking statements give our expectations or forecasts of future events. You can identify these statements by the fact that they do not relate strictly to historical or current facts. They use words such as "anticipate," "estimate," "expect," "project," "intend," "plan," "believe," and other words and terms of a similar meaning in connection with a description of potential earning or financial performance. Any and all forward-looking statements here or on any of our sales material are intended to express our opinion of earnings potential.

<p>Many factors will be important in determining your actual results and no guarantees are made that you will achieve results similar to our or anyone else’s. In fact, no guarantees are made that you will achieve any results from our opportunity, system, ideas, and techniques in this material (especially if you never do anything).

<p>Any projections made are based on you earning 30% commissions on a referred membership sale as a VIP member, or 50% commissions on a referred membership
   sale as a PRO or ELITE member, as described in the <a href="/pop-comp-plan.php?dlg=<?php echo $dlg?>">Compensation Plan<a> per
   <a href="/pop-aff-agmt.php?dlg=<?php echo $dlg?>">The Affiliate Agreement</a>.

<p>So there you have it, plain and simple and hopefully straight forward enough for you to understand the potential. Now, let's go have some fun, making a lot of money!

      </td>
    </tr>
    <tr height=25 valign=top>
      <td bgcolor="#FFFFFF" >

        <div align=center class="tahoma size10">
          <a href="/pop-terms.php?dlg=<?php echo $dlg?>">Terms of Use</a> ||
          <a href="/pop-tos.php?dlg=<?php echo $dlg?>">Terms of Service</a> ||
          <a href="/pop-aff-agmt.php?dlg=<?php echo $dlg?>">Affiliate Agreement</a> ||
          <a href="/pop-copyright.php?dlg=<?php echo $dlg?>">Copyright Notice</a> ||
          <a href="/pop-privacy.php?dlg=<?php echo $dlg?>">Privacy Notice</a> ||
          <a href="/pop-comp-plan.php?dlg=<?php echo $dlg?>">Compensation Plan</a>
        </div>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
