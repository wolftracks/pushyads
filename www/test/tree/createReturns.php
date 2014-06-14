<?php
 $DEBUG=FALSE;
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");
 include_once("tree_gen_options.php");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $sql  = "SELECT * from receipts ";
 $sql .= " WHERE txtype=0";
 $sql .= " AND yymmdd <= '2009-10-12'";
 $sql .= " ORDER by yymmdd";
 $result = mysql_query($sql,$db);

 $count=0;
 $cbcount=0;

 printf("SQL: %s\n",$sql);
 printf("ERR: %s\n",mysql_error());

 if (($result) && mysql_num_rows($result) > 0)
   {
     while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
       {
         $member_id      = $myrow["member_id"];
         $yymmdd         = $myrow["yymmdd"];
         $firstname      = stripslashes($myrow["firstname"]);
         $lastname       = stripslashes($myrow["lastname"]);
         $refid          = $myrow["refid"];
         $amount         = $myrow["amount"];
         $user_level     = $myrow["user_level"];
         $receiptid      = $myrow["receiptid"];
         $transaction    = $myrow["transaction"];

         $count++;

         if ((!startsWith($refid,"mg") && $count % 5 == 0) || (startsWith($refid,"mg") && $count % 3 == 0))
           {
             $dateArray = dateToArray($yymmdd);
             $dateArray = calStepDays(rand(1,3),$dateArray);

             $new_yymmdd = dateArrayToString($dateArray);
             $txtype=rand(1,3);
             if ($txtype==1)
               {
                 $new_amount  = -(round($amount/2, 2));
                 $new_txtype  = $TXTYPE_CREDIT;
                 $description = "Crediting this guy fome some reason";
               }
             else
             if ($txtype==2)
               {
                 $new_amount = -$amount;
                 $new_txtype = $TXTYPE_VOID;
                 $description = "Voiding this Trasaction for whatever reason";
               }
             else
             if ($txtype==3)
               {
                 $new_amount = -$amount;
                 $new_txtype = $TXTYPE_CHARGE_BACK;
                 $description = "Customer Charge Back - blah,blah";
               }

             $orig_transaction = $transaction;
             $orig_receiptid   = $receiptid;
             $orig_yymmdd      = $yymmdd;

             $x_auth_code = "XVT".rand(1257,8659);
             $x_trans_idd = "YPRTW".rand(1257,8659);


             $sql  = "INSERT into receipts set";
             $sql .= " txtype        = $txtype,";
             $sql .= " src           = 1,";
             $sql .= " receiptid     = '$new_receiptid',";
             $sql .= " refid         = '$refid',";

             //----------------------------------------------------------

             $sql .= " order_type           = '',";
             $sql .= " product_title        = '',";
             $sql .= " product_description  = '$description',";
             $sql .= " amount               = $new_amount,";
             $sql .= " is_prorated          = 0,";

             $sql .= " ts_order             = ".time().",";
             $sql .= " yymmdd               = '$new_yymmdd',";
             $sql .= " yymm                 = '".substr($new_yymmdd,0,7)."',";

             $sql .= " member_id            = '$member_id',";
             $sql .= " firstname            = '".addslashes($firstname)."',";
             $sql .= " lastname             = '".addslashes($lastname)."',";
             $sql .= " user_level           = '".$user_level."',";

             $sql .= " paymentmethod        = '',";
             $sql .= " authorizationcode    = '$x_auth_code',";
             $sql .= " transaction          = '$x_trans_id',";

             $sql .= " orig_transaction     = '$orig_transaction',";
             $sql .= " orig_receiptid       = '$orig_receiptid',";
             $sql .= " orig_yymmdd          = '$orig_yymmdd',";

             $sql .= " ipaddr               = '0.0.0.0'";

             $res = mysql_query($sql,$db);

             if (mysql_errno($db) == 0)
               {

             printf(" %s  %s  %s  %s  %s   %s  %s  %s\n",
                  $refid,
                  $yymmdd,
                  $member_id,
                  $firstname,
                  $lastname,
                  $amount,
                  $user_level,
                  $receiptid,
                  $transaction);



               }
             else
               {
                  printf("SQL: %s\n",$sql);
                  printf("ERR: %s\n",mysql_error());

                  exit;
               }
           }
       }
   }

?>
