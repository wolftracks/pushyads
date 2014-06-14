<?php
 $DEBUG=FALSE;

 $INCLUDE_ROOT = TRUE;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

 include_once("pushy_tree.inc");
 // include_once("pushy_tree.inc");

 include_once("tree_gen_options.php");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 tree_display($db,$PUSHY_ROOT);
 // tree_show($db,$PUSHY_ROOT);

 $members = selectRandomMembers($db,4);
 for ($i=0; $i<count($members); $i++)
   {
     $member = $members[$i];
     $member_id = $member["member_id"];
     printf("Member Selected: %s\n",$member_id);
   }
 printf("\n\n\n");

 for ($i=0; $i<count($members); $i++)
   {
     $member = $members[$i];
     $member_id = $member["member_id"];
     printf("\n\n\n\n======= MemberId='%s'  ====================================================\n", $member_id);

     printf("\n\n --- tree_display -------------------------------------------------------------\n");
     tree_display($db, $member_id);

     // printf("\n\n --- tree_show ----------------------------------------------------------------\n");
     // tree_show   ($db, $member_id);

     // printf("\n\n --- tree_displayLeafNodes ----------------------------------------------------\n");
     // tree_displayLeafNodes($db);

     printf("\n\n --- tree_hasDescendants ------------------------------------------------------\n");
     $hasDescendants  = tree_hasDescendant($db, $member_id);
     printf("  hasDescendants=%s\n",($hasDescendants?"YES":"NO"));

     printf("\n\n --- tree_getDescendantCounts -------------------------------------------------\n");
     $counts = tree_getDescendantCounts($db, $member_id);
     print_r($counts);

     printf("\n\n --- tree_numDescendants  VIP   -----------------------------------------------\n");
     $descendants_0   = tree_numDescendants($db, $member_id, $PUSHY_LEVEL_VIP);
     printf("   ==> %d\n",$descendants_0);

     printf("\n\n --- tree_numDescendants  PRO   -----------------------------------------------\n");
     $descendants_1   = tree_numDescendants($db, $member_id, $PUSHY_LEVEL_PRO);
     printf("   ==> %d\n",$descendants_1);

     printf("\n\n --- tree_numDescendants  ELITE -----------------------------------------------\n");
     $descendants_2   = tree_numDescendants($db, $member_id, $PUSHY_LEVEL_ELITE);
     printf("   ==> %d\n",$descendants_2);

     printf("\n\n --- tree_numDescendants  ALL   -----------------------------------------------\n");
     $descendants_ALL = tree_numDescendants($db, $member_id, $PUSHY_LEVEL_ALL);
     printf("   ==> %d\n",$descendants_ALL);

     printf("\n\n --- tree_showUpline  ---------------------------------------------------------\n");
     tree_showUpline($db, $member_id);

     printf("\n\n --- tree_getUpline -----------------------------------------------------------\n");
     $upline = tree_getUpline($db,$member_id);
     // $upline = tree_getUpline($db,'drsj214876');
     printf("LEVEL=%d\nUPLINE=\n%s\n",count($upline),print_r($upline,TRUE));

     printf("\n\n --- tree_getParentMemberId ---------------------------------------------------\n");
     $parent1 = tree_getParentMemberId($db, $member_id);
     $parent2 = tree_getRefId($db, $member_id);
     printf(" %10s - %10s\n",$parent1,$parent2);

     printf("\n\n --- tree_getNodeLevelForMember  ----------------------------------------------\n");
     // $level=tree_getNodeLevelForMember($db,'drsj214876');
     $level=tree_getNodeLevelForMember($db,$member_id);
     printf("   ==> %d\n",$level);

   }

 exit;
?>
