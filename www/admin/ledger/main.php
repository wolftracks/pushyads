<html>

<?php
    $START_YEAR = 2009;


    $months=array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    $dateToday=getDateToday();
    $timeNow=getTimeNow();

    $temp=getDateTodayAsArray();
    $currentYear=$temp["year"];
    if (!isset($calendarYear) || strlen($calendarYear) < 4)
      {
        $calendarYear = $currentYear;
      }
    $save_year=$calendarYear;
?>

<head>
<meta http-equiv="Page-Enter" content="RevealTrans (Duration=.6,Transition=12)">

<script LANGUAGE="JavaScript">
function ValidateForm(theForm)
  {
    var xlist="";
    for (var i=0; i<theForm.elements.length; i++)
     {
       if (theForm.elements[i].type == "checkbox")
         {
           cbox=theForm.elements[i];
           if (cbox.checked)
             {
               if (xlist.length > 0)
                  xlist+="&";
               xlist+=cbox.name
             }
         }
     }

    if (xlist == "")
      {
        alert("No Months Selected");
        return false;
      }

    // alert("XLIST:  "+xlist);
    // return false;

    theForm.ExportList.value=xlist;
    return true;
  }

function checkAll()
  {
    for (var i=0; i<document.EXPORT.elements.length; i++)
      {
        if (document.EXPORT.elements[i].type == "checkbox" && document.EXPORT.elements[i].checked == false)
            document.EXPORT.elements[i].click();
      }
  }

function uncheckAll()
  {
    for (var i=0; i<document.EXPORT.elements.length; i++)
      {
        if (document.EXPORT.elements[i].type == "checkbox" && document.EXPORT.elements[i].checked == true)
            document.EXPORT.elements[i].click();
      }
  }

function setCalendarYear(theForm)
  {
    theForm.calendarYear.value = theForm.YearSelector.value;
    theForm.submit();
  }
// End of JavaScript-->
</script>

<title>Account Manager</title>
</head>

<body>

<table border="0" cellPadding="4" cellSpacing="0" width="90%">
<tbody>
  <tr>
    <td width="25%"><p align="left">
       <font color="#0000a0" face="Arial"><small>&nbsp;&nbsp;&nbsp;&nbsp;<strong>RECEIPTS</strong></small></font><br>
       <font color="#CC0000" face="Arial"><small>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $PGAGENT?></strong></small></font>
    </td>
    <td width="50%"><p align="center"><font color="#ff0000" face="Arial"><big><strong><big>
    PushyAds</big></strong></big></font></td>
    <td width="25%"><font face="Arial"><strong><small><font color="#0000A0">DATE:</font>
    &nbsp; <font color="#000000"><?php echo $dateToday?></font></small><br>
    <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo $timeNow?></font></small></strong></font></td>
  </tr>
</tbody>
</table>
<br>

<form method="POST" action="index.php" align="left">
<input type="hidden" name="calendarYear" value="">
<strong><font face="Arial" color="#0000A0">Year: </font></strong>
&nbsp;&nbsp;
<select name="YearSelector" onChange="setCalendarYear(this.form)">
<?php
  for ($year=$currentYear; $year>=$START_YEAR; $year--)
    {
      if ($calendarYear==$year)
        $sel="selected";
      else
        $sel="";
      echo  "<option value=\"$year\" $sel>&nbsp;&nbsp;$year&nbsp;&nbsp;</option>\n";
    }
?>
</select>
</form>


<form name="EXPORT" method="POST" action="index.php" onsubmit="return ValidateForm(this)">
<input type="hidden" name="op" value="EXPORT">
<input type="hidden" name="ExportList" value="">
<table border="0" width="600" cellpadding="1" cellspacing="1">
  <tr height="28">
    <td width="8%" ><p align="center"><font color="#0000A0" face="Arial"><u><small><strong>Export</strong></small></u></font></td>
    <td width="6%" >&nbsp;</td>

    <td width="20%"><p align="left"><font color="#0000A0" face="Arial">&nbsp;</font></td>

    <td width="26%" align="center" colspan="2"><font color="#0000A0" face="Arial"><u><strong><small>Month</small></strong></u></font></td>

    <td width="14%" align="center">&nbsp;</td>

    <td width="26%" align="center" colspan="2"><font color="#0000A0" face="Arial"><u><strong><small>YTD</small></strong></u></font></td>
  </tr>
<?php
    $sql  = "SELECT yymm, count(*), sum(amount) FROM receipts";
    $sql .= " WHERE yymm >= '$calendarYear"."-01"."'";
    $sql .= " AND yymm <= '$calendarYear"."-12"."'";
    $sql .= " AND txtype < $TXTYPE_PAYMENT";

    $sql .= " GROUP BY yymm";
    $result = exec_query($sql,$db);
    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());
    if ($result)
      {
        while ($myrow=mysql_fetch_array($result))
          {
            $yymm        = $myrow[0];
            $count       = $myrow[1];
            $amount      = $myrow[2];
            $ytd_count  += $count;
            $ytd_amount += $amount;
            $year = (int) substr($yymm, 0, 4);
            $mth  = (int) substr($yymm, 5, 2);
            $date = "$months[$mth], $year";
?>
<tr height="28">
  <td width="8%"  align="center"><input type="checkbox" name="<?php echo $yymm?>"></td>
  <td width="6%">&nbsp;</td>


  <td width="20%"><strong><small><font face="Arial"><a href="index.php?op=TRANSACTIONS&select_yymm=<?php echo $yymm?>"><?php echo $date?></a></font></small></strong></td>

  <td width="10%" align="left"  ><small><font face="Arial">(<?php echo $count?>)</font></small></td>
  <td width="16%" align="right" ><small><font face="Arial">$ <?php echo number_format($amount,2,".",",")?></font></small></td>

  <td width="14%" align="center">&nbsp;</td>

  <td width="10%" align="left"  ><small><font face="Arial">(<?php echo $ytd_count?>)</font></small></td>
  <td width="16%" align="right" ><small><font face="Arial">$ <?php echo number_format($ytd_amount,2,".",",")?></font></small></td>
</tr>
<?php
          }

        mysql_free_result($result);
      }
    else
      {
        echo "<div align=\"left\">\n";
        echo "<br><strong><font face=\"Arial\" color=\"#CC0000\">No payments Found</font></strong>\n";
        echo "</div>\n";
      }
?>
</table>

&nbsp<br>&nbsp<br>

<br>

<table border="0" cellpadding="0" cellspacing="0" width="600">
  <tr>
    <td width="80%" colspan="3" align="left">
      <table border="3" cellpadding="2" cellspacing="2" bgcolor="#E8E8FF">
        <tr>
          <td>
            <input type="button" value="Select All" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"
                     onClick="checkAll()">
          </td>
          <td>
            <input type="button" value="Deselect All" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"
                     onClick="uncheckAll()">
          </td>
          <td>
            <input type="Submit" value="   Export   " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;">
          </td>
        </tr>
      </table>
    </td>
    <td width="20%" colspan="3" align="center">
      <table border="3" cellpadding="2" cellspacing="2" bgcolor="#E8E8FF">
        <tr>
          <td>
            <input type="button" value="  Search  " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick="javascript:document.location='index.php?op=FIND'">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</form>

</body>
</html>
