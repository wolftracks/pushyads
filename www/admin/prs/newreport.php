<html>
<head>
<script language="JavaScript">
<!--
 function VerifyReport(theForm)
   {
     theForm.in_title.value       =   striplt(theForm.in_title.value);
     theForm.in_description.value =   striplt(theForm.in_description.value);

     theForm.in_title.value       =   stripTitle(theForm.in_title.value);
     theForm.in_description.value =   stripSpecial(theForm.in_description.value);

     theForm.in_pr_reference.value =  striplt(theForm.in_pr_reference.value);

     if (theForm.in_title.value.length==0)
       {
         alert("Please provide a descriptive Title for this PR ");
         theForm.in_title.focus();
         return (false);
       }

     if (theForm.in_author.selectedIndex==0)
       {
         alert("Please indicate the author of this PR ");
         return (false);
       }

     if (theForm.in_priority.selectedIndex==0)
       {
         alert("Please indicate a Priority for this PR ");
         return (false);
       }

     if (theForm.in_pr_reference.value.length == 0)
        theForm.in_pr_reference.value="0";
     else
       {
         if (!isNumeric(theForm.in_pr_reference.value))
           {
             alert("Refrence PR# must be NUMERIC");
             theForm.in_pr_reference.focus();
             return (false);
           }
         theForm.in_pr_reference.value = getDigits(theForm.in_pr_reference.value);
       }

     if (theForm.in_description.value.length < 10)
       {
         alert("Please provide a detailed description for this PR ");
         theForm.in_description.focus();
         return (false);
       }

     return (true);
   }


 function addReport(theForm,obj)
   {
     if (VerifyReport(theForm))
       {
         theForm.op.value="AddReport";
         theForm.submit();
       }
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

 function stripTitle(aString)
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

 function isNumeric(aString)
   {
     for (i=0; i<aString.length; i++)
       {
         if (!(aString.charAt(i) >= '0' && aString.charAt(i) <= '9'))
           {
             return(false)
           }
       }
     return(true);
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

 function prepareTo(option)
   {
     document.REPORT.op.value=option;
   }
// -->
</script>

</script>
<title>AutoProspector Administration - New Problem Report</title>
</head>

<body LINK="#0000DD" VLINK="#0000DD" ALINK="#0000DD">
<div align="left">

<table border="0" cellPadding="0" cellSpacing="0" width="720">
<tbody>
  <tr>
    <td width="25%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><strong>New Problem Report</strong></font></td>
    <td width="50%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><font color="#CC0000"><big><em><strong>AutoProspector</strong></em></big></font>&nbsp;&nbsp;Administration</font></td>
    <td width="25%" bgcolor="#E8E8FF"><font face="Arial"><strong><small><font color="#0000A0">DATE:</font>
    &nbsp; <font color="#000000"><?php echo getDateToday()?></font></small><br>
    <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo getTimeNow()?></font></small></strong></font></td>
  </tr>
</tbody>
</table>

<form name="REPORT" method="POST" action="index.php">
  <input type="hidden" name="op" value="ListReports">
  <input type="hidden" name="SortBy" value="<?php echo $SortBy?>">
  <input type="hidden" name="archive_included" value="<?php echo $archive_included?>">
  <input type="hidden" name="prs_key" value="<?php echo $prs_key?>">
  <div align="left">
  <table border="0" cellpadding="1" cellspacing="0" width="720">
    <tr>
      <td width="100%" align="center" colspan="3">
        <table border="3" cellpadding="2" cellspacing="2" bgcolor="#E8E8FF">
          <tr>
            <td>
              &nbsp;
              <input type="button" value=" Submit "  STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onclick=addReport(this.form,this)>
              &nbsp;
            </td>
            <td>
              &nbsp;
              <input type="submit" value=" Cancel " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onclick=prepareTo('ListReports')>
              &nbsp;
            </td>
           </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="20" width="100%" align="left" colspan="3">
       <?php
           echo "<font face=\"Arial\" color=\"#CC0000\"><small><strong>$StatusMessage</strong></small></font>\n";
       ?>
      </td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Title/Description:&nbsp;&nbsp;</font></strong></small></td>
      <td width="80%" colspan="2"><input type="text" name="in_title" size="60" maxlength="60" value="<?php echo $in_title?>"></td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Author:&nbsp;&nbsp;</font></strong></small></td>
      <td width="40%">
      <select name="in_author"><option name="NONE" value="NONE" selected> -- SELECT -- </option>
<?php
        ksort($users);
        reset($users);
        while (list($user, $email) = each($users))
          {
            echo "<option value=\"$user\">$user</option>\n";
          }
?>
      </select>
      </td>
      <td width="40%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Priority:&nbsp;&nbsp;</font></strong></small></td>
      <td width="40%">
        <select name="in_priority"><option name="NONE" value="NONE"> -- SELECT -- </option>
          <option name="CRITICAL" value="0">CRITICAL</option>
          <option name="HIGH"     value="1">HIGH</option>
          <option name="MEDIUM"   value="2">MEDIUM</option>
          <option name="LOW"      value="3">LOW</option>
        </select>
      </td>
      <td width="40%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Reference PR #:&nbsp;&nbsp;</font></strong></small></td>
      <td width="80%" colspan="2"><input type="text" name="in_pr_reference" size="6" maxlength="6" value="<?php echo $in_pr_reference?>">&nbsp;&nbsp;&nbsp;&nbsp;<font face="Arial"><small><small>a PR that this one may be associated with or related to</small></small></td>
    </tr>
    <tr>
      <td width="100%" colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" width="100%" colspan="3">
      <blockquote>
      <table border="0" cellPadding="0" cellSpacing="0" width="92%" align="left">
        <tr>
          <td align="center"  width="100%" bgcolor="#E8E8FF">
            <strong><small><font face="Arial">Detailed Description</font></small></strong>
            <textarea cols="80" name="in_description" rows="15"><?php echo $in_description?></textarea><small><br>&nbsp;</small>
          </td>
        </tr>
      </table>
      </blockquote>
      </td>
    </tr>
    <tr>
      <td width="100%" colspan="3">&nbsp;</td>
    </tr>
  </table>
  </div>
</form>

</body>
</html>
