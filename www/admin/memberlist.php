<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css" />
</script>

<title>PushyAds Administration - Members</title>
</head>

<body>
<div align="center"><center>

<table border="0" cellpadding="4" cellspacing="0" width="90%">
  <tr>
    <td width="20%" class="normaldarkbluebold">Members</td>
    <td width="60%"><p align="center"><font face="Arial" color="#FF0000"><big><strong><big><strong>
    PushyAds</strong></big></strong></big></font><br>
    <font face="Arial" color="#0000A0"><strong>
    PushyAds Administration</strong></font></td>
    <td width="20%"></td>
  </tr>
</table>
</center></div>


<?php
 $sql  = "SELECT COUNT(*) FROM member";
 $result = exec_query($sql,$db);
 $totalMembers=0;
 if (($result) && ($myrow=mysql_fetch_array($result)))
   {
     $totalMembers = $myrow[0];
     mysql_free_result($result);
   }
?>

<p>
<table width=200 cellpadding=0 cellspacing=0 border=0>
  <tr>
      <td width="150" class=normaldarkbluebold>TOTAL MEMBERS:</td>
      <td align=right width="50"  class=normalredbold><?php echo $totalMembers?></td>
  </tr>
</table>

<div align="center"><center>
<form method="POST" action="index.php">
  <input type="hidden" name="op" value="ListMembers">
  <div align="left">
  <table border="0" cellspacing="1" width="98%">
    <tr>
      <td width="60%"><div align="left"><table border="1" cellspacing="0" width="50%" bordercolor="#F2F2F2" cellpadding="0">
        <tr>
          <td width="4%" align="center"><input type="submit" class=button value="A " name="A"></td>
          <td width="4%" align="center"><input type="submit" class=button value="B " name="B"></td>
          <td width="4%" align="center"><input type="submit" class=button value="C " name="C"></td>
          <td width="4%" align="center"><input type="submit" class=button value="D " name="D"></td>
          <td width="4%" align="center"><input type="submit" class=button value="E " name="E"></td>
          <td width="4%" align="center"><input type="submit" class=button value="F " name="F"></td>
          <td width="4%" align="center"><input type="submit" class=button value="G " name="G"></td>
          <td width="4%" align="center"><input type="submit" class=button value="H " name="H"></td>
          <td width="4%" align="center"><input type="submit" class=button value=" I " name="I"></td>
          <td width="4%" align="center"><input type="submit" class=button value="J " name="J"></td>
          <td width="4%" align="center"><input type="submit" class=button value="K " name="K"></td>
          <td width="4%" align="center"><input type="submit" class=button value="L " name="L"></td>
          <td width="4%" align="center"><input type="submit" class=button value="M " name="M"></td>
          <td width="4%" align="center"><input type="submit" class=button value="N " name="N"></td>
          <td width="4%" align="center"><input type="submit" class=button value="O " name="O"></td>
          <td width="4%" align="center"><input type="submit" class=button value="P " name="P"></td>
          <td width="4%" align="center"><input type="submit" class=button value="Q " name="Q"></td>
          <td width="4%" align="center"><input type="submit" class=button value="R " name="R"></td>
          <td width="4%" align="center"><input type="submit" class=button value="S " name="S"></td>
          <td width="4%" align="center"><input type="submit" class=button value="T " name="T"></td>
          <td width="4%" align="center"><input type="submit" class=button value="U " name="U"></td>
          <td width="4%" align="center"><input type="submit" class=button value="V " name="V"></td>
          <td width="4%" align="center"><input type="submit" class=button value="W " name="W"></td>
          <td width="4%" align="center"><input type="submit" class=button value="XYZ" name="X"></td>
        </tr>
      </table>
      </div></td>
      <td width="40%">
      <div align="left"><table border="0" cellspacing="0" width="50%" cellpadding="0">
        <tr>
         <td><input type="submit" class=button value="Refresh ALL" name="Refresh">&nbsp;&nbsp;</td>
        </tr>
      </table>
      </div></td>
    </tr>
  </table>
  </div><div align="left">
  <table border="1" cellspacing="1" width="98%" bgcolor="#FFFFFF" bordercolor="#F2F2F2">
    <tr>
      <td align="center"><input type="radio" value="member_id"   name="field_selected" <?php echo $member_id_checked?>  ></td>
      <td align="center"><input type="radio" value="user_level"  name="field_selected" <?php echo $user_level_checked?>  ></td>
      <td align="center"><input type="radio" value="firstname"   name="field_selected" <?php echo $firstname_checked?>  ></td>
      <td align="center"><input type="radio" value="lastname"    name="field_selected" <?php echo $lastname_checked?>   ></td>
      <td align="center"><input type="radio" value="password"    name="field_selected" <?php echo $password_checked?>   ></td>
      <td align="center"><input type="radio" value="refid"       name="field_selected" <?php echo $refid_checked?>      ></td>
      <td align="center"><input type="radio" value="date_registered" name="field_selected" <?php echo $date_registered_checked?> ></td>
      <td align="center"><input type="radio" value="date_lastaccess" name="field_selected" <?php echo $date_lastaccess_checked?> ></td>
    </tr>
    <tr bgcolor="#C0C0C0">
      <td align="left" class="smalltext bold"><strong>&nbsp;<font face="Arial">Member ID</font></strong></td>
      <td align="left" class="smalltext bold"><strong>&nbsp;<font face="Arial">User Level</font></strong></td>
      <td align="left" class="smalltext bold"><strong>&nbsp;<font face="Arial">First Name</font></strong></td>
      <td align="left" class="smalltext bold"><strong>&nbsp;<font face="Arial">Last Name</font></strong></td>
      <td align="left" class="smalltext bold"><strong>&nbsp;<font face="Arial">Password</font></strong></td>
      <td align="left" class="smalltext bold"><strong>&nbsp;<font face="Arial">Referer</font></strong></td>
      <td align="center" class="smalltext bold"><strong>&nbsp;<font face="Arial">Registered</font></strong></td>
      <td align="center" class="smalltext bold"><strong>&nbsp;<font face="Arial">LastAccess</font></strong></td>
    </tr>

    <?php

       $today=getDateToday();

       $sql  = "SELECT * FROM member";
       if (strlen($start_select_value) > 0 && strlen($end_select_value) > 0)
         $sql .= " WHERE $SelectField >= '$start_select_value'  AND   $SelectField <= '$end_select_value'";
       else
       if (strlen($start_select_value) > 0)
         $sql .= " WHERE $SelectField >= '$start_select_value'";

       if (strlen($SelectField) > 0)
         $sql .= " ORDER BY $SelectField $SORTDIR";
       else
         $sql .= " ORDER BY lastname,firstname ASC";
       $result = exec_query($sql,$db);

       $count=0;
       if ($result)
         {
           while ($myrow  = mysql_fetch_array($result))
            {
              $count++;
              $member_id=$myrow["member_id"];
              $user_level=$myrow["user_level"];

              $ulevel=$UserLevels[$user_level];

              $refid     = $myrow["refid"];
              $password  = $myrow["password"]."&nbsp";
              $firstname = stripslashes($myrow["firstname"]);
              $lastname  = stripslashes($myrow["lastname"]);
              $fullname  = $lastname.", ".$firstname;
              $date_registered = $myrow["date_registered"];
              $date_lastaccess = $myrow["date_lastaccess"];
              $email=$myrow["email"];

              echo "<tr>\n";
              echo "<td class=\"smalltext\"><a href=\"index.php?op=OpenMember&in_member_id=$member_id\"><font color=\"#0000EE\">$member_id</font></a></td>\n";
              echo "<td class=\"smalltext\">$ulevel</td>\n";
              echo "<td class=\"smalltext\">$firstname</td>\n";
              echo "<td class=\"smalltext\">$lastname</td>\n";
              echo "<td class=\"smalltext\">$password</td>\n";
              echo "<td class=\"smalltext\"><a href=\"index.php?op=OpenMember&in_member_id=$refid\"><font color=\"#0000EE\">$refid</font></a></td>\n";

              $registered_bg="#FFFFFF";
              $lastaccess_bg="#FFFFFF";
              if ($date_registered==$today)  $registered_bg = "#D0D0FF";
              if ($date_lastaccess==$today)  $lastaccess_bg = "#D0D0FF";

              if ($date_registered=="")
                echo "<td class=\"smalltext\" bgcolor=\"$registered_bg\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\">Not Registered</font></td>\n";
              else
                echo "<td class=\"smalltext\" bgcolor=\"$registered_bg\" align=\"center\"><font face=\"Arial\" color=\"#0000CC\">".$date_registered."</font></td>\n";

              if ($date_lastaccess=="")
                echo "<td class=\"smalltext\" bgcolor=\"$lastaccess_bg\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\">Never</font></td>\n";
              else
                echo "<td class=\"smalltext\" bgcolor=\"$lastaccess_bg\" align=\"center\"><font face=\"Arial\" color=\"#0000CC\">".$date_lastaccess."</font></td>\n";
              echo "</tr>\n";
            }
         }
    ?>

  </table>
  </div>
</form>

</body>
</html>
