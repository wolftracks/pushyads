<?php
 $DEBUG=FALSE;

 $totalMembers=500;
 $maxDepth=25;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");
 include_once("tree_gen_options.php");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();


 //  tree_Rebuild($db, $PUSHY_ROOT, 0);


 //========== PICK ONE OF THESE 3 OPTIONS =====================

 $GENERATOR_OPTION = 0;  // DEFAULT:  Random Tree generation

 //************************** GENERATE A WIDE SET OF DIRECT REFERRALS FOR A SPECIFIC MEMBER **************************
 $GENERATOR_OPTION = 1;
 $GENERATOR_MEMBER_TARGET = "mg1251005566198572";

 //************************** GENERATE A DEEP TREE OF INDIRECT REFERRALS FOR A SPECIFIC MEMBER ***********************
 $GENERATOR_OPTION = 2;
 $GENERATOR_MEMBER_TARGET = "mkd1476";


 /************
 for ($i=0; $i<100; $i++)
   {
     $member_id=selectReferer($db);
     $GENERATOR_MEMBER_TARGET = $member_id;
     $count=rand(2,5);
     $result=addMembers($db, $count);
   }
 ************/

 $GENERATOR_OPTION = 0;  // DEFAULT:  Random Tree generation
 $count=1;
 for ($i=0; $i<100; $i++)
   {
     $result=addMembers($db, $count);
   }

 if ($result)
   printf("\n\n%d new members added ...\n",$count);
 else
   printf("\n\n-- Failed adding new member ...\n");

 //================================================================ DUMP ALL NODES ==================
 $sql  = "SELECT * FROM member where member_disabled=0 ORDER by lft ASC";
 $result = mysql_query($sql,$db);
 if ($result)
   {
     $control = "%-16s   %-8s   %-5s   %-5s\n";
     printf($control, "Member ID", "REFID",  "LFT", "RGT");
     printf($control, "----------------", "--------",  "-----", "-----");
     while ($myrow = mysql_fetch_array($result))
       {
         printf($control, $myrow["member_id"], $myrow["refid"], $myrow["lft"], $myrow["rgt"]);
       }
   }
 printf("\n\n\n\n");
 //================================================================ DUMP ALL NODES ==================

 tree_display($db,$PUSHY_ROOT);

 exit;


 // retrieve the left and right value of the $root node

 $sql  = "SELECT lft, rgt FROM member ";
 $sql .= " WHERE  mid='$root' AND member_disabled=0";
 $result = mysql_query($sql,$db);
 if (($result) && ($myrow = mysql_fetch_array($result)))
   {
     $lft=$myrow["lft"];
     $rgt=$myrow["rgt"];

     // start with an empty $rgt stack
     $rightNodes = array();

     // now, retrieve all descendants of the $root node
     $sql  = "SELECT member_id, lft, rgt FROM member ";
     $sql .= " WHERE lft BETWEEN $lft AND $rgt AND member_disabled=0";

     if (!$INCLUDE_ROOT)
       {
         $sql .= " AND member_id != '$root'";
       }

     $sql .= " ORDER by lft ASC";
     $result = mysql_query($sql,$db);

     if ($result)
       {
         // display each row
         while ($myrow = mysql_fetch_array($result))
           {
                  // check if we should remove a node from the stack
             while ((count($rightNodes)>0) && $rightNodes[count($rightNodes)-1]<$myrow['rgt'])
               {
                 printf("Popping - RGT=%-5s  Count=%d->%d\n",$rightNodes[count($rightNodes)-1],count($rightNodes),count($rightNodes)-1);
                 // printf("  Reason: %-5s < %-5s\n",$rightNodes[count($rightNodes)-1],$myrow['rgt']);
                 array_pop($rightNodes);
               }

             $level = count($rightNodes);
             if ($level > 0)
               {
                  // display indented node
                  echo str_repeat('   ',count($rightNodes))."(".count($rightNodes).")  ".$myrow['member_id']."  L=".$myrow['lft']."  R=".$myrow['rgt']."\n";
               }

             // add this node to the stack
             printf("Pushing - RGT=%-5s  Count=%d->%d\n",$myrow['rgt'],count($rightNodes),count($rightNodes)+1);
             $rightNodes[] = $myrow['rgt'];
           }
       }
   }



 function getNodeLevel($db, $lft, $rgt)
   {
     $sql  = "SELECT COUNT(*) FROM member ";
     $sql .= " WHERE  lft<$lft AND rgt>$rgt AND member_disabled=0";
     $result = mysql_query($sql,$db);
     if (($result) && ($myrow = mysql_fetch_array($result)))
       {
         return $myrow[0];
       }
     return 0;
   }

 function getNodeLevelForMember($db, $mid)
   {
     $level=0;
     $sql  = "SELECT lft, rgt FROM member ";
     $sql .= " WHERE  member_id='$mid' AND member_disabled=0";
     $result = mysql_query($sql,$db);
     if (($result) && ($myrow = mysql_fetch_array($result)))
       {
         $level=getNodeLevel($db,$myrow["lft"],$myrow["rgt"]);
       }
     printf("  ... Level (%s)  =  %d\n",$mid,$level);
     return $level;
   }


 //================================================================ DUMP ALL NODES ==================
 $sql  = "SELECT * FROM member where member_disabled=0 ORDER by lft ASC";
 $result = mysql_query($sql,$db);
 if ($result)
   {
     $control = "%-16s  %-8s   %-5s   %-5s\n";
     printf($control, "Member ID", "REFID",  "LFT", "RGT");
     printf($control, "----------------", "--------",  "-----", "-----");
     while ($myrow = mysql_fetch_array($result))
       {
         printf($control, $myrow["member_id"], $myrow["refid"], $myrow["lft"], $myrow["rgt"]);
       }
   }
 printf("\n\n\n\n");
 //================================================================ DUMP ALL NODES ==================


 $LAST_MEMBER_GENERATED="";

 function generateMember($db)
   {
     global $PUSHY_LEVEL_VIP;
     global $PUSHY_LEVEL_PRO;
     global $PUSHY_LEVEL_ELITE;

     global $consonants;
     global $vowels;
     global $digits;
     global $states;
     global $emailhosts;
     global $table_country;
     global $security_questions;
     global $firstnames;
     global $lastnames;

     global $LAST_MEMBER_GENERATED;
     global $GENERATOR_OPTION;
     global $GENERATOR_MEMBER_TARGET;



     $user_level=rand(0,2);

     $hp1=rand(211,987);
     $hp2=rand(211,987);
     $hp3=rand(2111,9876);

     $h=rand(1,3);
     if ($h==1)
       $phone=rand(211,987).rand(211,987).$hp3=rand(2111,9876);
     else
     if ($h==2)
       $phone=rand(211,987)."-".rand(211,987)."-".$hp3=rand(2111,9876);
     else
       $phone=rand(23,988)." ".rand(2311,8987)." ".$hp3=rand(21,9876);

     $m=rand(1,4);
     if ($m==3)
       $ext=rand(211,9876);
     else
       $ext="";

     unset($state);
     if (rand(1,4)==2)
      $country = $table_country [rand(0,count($table_country    )-1)];
     else
       {
         $country     =  "USA";
         $state = $states[rand(0,count($states)-1)];
       }

     $firstname = $firstnames[rand(0,count($firstnames)-1)];
     $lastname  = $lastnames[rand(0,count($lastnames)-1)];

     $sw=rand(1,5);
     if ($sw == 1)
       {
         $email=strtolower($firstname);
         if (rand(1,19) <= 13)
           $email.= rand(2,834);
       }
     else
     if ($sw == 2)
       {
         $email=strtolower($lastname);
         if (rand(1,19) <= 13)
           $email.= rand(2,834);
       }
     else
     if ($sw == 3)
       {
         $email=substr(strtolower($firstname),0,1).substr(strtolower($lastname),0,(rand(0,strlen($lastname)-1)));
         if (rand(1,19) <= 16)
           $email.= rand(2,834);
       }
     else
     if ($sw == 4)
       {
         $email=strtolower($firstname).strtolower($lastname);
         if (rand(1,19) <= 13)
           $email.= rand(2,834);
       }
     else
       {
         $m=rand(3,5);
         $email="";
         if ($m==3 && rand(1,9) <= 3)
           {
             $email=strtolower($firstname);
             if (rand(1,19) <= 11)
               $email.= rand(2,834);
           }
         else
         if ($m==3 && rand(1,23) <= 5)
           {
             $email=strtolower($lastname);
             if (rand(1,19) <= 11)
               $email.= rand(2,834);
           }
         else
           {
             for ($j=0; $j<$m; $j++)
               {
                 $email .= $consonants[rand(0,count($consonants)-1)];
                 if (rand(1,7) <= 3)
                   $email .= $vowels[rand(0,count($vowels)-1)];
                 if (rand(1,37) <= 5)
                   $email .= $digits[rand(0,count($digits)-1)];
               }
           }
       }
     $email .= "@";
     $email .= $emailhosts[rand(0,count($emailhosts)-1)];

     $zip=rand(21,92).rand(101,999);
     $st=rand(0,count($states)-1);
     $state=$states[$st];
     $address1=rand(112,9853)." Main Street";
     $m=rand(1,4);
     if ($m==3)
       $address2="Suite ".rand(212,9876);
     else
       $address2="";

     $cty=rand(1,9);
     if ($cty==1) {$city="Chicago";       $state="IL"; }
     else
     if ($cty==2) {$city="Los Angeles";   $state="CA"; }
     else
     if ($cty==3) {$city="Phoenix";       $state="AZ"; }
     else
     if ($cty==4) {$city="Philadelphia";  $state="PA"; }
     else
     if ($cty==5) {$city="Atlanta";       $state="GA"; }
     else
     if ($cty==6) {$city="New York";      $state="NY"; }
     else
     if ($cty==7) {$city="Miami";         $state="FL"; }
     else
     if ($cty==8) {$city="Dallas";        $state="TX"; }
     else
     if ($cty==9) {$city="Portland";      $state="OR"; }
     else
       {
         $city="";
       }




     $password="";  // Default Password
     $chars="bcdfghjkmnprstvwxz123456789";
     for ($i=0;$i<8;$i++)
       {
         $n=rand(0,strlen($chars)-1);
         $password .= $chars[$n];
       }



     //----------------------------------------- CREATE MEMBER

     $refid=selectReferer($db);



     if ($GENERATOR_OPTION == 1 && strlen($GENERATOR_MEMBER_TARGET) > 0)
       {
         //************************** GENERATE DIRECT REFERRALS FOR SPECIFIC MEMBER **************************
         $refid=$GENERATOR_MEMBER_TARGET;
       }

     if ($GENERATOR_OPTION == 2 && strlen($GENERATOR_MEMBER_TARGET) > 0)
       {
         //************************** GENERATE TREE REFERRALS FOR SPECIFIC MEMBER **************************
         if ($LAST_MEMBER_GENERATED=="")
           $refid=$GENERATOR_MEMBER_TARGET;
         else
           $refid=$LAST_MEMBER_GENERATED;
       }


     $tree_result = tree_createNewMember($db, $refid, $email);

     if (is_array($tree_result) && count($tree_result)==3)
       {
         list($member_id, $refid, $insert_id) = $tree_result;
       }
     else
       {
         return FALSE;
       }

     //----------------------------------------- CREATE MEMBER

     $LAST_MEMBER_GENERATED=$member_id;


     $affiliate_id = "".$insert_id."-";
     for ($i=0;$i<4;$i++)
       {
         $n=rand(0,strlen($chars)-1);
         $affiliate_id .= $chars[$n];
       }


     $tm = time() - (86400 * rand(0,60));

     $sql  = "UPDATE member set ";
     $sql .= " password                = '$password', ";
     $sql .= " affiliate_id            = '$affiliate_id', ";
     $sql .= " user_level              = '$user_level', ";
     $sql .= " firstname               = '$firstname', ";
     $sql .= " lastname                = '$lastname', ";
     $sql .= " address1                = '$address1', ";
     $sql .= " address2                = '$address2', ";
     $sql .= " city                    = '$city',  ";
     $sql .= " state                   = '$state',  ";
     $sql .= " zip                     = '$zip', ";
     $sql .= " email                   = '$email',  ";
     $sql .= " phone                   = '$phone', ";
     $sql .= " phone_ext               = '$ext', ";
     $sql .= " country                 = 'USA',  ";
     $sql .= " confirmed               = 1,";
     $sql .= " registered              = '".$tm."',";
     $sql .= " date_registered         = '".formatDate($tm)."' ";
     $sql .= " WHERE member_id='$member_id'";
     $result = mysql_query($sql,$db);


     if ($result)
       {
         return TRUE;
       }

     printf("SQL: %s<br>\n",$sql);
     printf("ERR: %s<br>\n",mysql_error());
     flush();
     exit;

     return FALSE;
   }



 function addMembers($db,$count)
   {
     for ($i=0; $i<$count; $i++)
       {
         $result = generateMember($db);
         if (!$result)
           return(FALSE);
       }
     return(TRUE);
   }


//    member_id varchar(20) NOT NULL default '',
//    lft int(11) default '0',
//    rgt int(11) default '0',
//    user_level int(11) default '0',
//    system int(11) NOT NULL default '0',
//    refid varchar(20) default '',
//    affiliate_id varchar(20) NOT NULL default '',
//    `password` varchar(20) default '',
//    firstname varchar(30) default '',
//    lastname varchar(30) default '',
//    email varchar(60) default '',
//    member_disabled int(11) NOT NULL default '0',
//    email_disabled int(11) default '0',
//    confirmed int(11) NOT NULL default '0',
//    registered int(11) NOT NULL default '0',
//    date_registered varchar(10) default '',
//    date_lastaccess varchar(10) default '',
//    company_name varchar(30) default '',
//    address1 varchar(30) default '',
//    address2 varchar(30) default '',
//    city varchar(30) default '',
//    state varchar(25) default '',
//    country char(30) default 'USA',
//    zip varchar(10) default '',
//    phone varchar(25) default '',
//    phone_ext varchar(6) default '',
//    payable_to varchar(40) default '',
//    taxid varchar(16) default '',
//    paypal_email varchar(60) default '',
//    currency varchar(3) NOT NULL default 'USD',
//    website varchar(100) default '',
//    user_ip varchar(20) NOT NULL default '',


 function selectReferer($db)
   {
     $count=0;
     $members=array();
     $sql  = "SELECT member_id FROM member ORDER BY member_id";
     $result = mysql_query($sql,$db);
     if ($result)
       {
         $count=mysql_num_rows($result);
         while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
           {
             $members[] = $myrow["member_id"];
           }
       }

     $n = rand(0,count($members)-1);
     return $members[$n];
   }
?>
