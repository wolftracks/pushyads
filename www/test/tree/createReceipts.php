<?php
 $DEBUG=FALSE;
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");
 include_once("tree_gen_options.php");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $sql = "SELECT * from member WHERE registered>0 && user_level>0";
 $memberResult = mysql_query($sql,$db);
 if (($memberResult) && mysql_num_rows($memberResult) > 0)
   {
     while ($memberRecord=mysql_fetch_array($memberResult,MYSQL_ASSOC))
       {
         $member_id      =$myrow["member_id"];
         $firstname      =stripslashes($myrow["firstname"]);
         $lastname       =stripslashes($myrow["lastname"]);
         $refid          =$myrow["refid"];
         $date_registered=$myrow["date_registered"];
         $registered     =$myrow["registered"];

         $user_level     =$myrow["user_level"];

         $invoice        = substr(strtolower($firstname),0,1).substr(strtolower($lastname),0,1).getmicroseconds();
         $receiptid      = strtolower($member_id)."-".getmicroseconds();

         $orderType      = "subscription";
         $orderLevel     = 1;
         $orderAmount    = 47.00;
         $description    = "PRO Membership Fee";

         if (rand(1,2)==2) $payment_method="Visa";  else  $payment_method="MasterCard";
         $x_auth_code = "XVT".rand(1257,8659);
         $x_trans_idd = "YPRTW".rand(1257,8659);

         $tm=$registered;
         $dateToday=$date_registered;
         $dateTime=formatDateTime($registered);

         $mid=$member_id;

         $sql  = "INSERT into receipts set";
         $sql .= " txtype = 0,";
         $sql .= " src = 1,";                       // 1= From Member Site
         $sql .= " receiptid = '$receiptid',";
         $sql .= " refid     = '$refid',";

         $sql .= " ts_order=$tm,";
         $sql .= " yymmdd               = '$dateToday',";
         $sql .= " yymm                 = '".substr($dateToday,0,7)."',";

         $sql .= " member_id            = '$mid',";
         $sql .= " firstname            = '".addslashes($firstname)."',";
         $sql .= " lastname             = '".addslashes($lastname)."',";
         $sql .= " user_level           = '".$orderLevel."',";
         $sql .= " amount               = $orderAmount,";

         //----------------------------------------------------------
         $sql .= " product_type         = '$orderType', ";
         $sql .= " product_name         = 'Pro',";
         $sql .= " product_title        = '".addslashes($description)."',";


         $sql .= " paymentmethod        = '$payment_method',";
         $sql .= " authorizationcode    = '$x_auth_code',";
         $sql .= " transaction          = '$x_trans_id',";
         $sql .= " invoice              = '$invoice'";

         $res=mysql_query($sql,$db);
         if (!$res)
           {
             printf("SQL: %s\n",$sql);
             printf("ERR: %s\n",mysql_error());
             exit;
           }
         printf("Insert: PRO\n");


         if ($user_level == 2)
           {
             $registered += (86400 * rand(13,47));
             $date_registerd=formatDate($registered);

             $invoice        = substr(strtolower($firstname),0,1).substr(strtolower($lastname),0,1).getmicroseconds();
             $receiptid      = strtolower($member_id)."-".getmicroseconds();

             $orderType      = "upgrade";
             $orderLevel     = 2;
             $orderAmount    = 97.00;
             $description    = "ELITE membership fee";

             if (rand(1,2)==2) $payment_method="Visa";  else  $payment_method="MasterCard";
             $x_auth_code = "XVT".rand(1257,8659);
             $x_trans_idd = "YPRTW".rand(1257,8659);

             $tm=$registered;
             $dateToday=$date_registered;
             $dateTime=formatDateTime($registered);

             $mid=$member_id;

             $sql  = "INSERT into receipts set";
             $sql .= " txtype = 0,";
             $sql .= " src = 1,";                       // 1= From Member Site
             $sql .= " receiptid = '$receiptid',";
             $sql .= " refid     = '$refid',";

             $sql .= " ts_order=$tm,";
             $sql .= " yymmdd               = '$dateToday',";
             $sql .= " yymm                 = '".substr($dateToday,0,7)."',";

             $sql .= " member_id            = '$mid',";
             $sql .= " firstname            = '".addslashes($firstname)."',";
             $sql .= " lastname             = '".addslashes($lastname)."',";
             $sql .= " user_level           = '".$orderLevel."',";
             $sql .= " amount               = $orderAmount,";

             //----------------------------------------------------------
             $sql .= " product_type         = '$orderType', ";
             $sql .= " product_name         = 'Pro',";
             $sql .= " product_title        = '".addslashes($description)."',";


             $sql .= " paymentmethod        = '$payment_method',";
             $sql .= " authorizationcode    = '$x_auth_code',";
             $sql .= " transaction          = '$x_trans_id',";
             $sql .= " invoice              = '$invoice'";

             $res=mysql_query($sql,$db);
             if (!$res)
               {
                 printf("SQL: %s\n",$sql);
                 printf("ERR: %s\n",mysql_error());
                 exit;
               }

             printf("Insert: ELITE\n");
           }

       }
   }

?>
