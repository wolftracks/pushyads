<html>
<title>Pushy Ads Advertising Widget</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="https://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<style>
.successnotice
  {
    font-family: Tahoma,Verdana,Arial;
    font-size:   14px;
  }
</style>

</head>
<body class=secure_background topmargin=0>

<table class=successnotice align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
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
            <tr valign=top>
              <td width=40 valign=top class=secure_cellleft>&nbsp;</td>
              <td width=587>

              <!----------------------------------------- START CONTENT ------------------------------------->
              <table align=left width=587 height=500 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0 class=largetext>
                <tr valign=top>
                  <td>

                     <div style="margin: -3px 35px 25px 35px;">
                       <span class="size18 bold darkred"><?php echo $firstname?>, Our records indicate that your order had been
                         previously submitted for the <b><?php echo $UserLevels[$orderLevel]?></b> level membership and has not
                         been submitted a second time.

                         <p>
                           This order attempt did not result in a charge to your credit card.
                         </p>
                       </span>

                       <p>The next step for you <?php echo $firstname?> is to Click on the button below to grab all the
                       goodies you get with your <?php echo $user_level_name?> membership. Ready? Oh, and don't forget to
                       <b>Get</b> <img src="https://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px">&#8482</p>

                       <?php $redirect_url = DOMAIN."/members/membersite.php?mid=$mid&sid=$sid&_tab_=mystuff"; ?>
                       <p align=center><a href=javascript:top.location.href='<?php echo $redirect_url?>'><img src="https://pds1106.s3.amazonaws.com/images/grab-goodies.png"></a></p>

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
