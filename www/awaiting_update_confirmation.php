<html>
<title>Pushy Ads Advertising Widget</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

</head>

<body class=background topmargin=0>

<?php include("register_header.php"); ?>

  <!--------------------------------------------- START CONTENT ------------------------------------------------>
  <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
    <tr>
      <td align=center style="padding: 20px 0 0 20px;"><img src="http://pds1106.s3.amazonaws.com/images/next.png"></td>
      <td rowspan=2 align=right valign=top style="padding-top: 90px;"><img src="http://pds1106.s3.amazonaws.com/images/pushyman-sh.png">
      </td>
    </tr>

    <tr>
      <td>

        <table align=left width=625 border=0 cellpadding=0 cellspacing=0>
          <tr>
            <td width=40 height=34 valign=top background="http://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
            <td width=587 height=34 valign=bottom class=boback></td>
          </tr>
          <tr>
            <td width=40 valign=top class=cellleft>&nbsp;</td>
            <td width=587 valign=top>

              <table align=left width=587 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
                <tr>
                  <td valign=top>
                    <div style="margin: -6px 35px 25px 35px ;">
                    <div align=center class=size20><b>Check Your New Email Account</b></div>

                    <p>
                       Pushy just sent you an email to
                       <?php
                          if (isset($_REQUEST["new_email"]) && strlen($_REQUEST["new_email"])>0)
                            {
                              echo "&nbsp;<b>".$_REQUEST["new_email"]."</b>&nbsp;";
                            }
                          else
                            {
                              echo " your new email address ";
                            }
                       ?>
                       with  a confirmation link. You will need to click on that link to confirm
                       your new email address.
                    </p>


                    <p>
                       Once you confirm, you will be able to sign into your PushyAds.com
                       account using your New Email Address.
                    </p>

                    <p>&nbsp;</p>

                    </div>
                  </td>
                </tr>
              </table>

            </td>
          </tr>
          <tr>
            <td width=40  height=38 background="http://pds1106.s3.amazonaws.com/images/shadow-crnr.png"></td>

            <td width=587 height=38>
              <table width=100% border=0 cellpadding=0 cellspacing=0>
                <tr>
                  <td width=547 height=38 valign=top class=cellbottom></td>
                  <td width=40 height=38 valign=top align=right background="http://pds1106.s3.amazonaws.com/images/shadow-rt.png">&nbsp;</td>
                </tr>
              </table>
            </td>

          </tr>
        </table>

      </td>
    </tr>
  </table>


  <!--------------------------------------------- END CONTENT ------------------------------------------------>

<?php include("register_footer.php"); ?>

</body>
</html>
