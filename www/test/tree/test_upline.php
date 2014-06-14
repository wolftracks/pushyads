<?php

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

// include_once("./pushy_tree.inc");
 include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();


$member_id="mg1251005566198572";
$member_id="t1486ja";

//   printf("\n\n --- tree_display -------------------------------------------------------------\n");
//   tree_display($db, $PUSHY_ROOT);

     // printf("\n\n --- tree_show ----------------------------------------------------------------\n");
     // tree_show   ($db, $member_id);

     // printf("\n\n --- tree_displayLeafNodes ----------------------------------------------------\n");
     // tree_displayLeafNodes($db);

//   printf("\n\n --- tree_hasDescendants ------------------------------------------------------\n");
//   $hasDescendants  = tree_hasDescendant($db, $member_id);
//   printf("  hasDescendants=%s\n",($hasDescendants?"YES":"NO"));

//   printf("\n\n --- tree_getDescendantCounts -------------------------------------------------\n");
//   $counts = tree_getDescendantCounts($db, $member_id);
//   print_r($counts);

//   printf("\n\n --- tree_numDescendants  VIP   -----------------------------------------------\n");
//   $descendants_0   = tree_numDescendants($db, $member_id, $PUSHY_LEVEL_VIP);
//   printf("   ==> %d\n",$descendants_0);

//   printf("\n\n --- tree_numDescendants  PRO   -----------------------------------------------\n");
//   $descendants_1   = tree_numDescendants($db, $member_id, $PUSHY_LEVEL_PRO);
//   printf("   ==> %d\n",$descendants_1);

//   printf("\n\n --- tree_numDescendants  ELITE -----------------------------------------------\n");
//   $descendants_2   = tree_numDescendants($db, $member_id, $PUSHY_LEVEL_ELITE);
//   printf("   ==> %d\n",$descendants_2);

//   printf("\n\n --- tree_numDescendants  ALL   -----------------------------------------------\n");
//   $descendants_ALL = tree_numDescendants($db, $member_id, $PUSHY_LEVEL_ALL);
//   printf("   ==> %d\n",$descendants_ALL);

     printf("\n\n --- tree_showUpline  ---------------------------------------------------------\n");
     tree_showUpline($db, $member_id);

//   printf("\n\n --- tree_getUpline -----------------------------------------------------------\n");
//   $upline = tree_getUpline($db,$member_id, 2);
//   printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),print_r($upline,TRUE));

     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "LT", $PUSHY_LEVEL_VIP);
     $upline = tree_findFirstUplineLevel($db,$member_id, "LT", $PUSHY_LEVEL_VIP);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "LT", $PUSHY_LEVEL_PRO);
     $upline = tree_findFirstUplineLevel($db,$member_id, "LT", $PUSHY_LEVEL_PRO);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "LT", $PUSHY_LEVEL_ELITE);
     $upline = tree_findFirstUplineLevel($db,$member_id, "LT", $PUSHY_LEVEL_ELITE);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));

     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "LE", $PUSHY_LEVEL_VIP);
     $upline = tree_findFirstUplineLevel($db,$member_id, "LE", $PUSHY_LEVEL_VIP);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "LE", $PUSHY_LEVEL_PRO);
     $upline = tree_findFirstUplineLevel($db,$member_id, "LE", $PUSHY_LEVEL_PRO);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "LE", $PUSHY_LEVEL_ELITE);
     $upline = tree_findFirstUplineLevel($db,$member_id, "LE", $PUSHY_LEVEL_ELITE);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));

     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "EQ", $PUSHY_LEVEL_VIP);
     $upline = tree_findFirstUplineLevel($db,$member_id, "EQ", $PUSHY_LEVEL_VIP);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "EQ", $PUSHY_LEVEL_PRO);
     $upline = tree_findFirstUplineLevel($db,$member_id, "EQ", $PUSHY_LEVEL_PRO);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "EQ", $PUSHY_LEVEL_ELITE);
     $upline = tree_findFirstUplineLevel($db,$member_id, "EQ", $PUSHY_LEVEL_ELITE);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));

     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "GE", $PUSHY_LEVEL_VIP);
     $upline = tree_findFirstUplineLevel($db,$member_id, "GE", $PUSHY_LEVEL_VIP);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "GE", $PUSHY_LEVEL_PRO);
     $upline = tree_findFirstUplineLevel($db,$member_id, "GE", $PUSHY_LEVEL_PRO);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "GE", $PUSHY_LEVEL_ELITE);
     $upline = tree_findFirstUplineLevel($db,$member_id, "GE", $PUSHY_LEVEL_ELITE);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));

     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "GT", $PUSHY_LEVEL_VIP);
     $upline = tree_findFirstUplineLevel($db,$member_id, "GT", $PUSHY_LEVEL_VIP);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "GT", $PUSHY_LEVEL_PRO);
     $upline = tree_findFirstUplineLevel($db,$member_id, "GT", $PUSHY_LEVEL_PRO);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
     printf("\n\n --- tree_findFirstUplineLevel ----- User Level  %s  %d  -------------------------\n", "GT", $PUSHY_LEVEL_ELITE);
     $upline = tree_findFirstUplineLevel($db,$member_id, "GT", $PUSHY_LEVEL_ELITE);
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),(is_array($upline)?"  ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));



//   printf("\n\n --- tree_getUplineIds -----------------------------------------------------------\n");
//   $upline = tree_getUpline($db,$member_id, 2);
//   printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),print_r($upline,TRUE));

     /****
     printf("\n\n --- tree_getParentMemberId ---------------------------------------------------\n");
     $parent1 = tree_getParentMemberId($db, $member_id);
     $parent2 = tree_getRefId($db, $member_id);
     printf(" %10s - %10s\n",$parent1,$parent2);

     printf("\n\n --- tree_getNodeLevelForMember  ----------------------------------------------\n");
     // $level=tree_getNodeLevelForMember($db,'drsj214876');
     $level=tree_getNodeLevelForMember($db,$member_id);
     printf("   ==> %d\n",$level);
      ****/

exit;
?>
