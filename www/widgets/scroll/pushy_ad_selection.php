<?php
$dates = tracker_dates();
$currentWeekStartDate=$dates[5];    // Current Week Start: Sunday
$priorWeekStartDate  =$dates[4];    // Prior   Week Start: Sunday

$DEBUG_AD_SELECTION=FALSE;

if ($DEBUG_AD_SELECTION)
  {
    printf("<PRE>\n");
  }


     //--------------------------------------------------------------------------------------------------------
     // Firat ... IF THIS IS THE PUSHYADS.COM Site (Affiliate Page) ... Assign WIDGET OWNER Per RULES
     //--------------------------------------------------------------------------------------------------------
if (startsWith($referer_domain,"pushyads.")   ||
    $REMOTE_ADDR=="127.0.0.1"                 ||
    $member_id == $PUSHY_ROOT)
 {
                  //-----------------------------------------------------------------------------------------
                  // If pushyads.com icludes Explicit Affiliate Id in URL - Make that the Widget Owner
                  //-----------------------------------------------------------------------------------------
    if (strlen($referer_page) > 1 && substr($referer_page,0,1) == "/")
      $temp_aff_id = substr($referer_page,1);
    else
    if (strlen($referer_page) > 0)
      $temp_aff_id = $referer_page;

    if (strlen($temp_aff_id) > 0 && (is_array($affiliateRecord=getMemberInfoForAffiliate($db,$temp_aff_id))))
      {
        $affMemberId = $affiliateRecord["member_id"];
        $WidgetOwner = $affiliateRecord["member_id"];
        $refid       = $affiliateRecord["refid"];
      }
    else
                  //-----------------------------------------------------------------------------------------
                  // If No Affiliate Present in PushyAds URL - See if the Member has a Referral Cookie (Existing Member)
                  //-----------------------------------------------------------------------------------------
    if (isset($_COOKIE["PAREF"]) && (is_array($affiliateRecord=getMemberInfoForAffiliate($db,$_COOKIE["PAREF"]))))
      {
        $affMemberId = $affiliateRecord["member_id"];
        $WidgetOwner = $affiliateRecord["member_id"];
        $refid       = $affiliateRecord["refid"];
      }


    if (strlen($affMemberId)>0)
      {
        $member_id=$affMemberId;
      }
    else
      {
                  //-----------------------------------------------------------------------------------------
                  // Randomly Pick An Elite Member
                  //-----------------------------------------------------------------------------------------
        $sql  = "SELECT member_id from member WHERE user_level='$PUSHY_LEVEL_ELITE' AND member_id != '$PUSHY_ROOT'";
        $result = mysql_query($sql,$db);

        if ($DEBUG_AD_SELECTION)
          {
            printf("SQL:%s\n",$sql);
            printf("ERR:%s\n",mysql_error());
          }

        if ($result && (($count=mysql_num_rows($result))>0))
          {
            $skip = rand(0,$count-1);
            mysql_data_seek($result, $skip);
            if ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
              {
                $affMemberId = $myrow["member_id"];
                $member_id   = $affMemberId;
                $WidgetOwner = $WidgetOwner;
                $refid       = $myrow["refid"];
              }
          }
      }
 }






function selectPushyAds($db,$members,$categories)
 {
   global $DEBUG_AD_SELECTION;
   global $PUSHY_LEVEL_VIP;
   global $PUSHY_LEVEL_PRO;
   global $PUSHY_LEVEL_ELITE;

   $productList = array();

   if ($DEBUG_AD_SELECTION)
     {
       printf("*-*-*-* selectPushyAds  members=%d *-*-*-*\n",count($members));
       var_dump($members);
       var_dump($categories);
     }

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


   $sql  = "SELECT * from ads LEFT JOIN member USING(member_id)  LEFT JOIN product USING(product_id) ";
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
   $sql .= ")";
   if (count($members) > 2)
     $sql .= " ) )";

   $result = mysql_query($sql,$db);

   if ($DEBUG_AD_SELECTION)
     {
       printf("* SQL:%s\n",$sql);
       printf("* ERR:%s\n",mysql_error());
     }

   if ($result && (($count=mysql_num_rows($result))>0))
     {
       while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
         {
           $member_id=$myrow["member_id"];
           $productList[$member_id]=$myrow;
         }
     }

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
   global $DEBUG_AD_SELECTION;
   global $PUSHY_ROOT;
   global $PUSHY_LEVEL_VIP;
   global $PUSHY_LEVEL_PRO;
   global $PUSHY_LEVEL_ELITE;

   $productSelected=FALSE;

   if ($DEBUG_AD_SELECTION)
     {
       printf("*-*-*-* selectCreditAdPool  excludeProductIds=%d  excludeProductNames=%d) *-*-*-*\n",count($excludeProductIds),count($excludeProductNames));
       var_dump($excludeProductIds);
       var_dump($excludeProductNames);
     }

   mysql_query("LOCK TABLES ads WRITE, credit_map WRITE, member WRITE, product WRITE",$db);

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

   if ($DEBUG_AD_SELECTION)
     {
       printf("SQL:%s\n",$sql);
       printf("ERR:%s\n",mysql_error());
       printf("ROWS=%s\n",mysql_num_rows($result));
     }

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

           if ($DEBUG_AD_SELECTION)
             {
               printf("SQL: %s\n",$sql);
               printf("ERR: %s\n",mysql_error());
               printf("AFFECTED: %d\n",mysql_affected_rows());
             }

           $sql = "SELECT COUNT(*),SUM(credits_allocated),SUM(credits_awarded) from credit_map WHERE credits_awarded < credits_allocated";
           $res = mysql_query($sql,$db);
           if ($res && ($myrow = mysql_fetch_array($res)))
             {
               $remaining=(int)$myrow[0];
               $allocated=(int)$myrow[1];
               $awarded  =(int)$myrow[2];
             }

           if ($DEBUG_AD_SELECTION)
             {
               printf("SQL: %s\n",$sql);
               printf("ERR: %s\n",mysql_error());
               printf("REMAINING: %d\n",$remaining);
               printf("ALLOCATED: %d\n",$allocated);
               printf("AWARDED:   %d\n",$awarded);
             }

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
   global $DEBUG_AD_SELECTION;
   global $PUSHY_ROOT;
   global $PUSHY_LEVEL_ELITE;

   $productSelected=FALSE;

   if ($DEBUG_AD_SELECTION)
     {
       printf("*-*-*-* selectEliteAdPool  excludeProductIds=%d  excludeProductNames=%d) *-*-*-*\n",count($excludeProductIds),count($excludeProductNames));
       var_dump($excludeProductIds);
       var_dump($excludeProductNames);
     }

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

   $sql .= " ORDER BY ads.lastview_pushy, ads.ad_id LIMIT 1";

   $result = mysql_query($sql,$db);

   if ($DEBUG_AD_SELECTION)
     {
       printf("SQL:%s\n",$sql);
       printf("ERR:%s\n",mysql_error());
       printf("ROWS=%s\n",mysql_num_rows($result));
     }

   if ($result && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
     {
       mysql_query("LOCK TABLES ads WRITE",$db);
         $productSelected=$myrow;
         $viewed = getmicroseconds();
         $sql    = "UPDATE ads set lastview_pushy='$viewed' WHERE ad_id='".$productSelected["ad_id"]."' ";
         $res = mysql_query($sql,$db);
       mysql_query("UNLOCK TABLES",$db);
     }

   if (!is_array($productSelected))
     {
       return FALSE;
     }
   return $productSelected;
 }



//-----------------------------------------------------------------------------------------
//
//
//
//
//-----------------------------------------------------------------------------------------

$categories=array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      $categories[]=$tarray[$i];
  }

if ($DEBUG_AD_SELECTION)
  {
    printf("CATEGORIES=%s\n%s\n",$WidgetCategories,print_r($categories,TRUE));
  }


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

if ($DEBUG_AD_SELECTION)
  {
    printf("site_owner = %s\n",$member_id);
  }

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
        if ($DEBUG_AD_SELECTION)
          {
            printf("sponsor_id = %s\n",$last_member_id);
          }
        $uplineCount++;
      }
  }

if ($DEBUG_AD_SELECTION)
  {
    printf("----\n");
  }

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

             if ($DEBUG_AD_SELECTION)
               {
                 printf("upline_id  = %s\n",$last_member_id);
               }

             $uplineCount++;
           }
       }
  }



$pushyAds = selectPushyAds($db,$upline,$categories);

// var_dump($upline);
// var_dump($pushyAds);
// exit;

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


if ($DEBUG_AD_SELECTION)
  {
    print_r($ads);
    // print_r($members);
    // exit;
  }

if ($DEBUG_AD_SELECTION)
  {
    printf("\n\n");
  }


while (count($ads)<6)
  {
    $ads[]=FALSE;
  }

for ($i=0; $i<count($ads); $i++)
  {
    if (is_array($ads[$i]))
      {
        if ($DEBUG_AD_SELECTION)
          {
            $member_id   = $ads[$i]["member_id"];
            $member_name = stripslashes($members[$i]["firstname"])." ".stripslashes($members[$i]["lastname"]);
            printf(" (#%d)  %-12s   %-20s   =>  (A-%-6s) (P-%-6s)   %s\n", $i+1,$member_id,$member_name,$ads[$i]["ad_id"],$ads[$i]["product_id"],$ads[$i]["product_title"]);
          }
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
        if (!is_array($productSelected))
          {
            $productSelected = selectEliteAdPool($db,array_keys($product_ids),array_keys($product_names));
          }
        if (is_array($productSelected))
          {
            $product_id   = $productSelected["product_id"];
            $product_name = strtolower($productSelected["product_name"]);
            $ads[$i]      = $productSelected;
            $members[$i]  = $productSelected;     // contains Credit Ad Pool Member Info
            $product_ids[$product_id]     = TRUE;
            $product_names[$product_name] = TRUE;

            if ($DEBUG_AD_SELECTION)
              {
                $member_id   = $productSelected["member_id"];
                $member_name = stripslashes($productSelected["firstname"])." ".stripslashes($productSelected["lastname"]);
                printf(" (#%d)  %-12s   %-20s   =>  (A-%-6s) (P-%-6s)   %s\n", $i+1,$member_id,$member_name,$ads[$i]["ad_id"],$ads[$i]["product_id"],$ads[$i]["product_title"]);
              }
          }
        else
          {
            if ($DEBUG_AD_SELECTION)
              {
                $member_id   = $members[$i]["member_id"];
                $member_name = stripslashes($members[$i]["firstname"])." ".stripslashes($members[$i]["lastname"]);
                printf(" (#%d)  -none-  for   %-12s   %-20s\n",$i+1,$member_id,$member_name);
              }
          }
      }
  }

if ($DEBUG_AD_SELECTION)
  {
    printf("(A12) ---- ADS ----\n");
    print_r($ads);
    // print_r($members);
    // exit;
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

    if ($DEBUG_AD_SELECTION)
      {
        $member_id   = $productSelected["member_id"];
        $member_name = stripslashes($productSelected["firstname"])." ".stripslashes($productSelected["lastname"]);
        printf(" (#%d)  %-12s   %-20s   =>  (A-%-6s) (P-%-6s)   %s\n", $i+1,$member_id,$member_name,$ads[$inx]["ad_id"],$ads[$inx]["product_id"],$ads[$inx]["product_title"]);
      }
  }

if ($DEBUG_AD_SELECTION)
  {
    printf("</PRE>\n");
    exit;
  }
?>
