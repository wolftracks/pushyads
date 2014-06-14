<?php
$db = getPushyDatabaseConnection();

$mid = $in_member_id;
$memberRecord=getMemberInfo($db,$in_member_id);
$confirmed       = $memberRecord["confirmed"];
$registered      = $memberRecord["registered"];
$lastaccess      = $memberRecord["lastaccess"];
$date_registered = $memberRecord["date_registered"];
$date_lastaccess = $memberRecord["date_lastaccess"];
$record_created  = $memberRecord["record_created"];

$user_level=$memberRecord["user_level"];
$ulevel=$UserLevels[$user_level];

$sendmailURL = "index.php?op=MemberMail&PushyAdsMemberId=".$memberRecord['member_id'];


function direct_referrals($db,$member_id)
 {
   $sql  = "SELECT count(*) from member";
   $sql .= " WHERE refid='$member_id'";
   $sql .= " AND registered>0";
   $sql .= " AND system=0";
   $result=mysql_query($sql,$db);
   if ($result && ($myrow=mysql_fetch_array($result)))
     {
       return (int) $myrow[0];
     }
   return 0;
 }


function getWidgetDomain($db,$member_id,$widget_key)
  {
    $sql  = "SELECT domain from widget";
    $sql .= " WHERE member_id='$member_id'";
    $sql .= " AND   widget_key='$widget_key'";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());

    if ($result && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
       return $myrow["domain"];
    return "";
  }


function getWidgetsForUser($db,$member_id)
  {
    // printf("<PRE>\n");
    $widget_count=0;
    $domains=array();
    $widgets=array();
    $sql  = "SELECT widget_key,COUNT(*) from tracker_pushy_widget";
    $sql .= " WHERE member_id='$member_id'";
    $sql .= " GROUP BY widget_key";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());

    if ($result)
      {
        while ($myrow = mysql_fetch_array($result))
          {
            $widget = $myrow[0];
            $count  = $myrow[1];
            if ($count > 0)
              {
                $widgetArray=splitWidgetKey($widget);
                $widget_key    = $widgetArray["WidgetConfigurationKey"];
                $domain = getWidgetDomain($db,$member_id,$widget_key);
                if (strlen($domain)>0)
                  {
                    $widgets[$widget_key] = $domain;
                    if (isset($domains[$domain]))
                       $domains[$domain]++;
                    else
                       $domains[$domain]=1;
                  }
              }
            $widget_count+=$count;
          }
      }

//  $widget_count=0;
//  if (count($domains)>0)
//    {
//      foreach($domains as $dom=>$occur)
//        $widget_count+=$occur;
//    }

    // print_r($widgets);
    // printf("</PRE>\n");
    return array($widget_count, $widgets, $domains);
  }


$referrals = direct_referrals($db,$mid);

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css" />
<script type="text/javascript" src="/local-js/common.js"></script>
<script language="JavaScript">
<!--
 var win=null;
 var mid="<?php echo $in_member_id?>";

 function ValidateForm(theForm)
  {
    return (true);
  }


  function CheckMessageSent()
    {
<?php
    if (isset($MESSAGE_SENT) && ($MESSAGE_SENT))
      {
?>
        alert("  MESSAGE  SENT  ");
<?php
      }
?>
    }


  function prepareTo(functionName)
    {
      if (functionName == "Update")
        {
          document.MEMBER.op.value = "UpdateMember";
        }
      else
      if (functionName == "Delete")
        {
          if (confirm("Are You Sure you Want to DELETE this Member?"))
            {
              document.MEMBER.op.value = "DeleteMember";
            }
        }
      else
      if (functionName == "ListCampaigns")
        {
          document.MEMBER.op.value = "ListCampaignsForMember";
        }
    }


  function signin(member_id)
    {
      var windowName=getUniqueWindowName("Signin");
      var leftmargin = rand(0,10)*4;
      var topmargin  = rand(0,10)*4;

      var url="member_signin.php?member_id="+member_id;
      win=window.open(url,windowName,
         'width=760,height=760,top='+topmargin+',left='+leftmargin+
         ',scrollbars=yes,location=yes,directories=no,status=no,menubar=yes,toolbar=yes,resizable=yes');
      win.focus();
    }


  function sendmail()
    {
      document.location.href="<?php echo $sendmailURL?>";
    }

  function goTo(functionName)
    {
      document.location.href="index.php?op="+functionName;
    }

 function numDigits(aString)
   {
     var count=0;
     for (i=0; i<aString.length; i++)
       {
         if (aString.charAt(i) >= '0' && aString.charAt(i) <= '9')
           count++;
       }
     return(count);
   }

 function stripl(aString)
   {
     var newString = "";
     for (i=0; i<aString.length; i++)
       {
         if (aString.charAt(i) != ' ')
           {
             for (j=i; j<aString.length; j++)
               newString=newString+aString.charAt(j);
             return(newString);
           }
       }
     return(newString);
   }

 function stript(aString)
   {
     var newString = "";
     var len = aString.length;
     for (i=aString.length-1; i>=0; i--)
       {
         if (aString.charAt(i) == ' ')
           len--;
         else
           {
             for (j=0; j<len; j++)
               newString=newString+aString.charAt(j);
             return(newString);
           }
       }
     return(newString);
   }

 function striplt(aString)
   {
     var newString;
     newString = stripl(aString);
     newString = stript(newString);
     return(newString);
   }

 function stripa(aString)
   {
     var newString = "";
     for (i=0; i<aString.length; i++)
       {
         if (aString.charAt(i) != ' ')
           {
             newString=newString+aString.charAt(i);
           }
       }
     return(newString);
   }

 function getDigits(aString)
   {
     var newString = "";
     for (i=0; i<aString.length; i++)
       {
         if (aString.charAt(i) >= '0' && aString.charAt(i) <= '9')
           {
             newString=newString+aString.charAt(i);
           }
       }
     return(newString);
   }

 function stripSpecial(aString)
   {
     var omit="~`!@#$%^&*()-_+={}[]|\:;'<>,./?0123456789";
     var newString = "";
     for (i=0; i<aString.length; i++)
       {
         ch = aString.charAt(i);
         if (omit.indexOf(ch) >= 0)
           { }
         else
           {
             newString=newString+ch;
           }
       }
     return(newString);
   }


  function getRadioChecked(theForm,eName)
    {
      for (var i = 0; i < theForm.elements.length; i++)
        {
          if (theForm.elements[i].type == "radio")
            {
              rbox=theForm.elements[i];
              if (rbox.name==eName && rbox.checked)
                return rbox;
            }
        }
      return null;
    }


 function startsWith(src, str)
   {
     if (str.length > src.length)
       return false;
     if (str == src)
       return true;
     if (src.substring(0,str.length) == str)
       return true;
     return false;
   }

function init()
  {
    CheckMessageSent();
  }


function openAffiliatePage(url)
  {
    var leftmargin = 0;
    var topmargin  = 0;
    win=window.open(url,"AffiliateLandingPage",
       'width=760,height=760,top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=yes,directories=no,status=no,menubar=yes,toolbar=yes,resizable=yes');
    win.focus();
  }

// -->
</script>
<title>Member</title>
</head>

<body onLoad=init()>

<div align="left">
<table border="0" cellpadding="3" cellspacing="0" width="760">
  <tr>
    <td width="30%" align="center" class="normaldarkbluebold">Member</td>
    <td width="40%"><p align="center"><font face="Arial" color="#FF0000"><big><strong><big><strong>
    PushyAds</strong></big></strong></big></font><br>
    <font face="Arial" color="#0000A0"><strong>
    PushyAds Administration</strong></font></td>

     <td width="30%" align="center">
       <?php
          $disabled="disabled";
          if ($registered>0 && $lastaccess>0)
            $disabled="";
       ?>
       <input type="button" <?php echo $disabled?> class="button" value="SIGN IN" onClick=signin('<?php echo $mid?>')>
           &nbsp;&nbsp;&nbsp;
       <input type="button" value="SEND MAIL" class="button" onClick=sendmail()>
           &nbsp;&nbsp;&nbsp;
       <input type="button" value=" Raw " class="button" onClick=window.location.href="raw.php?member_id=<?php echo $mid?>">
     </td>
  </tr>
</table>
</div>
<br>
<form method="POST" name="MEMBER" action="index.php" onsubmit="return ValidateForm(this)">
  <input type="hidden" name="op" value="">
  <input type="hidden" name="in_member_id" value="<?php echo $mid?>">
  <input type="hidden" name="member_id" value="<?php echo $mid?>">

  <div align="center"><center>
  <table border="0" cellpadding="3" cellspacing="0" width="90%">
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Member ID:&nbsp; </font></strong></td>
      <td width="75%">
        <table cellpadding="0" cellspacing="0" width="100%">
           <tr>
              <td width="40%" align="left"><?php echo $mid?></td>
              <td width="60%" align="left"><font face="Arial"><?php echo $RemovedStatus?></font></td>
           </tr>
        </table>
      </td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Password:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["password"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">First Name:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["firstname"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Last Name:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["lastname"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Phone:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["phone"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Email:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["email"]?></td>
      <td width="5%"></td>
    </tr>

    <tr height="6"><td colspan="3">&nbsp;</td></tr>

    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Address 1:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["address1"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Address 2:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["address2"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">City:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["city"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">State:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["state"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Country:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["country"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Zip:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["zip"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Paypal_Email:&nbsp;</font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["paypal_email"]?></td>
      <td width="5%"></td>
    </tr>

    <tr height="6"><td colspan="3">&nbsp;</td></tr>

    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Affiliate ID:&nbsp;
      </font></strong></td>
      <td width="75%" class=text><?php echo $memberRecord["affiliate_id"]?></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Affiliate URL:&nbsp;
      </font></strong></td>
      <td width="75%" class=text>http://pushyads.com/<?php echo $memberRecord["affiliate_id"]?><br>
        <a href=javascript:openAffiliatePage('/<?php echo $memberRecord["affiliate_id"]?>')>http://pushyads.com/<?php echo $memberRecord["affiliate_id"]?></a>
      </td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">User Level:&nbsp;
      </font></strong></td>
      <td width="75%"><font face="Arial" color="#CC0000"><b><?php echo $ulevel?></b></font></td>
      <td width="5%"></td>
    </tr>

    <tr height="6"><td colspan="3">&nbsp;</td></tr>

    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Creation Date:&nbsp;
      </font></strong></td>
      <td width="75%"><font face="Arial" color="#0000CC"><b><?php echo $record_created?></b></font></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Date Confirmed:&nbsp;
      </font></strong></td>
      <td width="75%"><font face="Arial" color="#0000CC"><b><?php echo (($confirmed>0)?formatDate($confirmed)."&nbsp; &nbsp;".formatTime($confirmed):"- not confirmed -")?></b></font></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Date Registered:&nbsp;
      </font></strong></td>
      <td width="75%"><font face="Arial" color="#0000CC"><b><?php echo (($registered>0)?formatDate($registered)."&nbsp; &nbsp;".formatTime($registered):"- not registered -")?></b></font></td>
      <td width="5%"></td>
    </tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Last Access:&nbsp;
      </font></strong></td>
      <td width="75%"><font face="Arial" color="#0000CC"><b><?php echo (($lastaccess>0)?formatDate($lastaccess)."&nbsp; &nbsp;".formatTime($lastaccess):"- not accessed -")?></b></font></td>
      <td width="5%"></td>
    </tr>

    <tr><td>&nbsp;</td></tr>
    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Widgets:&nbsp;</font></strong></td>
<?php
      list($wcount, $widgets, $domains) = getWidgetsForUser($db,$mid);
      if ($wcount>0)
        {
          echo "<td width=\"75%\">";
           foreach($domains AS $dom=>$occur)
             {
               echo "<a href=javascript:openPopup('http://$dom',600,600,true,true)>http://$dom</a> &nbsp; &nbsp;($occur)&nbsp;<br>\n";
             }
          echo "</td>\n";
        }
      else
        {
           echo "<td width=\"75%\" class=\"smalldarkredbold\">-- none --</td>\n";
        }
?>
      <td width="5%"></td>
    </tr>
    <tr><td>&nbsp;</td></tr>

    <tr>
      <td width="20%" align="left"><div align="left"><strong><font face="Arial">Direct Referrals:&nbsp;
      </font></strong></td>
      <td width="75%"><font face="Arial" color="#0000CC"><b><?php echo $referrals?></b></font></td>
      <td width="5%"></td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr valign=middle>
      <td valign=middle width="20%" align="left"><div align="left"><strong><font face="Arial">Referred By:&nbsp;</font></strong></td>
      <?php
        $refid = $memberRecord["refid"];
        if (strlen($refid) > 0 && is_array($referer = getMemberInfo($db, $refid)))
          {
            $ref_member_id    = $referer["member_id"];
            $ref_affiliate_id = $referer["affiliate_id"];
            $refname   = stripslashes($referer["firstname"])." ".stripslashes($referer["lastname"]);
            $referral  = "&nbsp;<br>";
            $referral .= "<span class=\"smallbold\">Referer Name:&nbsp;&nbsp;&nbsp;</span>$refname<br>";
            $referral .= "<span class=\"smallbold\">Referer ID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>$ref_member_id<br>";
            $referral .= "<span class=\"smallbold\">Referer Aff ID:&nbsp;&nbsp;&nbsp;&nbsp;</span>$ref_affiliate_id<br>";
          }
        else
          $referral = "None";
      ?>
      <td width="75%" class="smalldarkredbold"><?php echo $referral?></td>
      <td width="5%"></td>
    </tr>

  </table>
  </center></div>
  <font face="Arial" color="CC0000"><b><br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $StatusMessage?></b></font>
  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <!-- input type="submit" class="button" value=" Update " name="Update" onClick='prepareTo("Update")'> &nbsp;&nbsp; -->

    <?php
       $disabled="disabled";
       if ($referrals == 0)
         $disabled="";
    ?>
    <input type="submit" <?php echo $disabled?> class="button" value=" Delete " name="Delete" onClick='prepareTo("Delete")'> &nbsp;&nbsp;
    <input type="button" class="button" value=" Who IS " name="Whois"  onClick="javascript:document.location='whois.php'"> &nbsp;&nbsp;
    <input type="button" class="button" value=" Members " name="MemberList" onClick="goTo('ListMembers')"> &nbsp;&nbsp;
    <input type="button" class="button" value="  Back  " name="Back" onClick="javascript:history.back()">
</form>
</body>
</html>
