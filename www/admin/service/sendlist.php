<?php
$sql  = "SELECT * FROM member";
$sql .= " WHERE member_id='$member_id'";
$temp_result = exec_query($sql,$db);
if ( ($temp_result) && ($temp_row = mysql_fetch_array($temp_result)) )
  {
    $firstname = stripslashes($temp_row["firstname"]);
    $lastname  = stripslashes($temp_row["lastname"]);
    $password  = $temp_row["password"];
    $phone     = $temp_row["phone"];
    $email     = $temp_row["email"];
  }

// printf("SQL:%s<br>\n",$sql);
// printf("ERR:%s<br>\n",mysql_error());

?>
<html>
<head>
<head>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/admin/admin.js"></script>
<script LANGUAGE="JavaScript">
<!--
 function setAction(val)
   {
     if (val == "cancel")
       {
         // window.close();
         history.back();
       }
   }

 function ValidateForm(theForm)
  {
    theForm.COPYLIST.value = "";
    for (var i=0; i<theForm.BCC.length; i++)
      {
        if (theForm.BCC[i].selected)
          {
            theForm.COPYLIST.value += theForm.BCC[i].value+"/";
          }
      }

<?php
    if ($op == "SendMessage")
      {
?>
        theForm.in_subject.value = striplt(theForm.in_subject.value);
        if (theForm.in_subject.value.length == 0)
          {
            alert("Please enter a Subject for this message");
            theForm.in_subject.focus();
            return (false);
          }
<?php
      }
?>
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
// -->
</script>
<title>SENDLIST</title>
</head>
<body bgcolor="lightblue"><font face="Arial">
<FORM name="SENDMAIL" method="POST" action="index.php" onsubmit="return ValidateForm(this)">
<input type="hidden" name="op" value="SendMail">
<input type="hidden" name="service_id" value="<?php echo $service_id?>">
<input type="hidden" name="seq" value="<?php echo $seq?>">
<input type="hidden" name="WB"  value="<?php echo $WB?>">
<input type="hidden" name="WE"  value="<?php echo $WE?>">
<input type="hidden" name="searchterm"  value="<?php echo $searchterm?>">
<input type="hidden" name="searchvalue" value="<?php echo $searchvalue?>">
<input type="hidden" name="source"   value="<?php echo $op?>">
<input type="hidden" name="COPYLIST" value="">
<table align=left width="700">
   <tr>
      <td width="10%"><font face="Arial"><small><b>From:</b></small></font></td>
      <td width="65%"><font face="Arial" color="#000099"><small><b><?php echo $from?></b></small></font></td>
      <td width="5%" align="right">
        <font face="Arial" color="#CC0000"><small><b>BCC:</b>&nbsp;</small></font>
      </td>
      <td width="20%" rowspan="4" align="center" valign="top">
<?php
          $numUsers=count($users);
          ksort($users);
          reset($users);
          echo "<select name=\"BCC\" size=\"$numUsers\" multiple>";
          while (list($user, $user_email) = each($users))
            {
              echo "<option value=\"$user\">$user</option>\n";
            }
          echo "</select>\n";
?>
      </td>
   </tr>
   <tr>
      <td width="10%"><font face="Arial"><small><b>To:</b></small></font></td>
      <td width="65%" align="left">  <font face="Arial" color="#000099"><small><b><?php echo $to?></b></small></font>&nbsp;&nbsp;<input type="text" name="email" size=35 value="<?php echo $email?>"></td>
      <td width="5%">&nbsp;</td>
   </tr>
   <tr height=28 valign=middle>
      <td width="75%" align="left" colspan="2">
         <font face="Arial"><small><b>Signin ID:</b></small></font>&nbsp;&nbsp;&nbsp;&nbsp;
         <font face="Arial" color="#000099"><small><?php echo $signin_id?></small></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <font face="Arial"><small><b>MemberID:</b></small></font>&nbsp;&nbsp;
         <font face="Arial" color="#000099"><small><a href="javascript:openMember('<?php echo $member_id?>')"><?php echo $member_id?></a></small></font>&nbsp;&nbsp;&nbsp;
      </td>
      <td width="5%">&nbsp;</td>
   </tr>


   <?php
     if ($op == "SendList")
       {
   ?>
         <tr height=28 valign=middle>
            <td width="10%"><font face="Arial"><small><b>Subject:</b></small></font></td>
            <td width="65%"><font face="Arial" color="#000099"><small><b><?php echo $subject?></b></small></font></td>
            <td width="5%">&nbsp;</td>
         </tr>
   <?php
       }
     else
       {
   ?>
         <tr height=28 valign=middle>
            <td width="10%"><font face="Arial"><small><b>Subject:</b></small></font></td>
            <td width="65%"><input name="in_subject" type="text" size="40" value="<?php echo $subject?>"></td>
            <td width="5%">&nbsp;</td>
         </tr>
   <?php
       }
   ?>

   <tr height="4"><td colspan="3">&nbsp;</td></tr>

   <tr valign="bottom">
      <td width="10%"><font face="Courier"><small>Response:</small></font></td>
      <td width="65%" align="left">
        &nbsp;&nbsp;&nbsp;
        <input type="submit" name="send"   value="    SEND    " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;">
        &nbsp;&nbsp;
        <input type="button" name="cancel" value="  CANCEL  " onclick=setAction('cancel') STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;">
      </td>
      <td width="5%">&nbsp;</td>
   </tr>
   <tr valign="bottom">
      <td width="100%" colspan="3"><font face="Courier"><br><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8</small></font><font face="Courier" color="#CC0000"><small><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0</b></small></font></td>
   </tr>
   <tr valign="bottom">
      <td width="100%" colspan="3"><font face="Courier"><small>12345678901234567890123456789012345678901234567890123456789012345678901234567890</small></font><font face="Courier" color="#CC0000"><small><b>12345678901234567890</b></small></font></td>
   </tr>
   <tr valign="bottom">
      <td width="100%" colspan="3">
        <textarea cols="100" name="message" rows="12"><?php echo $message?></textarea><br>
      </td>
   </tr>

<?php
   if ($op != "SendMessage")
     {
?>
       <tr valign="bottom">
          <td width="100%" colspan="3">
             <font face="Courier" color="#CC0000"><small><b>Customer Request - Posted <?php echo $datetimePosted?></b>&nbsp;&nbsp;(Will be appended to your response)</small></font>
             <textarea cols="80" name="displayonly" rows="10"><?php echo $request?></textarea>
          </td>
       </tr>
<?php
     }
?>
</table>

</form>
</p></b></b></font>

</body>
</html>
