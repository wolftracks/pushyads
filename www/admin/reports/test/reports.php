<?php
include_once("pushy_constants.inc");
include_once("pushy_common.inc");
include_once("pushy.inc");
include_once("pushy_commonsql.inc");

$MAX_RECORDS = 300;

function direct_referrals($db,$member_id)
 {
   $sql  = "SELECT count(*) from member";
   $sql .= " WHERE refid='$member_id'";
   $sql .= " AND registered>0";
   $sql .= " AND system=0";
   $result=mysql_query($sql,$db);
   if ($result && ($myrow=mysql_fetch_array($result)))
     {
       return (int) $myrow[0];
     }
   return 0;
 }


function getUniqueDomainsForUser($db,$member_id)
  {
    $domains=array();
    $sql    = "SELECT domain from widget";
    $sql   .= " WHERE member_id='$member_id'";

    $sql .= " AND date_first_access!=''";

    $result = mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
          {
            $domain = $myrow["domain"];
            if (isset($domains[$domain]))
               $domains[$domain]++;
            else
               $domains[$domain]=1;
          }
      }
    return $domains;
  }
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css" />
<title>PushyAds Administration - Who Is</title>
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
<table border="0" cellpadding="0" cellspacing="0" width="760">
  <tr>
    <td width="20%" align="center" class="normaldarkbluebold">WHO IS<br><span class=smalldarkblue>Member Locator</span></td>
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
<br>
<form name=MEMBER_SELECTION>
<input type="hidden" name="current_filter_type"   value="<?php echo $filter_type?>">
<input type="hidden" name="current_filter_value"  value="<?php echo $filter_value?>">
<input type="hidden" name="current_filter_query"  value="<?php echo $filter_query?>">
<input type="hidden" name="current_sort"          value="<?php echo $sort?>">
<table border="0" cellpadding="0" cellspacing="0" width="760" bgcolor="#E0E0FF">
  <tr height=30 valign="middle">
    <td align=left width="20%"> &nbsp;
      <select style="width:120px" name="filter_type" class=smalltext><option value="null"> -- Filter On -- </option>
        <option value="lastname"     <?php echo ($filter_type=="lastname")?"selected":""?>  > Name </option>
        <option value="email"        <?php echo ($filter_type=="email")?"selected":""?>     > Email (Signin) </option>
        <option value="member_id"    <?php echo ($filter_type=="member_id")?"selected":""?> > Member Id </option>
        <option value="affiliate_id" <?php echo ($filter_type=="affiliate_id")?"selected":""?> > Affiliate Id </option>
      </select>
    </td>
    <td align=left width="40%">
      <input class="smalltext" name="filter_value" type=text size=30 value="<?php echo $filter_value?>"> &nbsp;<span class=tinytext>* = any</span>
    </td>
    <td align=left width="30%">
       <a href=javascript:submit_query('equals')>Equals</a> &nbsp; &nbsp;
       <a href=javascript:submit_query('contains')>Contains</a> &nbsp; &nbsp;
       <a href=javascript:submit_query('startswith')>Starts With</a> &nbsp; &nbsp;
    </td>
    <td align=right width="10%">
       <a href=javascript:allMembers()>All</a> &nbsp; &nbsp;
    </td>
  </tr>
</table>

<span align=left style="height:30px; width:760px; float:left;"><hr width=760></span>

</form>
</div>

<div style="width:100%; float:left;">
<?php
   $StatusMessage="";
   if (isset($filter_query) && strlen($filter_query) > 0)
     {
       $db = getPushyDatabaseConnection();

       $count=0;
       $sql  = "SELECT * FROM member";
       if (strlen($filter_value)>0 && $filter_value!="*")
         {
           if ($filter_query=="equals")
             $sql .= " WHERE $filter_type='$filter_value'";
           else
           if ($filter_query=="contains")
             $sql .= " WHERE $filter_type LIKE '%$filter_value%'";
           else
           if ($filter_query=="startswith")
             $sql .= " WHERE $filter_type LIKE '$filter_value%'";
         }
       if (isset($sort) && strlen($sort)>0)
         $sql .= " ORDER BY $sort";
       else
         $sql .= " ORDER BY lastname, firstname";
       $sql .= " LIMIT $MAX_RECORDS";
       $result = exec_query($sql,$db);
       if ($result)
         {
           while($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
            {
              $count++;
              if ($count==1)
                {
                  echo "<div align=\"left\"><table border=\"0\" cellspacing=\"2\" width=\"100%\" bgcolor=\"#FFFFFF\" bordercolor=\"#F2F2F2\">\n";
                  echo "<tr>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\">&nbsp;</font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\"><a href=javascript:sort_by('member_id')>MemberID</a></font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\"><a href=javascript:sort_by('user_level')>UserLevel</a></font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\"><a href=javascript:sort_by('lastname,firstname')>Member Name</a></font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\"><a href=javascript:sort_by('refid')>RefID</font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\"><a href=javascript:sort_by('email')>Email/Signin</a></font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\"><a href=javascript:sort_by('affiliate_id')>Aff-Id</font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\"><a href=javascript:sort_by('registered%20DESC')>Registered</a></font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\"><a href=javascript:sort_by('lastaccess%20DESC')>Signed-In</a></font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\">Ref</font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\">Dom</font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\">LastPayment</font></strong></small></td>\n";
                  echo "<td bgcolor=\"#C0C0C0\" align=\"left\" nowrap><small><strong><font face=\"Arial\">NextPayment</font></strong></small></td>\n";
                  echo "</tr>\n";
                }
              $member_id    = $myrow["member_id"];
              $password     = $myrow["password"];
              $affiliate_id = $myrow["affiliate_id"];
              $refid        = $myrow["refid"];
              $user_level   = $myrow["user_level"];
              $user_level_name=$UserLevels[$user_level];
              $lastaccess=$myrow["lastaccess"];
              $registered=$myrow["registered"];
              $free_trial=$myrow["free_trial"];
              $referrals=direct_referrals($db,$member_id);
              $last_payment_date = $myrow["last_payment_date"];
              if ($last_payment_date=="") $last_payment_date="&nbsp;";
              $next_payment_due  = $myrow["next_payment_due"];
              if ($next_payment_due=="")  $next_payment_due="&nbsp;";

              $mbg="#FFFFFF";
              if ($user_level==$PUSHY_LEVEL_VIP && $free_trial>0)
                $mbg="#D0D8FF";

              $domains=getUniqueDomainsForUser($db,$member_id);
              $dcount=count($domains);

              $email=$myrow["email"];
              $name=$myrow["firstname"]." ".$myrow["lastname"];
              echo "<tr>\n";
              if ($registered>0 && $lastaccess>0)
                 echo "<td bgcolor=\"#0000A0\" align=\"left\" nowrap><small><font face=\"Arial\" color=\"#FFFFFF\"><a style=\"color:white;text-decoration:none\" href=javascript:signin('$member_id','$password')><span class=\"size12 white\"> S </span></a></font></small></td>\n";
              else
                 echo "<td bgcolor=\"#FFFFFF\" align=\"left\" nowrap><small>&nbsp;</small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\"><a href=\"index.php?op=OpenMember&in_member_id=$member_id\">$member_id</a></font></small></td>\n";
              echo "<td bgcolor=\"".$mbg."\" align=\"left\"><small><font face=\"Arial\">".$user_level_name."</font></small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\">".$name."</font></small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\"><a href=\"index.php?op=OpenMember&in_member_id=$refid\">$refid</a></font></small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\">".$myrow["email"]."</font></small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\">".$myrow["affiliate_id"]."</font></small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\">"."&nbsp;".(($registered>0)?formatDate($registered)."&nbsp; &nbsp;".formatTime($registered):"- not registered -")."</font></small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\">"."&nbsp;".(($lastaccess>0)?formatDate($lastaccess)."&nbsp; &nbsp;".formatTime($lastaccess):"- not accessed -")."</font></small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"right\"><small><font face=\"Arial\">".$referrals."&nbsp;</font></small></td>\n";
              if ($dcount>0)
                echo "<td bgcolor=\"#FFFFFF\" align=\"center\"><span onClick=javascript:toggle(this,$count) onMouseOver=javascript:mouse_over(this) onMouseOut=javascript:mouse_out(this) style=\"color:#990000; font-weight:bold\">&nbsp;&nbsp;$dcount&nbsp;&nbsp;</span></td>\n";
              else
                echo "<td bgcolor=\"#FFFFFF\" align=\"center\">$dcount</td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\">".$last_payment_date."</font></small></td>\n";
              echo "<td bgcolor=\"#FFFFFF\" align=\"left\"><small><font face=\"Arial\">".$next_payment_due."</font></small></td>\n";
              echo "</tr>\n";

              $html="";
              if ($dcount>0)
                {
                  $html .= "<tr id=\"DOMAIN-$count\" style=\"display:none\"><td colspan=7>&nbsp;</td><td colspan=4 align=right bgcolor=\"#FFFA000\">\n";
                     foreach($domains AS $dom=>$occur)
                       {
                         $html .= "<a href=javascript:openPopup('http://$dom',600,600,true,true)>http://$dom</a> &nbsp; &nbsp;($occur)&nbsp;<br>\n";
                       }
                  $html .= "</td></tr>\n";
                  echo $html;
                }
            }




           if ($count > 0)
             {
               echo "</table></div></body></html>\n";
               exit;
             }
           else
             {
               $StatusMessage = "Member Not Found";
             }
         }
     }
?>
</div>
</body>
</html>
