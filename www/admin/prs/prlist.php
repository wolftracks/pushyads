<html>
<head>
<?php
  $dateArray = getDateTodayAsArray();
  $archiveDate  = calStepDays(-3, $dateArray);
  $archdate= dateArrayToString($archiveDate);
?>
<script language="JavaScript">
<!--
function SetSortBy(field, theForm)
 {
   theForm.op.value="ListReports";
   theForm.SortBy.value=field;
   theForm.submit();
 }
function prepareTo(option)
 {
   document.SOURCES.op.value=option;
 }
function refresh(theForm)
 {
   theForm.submit();
 }
function newReport(theForm,obj)
 {
   theForm.op.value="NewReport";
   theForm.submit();
 }
// -->
</script>
<title>AutoProspector Administration - Problem Reporting System</title>
</head>

<body LINK="#0000DD" VLINK="#0000DD" ALINK="#0000DD">
<div align="left">

<table border="0" cellPadding="0" cellSpacing="0" width="820">
<tbody>
  <tr>
    <td width="33%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><strong>Problem Reporting System<small><br>Author:&nbsp;&nbsp;<font color="#CC0000"><?php echo $PRSAUTHOR?></font></small></strong></font>
    </td>
    <td width="33%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><font color="#CC0000"><big><em><strong>Auto Prospector</strong></em></big></font><br>Administration</font></td>
    <td width="33%" bgcolor="#E8E8FF"><font face="Arial"><strong><small><font color="#0000A0">DATE:</font>
    &nbsp; <font color="#000000"><?php echo getDateToday()?></font></small><br>
    <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo getTimeNow()?></font></small></strong></font></td>
  </tr>
</tbody>
</table>

<div align="left">
<form name="SOURCES" method="POST" action="index.php">
  <input type="hidden" name="op" value="ListReports">
  <input type="hidden" name="SortBy" value="<?php echo $SortBy?>">
  <table border="0" width="820" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left" width="50%">
         <input type="submit" value="New Report"  STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onclick=prepareTo("NewReport")>
         &nbsp;&nbsp;&nbsp;
         <input type="submit" value=" Refresh "  STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick="refresh(this.form)">
      </td>
      <td align="right" width="50%">
         <?php
           $check0="";
           $check1="";
           if ($archive_included=="1")
             $check1="checked";
           else
             $check0="checked";
         ?>
         <font face="Arial" color="#000000"><small><small>Include Archive:</small></small></font>
         &nbsp;&nbsp;
         <font face="Arial" color="#0000DD"><small><small>NO</small></small></font>
         <input type="radio" name="archive_included" <?php echo $check0?> value="0" onClick="refresh(this.form)">
         &nbsp;&nbsp;
         <font face="Arial" color="#0000DD"><small><small>YES</small></small></font>
         <input type="radio" name="archive_included" <?php echo $check1?>  value="1" onClick="refresh(this.form)">
      </td>
    </tr>
    <tr>
      <td width="100%" colspan="3">&nbsp;</td>
    </tr>
  </table>


   <!--------------------------------------------------------------------------------------------------------->
  <table border="0" width="820" cellpadding="0" cellspacing="0">
    <tr>
      <td width="5%"  align="right"><font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=id"><font color="#CC0000">PR #</font></a></strong></small></font></td>
      <td width="3%"  align="right"><font face="Arial"><small>&nbsp;</small></font></td>
      <td width="30%" align="left"> <font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=title"><font color="#CC0000">Title</font></a></strong></small></font></td>
      <td width="9%" align="left"> <font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=date_opened"><font color="#CC0000">Opened</font></a></strong></small></font></td>
      <td width="9%" align="left"> <font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=last_modified"><font color="#CC0000">Modified</font></a></strong></small></font></td>
      <td width="7%"  align="left"> <font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=author"><font color="#CC0000">Author</font></a></strong></small></font></td>
      <td width="11%" align="left"> <font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=target_date"><font color="#CC0000">Target</font></a></strong></small></font></td>
      <td width="8%"  align="left"> <font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=priority"><font color="#CC0000">Priority</font></a></strong></small></font></td>
      <td width="10%"  align="left"> <font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=assignee"><font color="#CC0000">Assignee</font></a></strong></small></font></td>
      <td width="8%"  align="left"> <font face="Arial" color="#0000DD"><small><strong><a href="index.php?op=ListReports&SortBy=status"><font color="#CC0000">Status</font></a></strong></small></font></td>
    </tr>

<?php
    $count=0;
    $sql  = "SELECT * FROM prs";
    if ($archive_included!="1")
      {
        $sql .= " WHERE status!=3";
        $sql .= " OR (status=3 AND last_modified>'$archdate')";
      }
    $sql .= " ORDER BY $SortBy";
    $result = mysql_query($sql,$db);
    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());

    if ($result)
      {
        while($myrow = mysql_fetch_array($result))
          {
            $count++;
            $id=$myrow["id"];
            $title=$myrow["title"];
            if (strlen($title) > 32)
              $title = substr($title,0,32)." ...";
            $pri=$myrow["priority"];
            if ($pri==0)
              $priority="Critical";
            if ($pri==1)
              $priority="High";
            if ($pri==2)
              $priority="Medium";
            if ($pri==3)
              $priority="Low";
            $assignee=$myrow["assignee"];
            $sta=$myrow["status"];
            if ($sta==0)
              {
                $status="Open";
                $fg="#009900";
              }
            if ($sta==1)
              {
                $status="Returned";
                $fg="#CC0000";
              }
            if ($sta==2)
              {
                $status="Rejected";
                $fg="#0000DD";
              }
            if ($sta==3)
              {
                $status="Closed";
                $fg="#000000";
              }
?>
    <tr>
      <td width="5%"  align="right"><small><strong><a href="index.php?op=OpenReport&id=<?php echo $id?>&SortBy=<?php echo $SortBy?>&archive_included=<?php echo $archive_included?>"><font face="Arial"><?php echo $myrow["id"]?></font></a></strong></small></td>
      <td width="3%"  align="right"><font face="Arial"><small>&nbsp;</small></font></td>
      <td width="30%" align="left"> <font face="Arial"><small><?php echo $title?></small></font></td>
      <td width="9%" align="left"> <font face="Arial"><small><small><?php echo $myrow["date_opened"]?></small></small></font></td>
      <td width="9%" align="left"> <font face="Arial"><small><small><?php echo $myrow["last_modified"]?></small></small></font></td>
      <td width="7%"  align="left"> <font face="Arial"><small><?php echo $myrow["author"]?></small></font></td>
      <td width="11%" align="left"> <font face="Arial"><small><?php echo $myrow["target_date"]?></small></font></td>
      <td width="8%"  align="left"> <font face="Arial"><small><?php echo $priority?></small></font></td>
      <td width="10%"  align="left"> <font face="Arial" color="#CC0000"><small><?php echo $assignee?></small></font></td>
      <td width="8%"  align="left"> <font face="Arial" color="<?php echo $fg?>"><small><b><?php echo $status?></b></small></font></td>
    </tr>
<?php
          }
      }
?>
  </table>
</form>
</div>

</body>
</html>
