<?php

   $PushyAdsMemberId=$_REQUEST["PushyAdsMemberId"];

   $sql  = "SELECT * from member";
   $sql .= " WHERE member_id='$PushyAdsMemberId'";
   $memresult=exec_query($sql,$db);
//   printf("SQL: %s<br>\n",$sql);
//   printf("ERR: %s<br>\n",mysql_error());
   if ($memresult)
     {
       $memrow = mysql_fetch_array($memresult);
       $member_id   = $memrow["member_id"];
       $firstname   = stripslashes($memrow["firstname"]);
       $lastname    = stripslashes($memrow["lastname"]);
       $membername  = stripslashes($memrow["firstname"])." ".stripslashes($memrow["lastname"]);
       $memberemail = $memrow["email"];
       $email       = $memrow["email"];
     }

   include("users.php");

   $db = getPushyDatabaseConnection();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css" />
<script language="JavaScript">
<!--
 function VerifyRequest(theForm)
   {
     theForm.in_subject.value   = striplt(theForm.in_subject.value);
     theForm.in_subject.value   = stripSubject(theForm.in_subject.value);
     theForm.in_message.value   = striplt(theForm.in_message.value);

  // alert("SUBJECT: "+theForm.in_subject.value);
  // alert("MESSAGE: "+theForm.in_message.value);
  // alert("SUBJECT LENGTH: "+theForm.in_subject.value.length);
  // return false;

     if (theForm.in_subject.value.length==0)
       {
         alert("Please provide a descriptive Subject");
         theForm.in_subject.focus();
         return (false);
       }

     if (theForm.in_message.value.length < 5)
       {
         alert("Please enter the message you would like to submit.");
         theForm.in_message.focus();
         return (false);
       }

     theForm.COPYLIST.value = "";
     for (var i=0; i<theForm.BCC.length; i++)
       {
         if (theForm.BCC[i].selected)
           {
             theForm.COPYLIST.value += theForm.BCC[i].value+"/";
           }
       }

     return (true);
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

 function stripSpecial(aString)
   {
     var newString = "";
     for (i=0; i<aString.length; i++)
       {
         if (aString.charAt(i) != "'")
           {
             newString=newString+aString.charAt(i);
           }
       }
     return(newString);
   }


 function stripSubject(aString)
   {
     var newString = "";
     for (i=0; i<aString.length; i++)
       {
         if (aString.charAt(i) != "'" && aString.charAt(i) != '"')
           {
             newString=newString+aString.charAt(i);
           }
       }
     return(newString);
   }
// -->
</script>
<TITLE>Customer Service</TITLE>
</HEAD>

<BODY TEXT="#000000" BGCOLOR="#D0D0E0" LINK="#3333FF" VLINK="#008FD5" ALINK="#FF6666">
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 COLS=1 WIDTH="97%">
<TR>
<TD class="text12">
  <form name="REPORT" method="POST" action="index.php" onSubmit='return VerifyRequest(this)'>
   <input type="hidden" name="op" value="SendMemberEmail">
   <input type="hidden" name="PushyAdsMemberId"  value="<?php echo $member_id?>">
   <input type="hidden" name="firstname" value="<?php echo $firstname?>">
   <input type="hidden" name="lastname"  value="<?php echo $lastname?>">
   <input type="hidden" name="email"     value="<?php echo $email?>">
   <input type="hidden" name="COPYLIST" value="">
     <table width="700">
       <tr>
         <td width="10%><font face="Arial">&nbsp;</td>
         <td width="55% valign="top"><font face="Arial">
          <table width="100%" cellpadding=0 cellspacing=0 border=0>
            <tr><td valign="top" align="center" colspan="2" class="text12"><b>( Variables )</b></td></tr>
            <tr valign=top>
               <td valign=top align="left" width="50%" class="text12">
                   %firstname%          <br>
                   %lastname%           <br>
                   %uid%                <br>
                   %password%"          <br>
                                        <br>
               </td>
               <td valign=top align="right" width="50%" class="text12">
                   %email%              <br>
                   %affiliate_id%       <br>
                   %affiliate_website%  <br>
               </td>
            </tr>
          </table>
         </td>
         <td width="35%><font face="Arial">&nbsp;</td>
       </tr>
       <tr valign="top">
         <td width="10%"><font face="Arial"><b>From:</b></font></td>
         <td width="55%"><font face="Arial">
            <span style="color:#000099;"><?php echo EMAIL_NOREPLY?></span><br>
            <span style="color:#990000;">Message Will be Tracked in Customer Service</span>
         </font></td>
         <td width="5%" align="right">
            <font face="Arial" color="#990000"><b>BCC:</b>&nbsp;</font>
         </td>
         <td width="30%" rowspan="4" align="center" valign="top">
<?php
            $numUsers=count($users);
            ksort($users);
            reset($users);
            echo "<select name=\"BCC\" size=\"$numUsers\" multiple>";
            while (list($user, $email) = each($users))
              {
                echo "<option value=\"$user\">$user</option>\n";
              }
            echo "</select>\n";
?>
        </td>
      </tr>
    </table>
    <div align="left">
    <table border="0" cellpadding="1" cellspacing="0" width="720">
      <tr valign="top">
        <td width="10%" align="left"><strong><font face="Arial">To Name:&nbsp;</font></strong></td>
        <td width="70%" align="left"><font face="Arial"><?php echo $membername?></font></td>
        <td width="20%" align="left">&nbsp;</small></td>
      </tr>
      <tr valign="top" height="30">
        <td width="10%" align="left"><strong><font face="Arial">To Email:&nbsp;</font></strong></td>
        <td width="70%" align="left"><font face="Arial"><?php echo $memberemail?></font></td>
        <td width="20%" align="left">&nbsp;</small></td>
      </tr>
      <tr>
        <td width="10%" align="left"><strong><font face="Arial">Subject:&nbsp;</font></strong></td>
        <td width="70%" align="left"><input type="text" name="in_subject" size="60" maxlength="60" value="<?php echo $subject?>"></td>
        <td width="20%" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td width="100%" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td width="10%" valign="top" align="left"><strong><font face="Arial">Message:&nbsp;&nbsp;</font></strong></td>
        <td width="70%" align="left">
          <textarea cols="70" name="in_message" rows="20"></textarea>
        </td>
        <td width="20%" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td width="100%" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td width="80%" align="center" colspan="2">
          <input type="submit" value=" Submit ">
          &nbsp;&nbsp;
          <input type="button" value="Cancel" onClick=javascript:history.back()>
        </td>
        <td width="20%" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td width="100%" colspan="3">&nbsp;</td>
      </tr>
    </table>
    </div>
  </form>
  </div>
</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

</body>
</html>
