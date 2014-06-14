<?php
 include("scaling.inc");

 $RESPONSE["result"]=0;
 $RESPONSE["message"]="";

  //  sid:             sid,
  //  mid:             mid,
  //  widget_key:      widget_key,
  //
  //  widget_domain:   widget_domain,
  //  widget_categories: widget_categories,
  //  widget_action:   widget_action,
  //  widget_size:     widget_size,
  //  widget_speed:    widget_speed,
  //  widget_wiggle:   widget_wiggle,
  //  widget_delay:    widget_delay,
  //  widget_pause:    widget_pause

 $operation         = $_REQUEST["operation"];
 $widget_name       = $_REQUEST["widget_name"];
 $widget_domain     = $_REQUEST["widget_domain"];
 $widget_categories = $_REQUEST["widget_categories"];
 $widget_posture    = $_REQUEST["widget_posture"];
 $widget_origin     = $_REQUEST["widget_origin"];
 $widget_motion     = $_REQUEST["widget_motion"];
 $widget_transition = $_REQUEST["widget_transition"];
 $widget_size       = $_REQUEST["widget_size"];
 $widget_speed      = $_REQUEST["widget_speed"];
 $widget_wiggle     = $_REQUEST["widget_wiggle"];
 $widget_delay      = $_REQUEST["widget_delay"];
 $widget_pause      = $_REQUEST["widget_pause"];


 if ($widget_posture == '0') // static
   {                  // True for all Motions
     $widget_origin=0;
   }
 else
 if ($widget_posture == '1') // hover
   {
     $widget_motion=0;
     $widget_pause=0;
     $widget_speed=0;
   }
 if ($widget_motion == '0') //  (no motion)
   {                  // True for all Motions
     $widget_speed=0;
     $widget_pause=0;
   }

 $member_id    = $memberRecord["member_id"];

 $width=$widget_size;
 if (isset($WIDGET_SCALE[$width]))
   {
     $widgetArray = $WIDGET_SCALE[$width];
     $height      = $widgetArray["height"];
   }

 $todays_date = getDateToday();

 if ($operation == "create")
   {
     $widget_id=0;
     $refid    =$memberRecord["refid"];
     // $widget_key  = md5("$mid:".getmicroseconds());
     $widget_key  = md5(strtolower($mid).":".strtolower($widget_name));

     $sql  = "INSERT INTO widget SET ";
     $sql .= " member_id  = '$mid',";
     $sql .= " refid      = '$refid',";
     $sql .= " domain     = '$widget_domain',";
     $sql .= " enabled    = 1,";
     $sql .= " widget_name= '$widget_name',";
     $sql .= " widget_key = '$widget_key',";
     $sql .= " widget_categories = '$widget_categories',";
     $sql .= " posture    = '$widget_posture',";
     $sql .= " origin     = '$widget_origin',";
     $sql .= " motion     = '$widget_motion',";
     $sql .= " transition = '$widget_transition',";
     $sql .= " width      = '$width',";
     $sql .= " height     = '$height',";
     $sql .= " speed      = '$widget_speed',";
     $sql .= " wiggle     = '$widget_wiggle',";
     $sql .= " delay      = '$widget_delay',";
     $sql .= " pause      = '$widget_pause',";
     $sql .= " date_created = '$todays_date',";
     $sql .= " date_last_modified = '$todays_date',";
     $sql .= " date_first_access  = '',";
     $sql .= " date_last_access   = ''";
     $result = exec_query($sql,$db);

     //printf("SQL: %s<br>\n",$sql);
     //printf("ERR: %s<br>\n",mysql_error());

     if (!$result)
       {
         $response=new stdClass();
         $response->success   = "FALSE";
         sendJSONResponse(201, $response, "Widget Update Failed");
         sendJSONResponse(201, $response, "SQL: $sql\n\n"."ERR:".mysql_error());
         exit;
       }

     $code  = "\n";
     $code .= "Step 1: (NOTE: This step is NOT required for the 'HOVER' posture:\n";
     $code .= "Pushy must be able to find his HOME (resting place) on your page.\n";
     $code .= "Place the following line on your web page where you want Pushy's HOME:\n";
     $code .= "<div id=\"PUSHY_HOME\" style=\"width:".$width.".px; height:".$height."px\"></div>";
     $code .= "\n\n";
     $code .= "Step 2: (Required for All installations)\n";
     $code .= "Place the following at the end of your HTML file immediately before the  </body>  tag:         \n";
     $code .= "<script type=\"text/javascript\"                                                               \n";
     $code .= "  src=\"".PUSHYWIDGETS."/control/$widget_key.js?tracker=YOUR_TRACKING_ID\"></script>\n";
     $code .= "</script>                                                                                      \n";
     $code .= "\n";
     $code .= "IMPORTANT: In the URL above, change YOUR_TRACKING_ID to whatever name you wish to use   \n";
     $code .= "  for tracking your impressions and clicks. These impressions and clicks will appear    \n";
     $code .= "  inside your Traffic Report under the name you select. The name you choose must contain\n";
     $code .= "  Alphabetic, Numeric, Hyphen, or Underscore characters ONLY and should be unique for   \n";
     $code .= "  each web page you install Pushy on. The maximum length is 16 characters.\n";

     $messageFile = MESSAGE_DIRECTORY."/general/pushy_configuration.txt";
     $vars["firstname"]       = getMemberFirstName($memberRecord);
     $vars["submit_date"]     = getDateToday();
     $vars["code"]            = $code;

     $fullname = getMemberFullName($memberRecord);
     $email    = strtolower($memberRecord["email"]);
     sendMessageFile($messageFile, $fullname, $email, $vars);

   }
 else
   {
     $widget_key  = $_REQUEST["widget_key"];

     $sql  = "UPDATE widget SET ";
     $sql .= " widget_categories = '$widget_categories',";
     $sql .= " posture    = '$widget_posture',";
     $sql .= " origin     = '$widget_origin',";
     $sql .= " motion     = '$widget_motion',";
     $sql .= " transition = '$widget_transition',";
     $sql .= " width      = '$width',";
     $sql .= " height     = '$height',";
     $sql .= " speed      = '$widget_speed',";
     $sql .= " wiggle     = '$widget_wiggle',";
     $sql .= " delay      = '$widget_delay',";
     $sql .= " pause      = '$widget_pause',";
     $sql .= " date_last_modified = '$todays_date'";
     $sql .= " WHERE member_id = '$mid'";
     $sql .= " AND   widget_key= '$widget_key'";
     $result = exec_query($sql,$db);

     //printf("SQL: %s<br>\n",$sql);
     //printf("ERR: %s<br>\n",mysql_error());

     if (!$result)
       {
         $response=new stdClass();
         $response->success   = "FALSE";
         sendJSONResponse(201, $response, "Widget Update Failed");
         sendJSONResponse(201, $response, "SQL: $sql\n\n"."ERR:".mysql_error());
         exit;
       }
   }

$response= new stdClass();
$response->success   = "TRUE";
$response->widget_name        = $widget_name;
$response->widget_key         = $widget_key;
$response->date_last_modified = $todays_date;
sendJSONResponse(0, $response, null);
exit;
?>
