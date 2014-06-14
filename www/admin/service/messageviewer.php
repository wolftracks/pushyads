<?php
$MEMBER_FOUND=FALSE;

$sql  = "SELECT * FROM member";
$sql .= " WHERE member_id='$member_id'";
$temp_result = exec_query($sql,$db);
if ( ($temp_result) && ($temp_row = mysql_fetch_array($temp_result)) )
  {
    $password  = $temp_row["password"];
    $phone     = $temp_row["home_phone"];
    $email     = $temp_row["email"];
  }
?>
<html>
<head>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/admin/admin.js"></script>
<script LANGUAGE="JavaScript">
<!--
 var sendAction="send";
 function setAction(val)
   {
     if (val == "close")
       {
         window.close();
       }
   }
// -->
</script>
<title>Message Viewer</title>
</head>
<body bgcolor="#E0FFE0"><font face="Arial">
 <table width=650>
   <tr>
      <td width="15%" align="right"><font face="Arial"><small><b>From:&nbsp;&nbsp;</b></small></font></td>
      <td width="85%"><font face="Arial" color="#000099"><small><b><?php echo $from?></b></small></font></td>
   </tr>
   <tr>
      <td width="15%" align="right"><font face="Arial"><small><b>Subject:&nbsp;&nbsp;</b></small></font></td>
      <td width="85%"><font face="Arial" color="#000099"><small><b><?php echo $subject?></b></small></font></td>
   </tr>
   <tr>
      <td width="15%" align="right"><font face="Arial"><small><b>Service ID:&nbsp;&nbsp;</b></small></font></td>
      <td width="85%"><font face="Arial" color="#000099"><small><b><?php echo $service_id?></b></small></font></td>
   </tr>
   <tr>
      <td width="15%" align="right"><font face="Arial"><small><b>Member:&nbsp;&nbsp;</b></small></font></td>
      <td width="85%" align="left">
         <font face="Arial"><small><b>ID:</b></small></font>&nbsp;
         <font face="Arial" color="#000099"><small><b><a href="javascript:openMember('<?php echo $member_id?>')"><?php echo strtoupper($member_id)?></a></b></small></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <font face="Arial"><small><b>PW:</b></small></font>&nbsp;
         <font face="Arial" color="#000099"><small><b><?php echo strtoupper($password)?></b></small></font>
      </td>
   </tr>
 </table>
<br>
<textarea cols="80" name="message" rows="20"><?php echo $message?></textarea>

<p>
<input type="button" name="close" value=" CLOSE " onclick=setAction('close')>
</p></b></b></font>

</body>
</html>
