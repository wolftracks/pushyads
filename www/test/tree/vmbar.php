<?php
$DEBUG=FALSE;

$INCLUDE_ROOT = TRUE;

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

include_once("pushy_tree.inc");
include_once("pushy_imagestore.inc");


function getVMBar($db, $category, $memberRecord=null)
  {
    global $PUSHY_LEVEL_VIP;
    global $PUSHY_LEVEL_PRO;
    global $PUSHY_LEVEL_ELITE;
    global $PUSHY_LEVEL_HITEK;
    global $PUSHY_LEVEL_ROOT;
    global $CATEGORY_ANY;

    $HIDE_DISABLED=TRUE;


    $UPLINE_ADS_NEEDED = 5;
    $ADS_NEEDED = 8;

    $vmbar = array();
    $v=0;
    $excludeList = array();

    if (isset($memberRecord) && is_array($memberRecord) && isset($memberRecord["member_id"]))
      {
        $member_id = $memberRecord["member_id"];
        $lft       = $memberRecord["lft"];
        $rgt       = $memberRecord["rgt"];
        $excludeList[] = $member_id;

        $sql  = "SELECT * from ads LEFT JOIN member USING(member_id) LEFT JOIN product USING(product_id) ";
        $sql .= " WHERE member.member_id = '$member_id'";
        $sql .= " AND   member.member_disabled=0 ";
//---   $sql .= " AND   product.product_approved>0 ";
//      $sql .= " AND   product.category='$category'";
        $result = exec_query($sql,$db);
        if ($result && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
          {
            $vmbar[]=$myrow;
          }

        printf("SQL:  %s\n",$sql);
        printf("ROWS: %s\n",mysql_num_rows($result));
        printf("ERR:  %s\n",mysql_error());

        $sql  = "SELECT * from ads LEFT JOIN member USING(member_id) LEFT JOIN product USING(product_id) ";
        $sql .= " WHERE member.member_id != '$member_id'";
        $sql .= " AND   member.member_disabled=0 ";
        $sql .= " AND   product.product_approved>0 ";
        $sql .= " AND   member.lft<$lft AND member.rgt>$rgt";
        $sql .= " AND   member.user_level != $PUSHY_LEVEL_ROOT";
        $sql .= " AND   member.system=0";
        if ($category != $CATEGORY_ANY)
            $sql .= " AND   product.category='$category'";
        $sql .= " ORDER by member.lft DESC";
        $result = exec_query($sql,$db);

        printf("SQL:  %s\n",$sql);
        printf("ROWS: %s\n",mysql_num_rows($result));
        printf("ERR:  %s\n",mysql_error());

        if ($result)
          {
            // display each row
             while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
               {
                 // print_r($myrow);

                 if ($v==0 || $v==1 || $myrow["user_level"] > $PUSHY_LEVEL_VIP)
                   {
                     $vmbar[] = $myrow;
                     $excludeList[] = $myrow["member_id"];
                     if (count(vmbar)==$UPLINE_ADS_NEEDED)
                       {
                         break;        // ---- Done - We have What We Need from the Upline
                       }
                   }
                 $v++;
               }
          }
      }

    $needed = $ADS_NEEDED - count($vmbar);

    printf("Use %d Random\n",$needed);

    // print_r($vmbar);
    // exit;
    return($vmbar);

    $memberList=private_getRandomVMBarSelection($db,$needed,$PUSHY_LEVEL_PRO,$category,$excludeList);
    for ($j=0; $j<count($memberList); $j++)
      {
        $myrow   = $memberList[$j];
        $vmbar[] = $myrow;
        $excludeList[] = $myrow["member_id"];
      }
    $needed -= count($memberList);
    if ($needed > 0)
      {
        $memberList=private_getRandomVMBarSelection($db,$needed,0,$category,$excludeList);
        for ($j=0; $j<count($memberList); $j++)
          {
            $myrow   = $memberList[$j];
            $vmbar[] = $myrow;
            $excludeList[] = $myrow["member_id"];
          }
      }
    return $vmbar;
  }



function private_getRandomVMBarSelection($db,$num,$minimum_level=0,$category,$excludeList=null)
  {
    global $PUSHY_LEVEL_HITEK;
    global $PUSHY_LEVEL_ROOT;
    global $HIDE_DISABLED;

    $members=array();

    $sql    = "SELECT * FROM member";
    $sql   .= " WHERE user_level >= $minimum_level";
    $sql   .= " AND   user_level <= $PUSHY_LEVEL_HITEK";
    $sql   .= " AND   system=0";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $sql   .= " AND   LENGTH(website) > 12";
    $sql   .= " AND   website like 'http://%'";
    $sql   .= " AND   LENGTH(ad_text) > 0";

    if (isset($excludeList) && is_array($excludeList) && count($excludeList) > 0)
      {
        for ($j=0; $j<count($excludeList); $j++)
          {
            $sql .= " AND member_id!='".$excludeList[$j]."'";
          }
      }
    $result = exec_query($sql,$db);
    if ($result && (($count=mysql_num_rows($result))>0))
      {
        if ($count<$num)
          $num=$count;

        // printf("NUM=%d\n",$num);
        // printf("COUNT=%d\n",$count);

        $mIndex=array();
        for ($i=0; $i<$num; $i++)
          {
            $m=rand(0,$count-1);
            // printf(" ...m=%d\n",$m);
            while (isset($mIndex[$m]))
              {
                $m=rand(0,$count-1);
                // printf(" .....m=%d\n",$m);
              }
            $mIndex[$m]=TRUE;
          }
        ksort($mIndex);

        // print_r($mIndex);

        foreach($mIndex as $indexValue=>$bool)
          {
            // printf("indexValue=%d\n",$indexValue);
            if ($indexValue > 0)
              mysql_data_seek($result, $indexValue);
            if ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
              {
                // printf("MID = \n",$myrow[0]);
                $members[]=$myrow;
              }
          }
      }
  }














set_time_limit(0);
$db = getPushyDatabaseConnection();

//================================================================
// tree_Rebuild($db, $PUSHY_ROOT, 0);

// tree_display($db,$PUSHY_ROOT);
// printf("\n\n\n");

//================================================================


$siteOwner="esn1309";
$memberRecord=getMemberInfo($db,$siteOwner);

// print_r($memberRecord);

$vmbar = getVMBar($db, $CATEGORY_ANY, $memberRecord);
$vcount=count($vmbar);
// print_r($vmbar);


printf("\n========== UPLINE for  %s %s  (%s) ==============\n",$memberRecord["firstname"],$memberRecord["lastname"],$memberRecord["member_id"]);
tree_showUpline($db, $siteOwner);
printf("\n\n\n");

printf("\n========== VMBAR for Site Owner: %s   (%s) ==============\n",$memberRecord["firstname"],$memberRecord["member_id"]);
for ($j=0; $j<$vcount; $j++)
  {
    $mbr = $vmbar[$j];
    $level               = $UserLevels[$mbr["user_level"]];
    $member_id           = stripslashes($mbr["member_id"]);
    $firstname           = stripslashes($mbr["firstname"]);
    $lastname            = stripslashes($mbr["lastname"]);
    $product_title       = stripslashes($mbr["product_title"]);
    $product_description = stripslashes($mbr["product_description"]);
    $product_url         = stripslashes($mbr["product_url"]);
    printf("\n%s - %s %s ---  TITLE: $product_title\n--------------------------------\n  $product_description\n  $product_url\n",
                                     $member_id,
                                     $firstname,
                                     $lastname);
  }

exit;
?>
