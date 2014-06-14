<?php
$dlg=0;
if (isset($_REQUEST["dlg"]) && $_REQUEST["dlg"]=="1")
  $dlg=1;
?>
<html>
<title>PushyAds SafeListing</title>

<head>
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

<body class=background>

</head>

<body class=poppage>

<table align=center width="620" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
  <tr><td>
    <table width="90%" align=center>
      <tr>
    <td>

<table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%">
      <table width=620 cellspacing=0 cellpadding=0 style="border: 1px solid #000000;" class=text>
        <tr height=25>
          <td align="left" bgcolor=#F9FA7C valign="middle" height="25" style="background-image: url('http://pds1106.s3.amazonaws.com/images/compare-bar.jpg'); background-repeat: repeat-y; border-bottom: 1px solid #000000; padding-left: 16px;" class=size15>
            <table width=100% border=0 cellspacing=0 cellpadding=0>
              <tr>
                <td width=50% class=white><b>Safelist Pushyads.com</b></td>
                <td width=50% align=right valign=middle><a href="/"><img src="http://pds1106.s3.amazonaws.com/images/favicon.ico"></a> &nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td><img src="http://pds1106.s3.amazonaws.com/images/menu-shadow2.jpg" width="620" height="6"></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF">
        <table width=94% align=center cellspacing=0 cellpadding=0>
          <tr>
            <td class="smalltext">
          <span class=spacer1>&nbsp;<br></span>

              <table align=center width=100% cellspacing=0 cellpadding=0>
                <tr>
                  <td align=left class="text">
                <span class=red><b>How To Add PushyAds.com To Your Safe List</b></span> </td>
                  <td align=right class="text">
            <span align=right class="smalltext"><a href="javascript:closeWindow()">Close Window</a></span> </td>
                    </tr>
              </table>

          <p>If you use Spam Arrest or a similar service, please whitelist <b>noreply at pushyads.com</b>. Replace "at" with the @
          symbol and with no spaces on either side. We had to list it this way because of the way spammers use email harvesters
          to collect email addresses on the web.</p>

          <p>If you do not get our emails, look in the "bulk folder" or "spam folder" of your email program. To prevent this
          from happening in the future, add our email address above to your "buddy list" or "white list" or "safe list".</p>

          <p>Here are instructions for safelisting Pushyads.com, depending on what email program you are using:</p>

          <br>
                  <p><img src="http://pds1106.s3.amazonaws.com/images/yahoomail.gif" width="196" height="33"></p>
                  <p>Here's how to add us to your Yahoo whitelist:</p>

                    <ol><li>Open your Yahoo mailbox</li>
                      <li>Click &quot;Mail Options&quot;</li>
                      <li>Click &quot;Filters&quot;</li>
                      <li>Next, click &quot;Add Filter&quot;</li>
                      <li>In the top row, labeled &quot;From Header:&quot; make sure &quot;contains&quot; is selected.</li>
                      <li>Click in the text box next to that drop-down menu, and enter our email address in the &quot;From&quot; line of our e-mail message (Please select the e-mail address from the list provided)</li>
                      <li>At the bottom, where it says &quot;Move the message to:&quot; select &quot;Inbox&quot; from the menu.</li>
                      <li>Click the &quot;Add Filter&quot; button again.</li>
            </ol>
                  <p>&nbsp;</p>

                  <p><span class="size20"><b>MSN</b> </span>
          <img src="http://pds1106.s3.amazonaws.com/images/msn.gif" alt="msn hotmail" width="48" height="40">
          <span class="size20"><b>Hotmail</b></span></p>
                  <p>To receive our emails with Hotmail, please follow these steps if you're having trouble: </p>

                    <ol><li>Click the &quot;Options&quot; tab</li>
                      <li>Under &quot;Mail Handling&quot; select &quot;Safe List&quot;</li>
                      <li>In the space provided, enter the email address in the "From" line</li>
                      <li>Click &quot;Add&quot;</li>
                      <li>When you see the address you entered in the Safe List box, click &quot;OK.</li>

            </ol>

                <p><img src="http://pds1106.s3.amazonaws.com/images/aol.jpg" width="103" height="104"></p>
                <p>If you're using AOL, here's how to receive our emails:</p>

          <ol><li>Go to &quot;Mail Controls&quot;</li>
                    <li>Select the screen name we're sending your e-mail to</li>
                    <li>Click &quot;Customize Mail Controls For This Screenname.&quot;</li>
                  </ol>
                <p>For AOL version 7.0: In the section for &quot;exclusion and inclusion parameters&quot;, include the following domain:
        pushyads.com, For AOL version 8.0: Select &quot;Allow e-mail from all AOL members, e-mail addresses and domains&rdquo;. Then...</p>

          <ol><li>
                    <li>Click &quot;Next&quot; until the Save button shows up at the bottom</li>
                    <li>Click &quot;Save.&quot;</li>
                  <ol>

                </td>
          </tr>
        </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</body>
</html>
