include_once("pushy_tree.inc");

function getVMBar($db, $memberRecord=null)
  {
    global $PUSHY_LEVEL_VIP;
    global $PUSHY_LEVEL_PRO;
    global $PUSHY_LEVEL_ELITE;
    global $PUSHY_LEVEL_HITEK;
    global $PUSHY_LEVEL_ROOT;

    $vmbar = array();
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
           $sql .= " AND member_disabled=0";
        $sql .= " AND   user_level != $PUSHY_LEVEL_ROOT";
        $sql .= " AND   system=0";
        $sql .= " AND   LENGTH(website) > 12";
        $sql .= " AND   website like 'http://%'";
        $sql .= " AND   LENGTH(ad_title) > 0";
        $sql .= " AND   LENGTH(ad_text) > 0";
        $sql .= " ORDER by lft DESC";
        $result = exec_query($sql,$db);

        // printf("SQL: %s\n",$sql);
        // printf("ERR: %s\n",mysql_error());

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
                     $count++;
                     if ($count==5)
                       {
                         return $vmbar;   // ---- Done - We have What We Need
                       }
                   }
                 $v++;
               }
          }
      }

    $needed = 5 - $count;

    // printf("Use %d Random\n",$needed);

    $memberList=private_getRandomVMBarSelection($db,$needed,$PUSHY_LEVEL_PRO,$excludeList);
    for ($j=0; $j<count($memberList); $j++)
      {
        $myrow   = $memberList[$j];
        $vmbar[] = $myrow;
        $excludeList[] = $myrow["member_id"];
      }
    $needed -= count($memberList);
    if ($needed > 0)
      {
        $memberList=private_getRandomVMBarSelection($db,$needed,0,$excludeList);
        for ($j=0; $j<count($memberList); $j++)
          {
            $myrow   = $memberList[$j];
            $vmbar[] = $myrow;
            $excludeList[] = $myrow["member_id"];
          }
      }
    return $vmbar;
  }



function private_getRandomVMBarSelection($db,$num,$minimum_level=0,$excludeList=null)
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
