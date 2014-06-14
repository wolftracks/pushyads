<?php
require("pushy_constants.inc");
require("pushy_payflow-test.inc");
require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");
require("pushy_sendmail.inc");

$db=getPushyDatabaseConnection();

$mid="mg1251005566198572";

$referrals[$PUSHY_LEVEL_VIP] =

for ($month=7; $month<=10; $month++)
  {
    $from_date = sprintf("%04d-%02d-%02d",2009,$month,1);
    $to_date   = sprintf("%04d-%02d-%02d",2009,$month,31);

    $sql  = "SELECT yymm, user_level, COUNT(*) from receipts;
    $sql .= " WHERE registered>0";
    $sql .= " AND   system=0";
    $sql .= " AND   user_level='$PUSHY_LEVEL_VIP'";
    $sql .= " AND   member_disabled=0";
    $sql .= " AND   refid='$mid'";
    $sql .= " AND   date_registered >= '$from_date'";
    $sql .= " AND   date_registered <= '$to_date'";
    $sql .= " GROUP BY date_registered";

    // printf("SQL: %s\n",$sql);

    $result = mysql_query($sql,$db);
    if ($result)
      {
        printf("\n--\n");
        while ($myrow=mysql_fetch_array($result,MYSQL_NUM))
          {
            $date  = $myrow[0];
            $count = $myrow[1];

            printf("(%s)   %s  -  %5d\n",$from_date,$date,$count);
          }
      }
  }
?>
