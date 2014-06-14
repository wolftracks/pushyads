<?php include("initialize.php"); ?>
<?php
  $db=getPushyDatabaseConnection();
  $removed=FALSE;
  $email="";
  if (isset($_REQUEST["mid"]) && strlen($_REQUEST["mid"])>0 && (is_array($memberRecord = getMemberInfo($db,$_REQUEST["mid"]))))
    {
      $mid       = $memberRecord["member_id"];
      $firstname = stripslashes($memberRecord["firstname"]);
      $email     = strtolower($memberRecord["email"]);

      $sql  = "DELETE FROM member ";
      $sql .= " WHERE (confirmed=0 OR registered=0) AND member_id='$mid'";
      $result=mysql_query($sql,$db);
      if ($result && mysql_affected_rows()>0)
        $removed=TRUE;
    }
?>
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
      <td>

        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>

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
                    <div style="margin: 0px 35px 25px 35px ;">

                    <p>
                       <?php
                          if ($removed)
                            {
                       ?>
                              <div align=center class=size20><b>You have been unsubscribed, <?php echo $firstname?></b></div>
                              <p> Your email address, <b><?php echo $email?></b>,  has been removed and you will no longer receive
                              notices regarding your recent attempt to register on the Pushyads.com website. </p>

                              <p> Should you wish to sign up on the PushyAds.com is the future, you may do so by submitting the
                              sign up form at <a href="http://pushyads.com">http://pushyads.com</a></p>
                       <?php
                            }
                          else
                            {
                       ?>
                              <div align=center class=size20><b>Subscription Not Found</b></div>
                              <p> This subscription was not found on our system. The subscription has either been previously removed
                                  or has expired.
                              </p>
                       <?php
                            }
                       ?>
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

      <td align=right valign=top style="padding-top: 90px;"><img src="http://pds1106.s3.amazonaws.com/images/pushyman-sh.png">

    </tr>
  </table>


  <!--------------------------------------------- END CONTENT ------------------------------------------------>

<?php include("register_footer.php"); ?>

</body>
</html>
