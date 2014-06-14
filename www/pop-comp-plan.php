<?php
$dlg=0;
if (isset($_REQUEST["dlg"]) && $_REQUEST["dlg"]=="1")
  $dlg=1;
?>
<html>
<head>
<title>PushyAds Compensation Plan</title>
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

<style type=text/css>
ol {list-style-type: none}
</style>

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


<center> <span class="largetext bold darkred">PushyAds Compensation Plan</span></center>

<p>PushyAds rewards affiliates very handsomely for referring membership sales and other advertising services. Each PushyAds affiliate receives a unique replicated PushyAds website, through which referred sales are credited.

<p>Compensation is based on an affiliate’s membership level during the same 24 hour time period a referral sale is made. Each 24 hour time period begins at midnight Mountain Standard Time, and ends at midnight 24 hours later. All membership sales and purchases are time stamped to the second, and tracked for reporting on the affiliate's Sales Report, located in the backoffice.

<p>Payment is made twice monthly on the 10th and 25th of every month through Paypal, and is spelled out in detail inside
 the <a href="/pop-aff-agmt.php?dlg=<?php echo $dlg?>">PushyAds Affiliate Agreement</a>. Making a purchase is not required to participate in the PushyAds Affiliate Program.
 Compensation is computed daily, and based on your membership level at the time the sale is made.

<ul>
  <li><b>30%: VIP members</b> (direct & recurring referral sales)
  <li><b>50%: PRO members</b> (direct & recurring referral sales)
  <li><b>50%: ELITE members</b> (direct & recurring referral sales) <br><div style="padding-top:7px;">An additional 20% <b>INFINITY Bonus</b> on all indirect & recurring membership sales, made by VIP members within the ELITE member's own Personal Network, where no other ELITE Member is positioned between them and the VIP member who made the sale.</div>
  </li>
</ul>

<p class="bold darkred">Commissions Computed Daily</p>

<p>Because commissions are computed daily, it is possible to be paid a different percentage commission one day for the same type of sale made the next day.

<blockquote>Example: John registers on Monday as a VIP member. He refers Mary to his PushyAds affiliate page, who then registers and immediately upgrades her membership to PRO status. John's Sales Report reflects a 30% commission for Mary's membership sale on Monday, since he was a VIP member on the day the sale occured.

<p>On Tuesday, John looks at his Sales Report and realizes he could have made more commission on Mary's sale if he would have been a PRO or ELITE member. So, he upgrades his membership to ELITE status. Later that same day (Tuesday), John refers Bill, who immediately signs up as a PRO member. John receives 50% commission for Bill's membership sale, since he was an ELITE member on the day the sale occured.

<p>NOTE: It is possible to upgrade your membership AFTER a sale is made, and be compensated at the higher commission, as long as you upgrade within the same 24 hour period the referred sale is made.</blockquote>

<p class="bold darkred">INFINITY Bonus Explained</p>

<p>The ELITE member <b>INFINITY Bonus</b> is also computed daily, and is based on sales made by VIP members within the ELITE member's qualifying Personal Network. An ELITE member "qualifies" to receive a 20% bonus on all direct sales made by VIP members in Network Levels below him, where no other ELITE member is positioned between the VIP who made the sale, and the qualifying ELITE member.

<blockquote>EXAMPLE: The structure below depicts several Network Levels within John's Personal Network, made up of members who were referred by other members within a hierarchy. The hierarchy shows that John referred Mary, who referred Terry, who referred Bob, who referred Steve, who referred Gail, who referred Dave, who referred Karen, who referred Rich. Each member represents a different Network Level, forming a lineage of referrals.

<p>In the example, John is an ELITE member, who receives the <b>INFINITY Bonus</b> on qualified sales made by VIP members in 6 Network Levels within his Personal Network of referrals. Below the example is an explanation of the <b>INFINITY Bonus</b> John will receive on which sales.

<p class=bold>John - <span style="background-color: #3991CC; color: #FFFFFF" class="tahoma size12 bold">&nbsp;ELITE&nbsp;</span></p>

  <ol>
    <li>(1) <b>Mary - <span style="background-color: #4EE022; color: #FFFFFF" class="tahoma size12">&nbsp;PRO&nbsp;</span></b>

      <ol>
        <li>(2) <b>Terry - <span style="background-color: #4EE022; color: #FFFFFF" class="tahoma size12">&nbsp;PRO&nbsp;</span></b>

          <ol>
            <li>(3) <b>Bob - <span style="background-color: #F7AA0F; color: #FFFFFF" class="tahoma size12">&nbsp;VIP&nbsp;</span></b>

              <ol>
                <li>(4) <b>Steve - <span style="background-color: #4EE022; color: #FFFFFF" class="tahoma size12">&nbsp;PRO&nbsp;</span></b>
                           <-- John earns 20%

                  <ol>
                    <li>(5) <b>Gail - <span style="background-color: #F7AA0F; color: #FFFFFF" class="tahoma size12">&nbsp;VIP&nbsp;</span></b>

                      <ol>
                        <li>(6) <b>Dave - <span style="background-color: #3991CC; color: #FFFFFF" class="tahoma size12">&nbsp;ELITE&nbsp;</span></b>
                                  <-- John earns 20%

                          <ol>
                            <li>(7) <b>Karen - <span style="background-color: #F7AA0F; color: #FFFFFF" class="tahoma size12">&nbsp;VIP&nbsp;</span></b>

                              <ol>
                                <li>(8) <b>Rich - <span style="background-color: #4EE022; color: #FFFFFF" class="tahoma size12">&nbsp;PRO&nbsp;</span></b>
                                </li>
                              </ol>
                            </li>
                          </ol>
                        </li>
                      </ol>
                    </li>
                  </ol>
                </li>
              </ol>
            </li>
          </ol>
        </li>
      </ol>
    </li>
  </ol>

  <p><b>Fact #1:</b> John earns 20% on all of Bob's direct referral sales, since Bob is a VIP member, with no ELITE member positioned between him and John within the hierarchy. Since Steve was referred by Bob, John will earn 20% of all purchases made by Steve (as long as Bob remains a VIP, AND all members between Bob and John in the hierarchy remain a VIP or PRO member at the time of sale).

  <p><b>Fact #2:</b> John earns 20% on all of Gail's direct referral sales, for all the same reasons as stated in Fact #1 above, regardless what type of sale, or how much the sale is. He will continue to earn a recurring bonus on these same sales, each time they are renewed each month.

  <p><b>Fact #3:</b> John DOES NOT earn 20% on Karen's direct referral sale to Rich, even though she is within John's Personal Network and lineage of referrals. Since Dave is an ELITE member positioned between John and Karen, any sales made by Karen or any other VIP members in Network Levels below Dave would earn Dave the <b>INFINITY Bonus</b> of 20%.

  <p><b>Fact #4:</b> The reason it is called an <b>INFINITY Bonus</b> is because mathematically, the potential number of Network Levels that could exist between an ELITE member and any qualifying VIP sale is "infinite". The greater the number of referrals on each Network Level, the greater the odds are of having greater layers of Network Levels between an ELITE member and qualifying VIP sales. The example above depicts the lineage of only one direct referral of John's. Imagine if John had 5, or 10, or 20, or 50 direct referrals or more, with similar lineages.

</blockquote>

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
          <a href="/pop-disclaimer.php?dlg=<?php echo $dlg?>">Earnings Disclaimer</a>
        </div>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
