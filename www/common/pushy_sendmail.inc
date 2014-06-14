<?php
include_once("pushy_constants.inc");
include_once("pushy_invoke.inc");

//*********** SAMPLE INVOCATION **************************************************************************
//*
//*  $count=0;
//*  $recipient = array("email"      => "twolf@webtribune.com",
//*                     "firstname"  => "Mike",
//*                     "lastname"   => "Wolf"
//*                    );
//*  $recipientList[$count]=$recipient;
//*  $count++;
//*
//*  $result=mailSendList("Tim Wolf", "twolf@webtribune.com", "This is the FinalSubject Again, %firstname%", "This is the Message, %firstname% %lastname% - Hw's Yor email: %email%", $recipientList);
//*
//********************************************************************************************************

function strip_all_slashes($s)
  {
    while (is_integer( strpos($s,"\\") ))
      $s=stripslashes($s);
    return $s;
  }

//=========================================================================================================
//
// mailSendList
//
//=========================================================================================================
function mailSendList($fromName, $fromEmail, $subject, $message, $recipientList)
  {
    return se_nd_ma_il($fromName, $fromEmail, $subject, $message, $recipientList);
  }


//=========================================================================================================
//
// mailSendList
//
//=========================================================================================================
function bulkSend($fromName, $fromEmail, $subject, $message, $recipientList)
  {
    return se_nd_ma_il($fromName, $fromEmail, $subject, $message, $recipientList, TRUE);
  }


//=========================================================================================================
//
// mailSend  --- Simple Enqueue - Single Recipient - No %Variable% substitution
//
//=========================================================================================================
function mailSend($toName, $toEmail, $fromName, $fromEmail, $subject, $message)
  {
    return send_mail_remote($toName, $toEmail, $fromName, $fromEmail, $subject, $message);
  }


//=========================================================================================================
//
// mailSendFile - Single Recipient - MESSAGES file with variable substitution
// File Name must be inside MESSAGES directory - filename is relative to MESSAGES directory;
// Variables = Associative Array  e.g. "%firstname%" => "Tim", ...
//
//=========================================================================================================
function mailSendFile($toName, $toEmail, $fileName, $variables)
  {
    if (strlen($fileName) > 1 && substr($fileName,0,1) == "/")
       $fileName = substr($fileName,1);

    $responseFile = MESSAGE_DIRECTORY."/".$fileName;

    if (strlen($toName) > 0 &&
        is_integer(strpos($toEmail,"@")) &&
        file_exists($responseFile) &&
        (is_array($msgData = loadFileAsArray($responseFile))))
      {
        $fromName  = "PUSHY Administration";
        $fromEmail = EMAIL_NOREPLY;
        $subject   = "";
        $message   = "";
        $inMessage = FALSE;
        $cnt=0;
        $result=FALSE;

        $message="";
        $numLines=count($msgData);
        for ($i=0; $i<$numLines; $i++)
          {
            if ($inMessage)
              {
                // echo ++$cnt;
                // echo $msgData[$i]."<br>\n";
                $message .= $msgData[$i]."\n";
              }
            else
              {
                if ($msgData[$i] == "MESSAGE")
                  $inMessage = TRUE;
                else
                  {
                    list($key,$value) = split("=",$msgData[$i]);
                    if ($key == "FROMNAME")
                      $fromName=$value;
                    if ($key == "FROMEMAIL")
                      $fromEmail=$value;
                    if ($key == "SUBJECT")
                      $subject=$value;
                  }
              }
          }

        if (is_array($variables))
          {
            foreach($variables AS $key=>$value)
              {
                $subject = str_replace($key, $value,  $subject);
                $message = str_replace($key, $value,  $message);
              }
           }

        // v_printf("TONAME:    %s\n",$toName);
        // v_printf("TOEMAIL:   %s\n",$toEmail);
        // v_printf("FROMNAME:  %s\n",$fromName);
        // v_printf("FROMEMAIL: %s\n",$fromEmail);
        // v_printf("SUBJECT:   %s\n",$subject);
        // v_printf("MESSAGE:   %s\n",$message);

        $resp=mailSend($toName, $toEmail, $fromName, $fromEmail, $subject, $message);
        // if ($resp)
        //   v_printf("SENT ---- \n");
        // else
        //   v_printf("ERR 1---- \n");

        return $resp;
      }
    else
      {
        // v_printf("ERR 2---- \n");

        return FALSE;
      }
  }



//=========================================================================================================
//
// PRIVATE - Do Not Invoke Directly - Called by MailSend Functions if MailService Is REMOTE
//
//=========================================================================================================
function se_nd_ma_il($fromName, $fromEmail, $subject, $message, $recipientList, $bulk=FALSE)
  {
    $isBulk = "NO";
    if ($bulk)
      $isBulk = "YES";

    if (strlen($fromEmail) == 0)
      $fromEmail=EMAIL_NOREPLY;
    if (strlen($message) == 0 || strlen($subject) == 0 || strlen($fromName) == 0)
      return FALSE;
    if (!is_array($recipientList) || count($recipientList) == 0)
      return FALSE;

    $mailRequest = array(
       "FROMNAME"   => $fromName,
       "FROMEMAIL"  => $fromEmail,
       "SUBJECT"    => $subject,
       "MESSAGE"    => $message,
       "BULK"       => $isBulk,
       "RECIPIENTS" => $recipientList
    );

    $request=urlencode(serialize($mailRequest));

    list($rc, $result) = invokeService(MAIL_SERVICE_HOST, SENDMAIL_SERVICE_URI, $request);
    if ($rc==0)
      {
        $response=unserialize(stripslashes(urldecode($result)));
        // print_r($response);
        if (is_array($response) and isset($response["STATUS"]))
          $rc=(int) $response["STATUS"];
      }
    // else
    //   printf("%s\n",$result);

    //  v_printf("MAILSERVICE: %s\n",MAIL_SERVICE_HOST);
    //  v_printf("SERVICE-URI: %s\n",SENDMAIL_SERVICE_URI);
    //  v_printf("MAILSEND: RC=%d\n",$rc);
    //  v_printf("MAILSEND-REQ: %s\n", print_r($mailRequest,TRUE) );
    //  v_printf("MAILSEND_RSP: %s\n", print_r($response,TRUE) );

    if ($rc==0)
      return TRUE;

    return FALSE;
  }
?>
