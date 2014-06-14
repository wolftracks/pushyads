<?php
 $RESPONSE["result"]=0;
 $RESPONSE["message"]="";

 $product_id    = $_REQUEST["product_id"];
 $ad_id         = $_REQUEST["ad_id"];

 $sql  = "SELECT reseller_listing FROM ads";
 $sql .= " WHERE member_id = '$mid'";
 $sql .= " AND   ad_id= '$ad_id'";
 $sql .= " AND   product_id= '$product_id'";
 $result = mysql_query($sql,$db);
 if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
   {
     $reseller_listing = $myrow["reseller_listing"];
     if ($reseller_listing == 0)  // Not added from Affiliate Offers List ... Assumption then is that I OWN the Product
       {
         $sql  = "SELECT product_name from product ";
         $sql .= " WHERE product_owner='$mid'";
         $sql .= " AND   product_id='$product_id'";
         $res  = mysql_query($sql,$db);
         //  printf("SQL: %s\n",$sql);
         //  printf("ERR: %s\n",mysql_error());
         if (($res) && (($pcount=mysql_num_rows($res)) > 0) && ($productRecord=mysql_fetch_array($res,MYSQL_ASSOC)))
           {
             $logtm=time();
             $logdate=formatDate($logtm);
             $logtime=formatTime($logtm);
             $logfname = LOG_DIRECTORY."/ads/".substr($logdate,0,7)."-success.log";
             $fp = fopen($logfname, "a");

             $product_name = stripslashes($productRecord["product_name"]);

             //--- Take the appropriat action for this Prouct
             //---     REASSIGNED to PUSHY if there are resellers (ads referencing it)
             //---     DELETED             if there are NO resellers (ads referencing it)
             $action=retireProduct($db,$mid,$product_id);
             if ($action==1)
               {
                 $lineout  = sprintf("$logdate $logtime: --- Product DELETED    for Member '$mid':  product_id($product_id) product_name($product_name)\n");
                 fputs($fp,$lineout);
               }
             else
               {
                 $lineout  = sprintf("$logdate $logtime: --- Product REASSIGNED for Member '$mid':  product_id($product_id) product_name($product_name)\n");
                 fputs($fp,$lineout);
               }
             fclose($fp);
           }
       }


     //---- Now we can delete the ad

     $sql  = "DELETE FROM ads";
     $sql .= " WHERE member_id = '$mid'";
     $sql .= " AND   ad_id= '$ad_id'";
     $sql .= " AND   product_id= '$product_id'";
     $result = mysql_query($sql,$db);

     //printf("SQL: %s<br>\n",$sql);
     //printf("ERR: %s<br>\n",mysql_error());

     if ($result && mysql_affected_rows()==1)
       {
         $response= new stdClass();
         $response->success   = "TRUE";
         sendJSONResponse(0, $response, null);
         exit;
       }
   }


//--- Failed
$response=new stdClass();
$response->success   = "FALSE";
sendJSONResponse(201, $response, "Unable to Remove Ad");
exit;
?>
