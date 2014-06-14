<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

include 'Spreadsheet/Excel/Writer.php';

$db = getPushyDatabaseConnection();

$week_start_dates = tracker_dates();

function tracker_sum_category($db)
  {
    $categories = array();

    $sql  = "SELECT ";
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_h0), sum(w".$week."_h1), sum(w".$week."_h2), sum(w".$week."_h3), sum(w".$week."_h4), sum(w".$week."_h5), sum(w".$week."_h6), ";
      }
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_c0), sum(w".$week."_c1), sum(w".$week."_c2), sum(w".$week."_c3), sum(w".$week."_c4), sum(w".$week."_c5), sum(w".$week."_c6), ";
      }
    $sql .= "  userkey ";
    $sql .= "  FROM tracker_pushy_category";
    $sql .= "  GROUP BY userkey";
    $result=mysql_query($sql,$db);

    if (FALSE)
     {
       printf("SQL: %s<br>\n",$sql);
       printf("ERR: %s<br>\n",mysql_error());
     }

    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $j=0;
            for ($i=0; $i<35; $i++, $j++)
              {
                $hits[$i]   = $myrow[$j];
              }
            for ($i=0; $i<35; $i++, $j++)
              {
                $clicks[$i] = $myrow[$j];
              }

            $category    = $myrow[70];

            $categories[$category] = array("hits"=>$hits, "clicks"=>$clicks);

          }
      }
    return $categories;
  }


$category_summary = tracker_sum_category($db);

$no_data = "0/0";
$no_data = "-";

//--- Categories with No Activity  ------- No Hits, No Clicks --------
$no_hits   = array();
$no_clicks = array();
$j=0;
for ($i=0; $i<35; $i++, $j++)   $no_hits[$i]   = 0;
for ($i=0; $i<35; $i++, $j++)   $no_clicks[$i] = 0;
$category_none = array("hits"=>$no_hits, "clicks"=>$no_clicks);
//--------------------------------------------------------------------

//echo "<PRE>";
//print_r($category_none);
//print_r($category_summary);
//echo "</PRE>";
// exit;


// create empty file
$excel = new Spreadsheet_Excel_Writer('categories.xls');

// add worksheet
$sheet =& $excel->addWorksheet('Categories');

// add data to worksheet
$rowCount=0;
foreach ($data as $row) {
  foreach ($row as $key => $value) {
    $sheet->write($rowCount, $key, $value);
  }
  $rowCount++;
}

// save file to disk
if ($excel->close() === true) {
  echo 'Spreadsheet successfully saved!';
} else {
  echo 'ERROR: Could not save spreadsheet.';
}
?>
$lnout="CATEGORY,LASTNAME,EMAIL,PHONE,COUNTRY,IPADDR,EXPERIENCE,INTEREST,NEED,DESIRE,AVAILABILITY,CONVICTION,URGENCY\r\n";
// echo $lnout;
$data = $lnout;

while ($myrow = mysql_fetch_array($result))
  {
    $id = $myrow["id"];
    $lead_firstname =  stripslashes($myrow["lead_firstname"]);
    $lead_lastname  =  stripslashes($myrow["lead_lastname"]);
    $lead_email     =  $myrow["lead_email"];
    $lead_dayphone  =  getDigits($myrow["lead_dayphone"]);
    $lead_country   =  $myrow["lead_country"];
    $ipaddr         =  $myrow["ipaddr"];
    $field01        =  $myrow["field01"];  // experience
    $field02        =  $myrow["field02"];  // interest
    $field03        =  $myrow["field03"];  // need
    $field04        =  $myrow["field04"];  // desire
    $field05        =  $myrow["field05"];  // availability
    $field06        =  $myrow["field06"];  // conviction
    $field07        =  $myrow["field07"];  // urgency


    // $lnout=sprintf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\r\n",
    $lnout=sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\r\n",
             $lead_firstname,
             $lead_lastname,
             $lead_email,
             $lead_dayphone,
             $lead_country,
             $ipaddr,
             $field01,
             $field02,
             $field03,
             $field04,
             $field05,
             $field06,
             $field07);

    // echo $lnout;
    $data .= $lnout;
    $LeadsDelivered++;
  }
$filesize=strlen($data);
$fname="categories.csv";

// header("Content-Type: application/force-download\n");
header("Content-Type: application/octetstream\n");
header("Content-Transfer-Encoding: Binary\n");
header("Accept-Ranges: bytes\n");

header("Content-Disposition: attachment; filename=$fname");
echo $data;
?>





























<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css" />
<title>PushyAds Administration - Who Is</title>

<style>
.column_header {
   background:#E0E0E0;
   font-family: Arial, Helvetica;
   font-size:   12px;
   font-weight: bold;
   text-align:  center;
   width:       200px;
   color:       #000000;
}
.category_header {
   background:#A0FFA0;
   font-family: Arial, Helvetica;
   font-size:   12px;
   font-weight: bold;
   text-align:  center;
   width:       200px;
   color:       #000000;
}
.current_week {
   background:#A0A0FF;
   font-family: Arial, Helvetica;
   font-size:   12px;
   font-weight: bold;
   text-align:  center;
   width:       200px;
   color:       #000000;
}
.prior_week {
   background:#FFA0A0;
   font-family: Arial, Helvetica;
   font-size:   12px;
   font-weight: bold;
   text-align:  center;
   width:       200px;
   color:       #000000;
}
</style>

<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript">

function submit_query(filter_query)
 {
   var theForm=document.MEMBER_SELECTION;
   if (theForm.filter_type.selectedIndex==0)
     {
       theForm.filter_type.focus();
       alert("Select Filter Type");
       return;
     }
   var filter_value=striplt(theForm.filter_value.value);
   if (filter_value.length==0)
     {
       theForm.filter_value.focus();
       alert("Enter Filter value");
       return;
     }

   theForm.current_filter_type.value  = theForm.filter_type.value;
   theForm.current_filter_value.value = theForm.filter_value.value;
   theForm.current_filter_query.value = filter_query;

   var url = "/admin/whois.php?filter_type="+theForm.filter_type.value+"&filter_value="+theForm.filter_value.value+"&filter_query="+theForm.current_filter_query.value+"&sort="+theForm.current_sort.value;

   // alert(url);
   window.location.href=url;
 }

function sort_by(sort)
 {
   var theForm=document.MEMBER_SELECTION;
   theForm.current_sort.value = sort;
   submit_query(theForm.current_filter_query.value);
 }

function allMembers()
 {
   var theForm=document.MEMBER_SELECTION;
   if (theForm.filter_type.selectedIndex==0)
       theForm.filter_type.selectedIndex=1;
   theForm.filter_value.value="*";
   theForm.current_filter_query.value = "equals";
   submit_query(theForm.current_filter_query.value);
 }

function signin(member_id,password)
  {
    // alert("Member="+member_id);
    // return;
    var leftmargin = 0;
    var topmargin  = 0;
    var url="member_signin.php?member_id="+member_id;
    win=window.open(url,"MemberSignin",
       'width=760,height=760,top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=yes,directories=no,status=no,menubar=yes,toolbar=yes,resizable=yes');
    win.focus();
  }

function toggle(obj,inx)
  {
    var id="DOMAIN-"+inx;
    var el=document.getElementById(id);
    if (el)
      {
        if (el.style.display=='none')
          {
            el.style.display='';
            obj.style.backgroundColor="#FFA000";
          }
        else
          {
            el.style.display='none';
            obj.style.backgroundColor="#FFFFFF";
          }
      }
  }


function mouse_over(obj)
  {
    obj.style.cursor='pointer';
    obj.style.backgroundColor="#FFA000";
    obj.style.color          ="#000000";
  }
function mouse_out(obj)
  {
    obj.style.cursor='text';
    obj.style.backgroundColor="#FFFFFF";
    obj.style.color          ="#990000";
  }

</script>
</head>

<body>
<div align="left">
<table border="0" cellpadding="0" cellspacing="0" width="1024">
  <tr>
    <td width="20%" align="center" class="normaldarkbluebold">Reports</td>
    <td width="60%"><p align="center"><font face="Arial" color="#FF0000"><big><strong><big><strong>
    PushyAds</strong></big></strong></em></big></font><br>
    <font face="Arial" color="#0000A0"><strong>
    PushyAds Administration</strong></font></td>

    <td width="20%">
       <span class="smalldarkredbold">DATE:&nbsp;&nbsp;</span>
       <span class="smallbold"><?php echo getDateToday()?></span><br>
       <span class="smalldarkredbold">TIME:&nbsp;&nbsp;</span>
       <span class="smallbold"><?php echo getTimeNow()?></span>
    </td>
  </tr>
</table>

<br>&nbsp;

<table border="0" cellpadding="0" cellspacing="0" width="1207" class="arial">
   <tr>
     <td width=1 bgcolor="#000000">&nbsp;</td>
     <td width=200 class="category_header">Category</td>

     <?php
     for ($i=1; $i<=5; $i++)
       {
         $wstart=$week_start_dates[$i];
         if ($i==5)
            $cls="current_week";
         else
            $cls="prior_week";
     ?>
         <td width=1 bgcolor="#000000">&nbsp;</td>
         <td colspan=7 class="<?php echo $cls?>"><?php echo $wstart?></td>
     <?php
       }
     ?>

     <td width=1 bgcolor="#000000">&nbsp;</td>
   </tr>

   <tr>
     <td width=1 bgcolor="#000000">&nbsp;</td>
     <td width=200 class="category_header">&nbsp;</td>

     <?php
       for ($i=0; $i<5; $i++)
         {
           echo "  <td width=1 bgcolor=\"#000000\">&nbsp;</td>\n";
           for ($j=0; $j<7; $j++)
             {
               echo "  <td align=center style=\"font-weight:bold; background:#CCCC88\">".substr($day_names[$j],0,1)."</td>\n";
             }
         }
      ?>
     <td width=1 bgcolor="#000000">&nbsp;</td>
   </tr>


  <?php
    asort($ProductCategories);
    foreach ($ProductCategories AS $cat => $ctitle)
      {
        if (isset($category_summary[$cat]))
          $category_data = $category_summary[$cat];
        else
          $category_data = $category_none;

        $hits   = $category_data["hits"];
        $clicks = $category_data["clicks"];

        echo "<tr>\n";
          echo "  <td width=1 bgcolor=\"#000000\">&nbsp;</td>\n";
          echo "  <td class=\"arial size14\">&nbsp;$ctitle</td>\n";

          $inx=0;
          for ($i=0; $i<5; $i++)
            {
              echo "  <td width=1 bgcolor=\"#000000\">&nbsp;</td>\n";
              for ($j=0; $j<7; $j++)
                {
                  $h = $hits[$inx+$j];
                  $c = $clicks[$inx+$j];
                  if ($h==0 && $c==0)
                    $data=$no_data;
                  else
                    $data=$h."/".$c;
                  echo "  <td align=center>$data</td>\n";
                }
              $inx+=7;
            }

        echo "  <td width=1 bgcolor=\"#000000\">&nbsp;</td>\n";
        echo "</tr>\n";
      }
  ?>

</table>

</div>
</body>
</html>
