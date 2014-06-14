<?php
   include_once("users.php");
?>
<html>
<head>

<script LANGUAGE="JavaScript">
<!--
 function ValidateForm(theForm)
  {
    if (theForm.PRSAUTHOR.selectedIndex==0)
     {
       alert("Please identify yourself");
       theForm.PRSAUTHOR.focus();
       return (false);
     }
    return (true);
  }


 function isNumeric(aString)
  {
    var count=0;
    for (i=0; i<aString.length; i++)
      {
        if (!(aString.charAt(i) >= '0' && aString.charAt(i) <= '9'))
          return false;
      }
    return(true);
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
//-->
</script>

<title>Signin</title>
</head>

<body>
<div align="left">

<table border="0" cellPadding="0" cellSpacing="0" width="760">
<tbody>
  <tr>
    <td width="33%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><strong>Problem Reporting System<br>Sign-In</strong></font></td>
    <td width="33%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><font color="#CC0000"><big><em><strong>Auto Prospector</strong></em></big></font><br>Administration</font></td>
    <td width="33%" bgcolor="#E8E8FF"><font face="Arial"><strong><small><font color="#0000A0">DATE:</font>
    &nbsp; <font color="#000000"><?php echo getDateToday()?></font></small><br>
    <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo getTimeNow()?></font></small></strong></font></td>
  </tr>
</tbody>
</table>

<br>&nbsp;<br>

<form name="SIGNIN" method="POST" action="init.php" onsubmit="return ValidateForm(this)">
  <div align="left">
  <table border="0" cellpadding="0" cellspacing="0" width="90%">
   <tr>
     <td width="30%" align="right"><font face="Arial, Helvetica" size="2"><b>Please identify yourself:&nbsp;&nbsp;&nbsp;&nbsp;</b></font></td>
     <td width="40%" align="left">
       <select name="PRSAUTHOR" size=1><option value=NONE selected>- Select -</option>
<?php
         while (list($user,$email) = each($users))
           {
             echo "  <option value=\"$user\">$user</option>\n";
           }
?>
       </select>
       &nbsp;&nbsp;&nbsp;
       <input type="submit" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" value=" Continue ">
     </td>
     <td width="30%">&nbsp;</td>
   </tr>
  </table>
  </div>
</form>
</body>
</html>
