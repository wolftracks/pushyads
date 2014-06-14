<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");

$DEBUG=FALSE;

$mid=$_REQUEST["mid"];
$sid=$_REQUEST["sid"];

$db = getPushyDatabaseConnection();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<title>TABS</title>
<head>
     <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
     <link rel='STYLESHEET' type='text/css' href='/local-css/styles.css'>
     <link rel="STYLESHEET" type="text/css" href="codebase/dhtmlxtabbar.css">
     <script  src="codebase/dhtmlxcommon.js"></script>
     <script  src="codebase/dhtmlxtabbar.js"></script>
     <script  src="codebase/dhtmlxtabbar_start.js"></script>

<style type='text/css'>

.border_lt1 {
   border-left:  2px solid #000000;
}

.border_rt1 {
   border-right:  2px solid #000000;
}

.bdr_crnr1 {
   border-left:  3px double #000000;
   border-bottom:  2px solid #000000;
}
.bdr_crnr2 {
   border-left:  1px solid #000000;
   border-bottom:  2px solid #000000;
}
.bdr_crnr3 {
   border-left:  3px double #000000;
   border-bottom:  1px solid #999999;
}
.bdr_crnr4 {
   border-left:  1px solid #000000;
   border-bottom:  1px solid #999999;
}
</style>

</head>

<body>

<!---------------------------------------------------------- BEGIN PAGE CONTENT ------------------------------------------------------>

  <table border=0 cellpadding=0 cellspacing=0>
    <tr>
      <td>
        <div id="reports_tabbar" class="dhtmlxTabBar text" mode="top" tabstyle="winDflt" hrefmode="iframes-on-demand" select="reports1" imgpath="codebase/imgs/" style="width:695;" skinColors="#FFFBF5,#F5F3F0">

<!------------------------------ BEGIN REFERRALS TAB -------------------------------->

          <div id="reports1" name="Referrals" width="80px" style="padding:15">
             <?php
                include_once("reports_referrals.php");
             ?>
          </div>
<!------------------------------ BEGIN NETWORK TAB -------------------------------->

          <div id="reports2" name="Network" width="80px" style="padding:15px">

          <table width="645" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%" height="35">
                <table width="100%" align=center border="0" cellspacing="0" cellpadding="0" class="text red">
                  <tr>
                    <td width="55%"><em class="required">&nbsp;September 10, 2009 </em><span class="text red"><i>(10:57 pm MST)</i></span></td>
                    <td width="20%" align=right valign=bottom >&nbsp;</td>
                    <td width="25%" align=right  valign=bottom style="padding-right: 14px;">&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <table width=645 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
            <tr>
              <td bgcolor="#FFFFFF">
                <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
                  <tr>
                    <td class="text">
                      This report shows you the extent of your ad exposure via the <b class=darkred>Pushy</b>&#8482 widget and
                      the <b class=darkred>Elite Bar</b>&#8482, and is based on the size of your <a href=javascript:void(0)>actual</a> &
                      <a href=javascript:void(0)>potential</a> advertising networks, which are made up of referrals many layers deep.

                      <table border=0 width=100% class=text>
                        <tr><td height=10 class=size10>&nbsp;</td></tr>
                        <tr>
                          <td width="40%" align=left><b>Personal Referrals:</b> &nbsp; </td>
                          <td width="20%" align=right><b>VIP:</b>&nbsp; <span class=darkred>12</span></td>
                          <td width="20%" align=right><b>PRO:</b>&nbsp; <span class=darkred>7</span></td>
                          <td width="20%" align=right><b>ELITE:</b>&nbsp; <span class=darkred>3</span></td>
                        </tr>
                        <tr><td height=10 class=size10>&nbsp;</td></tr>
                        <tr>
                          <td align=left colspan=4><b>Network Referrals:</b>&nbsp;
                            <i class=smalltext>(Numbers seen below are "actual" referrals within your network.)</i>
                            <p> Colored sections depict the number of "actual" Pushy members who may have <b class=darkred>Pushy</b>&#8482 on
                            their websites, and who may see your ad in the <b class=darkred>Elite Bar</b>&#8482 each time they login to their
                            <b class=darkred>Pushy</b>&#8482 backoffice. Your potential ad exposure is compounded exponentially, based on how many
                            of these referrals place <b class=darkred>Pushy</b>&#8482 on one or more websites and blogs, and the number of hits
                            each of them receives daily. </p>
                          </td>
                        </tr>
                        <tr><td height=6 class=size6>&nbsp;</td></tr>
                      </table>

                      <table width=100% class="smallgridb1 black" border=0 cellspacing=0 cellpadding=0>
                        <tr valign="middle" bgcolor="#DEE2E7">
                          <td width="15%" align=left><b>Level</b></td>
                          <td width="30%" align=left><b>Referrals</b></td>
                          <td width="55%" align=left><b>Ad Exposure</b></td>
                        </tr>
                        <tr valign="middle" bgcolor=#FFFDF4>
                          <td align=left>1</td>
                          <td align=right style="padding-right: 116px">197</td>
                          <td rowspan="2" align=left valign=bottom><b>Total VIP Exposure: <span class=darkred>539 Referrals</span></b></td>
                        </tr>
                        <tr valign="middle" bgcolor=#FFFDF4>
                          <td align=left>2</td>
                          <td align=right style="padding-right: 116px">342</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F1FEF1>
                          <td align=left>3</td>
                          <td align=right style="padding-right: 116px">744</td>
                          <td rowspan="3" align=left valign=bottom><b>Total PRO Exposure: <span class=darkred>4,370 Referrals</span></b></td>
                        </tr>
                        <tr valign="middle" bgcolor=#F1FEF1>
                          <td align=left>4</td>
                          <td align=right style="padding-right: 116px">1,251</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F1FEF1>
                          <td align=left>5</td>
                          <td align=right style="padding-right: 116px">1,836</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>6</td>
                          <td align=right style="padding-right: 116px">2,584</td>
                          <td rowspan="15" align=left valign=bottom><b>Total ELITE Exposure: <span class=darkred>1,576,276 Referrals</span></b></td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>7</td>
                          <td align=right style="padding-right: 116px">4,621</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>8</td>
                          <td align=right style="padding-right: 116px">7,879</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>9</td>
                          <td align=right style="padding-right: 116px">13,421</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>10</td>
                          <td align=right style="padding-right: 116px">22,724</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                        <td align=left>11</td>
                          <td align=right style="padding-right: 116px">39,012</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>12</td>
                          <td align=right style="padding-right: 116px">61,476</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>13</td>
                          <td align=right style="padding-right: 116px">87,185</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>14</td>
                          <td align=right style="padding-right: 116px">122,791</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>15</td>
                          <td align=right style="padding-right: 116px">176,247</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>16</td>
                          <td align=right style="padding-right: 116px">302,126</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>17</td>
                          <td align=right style="padding-right: 116px">412,824</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>18</td>
                          <td align=right style="padding-right: 116px">241,921</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>19</td>
                          <td align=right style="padding-right: 116px">71,401</td>
                        </tr>
                        <tr valign="middle" bgcolor=#F2FDFF>
                          <td align=left>20</td>
                          <td align=right style="padding-right: 116px">5,694</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          </div>

<!------------------------------ BEGIN TRAFFIC TAB -------------------------------->

          <div id="reports3" name="Traffic" width="80px" style="padding:15px 5px">




                      <table width="645" border=0 cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="100%" height="35">
                            <table width="100%" align=center border="0" cellspacing="0" cellpadding="0" class="text red">
                              <tr>
                                <td width="55%"><em class="required">&nbsp;September 10, 2009  </em><span class="text red"><i>(10:57 pm MST)</i></span></td>
                                <td width="20%" align=right valign=bottom >&nbsp;</td>
                                <td width="25%" align=right  valign=bottom style="padding-right: 14px;">&nbsp;</td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>

<div style="position:absolute; width:645px; height: 120px; border-top: 2px solid #FFCC00; border-left: 2px solid #FFCC00; border-right: 2px solid #FFCC00; margin: 0 12px 0px 15px"></div>

                      <table width=645 bgcolor=#FFFFFF valign=top border=0 cellpadding=0 cellspacing=0>
                        <tr>
                          <td >
                            <table width=100% align=center valign=top cellspacing=0 cellpadding=15 style="margin-left: 19px;">
                              <tr>
                                <td class="text">

                                  This report will help you understand where the best sources of traffic are coming from. In order to increase your ROI, you will
                                  need to know where to target your best customers. These reports reveal that information if you know how to read them.


                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>

                      <!------------------- BEGIN TRAFFIC REPORT -------------------->

                      <table width=680 border=0 cellspacing=0 cellpadding=0 style="border-collapse: collapse;" class="smalltext">
                        <tr height=40>
                          <td width=21% bgcolor=#FFFFFF> &nbsp;</td>
                          <td width=67% align=center valign=middle class="bdr_crnr1 largetext bold" bgcolor=#F1FEF1 colspan=8 style="border-top: 3px double #000000; border-right: 3px double #000000;">
                            <a href=""><img src="http://pds1106.s3.amazonaws.com/images/arrow2-lt.png" style="vertical-align:middle;"></a>
                            <a href=""><img src="http://pds1106.s3.amazonaws.com/images/arrow-lt.png" style="margin-right: 50px; vertical-align:middle;"></a>
                               SEPTEMBER
                            <a href=""><img src="http://pds1106.s3.amazonaws.com/images/arrow-rt.png" style="margin-left: 50px; vertical-align:middle;"></a>
                            <a href=""><img src="http://pds1106.s3.amazonaws.com/images/arrow2-rt.png" style="vertical-align:middle;"></a>
                            </td>
                          <td width=12% class="bdr_crnr1" bgcolor=#FFFFFF>&nbsp;</td>
                        </tr>

                        <tr height=37 bgcolor=#FFF8EB>
                          <td width=21% valign=top class="border_lt1 bold" bgcolor=#F1FEF1 style="border-top: 2px solid #000000; padding: 4px 5px;">
                            TRAFFIC SOURCE</td>
                          <td width=8%  align=center class="bdr_crnr1 bold">SUN<br>13</td>
                          <td width=8%  align=center class="bdr_crnr2 bold">MON<br>14</td>
                          <td width=8%  align=center class="bdr_crnr2 bold">TUE<br>15</td>
                          <td width=8%  align=center class="bdr_crnr2 bold">WED<br>16</td>
                          <td width=8%  align=center class="bdr_crnr2 bold">THU<br>17</td>
                          <td width=8%  align=center class="bdr_crnr2 bold">FRI<br>18</td>
                          <td width=8%  align=center class="bdr_crnr2 bold">SAT<br>19</td>
                          <td width=11% align=center class="bdr_crnr1 bold" bgcolor=#FFF8EB>TOTALS<br>Week</td>
                          <td width=12% align=center class="border_rt1 bdr_crnr1 bold" bgcolor=#F1FEF1>TOTALS<br>30 days</td>
                        </tr>

<!--------------------- PUSHY STATS --------------------->

                        <tr height=24 bgcolor=#FFFFFF>
                          <td class="border_lt1 bold" bgcolor=#F1FEF1 style="padding-left: 5px; ">
                             <a href="" title="Configure My Pushy" alt="Configure My Pushy">My Pushy</a>&nbsp;&#42;</td>
                          <td align=center class="bdr_crnr3">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr3 border_rt2" bgcolor=#FFF8EB>&nbsp;</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>&nbsp;</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1  style="padding-left: 10px;">
                            <a href="http://www.domain-name-1.xxx" title="domain-name-1.xxx" alt="domain-name-1.xxx" target="blank">On My Website #1</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1 style="padding-left: 10px;">
                            <a href="http://www.domain-name-2.xxx" title="domain-name-2.xxx" alt="domain-name-2.xxx" target="blank">On My Website #2</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1 style="padding-left: 10px;">
                            <a href="http://www.domain-name-3.xxx" title="domain-name-3.xxx" alt="domain-name-3.xxx" target="blank">On my website #99</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1" bgcolor=#F1FEF1 style="border-bottom: 2px solid #000000;">&nbsp;</td>
                          <td align=center class="bdr_crnr1">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr1" bgcolor=#FFF8EB>&nbsp;</td>
                          <td align=center class="bdr_crnr1 border_rt1" bgcolor=#F1FEF1>&nbsp;</td>
                        </tr>

<!--------------------- PRODUCT A STATS --------------------->

                        <tr height=24 bgcolor=#FFFFFF>
                          <td class="border_lt1 bold" bgcolor=#F1FEF1 style="padding-left: 5px; ">
                            <div style="width: 130px; white-space: nowrap; overflow: hidden;">
                              <a href="" title="Configure My Product Ad" alt="Configure My Product Ad">Product Name A</a>
                            </div>
                          </td>
                          <td align=center class="bdr_crnr3">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr3 border_rt2" bgcolor=#FFF8EB>&nbsp;</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>&nbsp;</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1  style="padding-left: 10px;">
                            <a href="" title="domain-name-1.xxx" alt="domain-name-1.xxx" target="blank">On my website #1</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1  style="padding-left: 10px;">
                            <a href="" title="domain-name-2.xxx" alt="domain-name-2.xxx" target="blank">On my website #2</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1  style="padding-left: 10px;">
                            <a href="" title="domain-name-3.xxx" alt="domain-name-3.xxx" target="blank">On my website #99</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
                              Referral Websites ^
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
                              Pushy Network
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
                              Elite Bar
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1" bgcolor=#F1FEF1 style="border-bottom: 2px solid #000000;">&nbsp;</td>
                          <td align=center class="bdr_crnr1">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr1" bgcolor=#FFF8EB>&nbsp;</td>
                          <td align=center class="bdr_crnr1 border_rt1" bgcolor=#F1FEF1>&nbsp;</td>
                        </tr>

<!--------------------- PRODUCT B STATS --------------------->

                        <tr height=24 bgcolor=#FFFFFF>
                          <td class="border_lt1 bold" bgcolor=#F1FEF1 style="padding-left: 5px; ">
                            <div style="width: 130px; white-space: nowrap; overflow: hidden;">
                              <a href="" title="Configure My Product Ad" alt="Configure My Product Ad">Product Name B</a>
                            </div>
                          </td>
                          <td align=center class="bdr_crnr3">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr4">&nbsp;</td>
                          <td align=center class="bdr_crnr3 border_rt2" bgcolor=#FFF8EB>&nbsp;</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>&nbsp;</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1  style="padding-left: 10px;">
                            <a href="" title="domain-name-1.xxx" alt="domain-name-1.xxx" target="blank">On my website #1</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1  style="padding-left: 10px;">
                            <a href="" title="domain-name-2.xxx" alt="domain-name-2.xxx" target="blank">On my website #2</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext" bgcolor=#F1FEF1  style="padding-left: 10px;">
                            <a href="" title="domain-name-3.xxx" alt="domain-name-3.xxx" target="blank">On my website #99</a>
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
                              Referral Websites ^
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
                              Pushy Network
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1 smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
                              Elite Bar
                          </td>
                          <td align=center class="bdr_crnr3">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr4">-</td>
                          <td align=center class="bdr_crnr3" bgcolor=#FFF8EB>-</td>
                          <td align=center class="bdr_crnr3 border_rt1" bgcolor=#F1FEF1>-</td>
                        </tr>

                        <tr height=24 bgcolor=#FFFFFF class="tinytext">
                          <td class="border_lt1" bgcolor=#F1FEF1 style="border-bottom: 2px solid #000000;">&nbsp;</td>
                          <td align=center class="bdr_crnr1">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr2">&nbsp;</td>
                          <td align=center class="bdr_crnr1" bgcolor=#FFF8EB>&nbsp;</td>
                          <td align=center class="bdr_crnr1 border_rt1" bgcolor=#F1FEF1>&nbsp;</td>
                        </tr>

<!-------------------- BOTTOM GREEN ROW --------------------->

                        <tr height=75>
                          <td width=21%></td>
                          <td width=67% align=right valign=middle class="bdr_crnr1 text bold" bgcolor=#F1FEF1 colspan=8 style="border-bottom: 3px double #000000; border-right: 3px double #000000; line-height:25px;">
                            "Existing Product" Referrals:&nbsp;
                            <br> ^ Direct referrals displaying Pushy on 1 or more websites:&nbsp;
                          </td>
                          <td width=12% align=center valign=middle class="text bold">
                            48
                            <br>36
                          </td>
                        </tr>

                        <tr>
                          <td width=21% bgcolor=#FFFFFF >&nbsp;</td>
                          <td width=67% bgcolor=#FFFFFF colspan=8 align=right height=50 >
                            * Hits on Pushy widget / Clicks on "Get Pushy" link (on your website) &nbsp;</td>
                          <td width=12% bgcolor=#FFFFFF>&nbsp;</td>
                        </tr>

                      </table>

<div style="position:absolute; width:645px; height: 124px; border-bottom: 2px solid #FFCC00; border-left: 2px solid #FFCC00; border-right: 2px solid #FFCC00; margin: -124px 12px 0px 15px;"></div>






          </div>

<!------------------------------ BEGIN CREDIT TAB -------------------------------->

          <!----------- FORMERLY RANKING TAB ---------------->
          <!----------- div id="reports4" name="Credit" width="80px" style="padding:15px" href="http://www.ibmt.net/_test/ranking2.php"></div ------------>

          <div id="a4" name="Credit" width="80px" style="padding:15px;">

            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>

          <table width="645" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%" height="35">
                <center> ~~ <b>IN PROGRESS</b> ~~</center>
              </td>
            </tr>
          </table>

          </div>

<!------------------------------ BEGIN SALES TAB ---------------------------------->

          <div id="reports5" name="Sales" width="80px" style="padding:15px;">

          <table width="645" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%" height="35">
                <table width="100%" align=center border="0" cellspacing="0" cellpadding="0" class="text red">
                  <tr>
                    <td width="55%"><em class="required">&nbsp;September 10, 2009  </em><span class="text red"><i>(10:57 pm MST)</i></span></td>
                    <td width="19%" align=right valign=bottom><b>REFERRALS</b></td>
                    <td width="26%" align=right  valign=bottom style="padding-right: 37px;"><b>EARNINGS</b></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <table width="645" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%">
                <table width=100% class="text red" cellspacing=3 cellpadding=0 bgcolor=#FBFFFA style="border: 1px solid #17B000">
                  <tr>
                    <td width="55%" align=left>&nbsp;&nbsp;<b><a href=javascript:void(0)>January</a>&nbsp;-</b></td>
                    <td width="20%" align=right style="padding-right: 3px;"><b>196</b></td>
                    <td width="25%" align=right style="padding-right:33px;"><b>$796.50</b></td>
                  </tr>
                </table>
                <table width=539 align=right cellspacing=0 cellpadding=0  style="margin-right: 27px; margin-bottom: 5px; border-right: 1px solid #FFCC00">
                  <tr>
                    <td>
                      <table width=100% class="smalltext black" border=0 cellspacing=0 cellpadding=3>
                        <tr valign="middle" bgcolor="#DEE2E7">
                          <td width="10%" bgcolor=#FFFBF5><b>&nbsp;</b></td>
                          <td width="20%" class=aff_rpts3 style="border-left: 1px solid #FFCC00"><b>VIP</b></td>
                          <td width="23%" class=aff_rpts3><b>PRO</b></td>
                          <td width="23%" class=aff_rpts3><b>ELITE</b></td>
                          <td width="24%" class=aff_rpts3><b>TOTALS</b></td>
                        </tr>

                        <tr valign="middle" bgcolor=#FFFFFF>
                          <td rowspan=2 bgcolor=#FFFBF5>&nbsp;</td>
                          <td class="aff_rpts darkred" style="border-left: 1px solid #FFCC00">129</td>
                          <td class="aff_rpts darkred">50</td>
                          <td class="aff_rpts darkred">14</td>
                          <td class="aff_rpts darkred"><b>196</b></td>
                        </tr>
                        <tr valign="middle" bgcolor=#F8F9FA>
                          <td class="aff_rpts3 black" style="border-left: 1px solid #FFCC00">~</td>
                          <td class="aff_rpts3 black">$117.50</td>
                          <td class="aff_rpts3 black">$679.00</td>
                          <td class="aff_rpts3 black"><b>$796.50</b></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table width=100% class="text red" cellspacing=3 cellpadding=0 bgcolor=#FBFFFA style="border: 1px solid #17B000; margin: 20px 0 -1px 0;">
                  <tr>
                    <td width="55%" align=left>&nbsp;&nbsp;<b><a href=javascript:void(0)>February</a>&nbsp;-</b></td>
                    <td width="20%" align=right style="padding-right: 3px;"><b>135</b></td>
                    <td width="25%" align=right style="padding-right:33px"><b>$1,914.50</b></td>
                  </tr>
                 </table>

<!--- BEGIN NESTED SALES TAB --->

                <table width=90% align=right cellspacing=0 cellpadding=0  style="margin: 0 10px 20px 0">
                  <tr>
                    <td>
                      <div id="b_tabbar" class="dhtmlxTabBar text" mode="left" hrefmode="iframes-on-demand" tabstyle="modern" select="b1" imgpath="codebase/imgs/" skinColors="#FFFFFF,#C2DFE9" style="width:100%; height:324">

                        <div id="b1" name="1" width="100px">

                        <table width=539 class="smalltext black" cellspacing=0 cellpadding=3  style="margin-bottom: 1px; border-right: 1px solid #FFCC00;">
                          <tr valign="middle" bgcolor="#DEE2E7">
                            <td width="10%" class=aff_rpts2><b>Day</b></td>
                            <td width="20%" class=aff_rpts3><b>VIP</b></td>
                            <td width="23%" class=aff_rpts3><b>PRO</b></td>
                            <td width="23%" class=aff_rpts3><b>ELITE</b></td>
                            <td width="24%" class=aff_rpts3><b>TOTALS</b></td>
                          </tr>

                          <tr valign="middle" bgcolor=#FFFFFF>
                            <td rowspan="2" class=aff_rpts2 bgcolor=#F1F2F5>1</td>
                            <td class="aff_rpts darkred">12</td>
                            <td class="aff_rpts darkred">7</td>
                            <td class="aff_rpts darkred">3</td>
                            <td class="aff_rpts darkred"><b>22</b></td>
                          </tr>
                          <tr valign="middle" bgcolor=#F8F9FA>
                            <td class="aff_rpts3 black">~</td>
                            <td class="aff_rpts3 black">$164.50</td>
                            <td class="aff_rpts3 black">$145.50</td>
                            <td class="aff_rpts3 black"><b>$310.00</b></td>
                          </tr>

                          <tr valign="middle" bgcolor=#FFFFFF>
                            <td rowspan="2" class=aff_rpts2 bgcolor=#F1F2F5>2</td>
                            <td class="aff_rpts darkred">27</td>
                            <td class="aff_rpts darkred">11</td>
                            <td class="aff_rpts darkred">8</td>
                            <td class="aff_rpts darkred"><b>46</b></td>
                          </tr>
                          <tr valign="middle" bgcolor=#F8F9FA>
                            <td class="aff_rpts3 black">~</td>
                            <td class="aff_rpts3 black">$258.50</td>
                            <td class="aff_rpts3 black">$388.00</td>
                            <td class="aff_rpts3 black"><b>$646.50</b></td>
                          </tr>

                          <tr valign="middle" bgcolor=#FFFFFF>
                            <td rowspan="2" class=aff_rpts2 bgcolor=#F1F2F5>3</td>
                            <td class="aff_rpts darkred">39</td>
                            <td class="aff_rpts darkred">16</td>
                            <td class="aff_rpts darkred">12</td>
                            <td class="aff_rpts darkred"><b>67</b></td>
                          </tr>
                          <tr valign="middle" bgcolor=#F8F9FA>
                            <td class="aff_rpts3 black">~</td>
                            <td class="aff_rpts3 black">$376.00</td>
                            <td class="aff_rpts3 black">$582.00</td>
                            <td class="aff_rpts3 black"><b>$958.00</b></td>
                          </tr>

                          <tr bgcolor=#FFFFFF>
                            <td class=aff_rpts>&nbsp;&nbsp;&nbsp;<img src="http://ibmt3.net/images/calculator_add.png" style="vertical-align: middle"></td>
                            <td colspan=5 align=center style="border-bottom: 1px solid #BFC4D0; color:#299900" class="size16 bold">
                              <i>OK <?php echo $firstname?>, add it all up now!</i></td>
                          </tr>

                          <tr valign="middle" bgcolor=#FFFFFF>
                            <td rowspan="2" class="aff_rpts2 red" bgcolor=#F1F2F5>MTD</td>
                            <td class="aff_rpts darkred"><b>78</b></td>
                            <td class="aff_rpts darkred"><b>34</b></td>
                            <td class="aff_rpts darkred"><b>23</b></td>
                            <td class="aff_rpts darkred"><b>135</b></td>
                          </tr>
                          <tr valign="middle" bgcolor=#F8F9FA>
                            <td class="aff_rpts3 black">~</td>
                            <td class="aff_rpts3 black"><b>$799.00</b></td>
                            <td class="aff_rpts3 black"><b>$1,115.50</b></td>
                            <td class="aff_rpts3 black"><b>$1,914.50</b></td>
                          </tr>

                          <tr>
                            <td colspan=5 height=10 class=size10 bgcolor=#E4E8EB style="border-bottom: 1px solid #BFC4D0;">&nbsp;</td>
                          </tr>

                          <tr valign="middle" bgcolor=#FFFFFF>
                            <td rowspan="2" class="aff_rpts2 red" bgcolor=#F1F2F5>YTD</td>
                            <td class="aff_rpts darkred"><b>207</b></td>
                            <td class="aff_rpts darkred"><b>84</b></td>
                            <td class="aff_rpts darkred"><b>37</b></td>
                            <td class="aff_rpts darkred"><b>332</b></td>
                          </tr>
                          <tr valign="middle" bgcolor=#F8F9FA>
                            <td class="aff_rpts3 black">~</td>
                            <td class="aff_rpts3 black"><b>$916.50</b></td>
                            <td class="aff_rpts3 black"><b>$1,794.50</b></td>
                            <td class="aff_rpts3 black"><b>$2,711.00</b></td>
                          </tr>

                        </table>
                        </div>

                        <div id="b2" name="2" width="100px">

                        <table align=center class="text" border=0 cellspacing=0 cellpadding=7 style="margin-left: 20px; margin-right: 20px">
                          <tr>
                            <td>
                              <p>Although payment for February membership sales will be made in March, this report will appear under February's
                              <b>Payment</b> tab on or before the 15th of March <i>(whenever your affiliate payment has been processed)</i>.</p>
                            </td>
                          </tr>
                        </table>

                        <table width=75% align=center class="text" cellspacing=0 cellpadding=15 style="border: 1px solid #FFCC00">
                          <tr>
                            <td align=center>
                              <table width=95% align=center class="text" border=0 cellspacing=0 cellpadding=0>
                                <tr class=red>
                                  <td width=56%><b>Sales</b></td>
                                  <td width=1%>&nbsp;</td>
                                  <td width=30%>&nbsp;</td>
                                  <td width=13%>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td style="padding-left: 15px">PRO Memberships</td>
                                  <td>$</td>
                                  <td align=right>216.00</td>
                                  <td align=right>(16)</td>
                                </tr>
                                <tr>
                                  <td style="padding-left: 15px">ELITE Memberships</td>
                                  <td>$</td>
                                  <td align=right style="border-bottom: 1px solid #000000">3,546.00</td>
                                  <td align=right>(12)</td>
                                </tr>
                                <tr height=30>
                                  <td style="padding-left: 15px"><b>Total Earnings</b></td>
                                  <td><b>$</b></td>
                                  <td align=right><b>3,762.00</b></td>
                                  <td>&nbsp;</td>
                                </tr>

                                <tr><td>&nbsp;</td></tr>

                                <tr class=red>
                                  <td><b>Returns</b></td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td align=right>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td style="padding-left: 15px">PRO Memberships</td>
                                  <td>$</td>
                                  <td align=right>0.00</td>
                                  <td align=right>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td style="padding-left: 15px">ELITE Memberships</td>
                                  <td>$</td>
                                  <td align=right style="border-bottom: 1px solid #000000">-98.50</td>
                                  <td align=right>(1)</td>
                                </tr>
                                <tr height=30>
                                  <td style="padding-left: 15px"><b>Total Returns</b></td>
                                  <td><b>$</b></td>
                                  <td align=right><b>-98.50</b></td>
                                  <td>&nbsp;</td>
                                </tr>

                                <tr><td>&nbsp;</td></tr>

                                <tr class=red>
                                  <td><b>Total Payment Amount</b></td>
                                  <td><b>$</b></td>
                                  <td align=right class=doubleline><b>3,663.50</b></td>
                                  <td>&nbsp;</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <span style="height:7px">&nbsp;</span>

                        </div>

                        <div id="b3" name="3" width="100px">

                        <table width=504 class="smalltext black" border=0 cellspacing=0 cellpadding=4 style="border" >
                          <tr valign="middle" bgcolor="#DEE2E7">
                            <td width="14%" class=aff_rpts2><b>Sun</b></td>
                            <td width="14%" class=aff_rpts2><b>Mon</b></td>
                            <td width="14%" class=aff_rpts2><b>Tue</b></td>
                            <td width="14%" class=aff_rpts2><b>Wed</b></td>
                            <td width="14%" class=aff_rpts2><b>Thu</b></td>
                            <td width="14%" class=aff_rpts2><b>Fri</b></td>
                            <td width="14%" class=aff_rpts2><b>Sat</b></td>
                          </tr>
                          <tr align=right valign="top" height=60 bgcolor="#FFFFFF">
                            <td class=aff_rpts><b>&nbsp;</b></td>
                            <td class=aff_rpts><b>&nbsp;</b></td>
                            <td class=aff_rpts><b>&nbsp;</b></td>
                            <td class=aff_rpts><b>&nbsp;</b></td>
                            <td class=aff_rpts><b>&nbsp;</b></td>
                            <td class=aff_rpts><b>1</b></td>
                            <td class=aff_rpts><b>2</b></td>
                          </tr>
                          <tr align=right valign="top" height=60 bgcolor="#FFFFFF">
                            <td class=aff_rpts><b>3</b></td>
                            <td class=aff_rpts><b>4</b></td>
                            <td class=aff_rpts><b>5</b></td>
                            <td class=aff_rpts><b>6</b></td>
                            <td class=aff_rpts><b>7</b></td>
                            <td class=aff_rpts><b>8</b></td>
                            <td class=aff_rpts><b>9</b></td>
                          </tr>
                          <tr align=right valign="top" height=60 bgcolor="#FFFFFF">
                            <td class=aff_rpts><b>10</b></td>
                            <td class=aff_rpts><b>11</b></td>
                            <td class=aff_rpts><b>12</b></td>
                            <td class=aff_rpts><b>13</b></td>
                            <td class=aff_rpts><b>14</b></td>
                            <td class=aff_rpts><b>15</b></td>
                            <td class=aff_rpts><b>16</b></td>
                          </tr>
                          <tr align=right valign="top" height=60 bgcolor="#FFFFFF">
                            <td class=aff_rpts><b>17</b></td>
                            <td class=aff_rpts><b>18</b></td>
                            <td class=aff_rpts><b>19</b></td>
                            <td class=aff_rpts><b>20</b></td>
                            <td class=aff_rpts><b>21</b></td>
                            <td class=aff_rpts><b>22</b></td>
                            <td class=aff_rpts><b>23</b></td>
                          </tr>
                          <tr align=right valign="top" height=60 bgcolor="#FFFFFF">
                            <td class=aff_rpts><b>24</b></td>
                            <td class=aff_rpts><b>25</b></td>
                            <td class=aff_rpts><b>26</b></td>
                            <td class=aff_rpts><b>27</b></td>
                            <td class=aff_rpts><b>28</b></td>
                            <td class=aff_rpts><b>29</b></td>
                            <td class=aff_rpts><b>&nbsp;</b></td>
                          </tr>
                        </table>

                        </div>

<!-------------------
                        <div id="b4" name="4" width="60px">Content 4</div>

                        <div id="b5" name="5" width="60px">Content 5</div>
--------------------->
                      </div>

                    </td>
                  </tr>
                </table>

<!--- END NESTED SALES TAB --->

              </td>
            </tr>
            <tr>
              <td>
                <table width=100% class="text red" cellspacing=3 cellpadding=0 bgcolor=#FBFFFA style="border: 1px solid #17B000; margin-bottom: -1px;">
                  <tr>
                    <td width="55%" align=left>&nbsp;&nbsp;<b><a href=javascript:void(0)>March</a>&nbsp;+</b></td>
                    <td width="20%" align=right style="padding-right: 3px;"><b>298</b></td>
                    <td width="25%" align=right style="padding-right:33px"><b>$5,789.50</b></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table width=100% class="text red" cellspacing=3 cellpadding=0 bgcolor=#FBFFFA style="border: 1px solid #17B000; margin-bottom: -1px;">
                  <tr>
                    <td width="55%" align=left>&nbsp;&nbsp;<b><a href=javascript:void(0)>April</a>&nbsp;+</b></td>
                    <td width="20%" align=right style="padding-right: 3px;"><b>0</b></td>
                    <td width="25%" align=right style="padding-right:33px"><b>$0.00</b></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table width=100% class="text red" cellspacing=3 cellpadding=0 bgcolor=#FBFFFA style="border: 1px solid #17B000; margin-bottom: -1px;">
                  <tr>
                    <td width="55%" align=left>&nbsp;&nbsp;<b><a href=javascript:void(0)>May</a>&nbsp;+</b></td>
                    <td width="20%" align=right style="padding-right: 3px;"><b>298</b></td>
                    <td width="25%" align=right style="padding-right:33px;"><b>$6,140.00</b></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table width=100% class="text red" cellspacing=3 cellpadding=0 bgcolor=#FBFFFA style="border: 1px solid #17B000;">
                  <tr>
                    <td width="55%" align=left>&nbsp;&nbsp;<b><a href=javascript:void(0)>June</a>&nbsp;+</b></td>
                    <td width="20%" align=right style="padding-right: 3px;"><b>482</b></td>
                    <td width="25%" align=right style="padding-right:33px;"><b>$10,476.50</b></td>
                  </tr>
                </table>
              </td>
            </tr>


            <tr>
              <td><br>
                <table width=100% class="size18 red" cellspacing=3 cellpadding=0 border=0 bgcolor=#FFFBF5>
                  <tr>
                    <td width="5%" align=left valign=middle>&nbsp;</td>
                    <td width="25%" align=left>&nbsp;</td>
                    <td width="45%" align=right class=black><i><b>Total YTD Earnings</b></i>&nbsp;:</td>
                    <td width="25%" align=right style="padding-right:34px">&nbsp;<b class=doubleline>$26,741.00</b></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          </div>

<!------------------------------ BEGIN STATUS TAB --------------------------------->

          <div id="reports6" name="Status" width="80px" style="padding:15px">

          <table width="645" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%" height="35">
                <table width="100%" align=center border="0" cellspacing="0" cellpadding="0" class="text red">
                  <tr>
                    <td width="55%"><em class="required">&nbsp;September 10, 2009 </em><span class="text red"><i>(10:57 pm MST)</i></span></td>
                    <td width="20%" align=right valign=bottom >&nbsp;</td>
                    <td width="25%" align=right  valign=bottom style="padding-right: 14px;">&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <table width=645 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
            <tr>
              <td bgcolor="#FFFFFF">
                <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
                  <tr>
                    <td class="largetext">

                      Below is your membership status and account information. Make sure to keep it up to date so you don't lose out on any of your membership privileges.

                        <br>&nbsp;<p class="darkred largetext"><b>Account Status</b></p>

                      <blockquote class="text" style="line-height: 20px; margin-top: -5px;">
                        <b>Member Since:</b> March 9, 2009
                        <br><b>Member Level:</b> PRO, since Friday, 09/04/2009, 09:27 AM MST
                        <br><b>Last Purchase Date:</b> Thursday, 08/27/2009, 011:59 PM MST
                        <br><b>Last Purchase Amount:</b> $97
                        <br><b>Next Purchase Date:</b> Sunday, 09/27/2009 MST
                        <br><b>Next Purchase Amount:</b> $47

                        <p><b>Upgrade or downgrade your account <a href="javascript: void(0)">HERE</b></a></p>
                      </blockquote>


                        <br><p class="darkred largetext"><b>Purchase History</b></p>

                          <table width=570 align=left valign=top border=0 cellspacing=0 cellpadding=0 class="text gridb1" style="margin: 0 0 40px 40px;">
                            <tr bgcolor=#F9F9F9>
                              <td width=32%><b>&nbsp; Date</b></td>
                              <td width=18% align=center><b>Amount</b></td>
                              <td width=50%><b>&nbsp; Description</b></td>
                            </tr>

                            <tr>
                              <td>&nbsp; August 27, 2009</td>
                              <td align=right style="padding-right: 28px;">$97.00</td>
                              <td>&nbsp; ELITE membership fee</td>
                            </tr>

                            <tr>
                              <td>&nbsp; August 9, 2009</td>
                              <td align=right style="padding-right: 28px;">$127.00</td>
                              <td>&nbsp; Pushy Network traffic</td>
                            </tr>

                            <tr>
                              <td>&nbsp; July 27, 2009</td>
                              <td align=right style="padding-right: 28px;">$97.00</td>
                              <td>&nbsp; ELITE membership fee</td>
                            </tr>

                            <tr>
                              <td>&nbsp; June 27, 2009</td>
                              <td align=right style="padding-right: 28px;">$97.00</td>
                              <td>&nbsp; ELITE membership fee</td>
                            </tr>

                            <tr>
                              <td>&nbsp; May 27, 2009</td>
                              <td align=right style="padding-right: 28px;">$97.00</td>
                              <td>&nbsp; ELITE membership fee</td>
                            </tr>

                            <tr>
                              <td>&nbsp; April 27, 2009</td>
                              <td align=right style="padding-right: 28px;">$70.48</td>
                              <td>&nbsp; ELITE membership fee (<a href="javascript: voic(0)" title="See proration details" alt="See proration details">prorated</a>)</td>
                            </tr>

                            <tr>
                              <td>&nbsp; April 14, 2009</td>
                              <td align=right style="padding-right: 28px;">$47.00</td>
                              <td>&nbsp; PRO membership fee</td>
                            </tr>

                            <tr>
                              <td>&nbsp; March 14, 2009</td>
                              <td align=right style="padding-right: 28px;">$47.00</td>
                              <td>&nbsp; PRO membership fee</td>
                            </tr>

                          </table>


                        <p class="darkred largetext"><b>Purchase Information</b></p>

                          <table width=60% align=left valign=top cellspacing=5 cellpadding=0 class="text">
                            <tr>
                              <td align=right><b>Cardholder Name:</b>&nbsp;</td>
                              <td align=left><input type=input value="John Doe"></td>
                            </tr>

                            <tr>
                              <td align=right><b>Billing Address:</b>&nbsp;</td>
                              <td align=left><input type=input value="1427 Someplace Ave"></td>
                            </tr>

                            <tr>
                              <td align=right><b>Billing Zip/Postal:</b>&nbsp;</td>
                              <td align=left><input type=input value="60992"></td>
                            </tr>

                            <tr>
                              <td align=right><b>Credit Card Type:</b>&nbsp;</td>
                              <td align=left><input type=input value="VISA"></td>
                            </tr>

                            <tr>
                              <td align=right><b>Credit Card#:</b>&nbsp;</td>
                              <td align=left><input type=input value="xxxx-xxxx-xxxx-1234"></td>
                            </tr>

                            <tr>
                              <td align=right><b>Expiration Date:</b>&nbsp;</td>
                              <td align=left><input type=input value="09-2011"></td>
                            </tr>

                            <tr>
                              <td align=right><b>Security Code:</b>&nbsp;</td>
                              <td align=left><input type=input value="234"></td>
                            </tr>

                            <tr height=40>
                              <td align=right></td>
                              <td align=left><input type=button value="UPDATE"></td>
                            </tr>
                          </table>

                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          </div>

<!------------------------------ BEGIN CALCULATORS TAB ---------------------------->

          <div id="reports7" name="Calculators" width="85px" style="padding:15px">

          <table width="645" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%" height="35">
                <table width="100%" align=center border="0" cellspacing="0" cellpadding="0" class="text red">
                  <tr>
                    <td width="55%"><em class="required">&nbsp;September 10, 2009  </em><span class="text red"><i>(10:57 pm MST)</i></span></td>
                    <td width="20%" align=right valign=bottom >&nbsp;</td>
                    <td width="25%" align=right  valign=bottom style="padding-right: 14px;">&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <table width=645 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
            <tr>
              <td bgcolor="#FFFFFF">
                <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
                  <tr>
                    <td class="text">

                      This tab provides you with several different calculators to determine the size and speed at which your referral network
                      will grow exponentially, based on how many referrals you plan to recruit, and how many each of them plan to recruit.

                      <p><b>SIDE NOTE:</b> Since most people don't comprehend exponential growth without seeing an example, this will help them
                      see for themselves just how powerful exponential growth can be. Using the age-old example question,

                      <blockquote>"Which would you rather have: (a) $1,000,000, or (b) a penny that doubles in amount each day for 30 days?"
                      </blockquote>

                      <p>If you chose (b), you would generate nearly 11 times as much: $10,737,418.24 in 30 days. </p>

                      <p>Likewise, if you recruited just 2 referrals who each recruit 2 referrals, who continue this on a daily process, you
                      would generate an ad exposure of 1,048,576 referrals within your network within 20 days.</p>

                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          </div>

        </div>
      </td>
    </tr>
  </table>
<!---------------------------------------------------------- END PAGE CONTENT ------------------------------------------------------>

</body>
</html>
