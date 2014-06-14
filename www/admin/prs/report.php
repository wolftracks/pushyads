<?php
   $numUsers=count($users);
   $numDevelopers=count($developers);
?>
<html>
<head>
<script language="JavaScript">
<!--
 function VerifyReport(theForm)
   {
     var prstatus="<?php echo $status?>";
     var author="<?php echo $author?>";
     var prsauthor="<?php echo $PRSAUTHOR?>";

     theForm.in_title.value       = striplt(theForm.in_title.value);
     theForm.in_target_date.value = striplt(theForm.in_target_date.value);
     theForm.in_description.value = striplt(theForm.in_description.value);
     theForm.in_response.value    = striplt(theForm.in_response.value);

     theForm.in_title.value       = stripTitle(theForm.in_title.value);
     theForm.in_description.value = stripSpecial(theForm.in_description.value);
     theForm.in_response.value    = stripSpecial(theForm.in_response.value);

     if (theForm.in_title.value.length==0)
       {
         alert("Please provide a descriptive Title for this PR ");
         theForm.in_title.focus();
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

     if (theForm.in_description.value.length < 3)
       {
         alert("Please provide a detailed description for this PR ");
         theForm.in_description.focus();
         return (false);
       }

     if (theForm.in_status.value != prstatus)
       {
         if (theForm.in_response.value.length < 3)
           {
             alert("Changing a PRs Status REQUIRES Some Reason or Description in the Response Field");
             theForm.in_response.focus();
             return (false);
           }
       }

     var cnt = 0;
     if (theForm.updated_by.value == author)
         theForm.notificationlist.value=""+author+"/";
     else
         theForm.notificationlist.value=""+author+"/"+theForm.updated_by.value+"/";
     for (var i=0; i<theForm.notify.length; i++)
       {
         if (theForm.notify[i].selected)
           {
             if (theForm.notify[i].value != author &&
                 theForm.notify[i].value != theForm.updated_by.value)
                   theForm.notificationlist.value += theForm.notify[i].value+"/";
           }
       }

     // alert(theForm.notificationlist.value);

//------------------------------------------------------- 05/15/2003
//   if (!dateValid(theForm.in_target_date.value))
//     {
//       alert("Target Date must be in the form: YYYY-MM-DD");
//       theForm.in_target_date.focus();
//       return (false);
//     }

     return (true);
   }


 function dateValid(dt)
   {
     if (dt.length == 0)
       return true;
     if (dt.length != 10)
       return false;

     yy = dt.substring(0,4);
     mm = dt.substring(5,7);
     dd = dt.substring(8,10);

     // alert("YY="+yy);
     // alert("MM="+mm);
     // alert("DD="+dd);

     if (!(isNumeric(yy) && isNumeric(mm) && isNumeric(dd)))
         return false;
     if (yy < 2002 || mm==0 || mm > 12 || dd==0 || dd > 31)
         return false;
     return true;
   }


 function updateReport(theForm,obj,id)
   {
     if (theForm.updated_by.selectedIndex==0)
       {
         alert("Please identify yourself as the person Updating this PR");
         theForm.updated_by.focus();
         return (false);
       }

     if (VerifyReport(theForm))
       {
         theForm.op.value="UpdateReport";
         theForm.submit();
       }
   }


 function deleteReport(theForm,obj,id)
   {
     msg  = "             --------- WARNING --------- \n";
     msg += " You have asked to Delete This Problem Report\n";
     msg += " \n";
     msg += " Click  OK           to Continue with the DELETE\n";
     msg += " Click  CANCEL  to Cancel This Request\n";
     msg += " \n";
     resp = confirm(msg);
     if (resp)
       {
         theForm.op.value="DeleteReport";
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
    <td width="25%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><strong>Problem Report #<?php echo $id?><small><br>Author:&nbsp;&nbsp;<font color="#CC0000"><?php echo $PRSAUTHOR?></font></small></strong></font></td>
    <td width="50%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><font color="#CC0000"><big><em><strong>AutoProspector</strong></em></big></font>&nbsp;&nbsp;Administration</font></td>
    <td width="25%" bgcolor="#E8E8FF"><font face="Arial"><strong><small><font color="#0000A0">DATE:</font>
    &nbsp; <font color="#000000"><?php echo getDateToday()?></font></small><br>
    <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo getTimeNow()?></font></small></strong></font></td>
  </tr>
</tbody>
</table>

<form name="REPORT" method="POST" action="index.php">
  <input type="hidden" name="op" value="ListReports">
  <input type="hidden" name="archive_included" value="<?php echo $archive_included?>">
  <input type="hidden" name="SortBy" value="<?php echo $SortBy?>">
  <input type="hidden" name="prs_key" value="<?php echo $prs_key?>">
  <input type="hidden" name="id" value="<?php echo $id?>">
  <input type="hidden" name="notificationlist" value="">
  <input type="hidden" name="updated_by" value="<?php echo $PRSAUTHOR?>">
<?php
if ($PRSAUTHOR!="tim" && $PRSAUTHOR!="mark")
  {
?>
     <input type="hidden" name="in_target_date" value="<?php echo $target_date?>">
     <input type="hidden" name="AssignedTo" value="<?php echo $assignee?>">
<?php
  }
?>

  <div align="left">
  <table border="0" cellpadding="1" cellspacing="0" width="720">
    <tr>
      <td width="100%" align="center" colspan="3">
        <table border="3" cellpadding="2" cellspacing="2" bgcolor="#E8E8FF">
          <tr>
            <td>
              &nbsp;
              <input type="button" value=" Update " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onclick=updateReport(this.form,this,'<?php echo $source_id?>')>
              &nbsp;
            </td>
            <td>
              &nbsp;
              <input type="button" value=" Delete " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onclick=deleteReport(this.form,this,'<?php echo $source_id?>')>
              &nbsp;
            </td>
            <td>
              &nbsp;
              <input type="submit" value=" Return " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onclick="prepareTo('ListReports')">
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
      <td width="80%" colspan="2"><input type="text" name="in_title" size="60" maxlength="60" value="<?php echo $title?>"></td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Author:&nbsp;&nbsp;</font></strong></small></td>
      <td width="40%" align="left"> <small><font face="Arial" color="#0000DD"><?php echo $author?></font></small></td>
      <td width="40%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Date Opened:&nbsp;&nbsp;</font></strong></small></td>
      <td width="40%" align="left"> <small><font face="Arial" color="#0000DD"><?php echo $date_opened?></font></small></td>
      <td width="40%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Last Modified:&nbsp;&nbsp;</font></strong></small></td>
      <td width="40%" align="left"> <small><font face="Arial" color="#0000DD"><?php echo $last_modified?></font></small></td>
      <td width="40%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Priority:&nbsp;&nbsp;</font></strong></small></td>
      <td width="40%">
<?php
        $pri0="";
        $pri1="";
        $pri2="";
        $pri3="";
        if ($priority==0) $pri0="selected";
        else
        if ($priority==1) $pri1="selected";
        else
        if ($priority==2) $pri2="selected";
        else
        if ($priority==3) $pri3="selected";
?>
        <select name="in_priority">
          <option name="CRITICAL" value="0" <?php echo $pri0?>>CRITICAL</option>
          <option name="HIGH"     value="1" <?php echo $pri1?>>HIGH</option>
          <option name="MEDIUM"   value="2" <?php echo $pri2?>>MEDIUM</option>
          <option name="LOW"      value="3" <?php echo $pri3?>>LOW</option>
        </select>
      </td>
      <td width="40%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Status:&nbsp;&nbsp;</font></strong></small></td>
      <td width="40%">
<?php
        $sta0="";
        $sta1="";
        $sta2="";
        $sta3="";
        if ($status==0) $sta0="selected";
        else
        if ($status==1) $sta1="selected";
        else
        if ($status==2) $sta2="selected";
        else
        if ($status==3) $sta3="selected";
?>
        <select name="in_status">
          <option name="OPEN"     value="0" <?php echo $sta0?>>OPEN</option>
          <option name="RETURNED" value="1" <?php echo $sta1?>>RETURNED</option>
          <option name="REJECTED" value="2" <?php echo $sta2?>>REJECTED</option>
          <option name="CLOSED"   value="3" <?php echo $sta3?>>CLOSED</option>
        </select>
      </td>
      <td width="40%">&nbsp;</td>
    </tr>


<!-----
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Updated By:&nbsp;&nbsp;</font></strong></small></td>
      <td width="40%">
      <select name="updated_by"><option name="NONE" value="NONE" selected> -- SELECT -- </option>
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
      <td width="40%"><small><font face="Arial">Required if PR is Being Updated</font></small></td>
    </tr>
----->


    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">Reference PR #:&nbsp;&nbsp;</font></strong></small></td>
      <td width="80%" colspan="2"><input type="text" name="in_pr_reference" size="6" maxlength="6" value="<?php echo $pr_reference?>">&nbsp;&nbsp;&nbsp;&nbsp;<font face="Arial"><small><small>a PR that this one may be associated with or related to</small></small></td>
    </tr>
    <tr>
      <td width="100%" align="left" colspan="2"><small><small>&nbsp;</small></small></td>
    </tr>
    <tr>
      <td width="100%" align="left" colspan="2"><small><font face="Arial"><strong>&nbsp;Notify team members:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><small>Hold Ctrl-Key Down to Select/unSelect</small></font></small></td>
    </tr>
    <tr>
      <td width="20%" align="right"><small><strong><font face="Arial">&nbsp;</font></strong></small></td>
      <td width="80%" align="left">
       <select name="notify"  size="<?php echo $numUsers?>" multiple>
         <?php
           ksort($users);
           reset($users);
           while (list($user, $email) = each($users))
             {
               if ($user == "mark" || $user == "tim" || $user == $author)
                  echo "<option value=\"$user\" selected>$user</option>\n";
               else
                  echo "<option value=\"$user\">$user</option>\n";
             }
         ?>
       </select>
      </td>
    </tr>
<?php
if ($PRSAUTHOR=="tim" ||
    $PRSAUTHOR=="mark")
  {
?>
    <tr>
      <td width="100%" align="left" colspan="2"><small><small>&nbsp;</small></small></td>
    </tr>
    <tr bgcolor="#F0F0FF">
      <!-- td width="20%" align="right"><small><strong><font face="Arial">Target Date:&nbsp;&nbsp;</font></strong></small></td -->
      <td width="20%" align="right"><small><strong><font face="Arial">Target:&nbsp;&nbsp;</font></strong></small></td>
      <td width="80%" colspan="2">  <input type="text" name="in_target_date" size="10" maxlength="10" value="<?php echo $target_date?>"></td>
    </tr>
    <tr bgcolor="#F0F0FF">
      <td width="20%" align="right"><small><font face="Arial"><strong>&nbsp;Assigned To:&nbsp;&nbsp;</strong></font></small></td>
      <td width="80%" colspan="2" align="left">
         <?php
            echo "<select name=\"AssignedTo\" size=\"1\">";
            if (strlen($assignee) == 0 || $assignee=="NONE")
              echo "<option value=\"\" selected>-- SELECT --</option>\n";

            //  while (list($user, $email) = each($developers))

            reset($users);
            while (list($user, $email) = each($users))
              {
                if ($user == $assignee)
                   echo "<option value=\"$user\" selected>$user</option>\n";
                else
                   echo "<option value=\"$user\">$user</option>\n";
              }
            echo "</select>\n";
         ?>
      </td>
    </tr>
<?php
  }
?>
    <tr>
      <td width="100%" colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td width="100%" colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" width="100%" colspan="3">
      <blockquote>
      <table border="0" cellPadding="0" cellSpacing="0" width="92%" align="left">
        <tr>
          <td align="center"  width="100%" bgcolor="#D0D0D0">
            <strong><font face="Arial">Detailed Description<br></font></strong>
            <textarea cols="80" name="in_description" rows="10"><?php echo $description?></textarea><br>&nbsp;
          </td>
        </tr>
      </table>
      </blockquote>
      </td>
    </tr>
<!--
    <tr>
      <td width="100%" colspan="3">&nbsp;</td>
    </tr>
-->
    <tr>
      <td align="left" width="100%" colspan="3">
      <blockquote>
      <table border="0" cellPadding="0" cellSpacing="0" width="92%" align="left">
        <tr>
          <td align="center"  width="100%" bgcolor="#D0D0D0">
            <strong><font face="Arial">Response/Action<br></font></strong>
            <textarea cols="80" name="in_response" rows="10"><?php echo $response?></textarea><br>&nbsp;
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
