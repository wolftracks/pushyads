<?php

include_once("pushy_invoke.inc");


function notifyGetResponse($operation,$member_id,$refid,$affiliate_id,$update,$firstname,$lastname,$email)
  {
    $fullname = $firstname." ".$lastname;

    $campaign = "pushy_ads";

    $ccode = $member_id;

    $gr_host  = "www.getresponse.com";        // "accept-charset="UTF-8";
    $gr_uri   = "/cgi-bin/add.cgi";

    $gr_payload  = "";
    $gr_payload .= "subscriber_name="   . $fullname                         . "&";
    $gr_payload .= "subscriber_email="  . $email                            . "&";

    // $gr_payload .= "confirmation_url="  . DOMAIN."/getresponse_confirm.php" . "&";
    // $gr_payload .= "error_url="         . DOMAIN."/getresponse_error.php"   . "&";

    // $gr_payload .= "confirmation_url="  . "http://autoprospector.com/tools/showme/showme.php" . "&";
    // $gr_payload .= "error_url="         . "http://pushyads.com/tools/showme/showme.php"       . "&";

    $gr_payload .= "campaign_name="     . $campaign                         . "&";
    $gr_payload .= "custom_ref="        . $member_id                        . "&";
    $gr_payload .= "custom_mid="        . $member_id                        . "&";
    $gr_payload .= "custom_refid="      . $refid                            . "&";
    $gr_payload .= "custom_affid="      . $affiliate_id                     . "&";
    $gr_payload .= "custom_update="     . $update                           . "&";
    $gr_payload .= "getpostdata="       . "post";

    // printf("%s\n",$gr_payload);
    // exit;

    list($rc, $resp) = invokeService($gr_host, $gr_uri, $gr_payload);

    // printf("RC=%d\n",$rc);
    // printf("RESP=<PRE>%s</PRE>\n",print_r($resp,TRUE));
    // exit;

    if ($rc==0)
      {
         $dt=substr(getDateToday(),0,7);
         $fp=fopen(PUSHY_DOCROOT."/logs/registry/".$dt."-pass.log","a");
         $lineout = sprintf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
              getDateTime(),
              $member_id,
              $refid,
              addslashes($firstname),
              addslashes($lastname),
              addslashes($email));
         fputs($fp,$lineout);
         // fputs($fp,sprintf("%s\n",print_r($resp,TRUE)));
         fclose($fp);
      }
    else
      {
         $dt=substr(getDateToday(),0,7);
         $fp=fopen(PUSHY_DOCROOT."/logs/registry/".$dt."-fail.log","a");
         $lineout = sprintf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
              getDateTime(),
              $member_id,
              $refid,
              addslashes($firstname),
              addslashes($lastname),
              addslashes($email));
         fputs($fp,$lineout);
         fputs($fp,sprintf("%s\n",print_r($resp,TRUE)));
         fclose($fp);
      }

    return (array($rc,$resp));
  }
?>
