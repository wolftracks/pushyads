<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db = getPushyDatabaseConnection();

$affiliates=array();

$sql  = "SELECT member_id,firstname,lastname,affiliate_id from member WHERE registered>0 AND system=0 AND user_level>0";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $member_id    = $myrow["member_id"];
        $fullname     = stripslashes($myrow["firstname"])." ".stripslashes($myrow["lastname"]);

        $affiliate_id = $myrow["affiliate_id"];
        $affiliates[] = $affiliate_id;

        printf("(%d) %-9s   %-9s  %s\n",count($affiliates),$affiliate_id,$member_id,$fullname);
     }
  }
printf("--------------------------------\n");
exit;



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
//
//
//      +----------------------------------+-----------+-------------------+
//      | widget_key                       | member_id | widget_categories |
//      +----------------------------------+-----------+-------------------+
//      | 2ff0bf884d5f568610bbc0a66551fc5d | pushy     | ~101~130~         |
//      | 29c6c527301b61068c1aa591bc50ea86 | paw1200   | ~111~145~         |
//      | f958e8ce1f1a881fe6bc12d1f8c23633 | pushy     | ~101~             |
//      | 2b1bc75a43876e01ebd8cccaec871e76 | gcw1222p  | ~108~137~         |
//      | b14470a0a77a8bf9791ceea98f5753a9 | hkd1225   | ~101~130~         |
//      | d6039d38b64468137c88515bca933362 | dht1227c  | ~108~             |
//      | 52c33fccfc7bbf2b7dbfc887d84f27b3 | hkd1225   | ~130~             |
//      | c5bbbf0764e773a184eaa053c9371108 | cfv1221s  | ~138~145~         |
//      | d9235fde9aa45e4e241df5b9fd9c8cdb | y1230vt   | ~108~130~         |
//      | 7b749c99ad4a75d90ec3eb65f08f40c3 | cfv1221s  | ~137~147~         |
//      | da34be221190cf41a952aec8d956889c | frg1223   | ~108~             |
//      | 68e7d2b4117e94b5326d86dccc8a2fa6 | gcw1222p  | ~108~             |
//      | d3819b7da9e13887655e580e73c8b40f | d1233cw   | ~108~120~         |
//      | f4d3c6d845d883ec45374f8e0b28606b | wef1232b  | ~103~             |
//      | f2ff5b7d91ba1bac47dc7aa75b15d752 | hkd1225   | ~130~             |
//      | bd2737d38810269baa848da7aa3d8337 | mjb1239n  | ~108~130~         |
//      | 1c781c13d734aded0673e1adf142679f | cfv1221s  | ~103~107~         |
//      | b209ded37e42db80a9cb486a0ca15dd8 | hkd1225   | ~108~             |
//      | 9a4f1fed4af718d9c2c48d3afb0dc0f5 | r1243ya   | ~101~108~         |
//      | abc4c5b21c30148d56af9e8a1108ff1b | fbt1241d  | ~108~             |
//      | bf5ed4e04ef5e1f034e248e9b4aab34b | r1243ya   | ~101~108~         |
//      | 49f912ddd8b70c8b2fe46aa0152b5c0e | r1243ya   | ~101~108~         |
//      +----------------------------------+-----------+-------------------+
//
//
//
//
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


function getIp()
 {
   return rand(110,254).".".rand(110,254).".".rand(110,254).".".rand(110,254);
 }


$WidgetKey   = '29c6c527301b61068c1aa591bc50ea86';
$widget      = getWidget($db,$WidgetKey);
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

// deleteTrackerKeys($db);

// $sql  = "DELETE  from tracker_widget_category";
// $result = mysql_query($sql,$db);

$_SERVER["REMOTE_ADDR"]=getIp();

//-----------------------------------------------------------------------------------------------------------
//
//  Affilate Page Hit
//
//-----------------------------------------------------------------------------------------------------------


$affiliate_member_id = $member_id;
tracker_hit($db,TRACKER_AFFILIATE_PAGE,$affiliate_member_id,'','',TRACKER_ID_AFFILIATE_GETPUSHY);

dumpTrackerKeys($db);


//-----------------------------------------------------------------------------------------------------------
//
//  ELITE BAR Loaded
//
//-----------------------------------------------------------------------------------------------------------

 //-- HITS (VIEWS)

 /* TEST For Duplicates - Same IP - Same Ad */

      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1225');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1226');


      tracker_hit($db,  TRACKER_ELITE_BAR,   'paw1200',   '',  '1207');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'wef1232b',  '',  '1242');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'hfe1201w',  '',  '1213');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'frg1223',   '',  '1252');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'hdn1208',   '',  '1221');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'mbr1217',   '',  '1230');
      tracker_hit($db,  TRACKER_ELITE_BAR,   'wef1232b',  '',  '1256');



 //-- CLICKS (Elite Bar Ad CLICKED)

 /* TEST For Duplicates - Same IP - Same Ad */
      tracker_click($db,   TRACKER_ELITE_BAR, 'wef1232b',  '',   '1256');
      tracker_click($db,   TRACKER_ELITE_BAR, 'wef1232b',  '',   '1256');
      tracker_click($db,   TRACKER_ELITE_BAR, 'wef1232b',  '',   '1256');
      tracker_click($db,   TRACKER_ELITE_BAR, 'wef1232b',  '',   '1256');



dumpTrackerKeys($db);



//-----------------------------------------------------------------------------------------------------------
//
//  Pushy Ads Displayed
//
//-----------------------------------------------------------------------------------------------------------
      $ads = array(
          '1235'   => 'paw1200',
          '1251'   => 'wef1232b',
          '1236'   => 'hfe1201w',
          '1237'   => 'frg1223',
          '1238'   => 'hdn1208',
          '1239'   => 'mbr1217',
          '1240'   => 'wef1232b',
      );


      //--- PUSHY ADS (HITS = VIEWS)
      foreach($ads AS $ad_id => $ad_owner)
        {
          tracker_hit($db, TRACKER_PUSHY_ADS, $ad_owner, '', $ad_id);
        }

      //--- TEST FOR DUPLICATES
      $ad_owner='paw1200';
      tracker_hit($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');
      tracker_hit($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');
      tracker_hit($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');
      tracker_hit($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');
      tracker_hit($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');


      //--- PUSHY ADS (CLICKS)  - Click every OTHER ONE
      $j=0;
      foreach($ads AS $ad_id => $ad_owner)
        {
          $j++;
          if ($j%2==0) continue;

          tracker_click($db, TRACKER_PUSHY_ADS, $ad_owner, '', $ad_id);
        }

      //--- TEST FOR DUPLICATES
      $ad_owner='paw1200';
      tracker_click($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');
      tracker_click($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');
      tracker_click($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');
      tracker_click($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');
      tracker_click($db, TRACKER_PUSHY_ADS, $ad_owner, '', '1235');


dumpTrackerKeys($db);



//-----------------------------------------------------------------------------------------------------------------






//-----------------------------------------------------------------------------------------------------------
//
//  Remote Pushy Widget Loaded - On some Page: (Sample shows Same Widget - 4 Pages - different Tracking Ids
//
//-----------------------------------------------------------------------------------------------------------

   //--- A HIT (VIEW) TO ANY WIDGET  MUST RECORD  HITS(VIEW) TO EACH WIDGET_CATEGORY

$_SERVER["REMOTE_ADDR"]=getIp();

      $tracking_id = "FirstPage";

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }


      $tracking_id = "SecondPage";

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }


      $tracking_id = "ThirdPage";

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }


      $tracking_id = TRACKER_PUSHY_WIDGET_DEFAULT;

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }



//-----------------------------------------------------------------------------------------------------------
//
//  "GET PUSHY" Clicked On a couple of the Above Widgets
//
//-----------------------------------------------------------------------------------------------------------

   //--- A Click ("Get Pushy") ON ANY WIDGET  MUST RECORD  CLICKS TO EACH WIDGET_CATEGORY


      $tracking_id = "SecondPage";

      tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }



      $tracking_id = "ThirdPage";

      tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }



$_SERVER["REMOTE_ADDR"]=getIp();

      $WidgetKey="2b1bc75a43876e01ebd8cccaec871e76";

      $widget      = getWidget($db,$WidgetKey);
      $WidgetOwner = $widget["member_id"];
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




      $tracking_id = "FirstPage";

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }


      $tracking_id = "SecondPage";

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }


      $tracking_id = "SecondPage";

      tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }



$_SERVER["REMOTE_ADDR"]=getIp();

      $WidgetKey="c5bbbf0764e773a184eaa053c9371108";

      $widget      = getWidget($db,$WidgetKey);
      $WidgetOwner = $widget["member_id"];
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


      $tracking_id = "My Web Site";

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }


      $WidgetKey="b14470a0a77a8bf9791ceea98f5753a9";
      $WidgetOwner="hkd1225";
      $categories=array("127");

      $tracking_id = "My Order Page ";

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
        }



$_SERVER["REMOTE_ADDR"]=getIp();

      $WidgetKey="bd2737d38810269baa848da7aa3d8337";

      $widget      = getWidget($db,$WidgetKey);
      $WidgetOwner = $widget["member_id"];
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


      $tracking_id = "About My Company";

      tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');
      for ($i=0; $i<count($categories); $i++)
        {
          tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $categories[$i]);
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
