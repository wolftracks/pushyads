<?php
 $DEBUG=FALSE;

 $INCLUDE_ROOT = TRUE;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

 include_once("pushy_tree.inc");
 // include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $member_id="p3178gsb";
 $memberRecord=getMemberInfo($db,$member_id);

 $elites = getEliteDisplayOrder($db, 10, $memberRecord);
 $ecount=count($elites);

 printf("\n========== ELITE ADS visible to Signed In User: %s   (%s) ====\n",$memberRecord["firstname"],$memberRecord["member_id"]);
 for ($j=0; $j<$ecount; $j++)
   {
     $mbr = $elites[$j];
     printf(" (%d) - Ad belonging to  %-12s    (%s - %s)\n",($j+1), $mbr["firstname"], $mbr["member_id"], $UserLevels[$mbr["user_level"]]);
   }




function getEliteDisplayOrder($db, $targetCount=10, $memberRecord=null)
  {
    global $PUSHY_LEVEL_VIP;
    global $PUSHY_LEVEL_PRO;
    global $PUSHY_LEVEL_ELITE;
    global $PUSHY_LEVEL_HITEK;
    global $PUSHY_LEVEL_ROOT;
    global $HIDE_DISABLED;

    $elites = array();
    $v=0;
    $count=0;
    $excludeList = array();


    if (isset($memberRecord) && is_array($memberRecord) && isset($memberRecord["member_id"]))
      {
        $member_id = $memberRecord["member_id"];
        $lft       = $memberRecord["lft"];
        $rgt       = $memberRecord["rgt"];
        $excludeList[] = $member_id;

        $sql  = "SELECT * FROM member ";
        $sql .= " WHERE member_id!='$member_id'";              // EXCLUDE SELF
        $sql .= " AND   lft<$lft AND rgt>$rgt";
        if ($HIDE_DISABLED)
           $sql .= " AND aff_disabled=0";
        $sql .= " AND   user_level = $PUSHY_LEVEL_ELITE";
        $sql .= " ORDER by lft DESC";
        $result = mysql_query($sql,$db);
        if ($result)
          {
            // display each row
             while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
               {
                 $elites[] = $myrow;
                 $excludeList[] = $myrow["member_id"];
                 $count++;
                 if ($count==$targetCount)
                   {
                     return $elites;   // ---- Done - We have What We Need
                   }
               }
          }
      }

    // ADD IMTOOLS t the list
    $elites[] = getMemberInfo($db, "imtools");
    $count++;
    if ($count==$targetCount)
      {
        return $elites;   // ---- Done - We have What We Need
      }
    $excludeList[] = "imtools";

    $needed = $targetCount - $count;

    // printf("Use %d Random\n",$needed);

    $memberList=selectRandomMembers($db,$needed,$PUSHY_LEVEL_ELITE, $excludeList);
    for ($j=0; $j<count($memberList); $j++)
      {
        $elites[] = $memberList[$j];
      }
    return $elites;
  }


 exit;
?>
