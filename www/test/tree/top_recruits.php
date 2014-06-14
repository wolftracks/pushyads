<?php
 $DEBUG=FALSE;

 $INCLUDE_ROOT = TRUE;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

 include_once("pushy_tree.inc");
 // include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $date=getDateToday(); // eventually - yesterday

 $sql  = "SELECT refid, COUNT(*) FROM member ";
// $sql .= " WHERE date_verified = '$date'";
 $sql .= " WHERE date_registered= '$date'";
 $sql .= " AND refid != 'imtools'";
 $sql .= " AND refid != '$PUSHY_ROOT'";
 $sql .= " AND refid != 'tjw998468'";
 $sql .= " AND aff_disabled=0";
 $sql .= " GROUP BY refid";
 $result = mysql_query($sql,$db);

 printf("SQL:%s\n",$sql);
 printf("ERR:%s\n",mysql_error());

 printf("\n\n-------------------------------------------------\n");

 $topdogs=array();
 if ($result)
   {
     // display each row
     while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
       {
         $topdogs[$myrow[0]]=$myrow[1];
         printf("%s  %6d\n",$myrow[0],$myrow[1]);
       }
   }

 printf("\n\n-------------------------------------------------\n");


 foreach($topdogs  AS $mbr => $cnt)
    printf("%s  %6d\n",$mbr,$cnt);

 arsort($topdogs);

 printf("\n\n-------------------------------------------------\n");

 foreach($topdogs  AS $mbr => $cnt)
    printf("%s  %6d\n",$mbr,$cnt);

 exit;
?>
