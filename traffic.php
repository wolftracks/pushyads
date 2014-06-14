<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db = getPushyDatabaseConnection();

$ProductCategories = array(
   "101"  =>  "Advertising",
   "102"  =>  "Animals/Pets",
   "103"  =>  "Art/Entertainment",
   "104"  =>  "Automotive Services",
   "105"  =>  "Aviation/Aerospace",
   "106"  =>  "Beauty",
   "107"  =>  "Building",
   "108"  =>  "Business Opportunities",
   "109"  =>  "Clothing/Fashion",
   "110"  =>  "Communications",
   "111"  =>  "Computer Software",
   "112"  =>  "Computer Hardware",
   "113"  =>  "Current Events",
   "114"  =>  "Culture/Society",
   "115"  =>  "Dating/Romance",
   "116"  =>  "Education/Instruction",
   "117"  =>  "Electronics",
   "118"  =>  "Employment/Careers",
   "119"  =>  "Family/Relationships",
   "120"  =>  "Financial",
   "121"  =>  "Fitness",
   "122"  =>  "Food/Beverage",
   "123"  =>  "Furniture",
   "124"  =>  "Health Care",
   "125"  =>  "Holidays/Events",
   "126"  =>  "Home Services",
   "127"  =>  "Home Applicance",
   "128"  =>  "Insurance",
   "129"  =>  "Legal",
   "130"  =>  "Marketing",
   "131"  =>  "Motor Vehicles",
   "132"  =>  "Real Estate",
   "133"  =>  "Recreation",
   "134"  =>  "Religious",
   "135"  =>  "Sports",
   "136"  =>  "Science",
   "137"  =>  "Self Improvement",
   "138"  =>  "Social Network",
   "139"  =>  "Technology",
   "140"  =>  "Toys",
   "141"  =>  "Travel/Lodging",
   "142"  =>  "Video",
   "143"  =>  "Water Sports",
   "144"  =>  "Weather",
   "145"  =>  "Web/Internet Services",
   "146"  =>  "Wedding",
   "147"  =>  "Writing/Speaking",
);


//-------------------------------------
// INSERT INTO widget VALUES ('2ff0bf884d5f568610bbc0a66551fc5d',1,'pushy','','SYSTEM','PushyAds','pushyads.com',0,'~101~130~',270,333,0,1,1,0,0,1,2,3,0,'2009-11-10','2009-11-18','2009-11-10','2009-12-15',890,890);
// INSERT INTO widget VALUES ('29c6c527301b61068c1aa591bc50ea86',1,'paw1200','','pushy','Tims Website','webtribune.com',0,'~111~145~',360,445,1,0,0,0,0,1,2,0,0,'2009-11-10','2009-12-09','2009-11-18','2009-12-09',44,44);
// INSERT INTO widget VALUES ('f958e8ce1f1a881fe6bc12d1f8c23633',1,'pushy','_pushy_registration_.php','SYSTEM','Registration','pushyads.com',0,'~101~',180,222,1,0,0,3,0,1,0,0,0,'2009-11-10','2009-11-10','2009-11-10','2009-12-10',178,178);
// INSERT INTO widget VALUES ('2b1bc75a43876e01ebd8cccaec871e76',1,'gcw1222p','','cfv1221s','andrea','rightfromtheheart.com',0,'~108~137~',180,222,0,2,3,0,0,1,0,0,0,'2009-11-19','2009-11-19','2009-11-19','2009-11-21',10,10);
// INSERT INTO widget VALUES ('b14470a0a77a8bf9791ceea98f5753a9',1,'hkd1225','','cfv1221s','webbiz','webbizmarket.com',0,'~101~130~',250,309,0,1,2,0,0,1,0,0,0,'2009-11-19','2009-11-21','2009-11-19','2009-11-24',28,28);
// INSERT INTO widget VALUES ('d6039d38b64468137c88515bca933362',1,'dht1227c','','gcw1222p','watchdog','ebiz4today.com',0,'~108~',360,445,1,0,1,2,0,1,0,0,0,'2009-11-19','2009-11-19','','',0,0);
// INSERT INTO widget VALUES ('52c33fccfc7bbf2b7dbfc887d84f27b3',1,'hkd1225','','cfv1221s','jbb','joinbrentbush.com',0,'~130~',240,296,1,0,1,1,0,1,2,0,0,'2009-11-20','2009-11-24','2009-11-20','2009-12-14',107,107);
// INSERT INTO widget VALUES ('c5bbbf0764e773a184eaa053c9371108',1,'cfv1221s','','pushy','DHPushy','networkernet.com',0,'~138~145~',200,247,1,0,2,0,0,1,0,0,0,'2009-11-20','2009-11-23','2009-11-20','2009-11-23',62,62);
// INSERT INTO widget VALUES ('d9235fde9aa45e4e241df5b9fd9c8cdb',1,'y1230vt','','pushy','Mobi','mobibizclub.com',0,'~108~130~',210,259,0,1,1,0,0,1,0,0,0,'2009-11-20','2009-11-20','2009-11-25','2009-12-10',50,50);
// INSERT INTO widget VALUES ('7b749c99ad4a75d90ec3eb65f08f40c3',1,'cfv1221s','','pushy','DHPushy2','networkernet.com',0,'~137~147~',350,432,0,0,0,0,0,0,0,0,0,'2009-11-20','2009-11-23','2009-11-20','2009-12-09',93,93);
// INSERT INTO widget VALUES ('da34be221190cf41a952aec8d956889c',1,'frg1223','','gcw1222p','getwitit','getwit-it.com',0,'~108~',320,395,1,0,2,2,0,1,2,0,0,'2009-11-20','2009-11-20','2009-11-20','2009-11-20',2,2);
// INSERT INTO widget VALUES ('68e7d2b4117e94b5326d86dccc8a2fa6',1,'gcw1222p','','cfv1221s','hover','rightfromtheheart.com',0,'~108~',180,222,1,0,2,3,0,1,0,0,0,'2009-11-21','2009-11-21','2009-11-22','2009-12-15',26,26);
// INSERT INTO widget VALUES ('d3819b7da9e13887655e580e73c8b40f',1,'d1233cw','','gcw1222p','magnetic','millioncashplan.com',0,'~108~120~',230,284,0,2,2,0,0,1,0,0,0,'2009-11-21','2009-11-21','','',0,0);
// INSERT INTO widget VALUES ('f4d3c6d845d883ec45374f8e0b28606b',1,'wef1232b','','cfv1221s','Bob','puresourcefineart.com',0,'~103~',280,346,0,1,1,0,0,1,0,0,0,'2009-11-21','2009-11-27','2009-11-21','2009-11-27',19,19);
// INSERT INTO widget VALUES ('f2ff5b7d91ba1bac47dc7aa75b15d752',1,'hkd1225','','cfv1221s','webbiz home','webbizmarket.com',0,'~130~',280,346,0,1,2,0,0,1,0,3,0,'2009-11-21','2009-11-22','2009-11-21','2009-12-13',76,76);
// INSERT INTO widget VALUES ('bd2737d38810269baa848da7aa3d8337',1,'mjb1239n','','gcw1222p','THBT','thehomebusinesstutor.com',0,'~108~130~',180,222,0,2,2,0,0,1,0,0,0,'2009-11-23','2009-11-23','2009-11-23','2009-12-03',150,150);
// INSERT INTO widget VALUES ('1c781c13d734aded0673e1adf142679f',1,'cfv1221s','','pushy','Pushy3','networkernet.com',0,'~103~107~',330,407,0,2,1,0,0,1,0,0,0,'2009-11-23','2009-11-23','2009-11-23','2009-12-09',130,130);
// INSERT INTO widget VALUES ('b209ded37e42db80a9cb486a0ca15dd8',1,'hkd1225','','cfv1221s','webbizdailypay','webbizmarket.com',0,'~108~',240,296,0,1,1,0,0,1,3,3,0,'2009-11-23','2009-11-23','2009-11-23','2009-12-15',32,32);
// INSERT INTO widget VALUES ('9a4f1fed4af718d9c2c48d3afb0dc0f5',1,'r1243ya','','hfe1201w','u2project','u2project.com',0,'~101~108~',210,259,1,0,1,1,0,1,0,0,0,'2009-11-24','2009-11-24','','',0,0);
// INSERT INTO widget VALUES ('abc4c5b21c30148d56af9e8a1108ff1b',1,'fbt1241d','','gcw1222p','plexy','doitnow4freedom.com',0,'~108~',200,247,1,0,3,3,0,1,4,0,0,'2009-11-25','2009-11-25','','',0,0);
// INSERT INTO widget VALUES ('bf5ed4e04ef5e1f034e248e9b4aab34b',1,'r1243ya','','hfe1201w','u2project2','u2project.com',0,'~101~108~',210,259,0,1,1,0,1,0,0,0,0,'2009-11-27','2009-11-27','','',0,0);
// INSERT INTO widget VALUES ('49f912ddd8b70c8b2fe46aa0152b5c0e',1,'r1243ya','','hfe1201w','autoprospector','autoprospector.com',0,'~101~108~',210,259,1,0,1,2,0,1,0,0,0,'2009-11-27','2009-11-27','','',0,0);



// INSERT INTO ads VALUES (1225,'2009-11-17','2009-11-17','paw1200',0,0,0,0,1,1213,'http://abc.def?my_id=98765','',0,0,0);
// INSERT INTO ads VALUES (1226,'2009-11-17','2009-11-19','paw1200',1,1,1,1,0,1223,'http://autoprospector.com','http://imtools.apr52.com',1260909467719208,1260910227900629,1);
// INSERT INTO ads VALUES (1207,'2009-11-10','2009-11-16','paw1200',0,0,0,0,0,1207,'http://yahoo.com','http://yahoo.com',1258658757999336,1258660396833554,1);
// INSERT INTO ads VALUES (1209,'2009-11-10','2009-11-17','paw1200',0,0,0,0,0,1209,'http://msn.com','http://my_product/signup.html',0,0,1);
// INSERT INTO ads VALUES (1242,'2009-11-19','2009-11-19','wef1232b',1,1,1,0,1,1222,'http://bigdogleads.apr52.com','',1260909396415987,1260909988048943,0);
// INSERT INTO ads VALUES (1213,'2009-11-12','2009-11-12','hfe1201w',0,0,0,0,0,1213,'http://pushyads.com/123','http://pushyads.com/',1258071276211839,1258657465322889,1);
// INSERT INTO ads VALUES (1252,'2009-11-20','2009-11-20','frg1223',0,0,0,0,1,1211,'http://www.affiliatemoney.ws/go/imking/get_tt','',0,1258757366791699,0);
// INSERT INTO ads VALUES (1215,'2009-11-12','2009-11-12','hfe1201w',1,1,1,0,0,1215,'http://pushyads.com/1202-m1dm','',1260679446229411,1260910227899094,0);
// INSERT INTO ads VALUES (1217,'2009-11-15','2009-11-15','hdn1208',0,0,0,0,0,1217,'http://cbs.com','http://autoprospector.com',0,0,1);
// INSERT INTO ads VALUES (1253,'2009-11-20','2009-11-20','frg1223',1,0,1,1,0,1238,'http://www.affiliatemoney.ws/go/imking/get_tt','http://www.affiliatemoney.ws/affiliates.php',1260909708920439,1260910107839432,1);
// INSERT INTO ads VALUES (1223,'2009-11-16','2009-11-19','hdn1208',1,1,1,1,0,1222,'http://pushy.apr52.com','http://pushy.apr52.com/affiliates',1260909467715741,1260910227900629,1);
// INSERT INTO ads VALUES (1221,'2009-11-15','2009-11-15','hdn1208',0,0,0,0,0,1221,'http://abc.com','http://cbs.com',0,0,1);
// INSERT INTO ads VALUES (1227,'2009-11-17','2009-11-17','mbr1217',0,0,0,0,0,1224,'http://google.com','http://autoprospector.com/service',0,0,1);
// INSERT INTO ads VALUES (1229,'2009-11-17','2009-11-17','mbr1217',0,0,0,0,0,1226,'http://cnn.com','',0,0,0);
// INSERT INTO ads VALUES (1230,'2009-11-17','2009-11-17','mbr1217',0,0,0,0,1,1214,'http://msn.com','',0,0,0);
// INSERT INTO ads VALUES (1256,'2009-11-21','2009-11-21','wef1232b',0,0,0,0,1,1211,'http://pushyads.com/1233-','',0,0,0);
// INSERT INTO ads VALUES (1232,'2009-11-19','2009-11-19','gcw1222p',1,0,0,0,0,1228,'http://www.andreapettit.net','',0,0,0);
// INSERT INTO ads VALUES (1233,'2009-11-19','2009-11-19','gcw1222p',0,1,1,0,0,1229,'http://www.rightfromtheheart.biz','',1260909708934864,1260910227900629,0);
// INSERT INTO ads VALUES (1234,'2009-11-19','2009-11-19','hkd1225',0,1,0,0,0,1230,'http://webbizmarket.com/blogomator','',1259425143013572,1259447063117758,0);
// INSERT INTO ads VALUES (1235,'2009-11-19','2009-11-19','x1229pd',1,0,0,0,1,1222,'http://zappacosta.apr52.com/affiliate','',0,0,0);
// INSERT INTO ads VALUES (1251,'2009-11-20','2009-11-20','frg1223',0,1,0,0,1,1222,'http://getwitit.apr52.com','',0,1258756103010220,0);
// INSERT INTO ads VALUES (1236,'2009-11-19','2009-11-19','x1229pd',0,0,0,0,0,1231,'http://www.tvi10kexplosion.com','',0,0,0);
// INSERT INTO ads VALUES (1237,'2009-11-19','2009-11-19','x1229pd',0,0,1,0,1,1223,'http://zappacosta.apr52.com','',1260909708912761,1260910107839432,0);
// INSERT INTO ads VALUES (1238,'2009-11-19','2009-11-19','x1229pd',0,0,0,0,0,1232,'http://www.recessionproofwealth.us','',0,0,0);
// INSERT INTO ads VALUES (1239,'2009-11-19','2009-11-19','dht1227c',0,0,0,0,1,1222,'http://watchdogleads.apr52.com/affiliates','',0,1258703640710060,0);
// INSERT INTO ads VALUES (1240,'2009-11-19','2009-11-19','dht1227c',0,0,0,0,1,1223,'http://watchdogleads.apr52.com','',0,1258704900906200,0);
// INSERT INTO ads VALUES (1244,'2009-11-20','2009-11-20','dht1227c',0,0,0,0,1,1211,'http://pushyads.com/1228-m9nj','',0,1258760084466864,0);
// INSERT INTO ads VALUES (1245,'2009-11-20','2009-11-20','d1233cw',1,1,0,0,1,1222,'http://markpetty.apr52.com','',0,0,0);
// INSERT INTO ads VALUES (1246,'2009-11-20','2009-11-20','d1233cw',0,0,0,0,0,1234,'http://www.millioncashplan.com','',0,0,0);
// INSERT INTO ads VALUES (1247,'2009-11-20','2009-11-20','d1233cw',0,0,0,0,0,1235,'http://www.realcashfreedom.com','',0,0,0);
// INSERT INTO ads VALUES (1248,'2009-11-20','2009-11-20','d1233cw',0,0,1,0,1,1211,'http://pushyads.com/1234-','',1260909467722701,1260910227900629,0);
// INSERT INTO ads VALUES (1249,'2009-11-20','2009-11-20','dht1227c',1,0,1,0,0,1236,'http://budurl.com/freegift1','',1260909708933895,1260910227900629,0);
// INSERT INTO ads VALUES (1250,'2009-11-20','2009-11-25','cfv1221s',0,0,0,1,0,1237,'http://networkernet.com','http://pushyads.com/1222-48hv',0,0,1);
// INSERT INTO ads VALUES (1255,'2009-11-20','2009-11-23','cfv1221s',0,1,0,0,1,1238,'http://pushyads.com/1222-48hv','',0,1260228856084978,0);
// INSERT INTO ads VALUES (1257,'2009-11-21','2009-11-21','djf1224',1,0,1,0,1,1211,'http://mymonavie.com/susiebrown','',1260909708916537,1260910107839432,0);
// INSERT INTO ads VALUES (1258,'2009-11-21','2009-11-21','hkd1225',1,0,0,0,1,1211,'http://pushyads.com/1226-tf2s','',0,0,0);
// INSERT INTO ads VALUES (1259,'2009-11-23','2009-11-23','mjb1239n',0,0,0,0,0,1241,'http://www.thehomebusinesstutor.com','',0,0,0);
// INSERT INTO ads VALUES (1260,'2009-11-23','2009-11-23','cfv1221s',0,0,1,0,1,1222,'http://pushyads.com/1222-48hv','',1260909677109799,1260910107839432,0);
// INSERT INTO ads VALUES (1261,'2009-11-23','2009-11-25','cfv1221s',1,0,0,0,1,1223,'http://networkernet.com','',0,0,0);
// INSERT INTO ads VALUES (1262,'2009-11-23','2009-11-23','hkd1225',0,0,1,0,0,1242,'http://webbizmarket.com/dailypay.htm','',1260909677118489,1260910227900629,0);
// INSERT INTO ads VALUES (1263,'2009-11-23','2009-11-23','d1240wy',0,0,0,0,0,1243,'http://www.12secondcommute.com/rep/recommends.html','',0,0,0);
// INSERT INTO ads VALUES (1264,'2009-11-23','2009-11-23','d1240wy',1,0,0,0,1,1211,'http://pushyads.com/1241-','',0,0,0);
// INSERT INTO ads VALUES (1265,'2009-11-24','2009-11-24','fbt1241d',0,0,0,0,1,1223,'http://www.doitnow4freedom.com','',0,0,0);
// INSERT INTO ads VALUES (1266,'2009-11-24','2009-11-24','fbt1241d',1,0,0,0,1,1222,'http://www.doitnow4freedom.com','',1259615477687354,1259622205823337,0);
// INSERT INTO ads VALUES (1267,'2009-11-24','2009-11-24','fbt1241d',0,1,0,0,1,1211,'http://www.doitnow4freedom.com','',0,0,0);
// INSERT INTO ads VALUES (1268,'2009-11-25','2009-11-25','fbt1241d',0,0,1,0,1,1238,'http://pushyads.com/1242-','',1260909677113670,1260910227900629,0);
// INSERT INTO ads VALUES (1269,'2009-11-26','2009-11-26','wce1245k',1,0,0,0,0,1244,'http://www.buildwealthonlinenow.com','',0,0,0);
// INSERT INTO ads VALUES (1270,'2009-11-26','2009-11-26','wce1245k',0,0,0,0,0,1246,'http://www.markep.futureawakenings.com','',0,0,0);
//-------------------------------------


function getIp($p1=0)
 {
   if ($p1 > 0)
     return $p1.".".rand(110,254).".".rand(110,254).".".rand(110,254);
   else
     return rand(110,254).".".rand(110,254).".".rand(110,254).".".rand(110,254);
 }



deleteTrackerKeys($db);

// $sql  = "DELETE  from tracker_widget_category";
// $result = mysql_query($sql,$db);




//=============== BOB ============================================================


$WidgetKey   = 'aaaaaaaa';

$widget = array(
                 "member_id" => "bob",
                 "widget_categories" => "~101~130~",
               );

$member_id   = $widget["member_id"];
$WidgetOwner = $member_id;
$WidgetCategories = $widget["widget_categories"];

$categories = array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $categories[]=$tarray[$i];
      }
  }

echo "\n--- WIDGET ---\n";
print_r($widget);
echo "\n--- WIDGET CATEGORIES ---\n";
print_r($categories);
// exit;


      $tracking_id = "bob-1";


for ($h=1; $h<=14; $h++)
  {
$_SERVER["REMOTE_ADDR"]=getIp($h);
      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }

      if ($h <= 3)
        {
          tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
          for ($i=0; $i<count($categories); $i++)
            {
              tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
            }
        }
  }




$WidgetKey   = 'bbbbbbbb';

$widget = array(
                 "member_id" => "bob",
                 "widget_categories" => "~101~",
               );

$member_id   = $widget["member_id"];
$WidgetOwner = $member_id;
$WidgetCategories = $widget["widget_categories"];

$categories = array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $categories[]=$tarray[$i];
      }
  }

echo "\n--- WIDGET ---\n";
print_r($widget);
echo "\n--- WIDGET CATEGORIES ---\n";
print_r($categories);
// exit;


      $tracking_id = "bob-2";


for ($h=1; $h<=10; $h++)
  {
$_SERVER["REMOTE_ADDR"]=getIp($h);
      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }

      if ($h <= 1)
        {
          tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
          for ($i=0; $i<count($categories); $i++)
            {
              tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
            }
        }
  }






//=============== MARY ===========================================================


$WidgetKey   = 'iiiiiiii';

$widget = array(
                 "member_id" => "mary",
                 "widget_categories" => "~101~120~",
               );

$member_id   = $widget["member_id"];
$WidgetOwner = $member_id;
$WidgetCategories = $widget["widget_categories"];

$categories = array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $categories[]=$tarray[$i];
      }
  }

echo "\n--- WIDGET ---\n";
print_r($widget);
echo "\n--- WIDGET CATEGORIES ---\n";
print_r($categories);
// exit;


      $tracking_id = "mary-1";


for ($h=1; $h<=52; $h++)
  {
$_SERVER["REMOTE_ADDR"]=getIp($h);
      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }

      if ($h <= 5)
        {
          tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
          for ($i=0; $i<count($categories); $i++)
            {
              tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
            }
        }
  }




$WidgetKey   = 'jjjjjjjj';

$widget = array(
                 "member_id" => "mary",
                 "widget_categories" => "~130~",
               );

$member_id   = $widget["member_id"];
$WidgetOwner = $member_id;
$WidgetCategories = $widget["widget_categories"];

$categories = array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $categories[]=$tarray[$i];
      }
  }

echo "\n--- WIDGET ---\n";
print_r($widget);
echo "\n--- WIDGET CATEGORIES ---\n";
print_r($categories);
// exit;


      $tracking_id = "mary-2";


for ($h=1; $h<=73; $h++)
  {
$_SERVER["REMOTE_ADDR"]=getIp($h);
      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }

      if ($h <= 11)
        {
          tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
          for ($i=0; $i<count($categories); $i++)
            {
              tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
            }
        }
  }







$WidgetKey   = 'kkkkkkkk';

$widget = array(
                 "member_id" => "mary",
                 "widget_categories" => "~108~120~",
               );

$member_id   = $widget["member_id"];
$WidgetOwner = $member_id;
$WidgetCategories = $widget["widget_categories"];

$categories = array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $categories[]=$tarray[$i];
      }
  }

echo "\n--- WIDGET ---\n";
print_r($widget);
echo "\n--- WIDGET CATEGORIES ---\n";
print_r($categories);
// exit;


      $tracking_id = "mary-3";


for ($h=1; $h<=82; $h++)
  {
$_SERVER["REMOTE_ADDR"]=getIp($h);
      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }

      if ($h <= 14)
        {
          tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
          for ($i=0; $i<count($categories); $i++)
            {
              tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
            }
        }
  }








//=============== JIM ============================================================


$WidgetKey   = 'xxxxxxxx';

$widget = array(
                 "member_id" => "jim",
                 "widget_categories" => "~108~120~",
               );

$member_id   = $widget["member_id"];
$WidgetOwner = $member_id;
$WidgetCategories = $widget["widget_categories"];

$categories = array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $categories[]=$tarray[$i];
      }
  }

echo "\n--- WIDGET ---\n";
print_r($widget);
echo "\n--- WIDGET CATEGORIES ---\n";
print_r($categories);
// exit;


      $tracking_id = "jim-1";


for ($h=1; $h<=70; $h++)
  {
$_SERVER["REMOTE_ADDR"]=getIp($h);
      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }

      if ($h <= 6)
        {
          tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
          for ($i=0; $i<count($categories); $i++)
            {
              tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
            }
        }
  }




$WidgetKey   = 'yyyyyyyy';

$widget = array(
                 "member_id" => "jim",
                 "widget_categories" => "~101~",
               );

$member_id   = $widget["member_id"];
$WidgetOwner = $member_id;
$WidgetCategories = $widget["widget_categories"];

$categories = array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $categories[]=$tarray[$i];
      }
  }

echo "\n--- WIDGET ---\n";
print_r($widget);
echo "\n--- WIDGET CATEGORIES ---\n";
print_r($categories);
// exit;


      $tracking_id = "jim-2";


for ($h=1; $h<=126; $h++)
  {
$_SERVER["REMOTE_ADDR"]=getIp($h);
      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }

      if ($h <= 18)
        {
          tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
          for ($i=0; $i<count($categories); $i++)
            {
              tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
            }
        }
  }





$WidgetKey   = 'zzzzzzzz';

$widget = array(
                 "member_id" => "jim",
                 "widget_categories" => "~112~117~",
               );

$member_id   = $widget["member_id"];
$WidgetOwner = $member_id;
$WidgetCategories = $widget["widget_categories"];

$categories = array();
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $categories[]=$tarray[$i];
      }
  }

echo "\n--- WIDGET ---\n";
print_r($widget);
echo "\n--- WIDGET CATEGORIES ---\n";
print_r($categories);
// exit;


      $tracking_id = "jim-3";


for ($h=1; $h<=12; $h++)
  {
$_SERVER["REMOTE_ADDR"]=getIp($h);
      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }

      if ($h <= 1)
        {
          tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
          for ($i=0; $i<count($categories); $i++)
            {
              tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
            }
        }
  }






dumpTrackerKeys($db);
exit;


















function dumpTrackerKeys($db)
  {
    printf("\n-- Tracker Keys ----------------\n");
    $sql  = "SELECT * from tracker_keys ORDER by created";
    $result = mysql_query($sql,$db);
    if ($result)
      {
        $inx=0;
        while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
          {
            $created      = $myrow["created"];
            $date_created = $myrow["date_created"];
            $keydata      = $myrow["keydata"];

            printf("(%2d)  %s | %s | %s\n",$inx,$created,$date_created,$keydata);
            $inx++;
          }
      }
    printf("--------------------------------\n");
  }


function deleteTrackerKeys($db)
  {
    $sql  = "DELETE  from tracker_keys";
    $result = mysql_query($sql,$db);
  }


?>
