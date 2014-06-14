<?php
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $sql  = "SELECT * from product ";
 $sql .= " WHERE sponsor_id!=''";
 $sql .= " ORDER by product_title";
 $result = mysql_query($sql,$db);
 if ($result)
   {
     while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
       {
         $product_id       = $myrow["product_id"];
         $product_category = $myrow["product_category"];
         $product_title    = stripslashes($myrow["product_title"]);

         printf(" %5d  : %s\n", $product_id,
                                $product_title);

       }
   }

