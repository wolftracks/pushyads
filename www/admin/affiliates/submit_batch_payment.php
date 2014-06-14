<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");


$TAB_DELIMITER   = "".chr(9);
$COMMA_DELIMITER = ",";

$DELIMITER = $COMMA_DELIMITER;

$todays_date = getDateToday();
$todays_time = getTimeNow();

$db=GetPushyDatabaseConnection();

if (FALSE)
  {
    printf("<PRE>\n");
    print_r($_REQUEST);
    printf("</PRE>\n");
    exit;
  }

$pay_period = $_REQUEST["PAY_PERIOD"];

$members=array();
foreach($_REQUEST AS $key=>$value)
  {
    if (strlen($key)>3 && substr($key,0,2)=="B_" && $value=="YES")
      {
        $mid=substr($key,2);
        array_push($members, $mid);
      }
  }

// print_r($members);


$todays_date=getDateToday();
$todays_time=getTimeNow();
$batch_id=time();

$totalAmount=0;
$recordsProcessed=0;

$submission_file_name = $todays_date."-paypal-batch-".$batch_id.".csv";
$submission_file=LOG_DIRECTORY."/paypal/submissions/".$submission_file_name;
$fsubmit = fopen($submission_file, "w");

$logfile=LOG_DIRECTORY."/affiliates/".$todays_date."-payment.log";
$fp = fopen($logfile, "a");

$member_count=count($members);

$sql  = "UPDATE earnings_semi_monthly SET";
$sql .= " date_paid='$todays_date',";
$sql .= " batch='$batch_id' ";
$sql .= " WHERE yymmdd = '$pay_period'";
$sql .= " AND (";
for ($i=0; $i<$member_count; $i++)
  {
    $member_id=$members[$i];
    $sql .= "member_id='$member_id'";
    if ($i+1 < $member_count)
      $sql .= " OR ";
  }
$sql .= ")";
$result=mysql_query($sql,$db);
$rows_affected=0;

if ($result)
  $rows_affected=mysql_affected_rows();

if ($rows_affected != $member_count)
  {
    printf("UNEXPECTED RESULT:  No Records Processed - DO NOT TRY AGAIN - Tim Will Need to take a Closer Look<br>\n");
    exit;
  }

//---------------------

$sql  = "SELECT * from earnings_semi_monthly JOIN member USING(member_id)";
$sql .= " WHERE earnings_semi_monthly.yymmdd = '$pay_period'";
$sql .= " AND (";
for ($i=0; $i<$member_count; $i++)
  {
    $member_id=$members[$i];
    $sql .= "member_id='$member_id'";
    if ($i+1 < $member_count)
      $sql .= " OR ";
  }
$sql .= ")";
$sql .= " ORDER BY member_id";
$result=mysql_query($sql,$db);

// printf("SQL: %s<br>\n",$sql);
// printf("ERR: %s<br>\n",mysql_error());
// exit;

if ($result)
  {
    while ($myrow=mysql_fetch_array($result, MYSQL_ASSOC))
      {
        $member_id    = $myrow["member_id"];
        $net_earnings = $myrow["net_earnings"];
        $firstname    = stripslashes($myrow["firstname"]);
        $lastname     = stripslashes($myrow["lastname"]);
        $fullname     = $firstname." ".$lastname;
        $email        = strtolower($myrow["email"]);
        $paypal_email = strtolower($myrow["paypal_email"]);

        $totalAmount += $net_earnings;

        $amount       = number_format($net_earnings, 2, '.', '');

        $affiliate_id = $myrow["affiliate_id"];
        $payable_to   = $myrow["payable_to"];
        $taxid        = $myrow["taxid"];

        $lineout  = "\"$batch_id\",";
        $lineout .= "\"$todays_date\",";
        $lineout .= "\"$todays_time\",";
        $lineout .= "\"$amount\",";
        $lineout .= "\"$paypal_email\",";
        $lineout .= "\"$firstname\",";
        $lineout .= "\"$lastname\",";
        $lineout .= "\"$member_id\",";
        $lineout .= "\"$payable_to\",";
        $lineout .= "\"$taxid\",";
        $lineout .= "\"$affiliate_id\"";

        fputs($fp, $lineout."\n");

        //------------------------------------------ PAYPAL SUBMISSION -----------------------
        $paypal=sprintf("%s%s%s%s%s%s%s%s%s\r\n",
                  $paypal_email,
                  $DELIMITER,
                  $amount,
                  $DELIMITER,
                  "USD",
                  $DELIMITER,
                  $member_id,
                  $DELIMITER,
                  "Your PushyAds Affiliate Payment");
        fputs($fsubmit,$paypal);
        //------------------------------------------ PAYPAL SUBMISSION -----------------------

        $recordsProcessed++;
      }
  }

fclose($fp);
fclose($fsubmit);

if ($recordsProcessed == 0)
  {
    printf("UNEXPECTED RESULT:  No Records Processed - DO NOT TRY AGAIN - Tim Will Need to take a Closer Look<br>\n");
    exit;
  }
if ($recordsProcessed != $member_count)
  {
    printf("UNEXPECTED RESULT:  Records Processed Does Not Match Number Of Members Being Processed - Have Tim Take a Closer Look<br>\n");
    exit;
  }

$totalPaymentAmount = number_format($totalAmount, 2, '.', '');
?>
<html>
<head>
<script type="text/javascript" src="/local-js/common.js"></script>

<script LANGUAGE="JavaScript">
function openPayPal()
  {
    var url = "https://www.paypal.com/us/cgi-bin/webscr?cmd=_batch-payment";

    var leftmargin = 0;
    var topmargin  = 0;

    win=window.open(url,"PayPalMassPay",
       'width=760,height=760,top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=yes,directories=no,status=no,menubar=yes,toolbar=yes,resizable=yes');
    win.focus();
  }
</script>


<link rel="stylesheet" type="text/css" href="/admin/admin.css" />
<title>Affiliate Payment System</title>
</head>

<body>
<br>&nbsp;<br>
<center><span class=bigdarkbluebold>A Batch Payment File has been created for Upload to PayPal</span></center>
<br>&nbsp;<br>
<center>
<table align="center" width="700" border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td width="30%" class="normaldarkredbold">Batch ID:</td>
    <td width="70%" class="normaltext"><?php echo $batch_id?></td>
  </tr>
  <tr>
    <td width="30%" class="normaldarkredbold">Batch File:</td>
    <td width="70%" class="normaltext"><?php echo $submission_file_name?></td>
  </tr>
  <tr>
    <td width="30%" class="normaldarkredbold">Records Processed:</td>
    <td width="70%" class="normaltext"><?php echo $recordsProcessed?></td>
  </tr>
  <tr>
    <td width="30%" class="normaldarkredbold">Total Payment Amount:</td>
    <td width="70%" class="normalbold">$<?php echo $totalPaymentAmount?></td>
  </tr>
</table>
</center>

<br>&nbsp;<br>&nbsp;<br>
<center><span class=bigdarkbluebold>Please Take these Next Steps to Complete this Batch Payment:</span></center>
<br>
<center>
<table align="center" width="800" border=0 cellpadding=0 cellspacing=0>
  <tr height="40">
    <td align="right": width="20%" class="bigredbold">STEP 1:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="80%" class="normaltext">
      <a href="download_batch.php?batch_id=<?php echo $batch_id?>&submission_file_name=<?php echo $submission_file_name?>">
         <font color="#0000EE"><b>CLICK HERE</b></font>
      </a>
      &nbsp;&nbsp;to Download and Save this Batch File<span class=smallbold>&nbsp;&nbsp;&nbsp;(use file name suggested)</span>
    </td>
  </tr>
  <tr height="40">
    <td align="right": width="20%" class="bigredbold">STEP 2:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td width="80%" class="normaltext">
      <a href=javascript:openPayPal()>
         <font color="#0000EE"><b>CLICK HERE</b></font>
      </a>
      &nbsp;&nbsp;to Sign in and Submit the File to Paypall (Mass Pay)
    </td>
  </tr>
</table>
</center>

<br>&nbsp;<br>
<table align="center" width="800" border=0 cellpadding=0 cellspacing=0>
<tr><td align=center>
<form method="GET" action="/admin/affiliates">
  <input type=submit value="Click Here to Return to Affiliates Home Page">
</form>
</td></tr>
</table>

<br>&nbsp;<br>

</body>
</html>
