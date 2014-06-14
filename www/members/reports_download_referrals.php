<?php
$TAB_SEPARATOR   = "".chr(9);
$COMMA_SEPARATOR = ",";

$SEP = $COMMA_SEPARATOR;

function getWebsiteCountForUser($db,$member_id)
  {
    $count=0;
    $sql    = "SELECT COUNT(*) from widget";
    $sql   .= " WHERE member_id='$member_id'";
    $result = mysql_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result,MYSQL_NUM)))
      {
        $count = $myrow[0];
      }
    return $count;
  }


$data=sprintf("%s$SEP%s$SEP%s$SEP%s$SEP%s$SEP%s$SEP%s$SEP%s$SEP%s$SEP%s$SEP%s$SEP%s$SEP%s\r\n",
           "FIRSTNAME",
           "LASTNAME",
           "COMPANY-NAME",
           "LEVEL",
           "REGISTERED",
           "EMAIL",
           "PHONE",
           "ADDRESS-1",
           "ADDRESS-2",
           "CITY",
           "STATE",
           "COUNTRY",
           "POSTAL-CODE");


$sql    = "SELECT * FROM member";
$sql   .= " WHERE refid='$mid'";
$sql   .= " AND   registered>0";
$sql   .= " AND   member_disabled=0";
$sql   .= " ORDER BY lastname, firstname";
$result = mysql_query($sql,$db);
if ($result)
  {
     while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
       {
         $member_id       = $myrow["member_id"];
         $firstname       = stripslashes($myrow["firstname"]);
         $lastname        = stripslashes($myrow["lastname"]);
         $email           = $myrow["email"];
         $phone           = $myrow["phone"];
         $phone_ext       = $myrow["phone_ext"];
         if (strlen($phone_ext)>0)
           $phone .= " x".$phone_ext;
         $company_name    = stripslashes($myrow["company_name"]);
         $address1        = stripslashes($myrow["address1"]);
         $address2        = stripslashes($myrow["address2"]);
         $city            = stripslashes($myrow["city"]);
         $state           = stripslashes($myrow["state"]);
         $country         = stripslashes($myrow["country"]);
         $zip             = stripslashes($myrow["zip"]);
         $date_registered = $myrow["date_registered"];
         $user_level      = $myrow["user_level"];
         $level           = $UserLevels[$user_level];

         // $websiteCount    = getWebsiteCountForUser($db,$member_id);

         $lnout=sprintf("\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"$SEP\"%s\"\r\n",
                  $firstname,
                  $lastname,
                  $company_name,
                  $level,
                  $date_registered,
                  $email,
                  $phone,
                  $address1,
                  $address2,
                  $city,
                  $state,
                  $country,
                  $zip);
         $data .= $lnout;
       }
  }

$filesize=strlen($data);
$fname="PushyAds_Referrals.csv";

     // -- header("Content-Type: application/force-download\n");
header("Content-Type: application/octetstream\n");
header("Content-Transfer-Encoding: Binary\n");
header("Accept-Ranges: bytes\n");
header("Content-Length: $filesize\n");
header("Content-Disposition: attachment; filename=$fname");
echo $data;
flush();
exit;
?>
