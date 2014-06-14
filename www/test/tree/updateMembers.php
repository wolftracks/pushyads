<?php
 $DEBUG=FALSE;
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");
 include_once("tree_gen_options.php");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $count=0;

 $sql = "SELECT * from member WHERE registered>0 && user_level>0";
 $memberResult = mysql_query($sql,$db);
 if (($memberResult) && mysql_num_rows($memberResult) > 0)
   {
     while ($memberRecord=mysql_fetch_array($memberResult,MYSQL_ASSOC))
       {
         $member_id            = $memberRecord["member_id"];
         $refid                = $memberRecord["refid"];
         $user_level           = $memberRecord["user_level"];

         $firstname            = stripslashes($memberRecord["firstname"]);
         $lqstname             = stripslashes($memberRecord["lastname"]);

         $city                 = stripslashes($memberRecord["city"]);
         $state                = stripslashes($memberRecord["state"]);
         $country              = stripslashes($memberRecord["country"]);
         $email                = strtolower(stripslashes($memberRecord["email"]));
         $phone                = $memberRecord["phone"];

         $cc_holdername        = stripslashes($memberRecord["cc_holdername"]);
         $cc_address           = stripslashes($memberRecord["cc_address"]);
         $cc_number            = $memberRecord["cc_number"];
         $cc_expmmyyyy         = $memberRecord["cc_expmmyyyy"];
         $cc_zip               = $memberRecord["cc_zip"];
         $cc_cvv2              = $memberRecord["cc_cvv2"];


         $cc_number  = rand(1,2)==2?"5":"4";
         $cc_number .= rand(1,2)==2?"5":"4";
         $cc_number .= rand(1,9);
         $cc_number .= rand(1,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);
         $cc_number .= rand(0,9);

         $cc_expmmyyyy = sprintf("%02d",rand(1,12));
         $cc_expmmyyyy .= '-20';
         $cc_expmmyyyy .= sprintf("%02d",rand(10,14));

         $cc_address  = $memberRecord["address1"];
         $cc_cvv2     = rand(109,985);

         $cc_holdername = $memberRecord["firstname"]." ".$memberRecord["lastname"];

         $cc_expmm             = (int) substr($cc_expmmyyyy,0,2);
         $cc_expyyyy           = (int) substr($cc_expmmyyyy,3);

         $last_payment_date    = $memberRecord["last_payment_date"];
         $next_payment_due     = $memberRecord["next_payment_due"];

         $cc_zip  = rand(2,9);
         $cc_zip .= rand(1,9);
         $cc_zip .= rand(1,9);
         $cc_zip .= rand(1,9);
         $cc_zip .= rand(1,9);

         $lastPaymentDate      = getDateTodayAsArray();
         $m=rand(1,30);
         $lastPaymentDate=calStepDays(-$m,$lastPaymentDate);
         $nextPaymentDate   = getNextPaymentDueDate($lastPaymentDate);
         $last_payment_date = dateArrayToString($lastPaymentDate);
         $next_payment_due  = dateArrayToString($nextPaymentDate);


         //-------------------- MEMBER PAYMENT UPDATE --------------------------------------
         $affected_rows=0;
         $sql  = "UPDATE member set ";
         $sql .= " cc_holdername     = '$cc_holdername',";
         $sql .= " cc_address        = '$cc_address',";
         $sql .= " cc_number         = '$cc_number',";
         $sql .= " cc_expmmyyyy      = '$cc_expmmyyyy',";
         $sql .= " cc_zip            = '$cc_zip',";
         $sql .= " zip               = '$cc_zip',";
         $sql .= " cc_cvv2           = '$cc_cvv2',";
         $sql .= " last_payment_date = '$last_payment_date',";
         $sql .= " next_payment_due  = '$next_payment_due'";
         $sql .= " WHERE member_id='$member_id'";
         $res = mysql_query($sql,$db);


         $count++;
         if ($count <= 3)
            printf("SQL: %s\n",$sql);

       }
   }

?>
