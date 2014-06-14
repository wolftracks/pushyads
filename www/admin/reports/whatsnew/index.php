<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$DAYS = 1;
if (isset($_REQUEST["DAYS"]) && isNumeric($_REQUEST["DAYS"]))
  $DAYS = (int) $_REQUEST["DAYS"];

$todayArray = getDateTodayAsArray();
$today      = getDateToday();
$todaymm    = $todayArray["month"];
$todaydd    = $todayArray["day"];
$todayyy    = $todayArray["year"];

$target     = calStepDays(-$DAYS,$todayArray);

$targetmm  = $target["month"];
$targetdd  = $target["day"];
$targetyy  = $target["year"];

$today_description  = sprintf("%s %d, %s",$month_names[$todaymm-1],$todaydd,$todayyy);
$target_description = sprintf("%s %d, %s",$month_names[$targetmm-1],$targetdd,$targetyy);

$target_date  = dateArrayToString($target);

$db=getPushyDatabaseConnection();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css" />

<script type="text/javascript">
</script>

<title>Whats New</title>
</head>

<body class="smallmono">
<span class="bold size14 darkblue arial">Date Today: &nbsp;</span>
<span class="bold size14 darkred arial"><?php echo $today_description?></span><br>

<span class="bold size14 darkblue arial">New &nbsp;Since: &nbsp;</span>
<span class="bold size14 darkred arial"><?php echo $target_description?></span><br>

<form method=GET action=index.php>
   <span class="bold size14 arial">DAYS: &nbsp;</span>
   <input type=text size=4 name=DAYS value="<?php echo $DAYS?>" />
   &nbsp;
   <input type="submit" class="button" value="  GO  " />  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;(0 = Today Only)
</form>
<PRE>

<?php

//============================= NEW MEMBERS
printf("<span class=\"bold darkred\">-- NEW MEMBERS --</span>\n\n");
$new_members=0;

$sql  = "SELECT * from member";
$sql .= " WHERE record_created  >= '$target_date'";
$sql .= " ORDER BY confirmed DESC, registered DESC, lastaccess desc";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result))
      {
        $member_id    = $myrow["member_id"];
        $firstname    = stripslashes($myrow["firstname"]);
        $lastname     = stripslashes($myrow["lastname"]);
        $fullname     = $firstname." ".$lastname;
        $user_level   = $myrow["user_level"];
        $email        = $myrow["email"];

        if ($myrow["confirmed"]==0 ||
            $myrow["registered"]==0)
           $user_level_name = "  -  ";
        else
        if ($user_level==$PUSHY_LEVEL_ELITE)
           $user_level_name = $UserLevels[$user_level];
        else
           $user_level_name = " ".$UserLevels[$user_level]." ";

        $confirmed    = ($myrow["confirmed"]==0)?"   No":"   Yes";
        $registered   = ($myrow["registered"]==0)?"   No"      :formatDate($myrow["registered"]);
        $lastaccess   = ($myrow["lastaccess"]==0)?" -Never-" :formatDate($myrow["lastaccess"]);

        $new_members++;
        if ($new_members==1)
          {
             printf("<span class=\"bold blue\">%-4s  %-10s  %-28s  %-5s  %-10s  %-10s  %-10s  %s</span>\n",
                "","MEMBER ID","MEMBER NAME","LEVEL",
                "CONFIRMED", "REGISTERED", "SIGNIN", "EMAIL");
          }

        printf("%-4s  %-10s  %-28s  %-5s  %-10s  %-10s  %-10s  %s\n",
           "",$member_id,$fullname,$user_level_name,
           $confirmed, $registered, $lastaccess, $email);

      }
  }


//============================= NEW WIDGETS
//widget_key varchar(64) NOT NULL default '',
//enabled int(11) NOT NULL default '0',
//member_id varchar(20) NOT NULL default '',
//_pushy_scroller_ varchar(48) NOT NULL default '',
//refid varchar(20) default '',
//widget_name varchar(32) NOT NULL default '',
//domain varchar(64) NOT NULL default '',
//widget_id int(11) NOT NULL default '0',
//widget_categories varchar(256) NOT NULL default '',
//width int(11) NOT NULL default '0',
//height int(11) NOT NULL default '0',
//posture int(11) NOT NULL default '0',
//motion int(11) NOT NULL default '0',
//transition int(11) NOT NULL default '0',
//origin int(11) NOT NULL default '0',
//speed int(11) NOT NULL default '0',
//wiggle int(11) NOT NULL default '0',
//delay int(11) NOT NULL default '0',
//pause int(11) NOT NULL default '0',
//style int(11) NOT NULL default '0',
//date_created varchar(10) NOT NULL default '',
//date_last_modified varchar(10) NOT NULL default '',
//date_first_access varchar(10) NOT NULL default '',
//date_last_access varchar(10) NOT NULL default '',
//weekly_access_count bigint(20) NOT NULL default '0',
//total_access_count bigint(20) NOT NULL default '0',
//uri varchar(96) default '',


printf("\n\n\n<span class=\"bold darkred\">-- NEW PUSHY WIDGETS --</span>\n\n");
$new_widgets=0;

$sql  = "SELECT * from widget";
$sql .= " WHERE date_created >= '$target_date'";
$sql .= " ORDER BY date_created DESC, member_id";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result))
      {
        $member_id     = $myrow["member_id"];
        $memberRecord=getMemberInfo($db,$member_id);

        $member_id     = $memberRecord["member_id"];
        $firstname     = stripslashes($memberRecord["firstname"]);
        $lastname      = stripslashes($memberRecord["lastname"]);
        $fullname      = $firstname." ".$lastname;
        $user_level    = $memberRecord["user_level"];

        $widget_name        = $myrow["widget_name"];
        $total_access_count = $myrow["total_access_count"];
        $date_last_access   = $myrow["date_last_access"];
        $uri                = $myrow["uri"];

        if ($user_level==$PUSHY_LEVEL_ELITE)
           $user_level_name = $UserLevels[$user_level];
        else
           $user_level_name = " ".$UserLevels[$user_level]." ";

        $new_widgets++;
        if ($new_widgets==1)
          {
             printf("<span class=\"bold blue\">%-4s  %-10s  %-28s  %-5s  %6s  %-20s  %-11s  %-20s</span>\n",
                "","MEMBER ID","MEMBER NAME","LEVEL",
                "COUNT", "WIDGET NAME", "LAST ACCESS", "LAST URL");
          }

        printf("%-4s  %-10s  %-28s  %-5s  %6d  %-20s  %-11s  %s\n",
           "",$member_id,$fullname,$user_level_name,
           $total_access_count, $widget_name, $date_last_access, $uri);

      }
  }



//============================= NEW PRODUCTS

//product_id bigint(20) NOT NULL auto_increment,
//product_owner varchar(20) default '',
//org_product_owner varchar(20) default '',
//product_approved int(11) NOT NULL default '0',
//product_submit_date varchar(10) NOT NULL default '',
//media_type int(11) NOT NULL default '0',
//media_format varchar(6) NOT NULL default '',
//media_width int(11) NOT NULL default '0',
//media_height int(11) NOT NULL default '0',
//media_size int(11) NOT NULL default '0',
//media_original_width int(11) NOT NULL default '0',
//media_original_height int(11) NOT NULL default '0',
//media_server int(11) NOT NULL default '0',
//media_file varchar(32) default '',
//product_name varchar(32) default '',
//product_title varchar(64) default '',
//product_categories varchar(256) default '',
//product_description varchar(512) default '',



printf("\n\n\n<span class=\"bold darkred\">-- NEW PRODUCTS --</span>\n\n");
$new_products=0;

$sql  = "SELECT * from product";
$sql .= " WHERE product_submit_date >= '$target_date'";
$sql .= " ORDER BY product_submit_date DESC, product_owner";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result))
      {
        $product_owner = $myrow["product_owner"];
        $product_id    = $myrow["product_id"];
        $memberRecord=getMemberInfo($db,$product_owner);

        $member_id     = $memberRecord["member_id"];
        $firstname     = stripslashes($memberRecord["firstname"]);
        $lastname      = stripslashes($memberRecord["lastname"]);
        $fullname      = $firstname." ".$lastname;
        $user_level    = $memberRecord["user_level"];

        $product_name  = stripslashes($myrow["product_name"]);
        $product_title = stripslashes($myrow["product_title"]);
        $product_submit_date = stripslashes($myrow["product_submit_date"]);

        if ($user_level==$PUSHY_LEVEL_ELITE)
           $user_level_name = $UserLevels[$user_level];
        else
           $user_level_name = " ".$UserLevels[$user_level]." ";

        $new_products++;
        if ($new_products==1)
          {
             printf("<span class=\"bold blue\">%-4s  %-10s  %-10s  %-28s  %-5s  %7s  %-20s  %-20s</span>\n",
                "","CREATED","MEMBER ID","MEMBER NAME","LEVEL",
                "PROD_ID", "PRODUCT_NAME", "PRODUCT_TITLE");
          }

        printf("%-4s  %-10s  %-10s  %-28s  %-5s  %7s  %-20s  %-20s\n",
           "",$product_submit_date,$member_id,$fullname,$user_level_name,
           $product_id, $product_name, $product_title);

      }
  }




//============================= NEW ADS

//ad_id bigint(20) NOT NULL auto_increment,
//date_created varchar(10) default '',
//last_modified varchar(10) default '',
//member_id varchar(20) default '',
//pushy_network int(11) NOT NULL default '0',
//elite_bar int(11) NOT NULL default '0',
//elite_pool int(11) NOT NULL default '0',
//product_list int(11) NOT NULL default '0',
//reseller_listing int(11) NOT NULL default '0',
//product_id bigint(20) NOT NULL default '0',
//product_url varchar(96) NOT NULL default '',
//affiliate_signup_url varchar(96) default '',
//lastview_pushy bigint(20) NOT NULL default '0',
//lastview_elitebar bigint(20) NOT NULL default '0',
//existing_products_requested int(11) NOT NULL default '0',


printf("\n\n\n<span class=\"bold darkred\">-- NEW ADS --                          (PN=Pushy Network, EB=Elite Bar, EP=Elite Pool, AO=Affiliate Offer)</span>\n\n");
$new_ads=0;

$sql  = "SELECT * from ads JOIN product USING(product_id)";
$sql .= " WHERE date_created  >= '$target_date'";
$sql .= " OR    last_modified >= '$target_date'";
$sql .= " ORDER BY date_created DESC, member_id";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result))
      {
        $ad_id         = $myrow["ad_id"];
        $product_id    = $myrow["product_id"];
        $product_owner = $myrow["product_owner"];
        $member_id     = $myrow["member_id"];
        $date_created  = $myrow["date_created"];
        $last_modified = $myrow["last_modified"];
        $memberRecord=getMemberInfo($db,$member_id);

        $member_id     = $memberRecord["member_id"];
        $firstname     = stripslashes($memberRecord["firstname"]);
        $lastname      = stripslashes($memberRecord["lastname"]);
        $fullname      = $firstname." ".$lastname;
        $user_level    = $memberRecord["user_level"];

        $product_name  = stripslashes($myrow["product_name"]);
        $product_title = stripslashes($myrow["product_title"]);
        $isOwner       = ($member_id==$product_owner)?"YES":"NO";

        $placement_options= " PN EB EP AO";
        $placement="";
        if ($myrow["pushy_network"]) $placement.="  Y";   else  $placement.="   ";
        if ($myrow["elite_bar"])     $placement.="  Y";   else  $placement.="   ";
        if ($myrow["elite_pool"])    $placement.="  Y";   else  $placement.="   ";
        if ($myrow["product_list"])  $placement.="  Y";   else  $placement.="   ";


        if ($user_level==$PUSHY_LEVEL_ELITE)
           $user_level_name = $UserLevels[$user_level];
        else
           $user_level_name = " ".$UserLevels[$user_level]." ";

        $new_ads++;
        if ($new_ads==1)
          {
             printf("<span class=\"bold blue\">%-4s  %-10s  %-10s  %-28s  %-5s  %7s  %7s  %3s  %12s   %-20s  %-20s</span>\n",
                "","MODIFIED","MEMBER ID","MEMBER NAME","LEVEL",
                "AD_ID", "PROD_ID", "OWN", $placement_options, "PRODUCT_NAME", "PRODUCT_TITLE");
          }

        printf("%-4s  %-10s  %-10s  %-28s  %-5s  %7s  %7s  %3s  %12s   %-20s  %-20s\n",
           "",$last_modified,$member_id,$fullname,$user_level_name,
           $ad_id, $product_id, $isOwner, $placement, $product_name, $product_title);

      }
  }

?>

</PRE>
</body>
</html>
