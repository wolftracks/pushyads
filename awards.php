<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

// $db = getPushyDatabaseConnection();

    $dbhost     = "pushyads.com";
    $dbname     = "pushyads";
    $dbuser     = "tjwolf";
    $dbpassword = "dragon";
    $db = mysql_pconnect($dbhost, $dbuser, $dbpassword);
    mysql_select_db($dbname,$db);


$sql  = "SELECT * from member where registered > 0";
$result=mysql_query($sql,$db);

if (FALSE)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }

if ($result)
  {
    $count=0;
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {
         $firstname  =  stripslashes($myrow["firstname"]);
         $lastname   =  stripslashes($myrow["lastname"]);
         $member_id  =  $myrow["member_id"];
         $email      =  $myrow["email"];
         $user_level =  $myrow["user_level"];
         $awards     =  $myrow["awards"];

         printf("%-9s :  %d   %s\n",$member_id,$user_level,$awards);

         if (is_integer(strpos($awards,"102")))
           {
             giveMemberAward($db,$member_id,"200");
             giveMemberAward($db,$member_id,"201");
             giveMemberAward($db,$member_id,"202");
           }
         if (is_integer(strpos($awards,"103")))
           {
             giveMemberAward($db,$member_id,"200");
             giveMemberAward($db,$member_id,"201");
             giveMemberAward($db,$member_id,"202");
             giveMemberAward($db,$member_id,"203");
           }
         if (is_integer(strpos($awards,"101"))  &&
             is_integer(strpos($awards,"104"))  )
           {
             giveMemberAward($db,$member_id,"200");
             giveMemberAward($db,$member_id,"204");
           }
         if (is_integer(strpos($awards,"101"))  &&
             is_integer(strpos($awards,"105"))  )
           {
             giveMemberAward($db,$member_id,"200");
             giveMemberAward($db,$member_id,"204");
             giveMemberAward($db,$member_id,"205");
           }

        $count++;
      }
  }


?>
