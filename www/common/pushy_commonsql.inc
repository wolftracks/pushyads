<?php
require_once("pushy_imagestore.inc");

$MAX_SESSION_TIME = 21600;        //  6 Hours
$MAX_SESSION_TIME = 28800;        //  8 Hours
$MAX_SESSION_TIME = 86400;        // 24 Hours
$RENEW_SESSION_INCREMENT = 3600;  //  1 Hour


//=========================================================================================================
//
// DATABASE Connections
//
//=========================================================================================================
function getPushyDatabaseConnection()
  {
    $dbhost     = "localhost";

    $dbname     = "pushyads";
    $dbuser     = "tjwolf";
    $dbpassword = "dragon";


 // if (rand(1,2) == 2)
 //    return null;

    $db = mysql_pconnect($dbhost, $dbuser, $dbpassword);
    mysql_select_db($dbname,$db);
    return($db);
  }

function getPushyImageDatabaseConnection()
  {
    $dbhost     = "localhost";

    $dbname     = "pushyimage";
    $dbuser     = "tjwolf";
    $dbpassword = "dragon";

 // if (rand(1,3) == 2)
 //    return null;

    $db = mysql_pconnect($dbhost, $dbuser, $dbpassword);
    mysql_select_db($dbname,$db);
    return($db);
  }


//=========================================================================================================
//
// REDIRECT - ensures you pop out of Frames
//
//=========================================================================================================
function topLevelRedirect($url)
  {
    ?>
      <script type="text/javascript">
      <!---
        top.location.href="<?php echo $url?>";
      //-->
      </script>

    <?php
    exit;
  }



//=========================================================================================================
//
// SESSIONS
//
//=========================================================================================================
function newSession($db, $mid, $isAdmin=FALSE)
  {
    global $MAX_SESSION_TIME;

    $mid=strtolower($mid);

    $sql  = "SELECT * FROM member";
    $sql .= " WHERE member_id='$mid'";
    $memresult = exec_query($sql,$db);

    if (($memresult) && ($memrow = mysql_fetch_array($memresult, MYSQL_ASSOC)))
      {
        $is_admin = 0;
        if ($isAdmin)
          $is_admin = 1;

        $MAX_ATTEMPTS=3;
        for ($i=1; $i<=$MAX_ATTEMPTS; $i++)
          {
            $tm=time();
            $expires=$tm+$MAX_SESSION_TIME;
            $sid=generateSession();

            $sql  = "INSERT into session set";
            $sql .= " session_id='$sid',";
            $sql .= " member_id='$mid',";
            $sql .= " expires='$expires',";
            $sql .= " is_admin='$is_admin'";
            $result = exec_query($sql,$db);

            if (($result) && (mysql_affected_rows()==1))
              {
                if (!$isAdmin)
                  {
                    $sql  = "UPDATE member set";
                    $sql .= " lastaccess='$tm', ";
                    $sql .= " date_lastaccess='".formatDate($tm)."' ";
                    $sql .= " WHERE member_id='$mid'";
                    exec_query($sql,$db);
                  }

                $expired=$tm."ZZZZ";
                // DELETE ANY EXPIRED SESSIONS
                $sql  = "DELETE FROM session";
                $sql .= " WHERE expires < '$expired'";
                $result = exec_query($sql,$db);

                return array(0,$sid);
              }
          }
      }
    return (array(-1,0));
  }


function closeSession($db, $sid)
  {
    $sql  = "SELECT * FROM session";
    $sql .= " WHERE session_id='$sid'";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        $sql  = "DELETE FROM session";
        $sql .= " WHERE session_id='$sid'";
        $result = exec_query($sql,$db);

        topLevelRedirect(DOMAIN);
        exit;
      }
  }


function updateSession($db, $sid, $mid)
  {
    global $MAX_SESSION_TIME;
    global $RENEW_SESSION_INCREMENT;

          // -- IS Member a Valid/Existing One ??
    $sql  = "SELECT member_id FROM member";
    $sql .= " WHERE member_id='$mid'";
    $memresult = exec_query($sql,$db);
    if (($memresult) && ($memberRecord = mysql_fetch_array($memresult, MYSQL_ASSOC)))
      {
        $sql  = "SELECT expires,is_admin FROM session";
        $sql .= " WHERE session_id='$sid'";
        $sql .= " AND   member_id ='$mid'";
        $result = exec_query($sql,$db);
        if (($result) && ($temp = mysql_fetch_array($result, MYSQL_ASSOC)))
          {
            $tm=time();
            $expires=$temp["expires"];
            $isAdminSession=FALSE;
            if ($temp["is_admin"] != 0)
               $isAdminSession=TRUE;
            // printf("EXPIRES : %d<br>\n",$expires);
            // printf("RENEWX : %d<br>\n",($tm+$RENEW_SESSION_INCREMENT));

            if ($expires < ($tm+$RENEW_SESSION_INCREMENT))
              {
                $sql  = "UPDATE session set";
                $sql .= " expires=".($tm+$RENEW_SESSION_INCREMENT).", ";
                $sql .= " WHERE session_id='$sid'";
                $sql .= " AND   member_id='$mid'";
                $result = exec_query($sql,$db);
              }
            return array(0, $isAdminSession);
          }
      }
  }


function extendSession($db, $sid, $mid, $minutes=60)
  {
    $time_extension = $minutes * 60; // in seconds;

    $sql  = "SELECT expires,is_admin FROM session";
    $sql .= " WHERE session_id='$sid'";
    $sql .= " AND   member_id='$mid'";
    $result = exec_query($sql,$db);
    if (($result) && ($temp = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        $tm=time();
        $expires=$temp["expires"];
        $isAdminSession=FALSE;
        if ($temp["is_admin"] != 0)
           $isAdminSession=TRUE;

        if ($expires < ($tm+$time_extension))
          {
            // printf("EXPIRES : %d<br>\n",$expires);
            // printf("RENEWAL : %d<br>\n",($tm+$time_extension));

             $sql  = "UPDATE session set";
             $sql .= " expires=".($tm+$time_extension)." ";
             $result = exec_query($sql,$db);
             if ($result)
               {
                 return (0);
               }
          }
        else
          {
              // Expiration is Still More than "minutes" out - Nothing to do
            return (0);
          }
      }

    return (-1);
  }


function getSession($db, $sid, $mid)
  {
    $sql  = "SELECT * FROM session";
    $sql .= " WHERE session_id='$sid'";
    $sql .= " AND   member_id='$mid'";
    $result = exec_query($sql,$db);

    // dumpSQL($sql,mysql_error(),TRUE);

    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        $expires = $myrow["expires"];
        $isAdminSession=FALSE;
        if ($myrow["is_admin"] != 0)
          $isAdminSession=TRUE;
        $tm=time();
        if ($expires > $tm)
          {
            if ($redirect)
              {
                closeSession($db,$sid,"Session Expired - Please Sign In","");
                exit;
              }
            return array(0, $isAdminSession);
          }

        // EXPIRED - DELETE IT
        $sql  = "DELETE FROM session";
        $sql .= " WHERE session_id='$sid'";
        $sql .= " AND member_id='$mid'";
        $result = exec_query($sql,$db);
      }
    return array(-1,FALSE);
  }


function isAdminSession($db, $sid, $mid)
  {
    $sql  = "SELECT is_admin FROM session";
    $sql .= " WHERE session_id='$sid'";
    $sql .= " AND   member_id='$mid'";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        if ($myrow["is_admin"] != 0)
          return TRUE;
      }
    return FALSE;
  }


//=========================================================================================================
//
// MEMBERS
//
//=========================================================================================================
function getMemberInfo($db, $mid)
  {
    $sql  = "SELECT * from member";
    $sql .= " WHERE member_id='$mid'";
    $result = exec_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return($myrow);
      }
    if ($result)
      mysql_free_result($result);
    return FALSE;
  }


function getMemberInfoForAffiliate($db,$affiliate_id)
  {
    //  $sql  = "SELECT member_id,affiliate_id,firstname,lastname,email from member";
    $sql  = "SELECT * from member";
    $sql .= " WHERE affiliate_id='$affiliate_id'";
    $result = exec_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return($myrow);
      }
    if ($result)
      mysql_free_result($result);
    return FALSE;
  }


function getMemberInfoForEmail($db, $email)
  {
    $sql  = "SELECT * from member";
    $sql .= " WHERE email='$email'";
    $result = exec_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return($myrow);
      }
    if ($result)
      mysql_free_result($result);
    return FALSE;
  }


function getMemberName($db, $mid)
  {
    $name="";
    $sql  = "SELECT lastname, firstname FROM member ";
    $sql .= " WHERE member_id = '$mid'";
    $result = exec_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        $name=getMemberFullName($myrow);
      }
    return $name;
  }


function getMemberFullName($memberRecord)
  {
    $name=stripslashes($memberRecord["firstname"])." ".stripslashes($memberRecord["lastname"]);
    return $name;
  }


function getMemberFirstName($memberRecord)
  {
    $name=stripslashes($memberRecord["firstname"]);
    return $name;
  }


function isSystemUser($db, $mid)
  {
    $sql  = "SELECT member_id from member";
    $sql .= " WHERE member_id='$mid'";
    $sql .= " AND system > 0";
    $result = exec_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return(TRUE);
      }
    return FALSE;
  }


function doesMemberIdExist($db, $mid)
  {
    $sql  = "SELECT firstname from member";
    $sql .= " WHERE member_id='$mid'";
    $result = exec_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return(TRUE);
      }
    return FALSE;
  }


function listMemberAwards($db, $mid)
  {
    $memberRecord = getMemberInfo($db,$mid);
    if (!is_array($memberRecord))
      return FALSE;

    $awards = $memberRecord["awards"];

    $awardsList=array();
    $awardsArray=array();

    $tarray = explode("~",$awards);
    // for ($i=0; $i<count($tarray); $i++)
    for ($i=count($tarray)-1; $i>=0; $i--)
      {
        $tempAward = $tarray[$i];
        if (strlen($tempAward)>0)
          {
            if (!isset($awardsArray[$tempAward]))
              {
                $awardsList[]=$tempAward;
                $awardsArray[$tempAward] = TRUE;
              }
          }
      }
    return($awardsList);
  }


function loadMemberAwards($db, $mid)
  {
    $memberRecord = getMemberInfo($db,$mid);
    if (!is_array($memberRecord))
      return FALSE;

    $firstname=stripslashes($memberRecord["firstname"]);

    // $htmlContent="";
    $awards = array();
    $awardList = listMemberAwards($db,$mid);
    if (is_array($awardList) && count($awardList)>0)
      {
        for ($i=0; $i<count($awardList); $i++)
          {
            $award=$awardList[$i];
            $filename=AWARDS_DIRECTORY."/".$award.".award";
            if (file_exists($filename))
              {
                ob_start();

                include($filename);

                $content = ob_get_contents();
                // $htmlContent .= $content;

                $awards[$award] = $content;

                ob_end_clean();
              }
          }
      }
    // return $htmlContent;

    krsort($awards);
    return $awards;
  }


function sortAwards($awards, $flags)
  {
    $flagCount=strlen($flags);
    $flagArray = array();
    for ($i=0; $i<$flagCount; $i++)
      {
        $flag=strtoupper(substr($flags,$i,1));
        $flagArray[]=$flag;
      }

    $result=array();

    foreach($awards AS $award => $content)
      {
        $key="";
        for ($i=0; $i<$flagCount; $i++)
          {
            $k=(int)substr($award,$i,1);
            if ($flagArray[$i]=="A")
              $key.= $k;
            else
              $key.= (9-$k);
          }
        $result[$key]=$award;
      }

  //  printf("<pre>\n---- result ----\n%s\n</pre>",print_r($result,true));

    ksort($result);

  //  printf("<pre>\n---- result ----\n%s\n</pre>",print_r($result,true));


    $awards_sorted=array();
    foreach($result AS $key=>$award)
      {
         // $content = "Award ".$award;
         $content = $awards[$award];
         // printf("<pre>%s => %s\n</pre>", $award,$content);
         $awards_sorted[$award] = $content;
      }

  //  printf("<pre>\n---- Flags  ---- %s\n</pre>",$flags);
  //  printf("<pre>\n---- SORTED -----\n%s\n\n\n\n\n</pre>",print_r($awards_sorted,true));

    return $awards_sorted;
  }



function giveMemberAward($db, $mid, $award)
  {
    $memberRecord = getMemberInfo($db,$mid);
    if (!is_array($memberRecord))
      return FALSE;

    $awards = $memberRecord["awards"];
    $awardsRecord="";
    $awardsList=array();
    $awardsArray=array();
    $tarray = explode("~",$awards);
    for ($i=0; $i<count($tarray); $i++)
      {
        $tempAward = $tarray[$i];
        if (strlen($tempAward)>0)
          {
            if ($tempAward == $award) return;   // Already  Has this Award
            if (!isset($awardsArray[$tempAward]))
              {
                $awardsList[]=$tempAward;
                $awardsArray[$tempAward] = TRUE;

                if (strlen($awardsRecord)==0)
                  $awardsRecord="~";
                $awardsRecord.= $tempAward."~";
              }
          }
      }

    $awardsList[]=$award;
    if (strlen($awardsRecord)==0)
      $awardsRecord="~";
    $awardsRecord.= $award."~";

    if ($awardsRecord != $awards)
      {
        $sql  = "UPDATE member set awards='$awardsRecord'";
        $sql .= " WHERE member_id = '$mid'";
        $result = mysql_query($sql,$db);
        if ($result && mysql_affected_rows()==1)
          {
            return TRUE;
          }
      }

    return FALSE;
  }


function getMemberIdFromEmail($db, $email)
  {
    $sql  = "SELECT member_id from member";
    $sql .= " WHERE email='$email'";
    $result = exec_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        $mid=$myrow["member_id"];
        mysql_free_result($result);
        return($mid);
      }
    return FALSE;
  }


function showFeatureAd($db, $member_id, $feature_ad)
  {
    $sql  = "UPDATE member set ";
    $sql .= " display_feature_ad='$feature_ad'";
    $sql .= " WHERE member_id='$member_id'";
    mysql_query($sql,$db);
  }


function resetFeatureAd($db, $member_id)
  {
    $sql  = "UPDATE member set ";
    $sql .= " display_feature_ad='0'";
    $sql .= " WHERE member_id='$member_id'";
    mysql_query($sql,$db);
  }


function getTopRecruits($db, $limit=10, $startDateArray=FALSE, $endDateArray=FALSE)
  {
    global $signin_ids_reserved;
    $sql  = "SELECT refid, COUNT(*) FROM member ";
    $sql .= " WHERE system=0";
    if (!is_array($startDateArray) || !is_array($endDateArray))
      {
          // All Time
        $todayArray = getDateTodayAsArray();
        $yesterdayArray = calStepDays(-1,$todayArray);
        $yesterday      = dateArrayToString($yesterdayArray);
        $sql .= " AND date_registered <= '$yesterday'";
      }
    else
      {
          // Specified Date Range
        $startDate = dateArrayToString($startDateAsArray);
        $endDate   = dateArrayToString($endDateAsArray);
        $sql .= " AND date_registered >= '$startDate'";
        $sql .= " AND date_registered <= '$endDate'";
      }

    foreach($signin_ids_reserved AS $reserved_id => $bool)
      {
        $sql .= " AND refid != '$reserved_id'";
      }
    $sql .= " AND aff_disabled=0";
    $sql .= " GROUP BY refid";
    $result = mysql_query($sql,$db);

    // printf("SQL:%s\n",$sql);
    // printf("ERR:%s\n",mysql_error());

    $topdogs=array();
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $topdogs[$myrow[0]]=$myrow[1];
            // printf("%s  %6d\n",$myrow[0],$myrow[1]);
          }
      }

    arsort($topdogs);

    $top_recuiters=array();
    $j=0;
    foreach($topdogs  AS $member_id => $cnt)
      {
        $memberRecord=getMemberInfo($db,$member_id);
        if (is_array($memberRecord))
          {
            $memberRecord["RECRUITS"]=$cnt;
            $top_recruiters[$j] = $memberRecord;
            $j++;
            if ($j==$limit)
              break;
          }
      }

    return ($top_recruiters);
  }


function getTopOneDayRecruits($db)
  {
    global $signin_ids_reserved;
    $sql  = "SELECT date_confirmed, refid, COUNT(*) FROM member ";
    $sql .= " WHERE system=0";
    $sql .= " AND date_confirmed != ''";
    foreach($signin_ids_reserved AS $reserved_id => $bool)
      {
        $sql .= " AND refid != '$reserved_id'";
      }
    $sql .= " AND aff_disabled=0";
    $sql .= " GROUP BY date_confirmed, refid";
    $result = mysql_query($sql,$db);

    // printf("SQL:%s\n",$sql);
    // printf("ERR:%s\n",mysql_error());

    $refid1="";  $count1=0;
    $refid2="";  $count2=0;
    $refid3="";  $count3=0;
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $cnt   = $myrow[2];
            // printf("(0)=%s (1)=%s  (2)=%s<br>\n",$myrow[0],$myrow[1],$myrow[2]);
            if ($cnt > $count1)
              {
                $refid1=$myrow[1];  $count1=$cnt;
              }
            else
            if ($cnt > $count2)
              {
                $refid2=$myrow[1];  $count2=$cnt;
              }
            else
            if ($cnt > $count3)
              {
                $refid3=$myrow[1];  $count3=$cnt;
              }
          }
      }

    $topdogs[$refid1]=$count1;
    $topdogs[$refid2]=$count2;
    $topdogs[$refid3]=$count3;

    arsort($topdogs);

    $top_recuiters=array();
    $j=0;
    foreach($topdogs  AS $member_id => $cnt)
      {
        if ($member_id != "")
          {
            $memberRecord=getMemberInfo($db,$member_id);
            if (is_array($memberRecord))
              {
                $memberRecord["RECRUITS"]=$cnt;
                $top_recruiters[$j] = $memberRecord;
                $j++;
                if ($j==$limit)
                  break;
              }
          }
      }

    return ($top_recruiters);
  }


//=========================================================================================================
//
// WIDGETS
//
//=========================================================================================================
function getWidget($db, $widget_key)
  {
    // returns: FALSE if hash not found || User Not Enabled || Widget Not Enabled
    //
    $sql  = "SELECT * from widget";
    $sql .= " WHERE widget_key='$widget_key'";
    $sql .= " AND   enabled='1'";
    $result = mysql_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return($myrow);
      }
    if ($result)
      mysql_free_result($result);
    return FALSE;
  }



//=========================================================================================================
//
// PRODUCTS
//
//=========================================================================================================
function getProductInfo($db, $product_id, $member_id=FALSE)
  {
    $sql  = "SELECT * from product";
    $sql .= " WHERE product_id='$product_id'";
    if ($member_id)
      {
        $sql .= " AND product_owner='$member_id'";
      }
    $result = exec_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return($myrow);
      }
    if ($result)
      mysql_free_result($result);
    return FALSE;
  }


function getProductName($db, $product_id, $member_id=FALSE)
  {
    $sql  = "SELECT * from product";
    $sql .= " WHERE product_id='$product_id'";
    if ($member_id)
      {
        $sql .= " AND product_owner='$member_id'";
      }
    $result = exec_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return(stripslashes($myrow["product_name"]));
      }
    return "";
  }


function isProductApprovalPending($db, $product_id)
  {
    $sql  = "SELECT disposition from product_pending";
    $sql .= " WHERE replaces_product_id='$product_id'";
    $result = exec_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        $disposition=$myrow["disposition"];
        mysql_free_result($result);
        if ($disposition <= 5)      // 0=New 1=Update      6=Request to place into Existing Products
          return(TRUE);
        return(FALSE);
      }
    if ($result)
      mysql_free_result($result);
    return FALSE;
  }


function retireProduct($db,$member_id,$product_id)
  {
     global $PUSHY_ROOT;
     $TRACE=FALSE;

     //--- THIS Fuction Properly handles product retirment in the case of a DOWNGRADE (from elite) or a User-Directed Product REMOVAL (elite)
     //--- Products OWNED and being REMOVED for Elites Must consider Reseller References and Handle accoringly:
     //---     REASSIGNED to PUSHY if there are resellers (ads referencing it)
     //---     DELETED             if there are NO resellers (ads referencing it)

     if ($TRACE)
       {
         printf("  RETIRE  Product:$product_id   for Member:$member_id ...\n");
       }

     $sql  = "SELECT product_id,product_name from product ";
     $sql .= " WHERE product_owner='$member_id'";
     $sql .= " AND   product_id='$product_id'";
     $res  = mysql_query($sql,$db);
     //  printf("SQL: %s\n",$sql);
     //  printf("ERR: %s\n",mysql_error());
     if (($res) && (($pcount=mysql_num_rows($res)) > 0))
        {
          if ($myrow=mysql_fetch_array($res,MYSQL_ASSOC))
           {
             $refcount=0;
             $product_name = stripslashes($myrow["product_name"]);

             if ($TRACE)
               {
                 printf("   ... Checking References for Owned Product Name: '%s'  (product_id=%s)\n",$product_name,$product_id);
               }
                           //---  any references other than me ?
             $sql   = "SELECT COUNT(*) from ads ";
             $sql  .= " WHERE product_id = '$product_id'";
             $sql  .= " AND   member_id != '$member_id'";        // Non-Owner References
             $res2  = mysql_query($sql,$db);
             //  printf("SQL: %s\n",$sql);
             //  printf("ERR: %s\n",mysql_error());
             if ($res2)
               {
                 $myrow=mysql_fetch_array($res2);
                 $refcount=$myrow[0];
               }

             if ($TRACE)
               {
                 printf("   ... References Found: %d\n",$refcount);
               }

             if ($refcount==0) // No References - Delete it
               {
                 if ($TRACE)
                   {
                     printf("   ... Deleting Product ... %s\n",$product_id);
                   }

                 deleteProduct($db,$product_id);
                 return(1);
               }
             else
               {
                 if ($TRACE)
                   {
                     printf("   ... Re-Assigning Product ... %s\n",$product_id);
                   }

                 $sql  = "UPDATE product set ";
                 $sql .= " product_owner='$PUSHY_ROOT'";
                 $sql .= " WHERE product_owner='$member_id'";
                 $sql .= " AND   product_id='$product_id'";
                 $res3 = mysql_query($sql,$db);

                 // printf("SQL: %s\n",$sql);
                 // printf("ERR: %s\n",mysql_error());
                 return(2);
               }
           }
        }
     return(0);  //-- No Action
  }



//=========================================================================================================
//
// Specialty Functions
//
//=========================================================================================================





//=========================================================================================================
//
// MESSAGE QUEUE
//
//=========================================================================================================

function getMessageQueue($db,$message_id)
  {
    $sql = "SELECT * from messagequeue where message_id='$message_id'";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        return($myrow);
      }
    return FALSE;
  }


function getMessageBox($db,$mid,$message_id)
  {
    $sql = "SELECT * from messagebox where member_id='$mid' AND  message_id='$message_id'";
    $result = exec_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        return($myrow);
      }
    return FALSE;
  }



function getMessage($db,$mid,$message_id)
  {
    $mbox = getMessageBox($db,$mid,$message_id);
    if (is_array($mbox))
      {
        $msgQ = getMessageQueue($db,$message_id);
        if (is_array($msgQ))
          {
            $msg = array_merge($mbox, $msgQ);
            return($msg);
          }
      }
    return FALSE;
  }




function dumpSQL($sql,$error,$quit)
  {
    printf("<br>SQL: %s<br>\n",$sql);
    printf("<br>ERR: %s<br>\n",$error);
    if ($quit)
      {
        exit;
      }
  }


//============================================================================ SQL LOGGING ======================
if (version_compare(phpversion(), "5.1.0", ">=")) {
       // you're on 5.1.0 or later
   // include("VLOG.php");
   // include("VLOG_QUIET.php");
} else {
}


function exec_query($sql,$db)
  {
    $result = mysql_query($sql,$db);

//      printf("<PRE>\n");
//      printf("SQL: %s\n",$sql);
//      $err=mysql_error();
//      if (strlen($err)>0)
//        {
//          printf("ERR: %s\n",mysql_error());
//        }
//      printf("</PRE>\n");


//  if (mysql_errno() != 0)
//    {
//      printf("SQL: %s\n",$sql);
//      $err=mysql_error();
//      if (strlen($err)>0)
//        {
//          printf("ERR: %s\n",mysql_error());
//        }
//    }

    return $result;
  }


function exec_fetch_array($result, $result_type=MYSQL_BOTH)
  {

    $row = mysql_fetch_array($result, $result_type);

    if (!is_array($row))
      {
        return $row;
      }

//  foreach($row AS $key => $data)
//    {
//      if (!isNumeric($key))
//        VLOG::printf("   %-20s => %s\n",$key,$data);
//    }

    return $row;
  }
?>
