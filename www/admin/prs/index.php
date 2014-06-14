<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy_sendmail.inc");
include_once("users.php");
include_once("prs.php");

$TRACE   = TRUE;
$INCLUDE = 1;
$EXCLUDE = 0;

$STATUS_OPEN     = 0;
$STATUS_RETURNED = 1;
$STATUS_REJECTED = 2;
$STATUS_CLOSED   = 3;

$priorities  = array ("CRITICAL", "HIGH", "MEDIUM", "LOW");
$statuscodes = array ("OPEN", "RETURNED", "REJECTED", "CLOSED");

$db=prs_getConnection();

function execsql($sql, $db)
  {
    global $TRACE;
    $result=mysql_query($sql,$db);
    $err=mysql_error();
    $m=strlen($err);
    if ((!$result) || (strlen($err) > 1))
      {
        if ($TRACE)
          {
            printf("<br>SQL: %s<br>\n",$sql);
            printf("ERR: %s<br>\n",$err);
            flush();
          }
      }
    return $result;
  }


// echo "AUTHOR: $PRSAUTHOR<br>\n";

if (strlen($PRSAUTHOR)==0)
  {
    if (strlen($SVCAUTHOR)>0)
      {
        $PRSAUTHOR=$SVCAUTHOR;
        setcookie("PRSAUTHOR",$PRSAUTHOR,time()+94608000,"/","",0);
        // echo "New Author Recognized<br>";
      }
    else
      {
        include_once("signin.php");
        exit;
      }
  }


   //****************--------------- MAIN ---------------------***************
if ( (!isset($op)) || (strlen($op)==0) || $op=="ListReports")
  {
    if (!(isset($SortBy) && strlen($SortBy) > 0))
       $SortBy = "id";
    include_once("prlist.php");
    exit;
  }

if ($op=="OpenReport")
  {
    $row=prs_getProblemReport($id);
    if ($row)
      {
        $prs_key       = $row["prs_key"];
        $title         = $row["title"];
        $date_opened   = $row["date_opened"];
        $author        = $row["author"];
        $assignee      = $row["assignee"];
        $last_modified = $row["last_modified"];
        $priority      = $row["priority"];
        $status        = $row["status"];
        $pr_reference  = $row["pr_reference"];
        $description   = $row["description"];
        $response      = $row["response"];
        $target_date   = $row["target_date"];

        include_once("report.php");
        exit;
      }
    include_once("prlist.php");
    exit;
  }

if ($op=="NewReport")
  {
    $prs_key=getmilliseconds();
    include_once("newreport.php");
    exit;
  }

if ($op=="AddReport")
  {
    if ((!isset($prs_key)) || ($prs_key==0) || (prs_getProblemReportByKey($prs_key)))
      {
        include_once("prlist.php");
        exit;
      }
    $initial_target = "~";
    $in_description = str_replace("\\", "", $in_description);
    $dt=getDateToday();
    execsql("LOCK TABLES prs WRITE",$db);
    $sql  = "INSERT into prs set";
    $sql .= " prs_key=$prs_key,";
    $sql .= " title='$in_title',";
    $sql .= " date_opened='$dt',";
    $sql .= " author='$in_author',";
    $sql .= " last_modified='$dt',";
    $sql .= " target_date='$initial_target',";
    $sql .= " pr_reference=$in_pr_reference,";
    $sql .= " priority=$in_priority,";
    $sql .= " status=0,";
    $sql .= " description='$in_description',";
    $sql .= " response=''";
    $result = execsql($sql,$db);
    if ($result && mysql_affected_rows() == 1)
      {
        $prs_id=mysql_insert_id($db);
        execsql("UNLOCK TABLES",$db);

        $subject  = "PR #".$prs_id." OPENED: (".$priorities[$in_priority].") ".$in_title;
        $message  = "";
        $message .= "PRS ID      : ".$prs_id."\n";
        $message .= "Title       : ".$in_title."\n";
        $message .= "Author      : ".$in_author."\n";
        $message .= "Priority    : ".$priorities[$in_priority]."\n";
        $message .= "\nDESCRIPTION :\n".$in_description."\n";
        while (list($user,$email) = each($users))
          {
            if ($user=="tim" || $user=="mark" || $user==$in_author)
                mailSend($user, $email, "PR System", "noreply@autoprospector.com", $subject, $message);
          }
        include_once("prlist.php");
        exit;
      }
    execsql("UNLOCK TABLES",$db);

    $StatusMessage="Insert Failed: ".mysql_error();
    include_once("newreport.php");
    exit;
  }

if ($op=="UpdateReport")
  {
    $row=prs_getProblemReport($id);
    if ($row)
      {
        $author        = $row["author"];
        $priority      = $row["priority"];
        $status        = $row["status"];
        $description   = $row["description"];
        $response      = $row["response"];
        $pr_reference  = $row["pr_reference"];
        $assignee      = $row["assignee"];
        $target_date   = $row["target_date"];
      }


    if (isNumeric($in_target_date) && strlen($in_target_date)==1)
       $in_target_date=sprintf("%02d",$in_target_date);


    $in_description = str_replace("\\", "", $in_description);
    $in_response    = str_replace("\\", "", $in_response);
    $dt=getDateToday();
    $sql  = "UPDATE prs set";
    $sql .= " title='$in_title',";
    $sql .= " last_modified='$dt',";
    $sql .= " target_date='$in_target_date',";
    $sql .= " assignee='$AssignedTo',";
    $sql .= " priority=$in_priority,";
    $sql .= " status=$in_status,";
    $sql .= " pr_reference=$in_pr_reference,";
    $sql .= " description='$in_description',";
    $sql .= " response='$in_response'";
    $sql .= " WHERE id=$id";
    $result = execsql($sql,$db);
    if ($result)
      {
        // $StatusMessage="<br>PR Updated Successfully<br><br>";
        $newAssignee=FALSE;
        if ($AssignedTo != $assignee)
          {
            $newAssignee=TRUE;
            $old_assignee=$assignee;
            $assignee=$AssignedTo;
          }

        $notification = "";
        $subject = "PR #".$id." (".$priorities[$in_priority].")";

        if ($newAssignee)
          {
            if (isset($users[$assignee]))
              {
                $email = $users[$assignee];
                mailSend($assignee, $email, "PR System", "noreply@autoprospector.com",
                              $subject." - Assigned to ".$assignee,
                              $subject." - has been assigned to $assignee");

                if (strlen($old_assignee) > 0 && $old_assignee != $assignee && isset($users[$old_assignee]))
                  {
                    if ($old_assignee != "tim" && $old_assignee != "mark")
                      {
                        $email = $users[$old_assignee];
                        mailSend($old_assignee, $email, "PR System", "noreply@autoprospector.com",
                                  $subject." - Assigned to ".$assignee,
                                  $subject." - has been assigned to $assignee");
                      }
                  }

                if ($assignee!="tim")
                  {
                    $email = $users["tim"];
                    mailSend("tim", $email, "PR System", "noreply@autoprospector.com",
                              $subject." - Assigned to ".$assignee,
                              $subject." - has been assigned to $assignee");
                  }
                if ($assignee!="mark")
                  {
                    $email = $users["mark"];
                    mailSend("mark", $email, "PR System", "noreply@autoprospector.com",
                              $subject." - Assigned to ".$assignee,
                              $subject." - has been assigned to $assignee");
                  }
              }
          }


        if ($priority != $in_priority)
          {
            $subject .= " - PRIORITY changed - ".$in_title;
            $notification .= "\n\nPriority Updated From ".$priorities[$priority]." to ".$priorities[$in_priority]."\n";
          }
        else
        if ($status   != $in_status)
          {
            $subject .= " - STATUS changed to ".$statuscodes[$in_status]." - ".$in_title;
            $notification .= "Status Updated From ".$statuscodes[$status]." to ".$statuscodes[$in_status]."\n";
          }
        else
        if ($in_description != $description)
          {
            $subject .= " - Description Modified - ".$in_title;
            $notification .= "Description has been modified\n";
          }
        else
        if ($in_response    != $response)
          {
            $subject .= " - Response Modified - ".$in_title;
            $notification .= "Response has been modified\n";
          }
        else
        if ($newAssignee)
          {
            $subject      .= " - ".$in_title;
            $notification .= "PR Assigned To $assignee\n";
          }
        else
        if (strlen($in_target_date) > 0 && ($in_target_date != $target_date))
          {
//          $subject      .= "Target Date Set - ".$in_target_date;
//          $notification .= "Target Date Set\n";
            $subject      .= "Target Set - ".$in_target_date;
            $notification .= "Target Set\n";
          }

        if (strlen($in_target_date) > 0)
          $target_date = $in_target_date;

        $sent_to_assignee=FALSE;
        $sent_to_author=FALSE;
        if (strlen($notification) > 0)
          {
            $message = $notification."\n";
            $message .= "\n";
            $message .= "Update Notification sent to: $notificationlist\n";
            $message .= "\n";
            $message .= "PRS ID      : ".$id."\n";
            $message .= "Title       : ".$in_title."\n";
            $message .= "Author      : ".$author."\n";
            $message .= "Updated By  : ".$updated_by."\n";
            $message .= "Assigned To : ".$assignee."\n";
//          $message .= "Target Date : ".$target_date."\n";
            $message .= "Target      : ".$target_date."\n";
            $message .= "\nDESCRIPTION :\n".$in_description."\n";
            $message .= "\n";
            $message .= "\nRESPONSE    :\n".$in_response."\n";

            $ulist = explode("/", $notificationlist);
            for ($i=0; $i<count($ulist); $i++)
              {
                $user=$ulist[$i];
                if (strlen($user) > 0 && isset($users[$user]) && $user != "tim" && $user != "mark")
                  {
                    if ($user == $author)
                       $sent_to_author=TRUE;
                    if ($user == $assignee)
                       $sent_to_assignee=TRUE;
                    $email = $users[$user];
                    mailSend($user, $email, "PR System", "noreply@autoprospector.com", $subject, $message);
                  }
              }


            if (!$sent_to_assignee  && isset($users[$assignee]))
              {
                if ($assignee == $author)
                  $sent_to_author=TRUE;
                $email = $users[$assignee];
                mailSend($assignee, $email, "PR System", "noreply@autoprospector.com", $subject, $message);
              }
            if (!$sent_to_author && isset($users[$author]))
              {
                $email = $users[$author];
                mailSend($author, $email, "PR System", "noreply@autoprospector.com", $subject, $message);
              }


            if ($assignee != "tim" && $author != "tim")
              {
                $email = $users["tim"];
                mailSend("tim", $email, "PR System", "noreply@autoprospector.com", $subject, $message);
              }
            if ($assignee != "mark" && $author != "mark")
              {
                $email = $users["mark"];
                mailSend("mark", $email, "PR System", "noreply@autoprospector.com", $subject, $message);
              }

          }

        include_once("prlist.php");
        exit;
      }
    else
      {
        $StatusMessage="<br>Update Failed: ".mysql_error()."<br><br>";
      }
    $op = "OpenReport";
  }


if ($op=="OpenReport")
  {
    $row=prs_getProblemReport($id);
    if ($row)
      {
        $prs_key       = $row["prs_key"];
        $title         = $row["title"];
        $date_opened   = $row["date_opened"];
        $author        = $row["author"];
        $assignee      = $row["assignee"];
        $last_modified = $row["last_modified"];
        $priority      = $row["priority"];
        $status        = $row["status"];
        $pr_reference  = $row["pr_reference"];
        $description   = $row["description"];
        $response      = $row["response"];
        $target_date   = $row["target_date"];

        include_once("report.php");
        exit;
      }
    include_once("prlist.php");
    exit;
  }


if ($op=="DeleteReport")
  {
    $sql  = "DELETE from prs";
    $sql .= " WHERE id=$id";
    $result = execsql($sql,$db);
    // printf("<br>SQL: %s<br>\n",$sql);
    // printf("<br>ERR: %s<br>\n",mysql_error());
    if ($result && mysql_affected_rows() == 1)
      {
        if (!(isset($SortBy) && strlen($SortBy) > 0))
           $SortBy = "id";
        include_once("prlist.php");
      }
    else
      {
        $StatusMessage="Delete Failed: ".mysql_error();
        include_once("report.php");
      }
    exit;
  }
?>
