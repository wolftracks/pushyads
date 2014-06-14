<html>
<title>Pushy Ads Advertising Widget</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="https://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<style>
.failnotice
  {
    font-family: Tahoma,Verdana,Arial;
    font-size:   14px;
  }
</style>

</head>
<body class=secure_background topmargin=0>

<table class=failnotice align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td valign=top>
      <DIV id="ORDERCONTENT">
      <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td align=center style="padding: 20px 0 0 0;"><img src="https://pds1106.s3.amazonaws.com/images/<?php echo $banner_image?>"></td>
          <td rowspan=2 align=right valign=top style="padding-top: 90px;"><img src="https://pds1106.s3.amazonaws.com/images/pushyman-sh.png">
          </td>
        </tr>
        <tr>
          <td>
          <table align=left width=625 border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td width=40 height=34 valign=top background="https://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
              <td width=587 height=34 valign=bottom class=secure_boback></td>
            </tr>
            <tr>
              <td width=40 valign=top class=secure_cellleft>&nbsp;</td>
              <td width=587 valign=top>

              <!----------------------------------------- START CONTENT ------------------------------------->
              <table align=left width=587 height=500 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
                <tr>
                  <td valign=top>
                    <div style="margin: -3px 35px 25px 35px ;">

                    <?php
                     if (strlen($mid)>0 && !is_array($memberRecord))
                         $memberRecord=getMemberInfo($db,$mid);

                     if (is_array($memberRecord))
                       {
                         $countdown = 0;
                         if ($memberRecord["confirmed"]>0)  // It will be
                           {
                             $elapsed = time() - $memberRecord["confirmed"];
                             if ($elapsed > 0 && $elapsed<=3600)
                                $countdown=3600-$elapsed;
                           }
                         if ($countdown > 0)
                           $redirect_url=DOMAIN."/login.php?mid=$mid&order_failed=true";
                         else
                           $redirect_url=DOMAIN."/login2.php?mid=$mid&order_failed=true";
                       }

                     if (strlen($mid) > 0 && strlen($sid) > 0)
                       {
                         list($rc, $isAdminSession) = getSession($db, $sid, $mid, FALSE);
                         if ($rc==0)
                           {
                             if (is_array($memberRecord) && strcasecmp($mid,$memberRecord["member_id"])==0)
                               {
                                 $redirect_url = DOMAIN."/members/membersite.php?mid=$mid&sid=$sid";  // Member site
                               }
                           }
                       }

                     if ($x_response_code == 12)
                       {
                         echo "<font color=\"#AA0000\">\n";
                         echo "<center><b class=size22>\n";
                         echo "Oops!\n";
                         echo "<p></b></center>\n";
                         echo "</font>\n";

                         echo "<font color=\"#293F69\">\n";
                         echo "<b>\n";
                         echo "Your payment has been declined by our merchant account system.<br>\n";
                         echo "</b><br>&nbsp;<br>\n";
                         echo "</font>\n";

                         echo "<font color=\"#AA0000\">\n";
                         echo "<center><b>\n";
                         echo "  $x_response_reason_text<br>\n";
                         echo "<br></b></center>\n";
                         echo "</font>\n";

                         echo "We ask that you contact your Credit Card Account Service Department to determine the cause.<br>&nbsp;<br>\n";

                         echo "If you feel that this charge was incorrectly declined by your bank card service, please contact our ";
                         echo "administration team and we will be happy to assist you.<br><br>\n";

                         echo "<a href=javascript:top.location.href='$redirect_url'><b>Click Here</b></a>&nbsp; to ";
                         echo "return to the home page.";
                       }
                     else
                       //   if ($x_response_code == 23 || $x_response_code == 24)
                       {
                         echo "<font color=\"#AA0000\">\n";
                         echo "<center><b class=size22>\n";
                         echo "Oops!\n";
                         echo "<p></b></center>\n";
                         echo "</font>\n";

                         echo "<font color=\"#293F69\">\n";
                         echo "<b>\n";
                         echo "Our merchant account system has rejected your transaction\n";
                         echo "for the following reason:";
                         echo "</b><br>&nbsp;<br>\n";
                         echo "</font>\n";

                         echo "<font color=\"#AA0000\">\n";
                         echo "<center><b>\n";
                         echo "  $x_response_reason_text<br>\n";
                         echo "<br></b></center>\n";
                         echo "</font>\n";

                         echo "If you feel this error was caused by incorrect information entered on the payment form,\n";
                         echo "simply click the &nbsp;<a href=javascript:window.history.go(-1)><b>Back</b></a>&nbsp; button on your browser to correct\n";
                         echo "the information and re-submit your order.<br><br>\n";

                         echo "Please do not try re-ordering with the same credit card if you feel that the information you have entered\n";
                         echo "is correct. Instead,&nbsp; <a href=javascript:top.location.href='$redirect_url'><b>Click Here</b></a>&nbsp; to return\n";
                         echo "to the signin page. Feel free to contact our administration team if we can assist you in getting the issue resolved.<br>\n";
                       }
                    ?>
                    </div>
                  </td>
                </tr>
              </table>
              <!-------------------------------------- END CONTENT --------------------------------------->

            </td>
          </tr>
          <tr>
            <td width=40  height=38 background="https://pds1106.s3.amazonaws.com/images/shadow-crnr.png"></td>
            <td width=587 height=38>
              <table width=100% border=0 cellpadding=0 cellspacing=0>
                <tr>
                  <td width=547 height=38 valign=top class=secure_cellbottom></td>
                  <td width=40 height=38 valign=top align=right background="https://pds1106.s3.amazonaws.com/images/shadow-rt.png">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </DIV>

      </td>
    </tr>
  </table>

</body>
</html>
