<?php
   require_once("pushy_common.inc");
   require_once("pushy_commonsql.inc");
   require_once("pushy.inc");
   require_once("pushy_sendmail.inc");
   require_once("pushy_tree.inc");
   require_once("pushy_tracker.inc");
   $StatusMessage="";

   $op=$_REQUEST["op"];

   $notification_list = array(
     "mark"        => EMAIL_MARK,
     "tim"         => EMAIL_TIM
   );

   $db = getPushyDatabaseConnection();

   $dateArray=getDateTodayAsArray();
   $calData=calendar($dateArray);
   $dow=$calData["DayOfWeek"];

   if (!isset($op) || strlen($op)==0)
     {
       $op="MAIN";
     }

   if ($op=="OrderActivity")
     {
       include("orderactivity.php");
       exit;
     }

   if ($op=="Registrations")
     {
       include("registrations.php");
       exit;
     }

   if ($op=="Signins")
     {
       include("signins.php");
       exit;
     }


   if ($op=="ListMembers")
     {
       $start_select_value = "";
       $end_select_value = "";
       if (isset($Resume))
         {
           $start_select_value=$SaveStartSelectValue;
           $end_select_value=$SaveEndSelectValue;
           $field_selected=$SaveSelectField;
         }

             if (isset($A))   $start_select_value = "A";
       else  if (isset($B))   $start_select_value = "B";
       else  if (isset($C))   $start_select_value = "C";
       else  if (isset($D))   $start_select_value = "D";
       else  if (isset($E))   $start_select_value = "E";
       else  if (isset($F))   $start_select_value = "F";
       else  if (isset($G))   $start_select_value = "G";
       else  if (isset($H))   $start_select_value = "H";
       else  if (isset($I))   $start_select_value = "I";
       else  if (isset($J))   $start_select_value = "J";
       else  if (isset($K))   $start_select_value = "K";
       else  if (isset($L))   $start_select_value = "L";
       else  if (isset($M))   $start_select_value = "M";
       else  if (isset($N))   $start_select_value = "N";
       else  if (isset($O))   $start_select_value = "O";
       else  if (isset($P))   $start_select_value = "P";
       else  if (isset($Q))   $start_select_value = "Q";
       else  if (isset($R))   $start_select_value = "R";
       else  if (isset($S))   $start_select_value = "S";
       else  if (isset($T))   $start_select_value = "T";
       else  if (isset($U))   $start_select_value = "U";
       else  if (isset($V))   $start_select_value = "V";
       else  if (isset($W))   $start_select_value = "W";
       else  if (isset($X))   $start_select_value = "X";

       if (!isset($Resume))
         {
           if (strlen($start_select_value) >= 1)
             {
               if ($start_select_value == "X")
                 $end_select_value = "";
               else
                 $end_select_value = $start_select_value."ZZZZZZZZZZZZZZZZZ";
             }
           else
             {  // ---- ALL
               $start_select_value = "";
               $end_select_value   = "";
             }
         }

       $SelectField = $field_selected;

       $member_id_checked  = "";
       $user_level_checked = "";
       $firstname_checked  = "";
       $lastname_checked   = "";
       $password_checked   = "";
       $refid_checked      = "";
       $affstatus_checked  = "";
       $date_registered_checked = "";
       $date_lastaccess_checked = "";

       $SORTDIR="ASC";

            if ($SelectField == "member_id"   )    { $member_id_checked       = "checked"; }
       else if ($SelectField == "user_level"  )    { $user_level_checked      = "checked"; }
       else if ($SelectField == "firstname"   )    { $firstname_checked       = "checked"; }
       else if ($SelectField == "password"    )    { $password_checked        = "checked"; }
       else if ($SelectField == "refid"       )    { $refid_checked           = "checked"; }
       else if ($SelectField == "aff_status"  )    { $aff_status_checked      = "checked"; $start_select_value="";  $end_select_value=""; $SORTDIR="DESC";}
       else if ($SelectField == "date_registered") { $date_registered_checked = "checked"; $SORTDIR="DESC";}
       else if ($SelectField == "date_lastaccess") { $date_lastaccess_checked = "checked"; $SORTDIR="DESC";}

       else { $lastname_checked       = "checked"; }

       setCookie("SaveStartSelectValue",$start_select_value);
       setCookie("SaveEndSelectValue",$end_select_value);
       setCookie("SaveSelectField",$SelectField);


       if (FALSE)
         {
           printf(" member_id_checked   =  (%s)<br>\n",$member_id_checked );
           printf(" user_level_checked  =  (%s)<br>\n",$user_level_checked);
           printf(" firstname_checked   =  (%s)<br>\n",$firstname_checked );
           printf(" lastname_checked    =  (%s)<br>\n",$lastname_checked  );
           printf(" password_checked    =  (%s)<br>\n",$password_checked  );
           printf(" refid_checked       =  (%s)<br>\n",$refid_checked     );
           printf(" aff_status_checked  =  (%s)<br>\n",$aff_status_checked);
           printf(" date_registered_checked  =  (%s)<br>\n",$date_registered_checked);
           printf(" date_lastaccess_checked  =  (%s)<br>\n",$date_lastaccess_checked);

           if (is_array($_REQUEST) && count($_REQUEST) > 0)
             {
               printf("-------- REQUEST VARS -- (Get/Post/Cookie/Files) ---<br>\n");
               while (list($key00, $value00) = each($_REQUEST))
                 {
                   printf("%s=%s<br>\n",$key00,$value00);
                 }
               printf("<br><br><br>\n");
             }
         }


       include("memberlist.php");

       exit;
     }

   if ($op=="MemberMail")
     {
       include("member_mail.php");
       exit;
     }


   if ($op=="SendMemberEmail")
     {
       $sql  = "SELECT * from member";
       $sql .= " WHERE member_id='$PushyAdsMemberId'";
       $sql .= " AND member_disabled=0";
       $result = exec_query($sql,$db);

       // printf("SQL: %s<br>\n",$sql);
       // printf("ERR: %s<br>\n",mysql_error());
       // exit;

       if ($result)
         {
           $myrow  = mysql_fetch_array($result);
           if ($myrow)
             {
               $member_firstname   = stripslashes($myrow["firstname"]);
               $member_lastname    = stripslashes($myrow["lastname"]);
               $member_email       = strtolower(stripslashes($myrow["email"]));
               $member_id          = $myrow["member_id"];
               $signin_id          = $member_email;
               $member_password    = stripslashes($myrow["password"]);
               $affiliate_id       = $myrow["affiliate_id"];
               $affiliate_website  = DOMAIN."/".$affiliate_id;

               $in_subject = str_replace("%firstname%",         $member_firstname,  $in_subject);
               $in_subject = str_replace("%lastname%",          $member_lastname,   $in_subject);
               $in_subject = str_replace("%email%",             $member_email,      $in_subject);
               $in_subject = str_replace("%uid%",               $member_email,      $in_subject);
               $in_subject = str_replace("%password%",          $member_password,   $in_subject);
               $in_subject = str_replace("%affiliate_id%",      $affiliate_id,      $in_subject);
               $in_subject = str_replace("%affiliate_website%", $affiliate_website, $in_subject);

               $in_message = str_replace("%firstname%",         $member_firstname,  $in_message);
               $in_message = str_replace("%lastname%",          $member_lastname,   $in_message);
               $in_message = str_replace("%email%",             $member_email,      $in_message);
               $in_message = str_replace("%uid%",               $member_email,      $in_message);
               $in_message = str_replace("%password%",          $member_password,   $in_message);
               $in_message = str_replace("%affiliate_id%",      $affiliate_id,      $in_message);
               $in_message = str_replace("%affiliate_website%", $affiliate_website, $in_message);
             }
         }
       else
         {
           printf("MEMBER NOT FOUND: %s\n",$PushyAdsMemberId);
           exit;
         }


       //-------------------------------------------------------
       include("users.php");

       $db=getPushyDatabaseConnection();

       $service_id = newServiceID();
       $seq=1;

       $dt=getDateToday();
       $tm=time();
       $sql  = "INSERT into service set";
       $sql .= " service_id='$service_id',";
       $sql .= " seq=$seq,";
       $sql .= " date_received='$dt',";
       $sql .= " ts_request=$tm,";
       $sql .= " ts_response=$tm,";
       $sql .= " member_id='$PushyAdsMemberId',";
       $sql .= " firstname='$firstname',";
       $sql .= " lastname='$lastname',";
       $sql .= " email='$email',";
       $sql .= " subject='".addslashes(stripslashes($in_subject))."',";
       $sql .= " request='-- Customer Email --',";
       $sql .= " assignee='',";
       $sql .= " responder='',";
       $sql .= " response='".addslashes(stripslashes($in_message))."'";
       $result=exec_query($sql,$db);

       // printf("SQL: %s<br>\n",$sql);
       // printf("ERR: %s<br>\n",mysql_error());

       $custname=$firstname." ".$lastname;

       $msg     = $in_message."\r\n".$trailer;

       $fromName  = "PushyAds Customer Service";

       send_mail_direct($custname, $email, $fromName, EMAIL_NOREPLY, $in_subject, $msg);

       $ulist = explode("/", $COPYLIST);
       for ($i=0; $i<count($ulist); $i++)
         {
           $user=$ulist[$i];
           if (strlen($user) > 0 && isset($users[$user]))
             {
               $em = $users[$user];
               send_mail_direct($user, $em, $fromName, EMAIL_NOREPLY, $in_subject, $msg);
             }
         }

       $in_member_id  = $PushyAdsMemberId;

       $MESSAGE_SENT = TRUE;

       $op="OpenMember";
     }



   if ($op=="RemoveMember")   //------------------------------------ UNSUBSCRIBE MEMBER FROM MAILINGS
     {
       $tm=time();
       $sql  = "UPDATE member set member_disabled=$tm";
       $sql .= " WHERE member_id='$in_member_id'";
       $sql .= " AND member_disabled=0";
       $result = exec_query($sql,$db);

       $op="OpenMember";
     }


   if ($op=="UpdateMember")
     {
       $exists=FALSE;
       $oldpassword  = "";
       if (!is_array($memberRecord=getMemberInfo($db,$in_member_id)))
         {
           $StatusMessage = "The member '$in_member_id' does not exist";
           include "member.php";
           exit;
         }

       $sql  = "UPDATE member set";
       $sql .= " signin_id      = '$in_signin_id',";
       $sql .= " password       = '$in_password',";
 //    $sql .= " refid          = '',";        -- must be updated by Tim Manually  - this is and MUST BE an INTERNAL ID (signin IDs CAN CHANGE)
       $sql .= " firstname      = '".addslashes($in_firstname)."',";
       $sql .= " lastname       = '".addslashes($in_lastname)."',";

 //----- READ ONLY From ADMIN ---------
 //    $sql .= " address1       = '".addslashes($in_address1)."',";
 //    $sql .= " address2       = '".addslashes($in_address2)."',";
 //    $sql .= " city           = '".addslashes($in_city)."',";
 //    $sql .= " state          = '".addslashes($in_state)."',";
 //    $sql .= " country        = '".addslashes($in_country)."',";
 //    $sql .= " zip            = '".addslashes($in_zip)."',";

       $sql .= " email          = '$in_email',";
       $sql .= " phone          = '".getDigits($in_phone)."'";
       $sql .= " WHERE member_id='$in_member_id'";
       $result = exec_query($sql,$db);

       // printf("SQL: %s<br>\n",$sql);
       // printf("ERR: %s<br>\n",mysql_error());

       if ($result)
         {
           $StatusMessage="Member Updated";
         }
       else
         {
           $StatusMessage="Member Update Failed";
         }

       $op="OpenMember";
     }


   if ($op=="OpenMember")
     {
       include("member.php");
       exit;
     }


   if ($op=="DeleteMember")
     {
       // $StatusMessage = "This function has been disabled ";
       // include "member.php";
       // exit;

       if (TRUE)
         {
           $exists=FALSE;
           $sql  = "SELECT * FROM member";
           $sql .= " WHERE member_id='$in_member_id'";
           $result = exec_query($sql,$db);
           if ($result)
             {
               $myrow  = mysql_fetch_array($result);
               if ($myrow)
                 {
                   $exists=TRUE;
                 }
             }

//         if (!$exists)
//           {
//             $StatusMessage = "The member '$in_member_id' does not exist";
//             include "member.php";
//             exit;
//           }

           $result = tree_deleteNode($db, $in_member_id);
           if (TRUE)
//         if ($result)
             {

               $sql  = "SELECT product_id,product_name from product ";
               $sql .= " WHERE product_owner='$in_member_id'";
               $res  = mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br><br>\n",mysql_error());
               if (($res) && (($pcount=mysql_num_rows($res)) > 0))
                 {
                   while ($productRecord=mysql_fetch_array($res,MYSQL_ASSOC))
                     {
                       $product_id   = $productRecord["product_id"];
                       $product_name = stripslashes($productRecord["product_name"]);

                       //--- In a DownGrade - All Ads Are Deleted and Any Products that you OWN are - Regardless of the Traget Downgrade Level (VIP,PRO)
                       //---     REASSIGNED to PUSHY if there are resellers (ads referencing it)
                       //---     DELETED             if there are NO resellers (ads referencing it)

                       // Must deal with the Product First Before Removing any Ads
                       $action=retireProduct($db,$member_id,$product_id);
                       if ($action==1)
                         {
                           $lineout  = sprintf("-- Product DELETED    for Member '$member_id':  product_id($product_id) product_name($product_name)\n");
                           echo $lineout;
                         }
                       else
                         {
                           $lineout  = sprintf("-- Product REASSIGNED for Member '$member_id':  product_id($product_id) product_name($product_name)\n");
                           echo $lineout;
                         }
                     }
                 }


               $tracker_tables = array(
                  "tracker_affiliate_page",
                  "tracker_elite_bar",
                  "tracker_keys",
                  "tracker_pushy_ads",
                  "tracker_pushy_ads_mysites",
                  "tracker_pushy_ads_referrals",
                  "tracker_pushy_widget",
                  "tracker_ad_category",
                  "tracker_widget_category"
               );

               for ($j=0; $j<count($tracker_tables); $j++)
                 {
                    $table = $tracker_tables[$j];
                    $sql = "DELETE from $table where member_id='$in_member_id'";
                    $res=mysql_query($sql,$db);
                        printf("SQL: %s<br>\n",$sql);
                        printf("ERR: %s<br>\n",mysql_error());
                        printf("ROWS: %d<br><br>\n",mysql_affected_rows());
                 }


               $sql = "DELETE from product_pending where product_owner='$in_member_id'";
               $res=mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br>\n",mysql_error());
                   printf("ROWS: %d<br><br>\n",mysql_affected_rows());
               $sql = "DELETE from ads where member_id='$in_member_id'";
               $res=mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br>\n",mysql_error());
                   printf("ROWS: %d<br><br>\n",mysql_affected_rows());
               $sql = "DELETE from credit_map where member_id='$in_member_id'";
               $res=mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br>\n",mysql_error());
                   printf("ROWS: %d<br><br>\n",mysql_affected_rows());
               $sql = "DELETE from earnings_semi_monthly where member_id='$in_member_id'";
               $res=mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br>\n",mysql_error());
                   printf("ROWS: %d<br><br>\n",mysql_affected_rows());
               $sql = "DELETE from resources where member_id='$in_member_id'";
               $res=mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br>\n",mysql_error());
                   printf("ROWS: %d<br><br>\n",mysql_affected_rows());
               $res=mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br>\n",mysql_error());
                   printf("ROWS: %d<br><br>\n",mysql_affected_rows());
               $sql = "DELETE from widget where member_id='$in_member_id'";
               $res=mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br>\n",mysql_error());
                   printf("ROWS: %d<br><br>\n",mysql_affected_rows());
               $sql = "DELETE from userlevel_change_requests where member_id='$in_member_id'";
               $res=mysql_query($sql,$db);
                   printf("SQL: %s<br>\n",$sql);
                   printf("ERR: %s<br>\n",mysql_error());
                   printf("ROWS: %d<br><br>\n",mysql_affected_rows());

               $StatusMessage="Member Deleted";
             }
           else
             {
               $StatusMessage="Member Delete Failed";
             }

           $registered=0;
           $lastaccess=0;
           include("member.php");
           exit;
         }
     }


   if ($op=="SendList")
     {
       include("sendlist.php");
       exit;
     }

   include("main.php");

   exit;
?>
