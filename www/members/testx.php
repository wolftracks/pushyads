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
$mid = "cnd1702w";

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");

$db = getPushyDatabaseConnection();


$list  =  listMemberAwards($db, $mid);
print_r($list);

printf("-------------------------\n\n");

$data=print_r($list,true);

printf("<pre>%s\n</pre>\n",$data);



printf("-------------------------\n\n");

$awards =  loadMemberAwards($db, $mid);
print_r($awards);

?>
