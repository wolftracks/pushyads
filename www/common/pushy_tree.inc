<?php
$DEBUG=FALSE;
$TRACE=FALSE;

$HIDE_DISABLED=FALSE;  // THIS MUST STAY FALSE - SEE DESCRIPTION BELOW

//=====================================================================================
// Nodes can be DISABLED (e.g. non-Payment)
// REFID ALWAYS points to Actual Sponsor
//
// NO FUNCTION SHOULD consider DISABLED
// The TREE must represent ALL NODES (disabled or not)
// DISABLED NODES must be considered in payment calculations but since these calculations
// always require "walking the tree" and looking at each node anyway (e.g. user_level),
// these functions should never "HIDE" diabled Nodes (and instead should be a consideration)
// for the application code using these functions.
//
//=============================================== MODIFIED PREORDER TRAVERSAL =========

function tree_display($db, $root="pushy")
  {
    global $DEBUG;
    global $HIDE_DISABLED;
    global $UserLevels;

    if ($TRACE)
      {
        // printf("\n   ... display_tree(%s);\n",$root);
      }

    // retrieve the left and right value of the $root node

    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$root'";
    if ($HIDE_DISABLED)
      $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];

        // start with an empty $rgt stack
        $rightNodes = array();

        // now, retrieve all descendants of the $root node
        // $sql  = "SELECT member_id, lft, rgt FROM member ";
        $sql  = "SELECT * FROM member ";
        $sql .= " WHERE lft BETWEEN $lft AND $rgt";
        $sql .= " AND member_id != '$root'";  // EXCLUDE SELF
        if ($HIDE_DISABLED)
           $sql .= " AND member_disabled=0";
        $sql .= " ORDER by lft ASC";
        $result = exec_query($sql,$db);

        if ($result)
          {
            // display each row
            while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
              {
                // only check stack if there is one
                while ((count($rightNodes)>0) && $rightNodes[count($rightNodes)-1]<$myrow['rgt'])
                  {
                    array_pop($rightNodes);
                  }

                // display indented node member_id
                // $member_info="(".$myrow["member_id"].") ".$myrow["firstname"]." ".$myrow["lastname"]."  [".$UserLevels[$myrow["user_level"]]."]  ".$myrow["ad_title"]." ".$myrow["ad_text"]." ".$myrow["website"];
                $member_info="(".$myrow["member_id"].") ".$myrow["firstname"]." ".$myrow["lastname"]."  [".$UserLevels[$myrow["user_level"]]."]";

                echo str_repeat('   ',count($rightNodes));
                printf("(%2d)  %-10s    L=%3d  R=%3d\n",count($rightNodes),$member_info,$myrow['lft'],$myrow['rgt']);

                // add this node to the stack
                $rightNodes[] = $myrow['rgt'];
              }
          }
      }
  }


function tree_getDownlineInfo($db, $root)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $userlevel_counts=array(0,0,0,0,0,0,0,0); // Up to 8 USER_LEVELS Supported (eventuually)
    $treelevel_counts=array();

    // retrieve the left and right value of the $root node
    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$root'";
    if ($HIDE_DISABLED)
      $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];

        // start with an empty $rgt stack
        $rightNodes = array();

        // now, retrieve all descendants of the $root node
        $sql  = "SELECT user_level, lft, rgt FROM member ";
        $sql .= " WHERE lft BETWEEN $lft AND $rgt";

        $sql .= " AND member_id != '$root'";  // EXCLUDE SELF
        $sql .= " AND system=0";
        $sql .= " AND registered>0";
        $sql .= " AND member_disabled=0";

        //  if ($HIDE_DISABLED)
        //     $sql .= " AND member_disabled=0";

        $sql .= " ORDER by lft ASC";
        $result = exec_query($sql,$db);

        if ($result)
          {
            while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
              {
                // only check stack if there is one
                while ((count($rightNodes)>0) && $rightNodes[count($rightNodes)-1]<$myrow['rgt'])
                  {
                    array_pop($rightNodes);
                  }

                $user_level = $myrow["user_level"];
                $userlevel_counts[$user_level]++;

                $level = count($rightNodes);
                $treelevel_counts[$level]++;

                // add this node to the stack
                $rightNodes[] = $myrow['rgt'];
              }
          }
      }

    $total_descendants=0;
    for ($j=0; $j<8; $j++)
      $total_descendants += $userlevel_counts[$j];

    $result = array (
                      "TOTAL_DESCENDANTS" => $total_descendants,
                      "USER_LEVEL_COUNTS" => $userlevel_counts,
                      "TREE_LEVEL_COUNTS" => $treelevel_counts,
                    );
    return($result);
  }


function tree_getLeafNodes($db)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    // retrieve the left and right value of the $root node

    $nodes=array();
    $sql  = "SELECT * FROM member ";
    $sql .= " WHERE  lft+1=rgt";

    $sql .= " AND system=0";
    $sql .= " AND registered>0";
    $sql .= " AND member_disabled=0";

    //  if ($HIDE_DISABLED)
    //     $sql .= " AND member_disabled=0";

    $result = exec_query($sql,$db);
    if ($result)
      {
        // display each row
        while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
          {
            $nodes[] = $myrow;
          }
      }
    return $nodes;
  }


function tree_displayLeafNodes($db)
  {
    global $DEBUG;
    global $HIDE_DISABLED;
    global $UserLevels;

    // retrieve the left and right value of the $root node

    $sql  = "SELECT * FROM member ";
    $sql .= " WHERE  lft+1=rgt";

    $sql .= " AND system=0";
    $sql .= " AND registered>0";
    $sql .= " AND member_disabled=0";

    // if ($HIDE_DISABLED)
    //   $sql .= " AND member_disabled=0";

    $result = exec_query($sql,$db);
    if ($result)
      {
        // display each row
        while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
          {

            $member_info="(".$myrow["member_id"].") ".$myrow["firstname"]." ".$myrow["lastname"]."  [".$UserLevels[$myrow["user_level"]]."]";
            printf(" %s\n",$member_info);
          }
      }
  }


function private_isLeafNode($lft, $rgt)
  {
    if (($lft+1) == $rgt)
      return TRUE;
    return FALSE;
  }


function tree_hasDescendant($db, $member_id)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $sql  = "SELECT lft FROM member ";
    $sql .= " WHERE  refid='$member_id'";

    $sql .= " AND system=0";
    $sql .= " AND registered>0";
    $sql .= " AND member_disabled=0";

    //  if ($HIDE_DISABLED)
    //     $sql .= " AND member_disabled=0";

    $sql .= " LIMIT 1";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        return TRUE;
      }
    return FALSE;
  }



function tree_numDescendants($db, $member_id, $user_level=99)
  {
    global $DEBUG;
    global $PUSHY_LEVEL_ALL;
    global $HIDE_DISABLED;

    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$member_id'";

    $sql .= " AND system=0";
    $sql .= " AND registered>0";
    $sql .= " AND member_disabled=0";

    //if ($HIDE_DISABLED)
    //   $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);

    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];

        $sql  = "SELECT COUNT(*) FROM member ";
        $sql .= " WHERE member_id!='$member_id'";              // EXCLUDE SELF

        $sql .= " AND system=0";
        $sql .= " AND registered>0";
        $sql .= " AND member_disabled=0";

        if ($user_level != $PUSHY_LEVEL_ALL)
           $sql .= " AND user_level = '$user_level'";
        $sql .= " AND   lft BETWEEN $lft AND $rgt";
        if ($HIDE_DISABLED)
           $sql .= " AND member_disabled=0";
        $result = exec_query($sql,$db);
        if (($result) && ($myrow = mysql_fetch_array($result)))
          {
            return ($myrow[0]);
          }
      }
    return 0;
  }



function private_getDescendantCounts($db, $lft, $rgt, $exclude_member_id)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $total=0;
    $descendants[0]=0;
    $descendants[1]=0;
    $descendants[2]=0;
    $descendants[3]=0;

    if (!private_isLeafNode($lft, $rgt))
      {
        $sql  = "SELECT user_level, COUNT(*) FROM member ";
        $sql .= " WHERE member_id!='$exclude_member_id'";              // EXCLUDE SELF
        $sql .= " AND   lft BETWEEN $lft AND $rgt";

        $sql .= " AND system=0";
        $sql .= " AND registered>0";
        $sql .= " AND member_disabled=0";

        // if ($HIDE_DIABLED)
        //   $sql .= " AND member_disabled=0";

        $sql .= " GROUP BY user_level";
        $result = exec_query($sql,$db);
        if ($result)
          {
            while ($myrow = mysql_fetch_array($result))
              {
                $descendants[$myrow[0]]=$myrow[1];
                $total+=$myrow[1];
              }
          }
      }
    return array($total,$descendants);
  }


function tree_getDescendantCounts($db, $member_id)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$member_id'";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];
        if (!private_isLeafNode($lft, $rgt))
          return(private_getDescendantCounts($db, $lft, $rgt, $member_id));
      }

    return array(0,array(0,0,0,0));
  }


function private_getUpline($db, $lft, $rgt, $exclude_member_id, $options=FALSE)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $minlevel = 0;      // VIP
    $order    = 0;      // Asc
    $limit    = 0;      // No Limit
    $idsonly  = FALSE;  //
    if (is_array($options))
      {
        if (isset($options["minlevel"]))
          $minlevel = $options["minlevel"];
        if (isset($options["limit"]))
          $limit   = $options["limit"];
        if (isset($options["idsonly"]))
          $idsonly = $options["idsonly"];
        if (isset($options["order"]))
          {
            $ch = strtoupper(substr($options["order"],0,1));
            if ($ch=="D")
              $order=1;
          }
      }

    $upline=array();

    $sql  = "SELECT * FROM member ";
    $sql .= " WHERE member_id!='$exclude_member_id'";              // EXCLUDE SELF
    $sql .= " AND   lft<$lft AND rgt>$rgt";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $sql .= " AND user_level >= '".$minlevel."'";

    if ($order > 0)
       $sql .= " ORDER by lft DESC";
    else
       $sql .= " ORDER by lft ASC";

    if ($limit > 0)
       $sql .= " LIMIT $limit";
    $result = exec_query($sql,$db);
    if ($result)
      {
        // display each row
         while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
           {
             if ($idsonly)
               $upline[] = $myrow["member_id"];
             else
               $upline[] = $myrow;
           }
      }
    return $upline;
  }



//--- Comparison_operators: "LT", "LE", "EQ", "GE", "GT"
function private_findFirstUplineLevel($db, $lft, $rgt, $exclude_member_id, $comparison_operator, $user_level)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $upline=array();

    $sql  = "SELECT * FROM member ";
    $sql .= " WHERE member_id!='$exclude_member_id'";              // EXCLUDE SELF
    $sql .= " AND   lft<$lft AND rgt>$rgt";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";

    if ($comparison_operator == "LT")
      $sql .= " AND user_level <  '".$user_level."'";
    else
    if ($comparison_operator == "LE")
      $sql .= " AND user_level <= '".$user_level."'";
    else
    if ($comparison_operator == "EQ")
      $sql .= " AND user_level =  '".$user_level."'";
    else
    if ($comparison_operator == "GE")
      $sql .= " AND user_level >= '".$user_level."'";
    else
    if ($comparison_operator == "GT")
      $sql .= " AND user_level >  '".$user_level."'";
    else
      return FALSE;

    $sql .= " ORDER by lft DESC";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
       return $myrow;


    return FALSE;
  }


function tree_getUpline($db, $member_id, $options)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$member_id'";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];
        return(private_getUpline($db, $lft, $rgt, $member_id, $options));
      }
    return(array());
  }



//--- Comparison_operators: "LT", "LE", "EQ", "GE", "GT"
function tree_findFirstUplineLevel($db, $member_id, $comparison_operator, $user_level)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$member_id'";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];
        return(private_findFirstUplineLevel($db, $lft, $rgt, $member_id, $comparison_operator, $user_level));
      }
    return(FALSE);
  }



function tree_showUpline($db, $member_id)
  {
    global $DEBUG;
    global $HIDE_DISABLED;
    global $UserLevels;

    $sql  = "SELECT lft, rgt, refid FROM member ";
    $sql .= " WHERE  member_id='$member_id'";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);

    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];
        $refid=$myrow["refid"];

        $sql  = "SELECT * FROM member ";
        $sql .= " WHERE  lft<$lft AND rgt>$rgt";
        if ($HIDE_DISABLED)
           $sql .= " AND member_disabled=0";
        $sql .= " ORDER by lft ASC";
        $result = exec_query($sql,$db);
        if ($result)
          {
            // display each row
             while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
               {
                 $member_info = "(".$myrow["member_id"].") ".$myrow["firstname"]." ".$myrow["lastname"]."  [".$UserLevels[$myrow["user_level"]]."]";
                 if ($myrow["member_id"] == $refid)
                   $member_info .= "   *DIRECT-SPONSOR*";
                 printf(" %-s\n",$member_info);
               }
          }
      }
  }


function private_getNodeLevel($db, $lft, $rgt)
  {
    $sql  = "SELECT COUNT(*) FROM member ";
    $sql .= " WHERE  lft<$lft AND rgt>$rgt AND member_disabled=0";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result)))
      {
        return $myrow[0];
      }
    return 0;
  }

function tree_getNodeLevelForMember($db, $mid)
  {
    $level=0;
    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$mid' AND member_disabled=0";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $level=private_getNodeLevel($db,$myrow["lft"],$myrow["rgt"]);
      }
    return $level;
  }


function tree_getParentMemberId($db, $member_id)
  {
    global $DEBUG;
    global $HIDE_DISABLED;

    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$member_id'";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);

    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];

        $sql  = "SELECT member_id FROM member ";
        $sql .= " WHERE  lft<$lft AND rgt>$rgt";
        if ($HIDE_DISABLED)
           $sql .= " AND member_disabled=0";
        $sql .= " ORDER by lft DESC LIMIT 1";
        $result = exec_query($sql,$db);
        if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
          {
                   // May Not Be REFID  ... if REFID has been disabled ...
            return $myrow["member_id"];
          }
      }
    return -1;
  }


function tree_getRefId($db, $member_id)
  {
    global $DEBUG;

    $sql  = "SELECT refid FROM member ";
    $sql .= " WHERE  member_id='$member_id'";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        return $myrow["refid"];
      }
    return -1;
  }


function tree_createNewMember($db, $parentMemberId, $email)
  {
    global $DEBUG;
    global $TRACE;
    global $HIDE_DISABLED;
    global $PUSHY_ROOT;

    if ($TRACE)
      {
        printf("\n\n  ..createNewMember ... Parent=%s\n", $parentMemberId);
      }

    $sql  = "SELECT * FROM member ";
    $sql .= " WHERE  member_id='$parentMemberId'";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);
    if ($DEBUG)
      {
        if ((!$result) || (mysql_errno($db)))
          {
            printf("SQL:   %s\n",$sql);
            printf("ERRNO: %s\n",mysql_errno($db));
            printf("ERR:   %s\n",mysql_error($db));
            return NULL;
          }
      }

    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
         // OK - Parent is There and Enabled
      }
    else
      {
        $parentMemberId = $PUSHY_ROOT;

         // Insert Under Root
        $sql  = "SELECT * FROM member ";
        $sql .= " WHERE  member_id='$parentMemberId'";
        $result = exec_query($sql,$db);
        if ($DEBUG)
          {
            if ((!$result)|| (mysql_errno($db)))
              {
                printf("SQL:   %s\n",$sql);
                printf("ERRNO: %s\n",mysql_errno($db));
                printf("ERR:   %s\n",mysql_error($db));
                return NULL;
              }
          }
        if (($result) && ($myrow = mysql_fetch_array($result)))
          {
            // OK  (root better be there)
          }
        else
          return NULL;
      }


    $lftParent=$myrow["lft"];
    $rgtParent=$myrow["rgt"];
    if ($TRACE)
      {
        printf("PARENT:  MEMBER_ID=%s  LFT=%s  RGT=%s\n",$myrow["member_id"],$myrow["lft"],$myrow["rgt"]);
      }

    //---------------------------------------
    $sql="LOCK TABLES member WRITE";
    $result=exec_query($sql,$db);
    //---------------------------------------
    if ($DEBUG)
      {
        if ((!$result)|| (mysql_errno($db)))
          {
            printf("SQL:   %s\n",$sql);
            printf("ERRNO: %s\n",mysql_errno($db));
            printf("ERR:   %s\n",mysql_error($db));
            exec_query("UNLOCK TABLES",$db);
            return NULL;
          }
      }

    $tm=time();
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $date_today = sprintf("%04d-%02d-%02d",$yy,$mm,$dd);

    $Success=FALSE;
    $member_id=newMemberId($db);
    $sql  = "INSERT INTO member SET member_id='$member_id', refid='$parentMemberId', email='$email', record_created='$date_today', member_disabled=0";
    $result = exec_query($sql,$db);
    if ($DEBUG)
      {
        if ((!$result)|| (mysql_errno($db)))
          {
            printf("SQL:   %s\n",$sql);
            printf("ERRNO: %s\n",mysql_errno($db));
            printf("ERR:   %s\n",mysql_error($db));
            exec_query("UNLOCK TABLES",$db);
            return NULL;
          }
      }

    if (($result) && (mysql_affected_rows()==1))
      {
        $insert_id=mysql_insert_id();

        $sql="UPDATE member SET rgt=rgt+2 WHERE rgt>='$rgtParent'";
        $result = exec_query($sql,$db);
        if ($DEBUG)
          {
            if ((!$result)|| (mysql_errno($db)))
              {
                printf("SQL:   %s\n",$sql);
                printf("ERRNO: %s\n",mysql_errno($db));
                printf("ERR:   %s\n",mysql_error($db));
                exec_query("UNLOCK TABLES",$db);
                return NULL;
              }
          }

        if ($result)
          {
            $sql="UPDATE member SET lft=lft+2 WHERE lft>='$rgtParent'";
            $result = exec_query($sql,$db);
            if ($DEBUG)
              {
                if ((!$result)|| (mysql_errno($db)))
                  {
                    printf("SQL:   %s\n",$sql);
                    printf("ERRNO: %s\n",mysql_errno($db));
                    printf("ERR:   %s\n",mysql_error($db));
                    exec_query("UNLOCK TABLES",$db);
                    return NULL;
                  }
              }
            if ($result)
              {
                $lft=$rgtParent;    // old Parent Right
                $rgt=$rgtParent+1;  // old Parent Right + 1
                $sql  = "UPDATE member SET lft='$lft', rgt='$rgt' where member_id='$member_id'";
                $result = exec_query($sql,$db);
                if (($result) && (mysql_affected_rows()==1))
                  $Success=TRUE;
                else
                  {
                    if ($DEBUG)
                      {
                        if ((!$result)|| (mysql_errno($db)))
                          {
                            printf("SQL:   %s\n",$sql);
                            printf("ERRNO: %s\n",mysql_errno($db));
                            printf("ERR:   %s\n",mysql_error($db));
                            exec_query("UNLOCK TABLES",$db);
                            return NULL;
                          }
                      }
                  }
              }
          }
      }
    //---------------------------------------
    exec_query("UNLOCK TABLES",$db);
    //---------------------------------------

    if ($Success)
      return array($member_id,$parentMemberId,$insert_id);
    else
      return NULL;
  }



function tree_deleteNode($db, $member_id)
  {
    global $DEBUG;
    global $TRACE;
    global $PUSHY_ROOT;

    if ($member_id==$PUSHY_ROOT) // CANNOT DELETE ROOT
      return FALSE;

    $Success=FALSE;
    $sql  = "SELECT * FROM member ";
    $sql .= " WHERE  member_id='$member_id'";
    $result = exec_query($sql,$db);
    if ($DEBUG)
      {
        if ((!$result)|| (mysql_errno($db)))
          {
            printf("SQL:   %s\n",$sql);
            printf("ERRNO: %s\n",mysql_errno($db));
            printf("ERR:   %s\n",mysql_error($db));
            return NULL;
          }
      }

    if (($result) && ($thisNode = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        // $parentMemberId=getParentMemberId($db,$member_id);
        $parentMemberId=$thisNode["refid"];

        $sql  = "SELECT * FROM member ";
        $sql .= " WHERE  member_id='".$parentMemberId."'";
        $result = exec_query($sql,$db);
        if ($DEBUG)
          {
            if ((!$result)|| (mysql_errno($db)))
              {
                printf("SQL:   %s\n",$sql);
                printf("ERRNO: %s\n",mysql_errno($db));
                printf("ERR:   %s\n",mysql_error($db));
                return NULL;
              }
          }
        if (($result) && ($parentNode = mysql_fetch_array($result,MYSQL_ASSOC)))
          {
            if ($TRACE)
              {
                  printf("DELETING:  MEMBER_ID=%s  LFT=%s  RGT=%s   FROM PARENT:  MEMBER_ID=%s  LFT=%s  RGT=%s\n",
                  $thisNode["member_id"],$thisNode["lft"],$thisNode["rgt"],
                  $parentNode["member_id"],$parentNode["lft"],$parentNode["rgt"]);
              }

            //---------------------------------------
            $sql="LOCK TABLES member WRITE";
            $result=exec_query($sql,$db);
            //---------------------------------------
            if ($DEBUG)
              {
                if ((!$result)|| (mysql_errno($db)))
                  {
                    printf("SQL:   %s\n",$sql);
                    printf("ERRNO: %s\n",mysql_errno($db));
                    printf("ERR:   %s\n",mysql_error($db));
                    return NULL;
                  }
              }
            $sql="UPDATE member SET lft=lft-2 WHERE lft>'".$thisNode["lft"]."'";
            $result = exec_query($sql,$db);
            if ($DEBUG)
              {
                if ((!$result)|| (mysql_errno($db)))
                  {
                    printf("SQL:   %s\n",$sql);
                    printf("ERRNO: %s\n",mysql_errno($db));
                    printf("ERR:   %s\n",mysql_error($db));
                    exec_query("UNLOCK TABLES",$db);
                    return NULL;
                  }
              }
            if ($result)
              {
                $sql="UPDATE member SET rgt=rgt-2 WHERE rgt>'".$thisNode["lft"]."'";
                $result = exec_query($sql,$db);
                if ($DEBUG)
                  {
                    if ((!$result)|| (mysql_errno($db)))
                      {
                        printf("SQL:   %s\n",$sql);
                        printf("ERRNO: %s\n",mysql_errno($db));
                        printf("ERR:   %s\n",mysql_error($db));
                        exec_query("UNLOCK TABLES",$db);
                        return NULL;
                      }
                  }
                if ($result)
                  {
                    $sql  = "UPDATE member SET refid='".$parentNode["member_id"]."' where refid='$member_id'";
                    $result = exec_query($sql,$db);
                    if ($DEBUG)
                      {
                        if ((!$result)|| (mysql_errno($db)))
                          {
                            printf("SQL:   %s\n",$sql);
                            printf("ERRNO: %s\n",mysql_errno($db));
                            printf("ERR:   %s\n",mysql_error($db));
                            exec_query("UNLOCK TABLES",$db);
                            return NULL;
                          }
                      }
                    if ($result)
                      {
                        $sql = "DELETE from member where member_id='$member_id'";
                        $result = exec_query($sql,$db);
                        if (($result) && (mysql_affected_rows()==1))
                          $Success=TRUE;
                        else
                          {
                            if ($DEBUG)
                              {
                                if ((!$result)|| (mysql_errno($db)))
                                  {
                                    printf("SQL:   %s\n",$sql);
                                    printf("ERRNO: %s\n",mysql_errno($db));
                                    printf("ERR:   %s\n",mysql_error($db));
                                    exec_query("UNLOCK TABLES",$db);
                                    return NULL;
                                  }
                              }
                          }
                      }
                  }
              }
            //---------------------------------------
            exec_query("UNLOCK TABLES",$db);
            //---------------------------------------

          }
      }
    return $Success;
  }



function tree_disableNode($db, $member_id)
  {
    global $DEBUG;
    global $PUSHY_ROOT;

    if ($member_id==$PUSHY_ROOT) // CANNOT DISABLE ROOT
      return FALSE;

    $Success=FALSE;
    //---------------------------------------
    $sql="LOCK TABLES member WRITE";
    $result=exec_query($sql,$db);
    //---------------------------------------
    $tm=time();
    $sql  = "UPDATE member SET member_disabled='$tm' where member_id='$member_id'";
    $result = exec_query($sql,$db);
    if (($result) && (mysql_affected_rows()==1))
      {
        $Success=TRUE;
      }
    if ($DEBUG)
      {
        if ((!$result)|| (mysql_errno($db)))
          {
            printf("SQL:   %s\n",$sql);
            printf("ERRNO: %s\n",mysql_errno($db));
            printf("ERR:   %s\n",mysql_error($db));
            exec_query("UNLOCK TABLES",$db);
            return NULL;
          }
      }
    //---------------------------------------
    exec_query("UNLOCK TABLES",$db);
    //---------------------------------------

    return $Success;
  }


function tree_enableNode($db, $member_id)
  {
    global $DEBUG;
    global $PUSHY_ROOT;

    if ($member_id==$PUSHY_ROOT) // Root is Never Disabled
      return TRUE;

    $Success=FALSE;
    //---------------------------------------
    $sql="LOCK TABLES member WRITE";
    $result=exec_query($sql,$db);
    //---------------------------------------
    $tm=time();
    $sql  = "UPDATE member SET member_disabled='0' where member_id='$member_id'";
    $result = exec_query($sql,$db);
    if (($result) && (mysql_affected_rows()==1))
      {
        $Success=TRUE;
      }
    if ($DEBUG)
      {
        if ((!$result)|| (mysql_errno($db)))
          {
            printf("SQL:   %s\n",$sql);
            printf("ERRNO: %s\n",mysql_errno($db));
            printf("ERR:   %s\n",mysql_error($db));
            exec_query("UNLOCK TABLES",$db);
            return NULL;
          }
      }

    //---------------------------------------
    exec_query("UNLOCK TABLES",$db);
    //---------------------------------------

    return $Success;
  }



function private_rebuild($db, $member_id, $lft)
  {
    global $TRACE;
    global $DEBUG;
    global $HIDE_DISABLED;

    if ($TRACE)
      {
        printf("\n   ... private_rebuild(%s,%s);\n",$member_id,$lft);
      }

    // the right value of this node is the left value + 1
    $rgt = $lft+1;

    // get all children of this node
    $sql  = "SELECT member_id, refid FROM member ";
    $sql .= " WHERE refid = '$member_id'";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);
    if ($DEBUG)
      {
        if ((!$result)|| (mysql_errno($db)))
          {
            printf("SQL:   %s\n",$sql);
            printf("ERRNO: %s\n",mysql_errno($db));
            printf("ERR:   %s\n",mysql_error($db));
            return NULL;
          }
      }
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
          {
            // recursive execution of this function for each
            // child of this node

            if ($HIDE_DISABLED)
              {
                 if ($myrow["member_disabled"] == 0)
                   $rgt = private_rebuild($db, $myrow["member_id"], $rgt);
              }
            else
              {
                 $rgt = private_rebuild($db, $myrow["member_id"], $rgt);
              }
          }
      }

    // we've got the left value, and now that we've processed
    // the children of this node we also know the right value
    $sql  = "UPDATE member set ";
    $sql .= " lft='$lft', ";
    $sql .= " rgt='$rgt' ";
    $sql .= " WHERE member_id = '$member_id'";
    $result = exec_query($sql,$db);
    if ($DEBUG)
      {
        if ((!$result)|| (mysql_errno($db)))
          {
            printf("SQL:   %s\n",$sql);
            printf("ERRNO: %s\n",mysql_errno($db));
            printf("ERR:   %s\n",mysql_error($db));
            return NULL;
          }
      }

     // return the right value of this node + 1
    return $rgt+1;
  }


function tree_Rebuild($db, $member_id="pushy", $lft=0)
  {
    global $TRACE;
    global $DEBUG;

    if ($TRACE)
      {
        printf("\n   ... rebuild_tree(%s,%s);\n",$member_id,$lft);
      }

    //---------------------------------------
    $sql="LOCK TABLES member WRITE";
    $result=exec_query($sql,$db);
    //---------------------------------------

    private_rebuild($db, $member_id, $lft);

    //---------------------------------------
    exec_query("UNLOCK TABLES",$db);
    //---------------------------------------
  }



function tree_show($db, $root, $format=0)
  {
    global $DEBUG;
    global $TRACE;
    global $HIDE_DISABLED;

    if ($TRACE)
      {
        // printf("\n   ... show_tree(%s);\n",$root);
      }

    $sql  = "SELECT lft, rgt FROM member ";
    $sql .= " WHERE  member_id='$root'";
    if ($HIDE_DISABLED)
       $sql .= " AND member_disabled=0";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
      {
        $lft=$myrow["lft"];
        $rgt=$myrow["rgt"];

        // start with an empty $rgt stack
        $rightNodes = array();

        // now, retrieve all descendants of the $root node
        $sql  = "SELECT member_id, lft, rgt FROM member ";
        $sql .= " WHERE lft BETWEEN $lft AND $rgt";
        $sql .= " AND member_id != '$root'";  // EXCLUDE SELF
                while ((count($rightNodes)>0) && $rightNodes[count($rightNodes)-1]<$myrow['rgt'])
                  {
                    array_pop($rightNodes);
                  }
           $sql .= " AND member_disabled=0";
        $sql .= " ORDER by lft ASC";
        $result = exec_query($sql,$db);

        if ($result)
          {
            // display each row
            while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
              {
                // only check stack if there is one
                while ((count($rightNodes)>0) && $rightNodes[count($rightNodes)-1]<$myrow['rgt'])
                  {
                    array_pop($rightNodes);
                  }

                // display indented node member_id
                // echo str_repeat('   ',count($rightNodes))."(".count($rightNodes).")   " .$myrow['member_id']."  L=".$myrow['lft']."  R=".$myrow['rgt']."\n";

                $level = count($rightNodes);

                $member_id = $myrow['member_id'];
                $tree[$level][]= $member_id;

                // add this node to the stack
                $rightNodes[] = $myrow['rgt'];
              }
          }
      }

    if (is_array($tree))
      {
        //print_r($tree);

        while (list($level, $xArray) = each($tree))
          {
            printf("(%s)    ",$level);

            for ($j=0; $j < ((10-$level)*2); $j++)
              {
                printf(" ");
              }

            while (list ($x,$element) = each($xArray) )
              {
                $member_id = $element;
                printf("%s   ",$member_id);
              }

            printf("\n");

          }
      }
  }



//================================== MEMBER ID CREATION ================================

function genMID($last_insert)
 {
   $vchars="abcdefghjkmnprstvwxy";
   $vlen=strlen($vchars);

   $c0 = $vchars[rand(0,$vlen-1)];

   $c1 = $vchars[rand(0,$vlen-1)];
   while($c1 == $c0)
     $c1 = $vchars[rand(0,$vlen-1)];

   $c2 = $vchars[rand(0,$vlen-1)];
   while($c2==$c0 || $c2==$c1)
     $c2 = $vchars[rand(0,$vlen-1)];

   $c3 = $vchars[rand(0,$vlen-1)];
   while($c3==$c0 || $c3==$c1 || $c3==$c2)
     $c3 = $vchars[rand(0,$vlen-1)];

   $x=rand(1,4);
   if ($x==1)
      $mid = $c0.$last_insert.$c1.$c2.$c3;
   else
   if ($x==2)
      $mid = $c0.$last_insert.$c2.$c3;
   else
   if ($x==3)
      $mid = $c0.$c1.$c2.$last_insert.$c3;
   else
      $mid = $c0.$c1.$c2.$last_insert;

   return $mid;
 }


function newMemberID($db)
 {
   $sql  = "SELECT record_id FROM member ORDER BY record_id DESC LIMIT 1";
   $result = mysql_query($sql,$db);
   if ($result && ($myrow = mysql_fetch_array($result)))
     $last_insert=$myrow[0];

   $mid = genMID($last_insert);
   while(TRUE)
     {
       $sql  = "SELECT member_id FROM member WHERE member_id='$mid'";
       $result = mysql_query($sql,$db);
       if ($result && ($myrow = mysql_fetch_array($result)))
         {
           // already exists
           $mid = genMID($last_insert);
         }
       else
         break;
     }
   return $mid;
 }

?>
