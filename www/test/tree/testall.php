<?php
require("pushy_constants.inc");
require("pushy_payflow-test.inc");
require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");
require("pushy_sendmail.inc");

$db=getPushyDatabaseConnection();

// $mid="tw012345678901234";
$mid="mg1251005566198572";


$members=array();

$sql  = "SELECT member_id, firstname, lastname, email from member ";
$sql .= " WHERE registered>0";
$sql .= " AND   system=0";
$sql .= " AND   member_disabled=0";
$result = mysql_query($sql,$db);

 //printf("SQL: %s\n",$sql);
 //printf("ERR: %s\n",mysql_error());

if ($result)
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_NUM))
      {
        $member_id  = $myrow[0];
        $firstname  = stripslashes($myrow[1]);
        $lastname   = stripslashes($myrow[2]);
        $email      = stripslashes($myrow[3]);

        $members[$member_id] = $firstname." ".$lastname." (".$email.")";
      }
  }


foreach($members AS $mid => $data)
  {
    //printf("mid=%s  data=%s\n",$mid, $data);
    for ($month=7; $month<=10; $month++)
      {
        $from_date = sprintf("%04d-%02d-%02d",2009,$month,1);
        $to_date   = sprintf("%04d-%02d-%02d",2009,$month,31);

        $sql  = "SELECT date_registered, COUNT(*) from member ";
        $sql .= " WHERE registered>0";
        $sql .= " AND   system=0";
        $sql .= " AND   user_level='$PUSHY_LEVEL_VIP'";
        $sql .= " AND   member_disabled=0";
        $sql .= " AND   refid='$mid'";
        $sql .= " AND   date_registered >= '$from_date'";
        $sql .= " AND   date_registered <= '$to_date'";
        $sql .= " GROUP BY date_registered";

        //printf("SQL: %s\n",$sql);
        //printf("ERR: %s\n",mysql_error());

        $result = mysql_query($sql,$db);
        if ($result && mysql_num_rows($result)>0)
          {
            printf("\n%-20s %s\n",$mid,$data);
            while ($myrow=mysql_fetch_array($result,MYSQL_NUM))
              {
                $date  = $myrow[0];
                $count = $myrow[1];

                printf("                    ...   (%s)   %s  -  %5d\n",$from_date,$date,$count);
              }
          }
      }
  }


?>
