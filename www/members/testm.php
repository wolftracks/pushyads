<?php

//  http://pushyads.local/members/testx.php?mid=vrg1980
//  http://pushyads.local/members/testx.php?mid=axm2086


include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");
include_once("pushy_tree.inc");
include_once("pushy_imagestore.inc");
include_once("pushy_tracker.inc");


$dates = tracker_dates();
$currentWeekStartDate=$dates[5];    // Current Week Start: Sunday
$priorWeekStartDate  =$dates[4];    // Prior   Week Start: Sunday




//  ath2449v          | 052eb3b3de5ad70975243a977abb208b
//  ath2449v          | 562403943edbf60e18d2d7ded95fb264
//  ath2449v          | dd6dc351e898a495bff3ce0b74eb1be0
//  ath2449v          | e3503009b515b3d9ea016114d20a20ee
//  axm2086           | a58bcc6367d057782fff9d356083998c
//  axm2086           | dab109d8e5f59199b601058c61f3e3c4
//  ckb2649           | 9d211f0adf291866795d668a10b2ab63
//  d1888yj           | 45a79b12ab5808f84384a79aab347e5c
//  d1888yj           | b8d09674bd9c1efb83527918dfa72b26
//  d2602gxh          | 2cd5a154a395d54ae6c82dd98aaa925e
//  d2602gxh          | 6556df340822cabc8a2f89e976d2cb4b
//  d2602gxh          | 953d54d70a163531d60c63cf572de5f1
//  d2602gxh          | 9683acef812a78aa229739543e5a5a63
//  d2602gxh          | aa59368f32cd25435d0e0025f135b844
//  d2602gxh          | aabcd2dbf47c5ef54c173f0e6a043c76
//  d2602gxh          | b735388f3958bbaf299774818a4d217d
//  d2602gxh          | da65287cf6084e705ec40700ad09d234
//  d2602gxh          | df8562e2942db3848eb2c756ba651733
//  d2602gxh          | fcdd83d8deccd4852f2f8edc2063993f
//  jab1667c          | 9f6c89311084ee5bb727eabb182d4dde
//  m2781pc           | 55b1036235b622cf2791052315cced3d
//  mg3               | 26213ed0e6e4c3fad8d462b5fbcf51f1
//  pushy             | 6d5fb8fd2efcecfd1375c62455142e5e
//  pushy             | e1101b3abd093bdc0844c4266ac198f1
//  pushy             | ef196b337042c814eb7e2d8e38ef9d49
//  rve2366           | 8a3ac90c2b2576809541b3b1566b498f
//  t1379cmw          | 8099ef9b495aded17a917f5adf4d2d6a
//  t1379cmw          | 8a97a4a6282e309804c5e2fea850c5b1
//  t1379cmw          | c0ab46314fa3c2e5a7b10e78e3066cde
//  t1379cmw          | eb5e945717265e532eb9d48129cfb2c6




$_fn_="a58bcc6367d057782fff9d356083998c";


$db = getPushyDatabaseConnection();

$DEBUG=TRUE;

$AD_COUNT=10;

function selectPushyAds($db,$members,$categories)
 {
   global $PUSHY_ROOT;
   global $PUSHY_LEVEL_VIP;
   global $PUSHY_LEVEL_PRO;
   global $PUSHY_LEVEL_ELITE;

   $productList = array();

   if (is_array($members) && count($members)>0)
     {
       // OK
     }
   else
     return $productList;

   if (is_array($categories) && count($categories)>0)
     {
       // OK
     }
   else
     return $productList;


   $sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
   $sql .= " WHERE (";

   for ($j=0; $j<count($members); $j++)
     {
       $member=$members[$j];
       $member_id =$member["member_id"];
       $user_level=$member["user_level"];
       if ($j<2)
         {
           // DO NOT Consider Category
           if ($user_level == $PUSHY_LEVEL_ELITE)
              $sql .= " (ads.member_id='$member_id' AND ads.pushy_network > 0) ";
           else
              $sql .= " (ads.member_id='$member_id') ";
         }
       else
         {
           // Consider Category
           if ($j==2)
             {
               $sql .= " ( (";
               for ($n=0; $n<count($categories); $n++)
                 {
                   $sql .= " product.product_categories LIKE '%~".$categories[$n]."~%' ";
                   if ($n+1 < count($categories))
                      $sql .= " OR ";
                   else
                      $sql .= " ) ";
                 }
               $sql .= " AND ( ";
             }

           if ($user_level == $PUSHY_LEVEL_ELITE)
              $sql .= " (ads.member_id='$member_id' AND ads.pushy_network > 0) ";
           else
              $sql .= " (ads.member_id='$member_id') ";
         }
       if ($j+1 < count($members))
          $sql .= " OR ";
     }
   $sql .= ") ) )";
   $result = mysql_query($sql,$db);

   // printf("SQL:%s\n",$sql);
   // printf("ERR:%s\n",mysql_error());
   // exit;

   if ($result && (($count=mysql_num_rows($result))>0))
     {
       while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
         {
           $member_id=$myrow["member_id"];
           $productList[$member_id]=$myrow;
         }
     }

// print_r($members);
// print_r($productList);

   return $productList;
 }







// CREATE TABLE credit_map (
//   member_id varchar(20) default '',
//   credits_allocated int(11) NOT NULL default '0',
//   credits_awarded int(11) NOT NULL default '0',
//   last_awarded bigint(20) NOT NULL default '0'
// ) TYPE=MyISAM;

function selectCreditAdPool($db,$excludeProductIds,$excludeProductNames)
 {
   global $PUSHY_ROOT;
   global $PUSHY_LEVEL_VIP;
   global $PUSHY_LEVEL_PRO;
   global $PUSHY_LEVEL_ELITE;

   $productSelected=FALSE;

   // mysql_query("LOCK TABLES ads WRITE, product WRITE",$db);

   $sql  = "SELECT * from ads LEFT JOIN credit_map USING(member_id) LEFT JOIN member USING(member_id) LEFT JOIN product USING(product_id) ";
   $sql .= " WHERE ads.member_id != '$PUSHY_ROOT'";
   $sql .= " AND   credit_map.credits_awarded < credit_map.credits_allocated ";
   $sql .= " AND   ((member.user_level='$PUSHY_LEVEL_PRO') OR (member.user_level='$PUSHY_LEVEL_ELITE' AND ads.pushy_network>0)) ";

   if (count($excludeProductIds) > 0)
     {
       $sql .= " AND ( ";
       for ($j=0; $j<count($excludeProductIds); $j++)
         {
           $product_id=$excludeProductIds[$j];
           $sql .= " product.product_id != '$product_id' ";
           if ($j+1 < count($excludeProductIds))
              $sql .= " AND ";
         }
       $sql .= " ) ";
     }

   if (count($excludeProductNames) > 0)
     {
       $sql .= " AND ( ";
       for ($j=0; $j<count($excludeProductNames); $j++)
         {
           $product_name=$excludeProductNames[$j];
           $sql .= " product.product_name != '$product_name' ";
           if ($j+1 < count($excludeProductNames))
              $sql .= " AND ";
         }
       $sql .= " ) ";
     }

   $sql .= " ORDER BY credit_map.last_awarded ASC, credit_map.credits_allocated DESC";
   $sql .= " LIMIT 1";

   $result = mysql_query($sql,$db);

   // printf("SQL:%s\n",$sql);
   // printf("ERR:%s\n",mysql_error());
   // printf("ROWS%s\n",mysql_num_rows($result));

   if ($result && (($count=mysql_num_rows($result))>0))
     {
       if ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
         {
           $member_id = $myrow["member_id"];
           $productSelected=$myrow;

           //------------------------------------------------
           // $user_level    = $myrow["user_level"];
           // $ad_id         = $myrow["ad_id"];
           // $product_id    = $myrow["product_id"];
           // $product_name  = $myrow["product_name"];
           //
           // $credits_allocated = $myrow["credits_allocated"];
           // $credits_awarded   = $myrow["credits_awarded"];
           //
           // $product_title = stripslashes($myrow["product_title"]);
           //
           // printf(" %-12s  Allocated:%d  Awarded:%d  =>  (%s)  (%s)  %s\n",$member_id,$credits_allocated,$credits_awarded,$ad_id,$product_id,$product_title);
           //------------------------------------------------

           $remaining=0;
           $allocated=0;
           $awarded=0;
           $last_awarded=getmicroseconds();

           $sql = "UPDATE credit_map set last_awarded='$last_awarded', credits_awarded=credits_awarded+1 WHERE member_id='$member_id'";
           $res = mysql_query($sql,$db);

           // printf("SQL: %s\n",$sql);
           // printf("ERR: %s\n",mysql_error());
           // printf("AFFECTED: %d\n",mysql_affected_rows());

           $sql = "SELECT COUNT(*),SUM(credits_allocated),SUM(credits_awarded) from credit_map WHERE credits_awarded < credits_allocated";
           $res = mysql_query($sql,$db);
           if ($res && ($myrow = mysql_fetch_array($res)))
             {
               $remaining=(int)$myrow[0];
               $allocated=(int)$myrow[1];
               $awarded  =(int)$myrow[2];
             }

           // printf("SQL: %s\n",$sql);
           // printf("ERR: %s\n",mysql_error());
           // printf("REMAINING: %d\n",$remaining);
           // printf("ALLOCATED: %d\n",$allocated);
           // printf("AWARDED:   %d\n",$awarded);

           if ($remaining==0)
             {
               $sql = "UPDATE credit_map set credits_awarded=0";
               $res = mysql_query($sql,$db);
             }
         }
     }

   if (!is_array($productSelected))
     {
       $sql = "UPDATE credit_map set credits_awarded=0";
       $res = mysql_query($sql,$db);
     }

   mysql_query("UNLOCK TABLES",$db);

   if (!is_array($productSelected))
     {
       return FALSE;
     }
   return($productSelected);
 }



function selectEliteAdPool($db,$excludeProductIds,$excludeProductNames)
 {
   global $PUSHY_ROOT;
   global $PUSHY_LEVEL_ELITE;

   $productSelected=FALSE;

   $sql  = "SELECT * from ads LEFT JOIN member USING(member_id) LEFT JOIN product USING(product_id) ";
   $sql .= " WHERE member.user_level='".$PUSHY_LEVEL_ELITE."' ";
   $sql .= " AND   member.member_disabled=0 ";
   $sql .= " AND   ads.elite_pool>0";

   if (count($excludeProductIds) > 0)
     {
       $sql .= " AND ( ";
       for ($j=0; $j<count($excludeProductIds); $j++)
         {
           $product_id=$excludeProductIds[$j];
           $sql .= " product.product_id != '$product_id' ";
           if ($j+1 < count($excludeProductIds))
              $sql .= " AND ";
         }
       $sql .= " ) ";
     }

   if (count($excludeProductNames) > 0)
     {
       $sql .= " AND ( ";
       for ($j=0; $j<count($excludeProductNames); $j++)
         {
           $product_name=$excludeProductNames[$j];
           $sql .= " product.product_name != '$product_name' ";
           if ($j+1 < count($excludeProductNames))
              $sql .= " AND ";
         }
       $sql .= " ) ";
     }

   //---- NEXT Line is for ROTATION over RANDOM
   $sql .= " ORDER BY ads.lastview_pushy, ads.ad_id LIMIT 1";

   $result = mysql_query($sql,$db);

   // printf("SQL:%s\n",$sql);
   // printf("ERR:%s\n",mysql_error());
   // printf("ROWS%s\n",mysql_num_rows($result));

   if ($result && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
     {
       $productSelected=$myrow;
       $viewed = getmicroseconds();
       $sql    = "UPDATE ads set lastview_pushy='$viewed' WHERE ad_id='".$productSelected["ad_id"]."' ";
       $res = mysql_query($sql,$db);
     }

   if (!is_array($productSelected))
     {
       return FALSE;
     }
   return $productSelected;
 }




printf("<PRE>\n");

$widget = getWidget($db, $_fn_); // returns FALSE if hash not found || User Not Enabled || Widget Not Enabled
if (!is_array($widget))
  {
    printf("Widget Not Found: Hash='$_fn_'");
    exit;
  }

$member_id        = $widget["member_id"];

$WidgetScroller   = $widget["_pushy_scroller_"];   // This is a SYSTEM LEVEL Setting Only  - Honored only for user='pushy'  (PUSHY_ROOT)

$WidgetKey        = $widget["widget_key"];
$WidgetDomain     = $widget["domain"];
$WidgetCategories = $widget["widget_categories"];


$categories=array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      $categories[]=$tarray[$i];
  }

printf("CATEGORIES=%s\n%s\n",$WidgetCategories,print_r($categories,TRUE));



$options = array(
   "minlevel"  =>  $PUSHY_LEVEL_VIP,
   "limit"     =>  1,
   "order"     =>  "Desc"
);


$uplineCount=0;
$rootFound=FALSE;
$upline=array();

$memberRecord=getMemberInfo($db,$member_id);
$upline[0]=$memberRecord;
$uplineCount++;
printf("site_owner = %s\n",$member_id);
$last_member_id=$member_id;


$res = tree_getUpline($db, $member_id, $options);
if (is_array($res))
  {
    for ($i=0; $i<count($res); $i++)
      {
        if ($res[$i]["member_id"]==$PUSHY_ROOT)
          {
            $rootFound=TRUE;
            break;
          }

        $upline[$uplineCount]=$res[$i];
        $last_member_id=$upline[$uplineCount]["member_id"];
        printf("sponsor_id = %s\n",$last_member_id);
        $uplineCount++;
      }
  }

printf("----\n");

if ($rootFound == FALSE)
  {
     $options = array(
        "minlevel"  =>  $PUSHY_LEVEL_PRO,
        "limit"     =>  4,
        "order"     =>  "Desc"
     );

     $res = tree_getUpline($db, $last_member_id, $options);
     if (is_array($res))
       {
         for ($i=0; $i<count($res); $i++)
           {
             if ($res[$i]["member_id"]==$PUSHY_ROOT)
               {
                 $rootFound=TRUE;
                 break;
               }

             $upline[$uplineCount]=$res[$i];

             $last_member_id=$upline[$uplineCount]["member_id"];
             printf("upline_id  = %s\n",$last_member_id);

             $uplineCount++;

           }
       }
  }


$pushyAds = selectPushyAds($db,$upline,$categories);
$ads=array();
$members=array();
$product_ids=array();
$product_names=array();
for ($i=0; $i<count($upline); $i++)
  {
    $member_id=$upline[$i]["member_id"];
    if (isset($pushyAds[$member_id]))
      {
        $ad = $pushyAds[$member_id];
        $product_id   = $ad["product_id"];
        $product_name = strtolower($ad["product_name"]);

        if (isset($product_ids[$product_id]) || isset($product_names[$product_name]))
          {                // CHECK FOR DUPLICATES - product_id or product_name
            $ads[$i]     = FALSE;
            $members[$i] = $upline[$i];
          }
        else
          {
            $ads[$i]     = $pushyAds[$member_id];
            $members[$i] = $upline[$i];
            $product_ids[$product_id]     = TRUE;
            $product_names[$product_name] = TRUE;
          }
      }
    else
      {
        $ads[$i]     = FALSE;
        $members[$i] = $upline[$i];
      }
  }

// print_r($ads);
// print_r($members);
// exit;

printf("\n\n");
for ($i=0; $i<count($ads); $i++)
  {
    if (is_array($ads[$i]))
      {
        $member_id   = $ads[$i]["member_id"];
        $member_name = stripslashes($members[$i]["firstname"])." ".stripslashes($members[$i]["lastname"]);
        printf(" (#%d)  %-12s   %-20s   =>  (A-%-6s) (P-%-6s)   %s\n", $i+1,$member_id,$member_name,$ads[$i]["ad_id"],$ads[$i]["product_id"],$ads[$i]["product_title"]);
      }
    else
      {
        $productSelected = selectCreditAdPool($db,array_keys($product_ids),array_keys($product_names));
        if (!is_array($productSelected))
          {
            $productSelected = selectCreditAdPool($db,array_keys($product_ids),array_keys($product_names));
          }
        if (!is_array($productSelected))
          {
            $productSelected = selectCreditAdPool($db,array_keys($product_ids),array_keys($product_names));
          }
        if (is_array($productSelected))
          {
            $product_id   = $productSelected["product_id"];
            $product_name = strtolower($productSelected["product_name"]);
            $ads[$i]      = $productSelected;
            $members[$i]  = $productSelected;     // contains Credit Ad Pool Member Info
            $product_ids[$product_id]     = TRUE;
            $product_names[$product_name] = TRUE;

            $member_id   = $productSelected["member_id"];
            $member_name = stripslashes($productSelected["firstname"])." ".stripslashes($productSelected["lastname"]);
            printf(" (#%d)  %-12s   %-20s   =>  (A-%-6s) (P-%-6s)   %s\n", $i+1,$member_id,$member_name,$ads[$i]["ad_id"],$ads[$i]["product_id"],$ads[$i]["product_title"]);

          }
        else
          {
            $member_id   = $members[$i]["member_id"];
            $member_name = stripslashes($members[$i]["firstname"])." ".stripslashes($members[$i]["lastname"]);
            printf(" (#%d)  -none-  for   %-12s   %-20s\n",$i+1,$member_id,$member_name);
          }
      }
  }


//---------------------------  Elite Pool Ad  --------------------------------------

$inx = 6;
$productSelected = selectEliteAdPool($db,array_keys($product_ids),array_keys($product_names));
if (is_array($productSelected))
  {
    $product_id     = $productSelected["product_id"];
    $product_name   = strtolower($productSelected["product_name"]);
    $ads[$inx]      = $productSelected;
    $members[$inx]  = $productSelected;     // contains Credit Ad Pool Member Info
    $product_ids[$product_id]     = TRUE;
    $product_names[$product_name] = TRUE;

    $member_id   = $productSelected["member_id"];
    $member_name = stripslashes($productSelected["firstname"])." ".stripslashes($productSelected["lastname"]);
    printf(" (#%d)  %-12s   %-20s   =>  (A-%-6s) (P-%-6s)   %s\n", $i+1,$member_id,$member_name,$ads[$inx]["ad_id"],$ads[$inx]["product_id"],$ads[$inx]["product_title"]);
  }



//printf("==============================================\n");
//printf("Product Selected:\n");
//print_r($productSelected);

printf("</PRE>\n");
exit;
?>
