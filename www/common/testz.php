<?php

//
// +----------------+-----------+-----------+----------+------------+---------------------------+
// | record_created | member_id | firstname | lastname | user_level | awards                    |
// +----------------+-----------+-----------+----------+------------+---------------------------+
// | 2010-02-20     | h1707sd   | Prothen   | Elite    |          2 | ~101~201~104~204~105~205~ |
// | 2010-02-20     | reg1706   | Nowa      | Elite    |          2 | ~101~201~105~205~         |
// | 2010-02-20     | gjd1705h  | Nowa      | Pro      |          1 | ~101~201~104~204~         |
// | 2010-02-20     | a1704ve   | Ima       | Elite    |          2 | ~103~203~                 |
// | 2010-02-20     | trn1703   | Ima       | Pro      |          1 | ~102~202~                 |
// | 2010-02-20     | cnd1702w  | Ima       | Vip      |          0 | ~101~201~                 |
//

// $mid = "h1707sd";
// $mid = "reg1706";
// $mid = "gjd1705h";
// $mid = "a1704ve";
// $mid = "trn1703";
$mid = "h1707sd";

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");

$db = getPushyDatabaseConnection();



function sortAwards($awards, $flags)
  {
    $flagCount=strlen($flags);
    $flagArray = array();
    for ($i=0; $i<$flagCount; $i++)
      {
        $flag=strtoupper(substr($flags,$i,1));
        $flagArray[]=$flag;
      }

    $result=array();

    foreach($awards AS $award => $content)
      {
        $key="";
        for ($i=0; $i<$flagCount; $i++)
          {
            $k=(int)substr($award,$i,1);
            if ($flagArray[$i]=="A")
              $key.= $k;
            else
              $key.= (9-$k);
          }
        $result[$key]=$award;
      }

//  printf("\n---- awards ----\n%s\n",print_r($awards,true));
//
//  printf("\n---- result ----\n%s\n",print_r($result,true));
//
//  ksort($result);
//
//  printf("\n---- result ----\n%s\n",print_r($result,true));


    $awards_sorted=array();
    foreach($result AS $key=>$award)
      {
//       printf("%s => %s\n", $award,$awards[$award]);
         $awards_sorted[$award] = $awards[$award];
      }

//  printf("\n---- Flags  ---- %s\n",$flags);
//  printf("\n---- awards ----\n%s\n",print_r($awards,true));
//  printf("\n---- SORTED -----\n%s\n\n\n\n\n",print_r($awards_sorted,true));

    return $awards_sorted;
  }





$awards =  loadMemberAwards($db, $mid);
    foreach($awards AS $award => $content)
      {
        $awards[$award] = "Award ".$award;
      }

// sortAwards($awards, "AAA");

// sortAwards($awards, "DDD");

// sortAwards($awards, "ADD");

sortAwards($awards, "ADD");

?>
