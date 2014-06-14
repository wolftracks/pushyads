<?php
// printf("<PRE>\n");
// print_r($_REQUEST);
// print_r($_SERVER);
// printf("</PRE>\n");

include_once("pushy_tracker.inc");

if (isset($_REQUEST["sort"]))
  $SortBy=$_REQUEST["sort"];
else
  $SortBy="Date";
if (isset($_REQUEST["page"]))
  $Page=$_REQUEST["page"];
else
  $Page=1;



$PAGE_SIZE=20;
$indexValue = 0;
$limit=$PAGE_SIZE;

function getWidgetDomain($db,$member_id,$widget_key)
  {
    $sql  = "SELECT domain from widget";
    $sql .= " WHERE member_id='$member_id'";
    $sql .= " AND   widget_key='$widget_key'";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());

    if ($result && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
       return $myrow["domain"];
    return "";
  }

function getWidgetsForUser($db,$member_id)
  {
    $widgets=array();
    $sql  = "SELECT widget_key,COUNT(*) from tracker_pushy_widget";
    $sql .= " WHERE member_id='$member_id'";
    $sql .= " GROUP BY widget_key";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());

    if ($result)
      {
        while ($myrow = mysql_fetch_array($result))
          {
            $widget = $myrow[0];
            $count  = $myrow[1];
            if ($count > 0)
              {
                $widgetArray=splitWidgetKey($widget);
                $widget_key    = $widgetArray["WidgetConfigurationKey"];
                $domain = getWidgetDomain($db,$member_id,$widget_key);
                if (strlen($domain)>0)
                  $widgets[$widget_key] = $domain;
              }
          }
      }
    return $widgets;
  }
?>

<div align=right style="margin: -41px 0 0 640px;">
  <a href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/int/reports-referrals.flv') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim2.gif"></a>
</div>

<table width=680 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
  <tr>
    <td bgcolor="#FFFFFF">
      <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
        <tr>
          <td class="text">

           Those who you have personally referred to <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482,
           and who have signed up as members will show up as your referrals in the list below. Coming Soon, you will be able to click on your referral and see
           what products, services, and opportunities they offer.

           <p>A <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 icon
           <img src="http://pds1106.s3.amazonaws.com/images/favicon.ico" style="vertical-align: -1px"> is shown below for every website your referral has
           <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on.</p>

           <p align=center class="darkred bold size16">WHAT? Do you see a member without
           <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"><span class=black>&#8482</span> on their website? </P>

           <p>Give 'em a call, or send 'em an email, telling them how much money they're losing out on by not having
           <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on their website (<b>HINT</b>: by doing so,
           you could get more traffic to your product ads when they add
           <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 to their website - or what if they have 2 or 3
           or 4 websites? <b class="darkred bold">YEE HAW!</b> Do you hear that stampede of traffic in the distance?)</p>

           <div ID="REPORT_PAGE">
             <?php
                $ul_referrals=array();
                $ul_referrals[0]=0;
                $ul_referrals[1]=0;
                $ul_referrals[2]=0;

                $referrals  = 0;
                $sql  = "SELECT user_level,COUNT(*) FROM member";
                $sql .= " WHERE refid='$mid'";
                $sql .= " AND   registered>0";
                $sql .= " AND   member_disabled=0";
                $sql .= " GROUP BY user_level";
                $result = mysql_query($sql,$db);
                if ($result)
                  {
                     while($myrow = mysql_fetch_array($result,MYSQL_NUM))
                       {
                         $lvl=$myrow[0];
                         $count=$myrow[1];
                         $ul_referrals[$lvl]=$count;
                         $referrals += $count;
                       }
                  }
                $TotalPages = (int) ($referrals/$PAGE_SIZE);
                if (($TotalPages * $PAGE_SIZE) < $referrals)
                   $TotalPages++;
                if ($TotalPages==0) $TotalPages=1;
                if ($referrals > 0)
                  {
                    if ($Page > 1)
                      {
                        $indexValue = ($Page-1) * $PAGE_SIZE;
                        if ($indexValue > $referrals)
                          {
                            $Page=1;
                            $indexValue = 0;
                            $limit=$PAGE_SIZE;
                          }
                      }
                    if ($indexValue > 0)
                      {
                        $limit = $indexValue+$PAGE_SIZE;
                      }
                    else
                      {
                        $indexValue = 0;
                        $Page=1;
                        $limit=$PAGE_SIZE;
                      }
                  }

                 // printf("<PRE>\nPAGE: %d\nIndexValue: %d\nREFERRALS: %d\nLIMIT: %d\nSORT: %s\n<br></PRE>\n",$Page,$indexValue,$referrals,$limit,$SortBy);

             ?>
             <iframe name=REPORTS_DOWNLOAD frameborder=0 width=0 height=0></iframe>
             <form name="REPORTS_REFERRALS_FORM" action=NULL>
               <input type="hidden" name="mid" value="<?php echo $mid?>">
               <input type="hidden" name="sid" value="<?php echo $sid?>">
               <table align=center border=0 width=100%>
                 <tr>
                   <td width="40%" align=left class=text>
                     <b>Total Personal Referrals: &nbsp; <span class=red><?php echo $referrals?></span></b>
                   </td>
                   <td width="60%" align=right class=text>
                     Page <b><SPAN id=CURRENTPAGE class="red bold"><?php echo $Page?></SPAN></b>&nbsp;of <b><?php echo $TotalPages?></SPAN></b>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <span class=smalltext><a href=javascript:referrals_getPage('Page')>Display Page:</a></span> &nbsp;
                     <input name=page_number type=text class=form_input size=2 maxlength=3 value="">
                   </td>
                 </tr>
                 <tr>
                   <td width="45%" align=left class=smalltext>
                     <table width=100% border=0 cellpadding=0 cellspacing=0>
                       <tr>
                         <td width="33%" class=size12><b>VIP:</b>&nbsp; <span class=darkred><?php echo $ul_referrals[$PUSHY_LEVEL_VIP]?></span></td>
                         <td width="33%" class=size12><b>PRO:</b>&nbsp; <span class=darkred><?php echo $ul_referrals[$PUSHY_LEVEL_PRO]?></span></td>
                         <td width="33%" class=size12><b>ELITE:</b>&nbsp; <span class=darkred><?php echo $ul_referrals[$PUSHY_LEVEL_ELITE]?></span></td>
                       </tr>
                     </table>
                   </td>
                   <td width="55%" align=right class=smalltext>
                     <a href=javascript:referrals_getPage('First')>First</a>&nbsp; <a href=javascript:referrals_getPage('Prev')>Prev</a>&nbsp; &nbsp;
                     <a href=javascript:referrals_getPage('Next')>Next</a>&nbsp; <a href=javascript:referrals_getPage('Last')>Last</a>&nbsp;
                   </td>
                 </tr>
               </table>

               <table align=center width=100% class=smallgridb1 border=0 cellspacing=0 cellpadding=0>
                 <tr valign="middle" bgcolor="#D0D6DF">
                   <td width="14%" align=left   class=smalltext><a href=javascript:referrals_sortBy('Date')><b>Date</b></a></td>
                   <td width="38%" align=left   class=smalltext><a href=javascript:referrals_sortBy('Name')><b>Name</b></a></td>
                   <td width="10%" align=left   class=smalltext><a href=javascript:referrals_sortBy('Level')><b>Member</b></a></td>
                   <td width="8%"  align=center class=smalltext><b>Email</b></td>
                   <td width="30%" align=left   class=smalltext><b>Widgets on Websites</b></td>
                 </tr>
                 <?php
                    if ($referrals > 0)
                      {
                        $sql    = "SELECT member_id,date_registered,firstname,lastname,email,user_level FROM member";
                        $sql   .= " WHERE refid='$mid'";
                        $sql   .= " AND   registered>0";
                        $sql   .= " AND   member_disabled=0";
                        if ($SortBy=="Name")
                           $sql   .= " ORDER BY lastname, firstname";
                        else
                        if ($SortBy=="Level")
                           $sql   .= " ORDER BY user_level DESC, lastname, firstname";
                        else
                           $sql   .= " ORDER BY date_registered DESC, lastname, firstname";

                        $sql   .= " LIMIT $limit";
                        $result = mysql_query($sql,$db);


                        if ($result)
                          {
                            if ($indexValue > 0)
                              mysql_data_seek($result, $indexValue);
                          }


                        while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
                          {
                            $member_id       = $myrow["member_id"];
                            $firstname       = stripslashes($myrow["firstname"]);
                            $lastname        = stripslashes($myrow["lastname"]);
                            $email           = $myrow["email"];
                            $date_registered = $myrow["date_registered"];
                            $user_level      = $myrow["user_level"];
                            $level = $UserLevels[$user_level];

                            $widgets=getWidgetsForUser($db,$member_id);
                 ?>
                            <tr valign="middle">
                              <td width="14%" align=left   class=smalltext><?php echo $date_registered?></td>
                              <td width="38%" align=left   class=smalltext><?php echo $firstname?> <?php echo $lastname?></td>
                              <td width="10%" align=left   class=smalltext><?php echo $level?></td>
                              <td width="8%"  align=center class=smalltext><a href="mailto:<?php echo $email?>"><img src="http://pds1106.s3.amazonaws.com/images/email2.png" title="<?php echo $email?>"></a></td>
                              <td width="30%" align=left   class=smalltext>
                                <?php
                                  foreach($widgets AS $widget_key => $domain)
                                    {
                                      $url="http://".$domain;
                                ?>
                                      <a href=javascript:referrals_DisplayPushyWebsite('<?php echo $url?>')><img src="http://pds1106.s3.amazonaws.com/images/favicon.ico" alt="<?php echo $domain?>" title="<?php echo $domain?>"></a>
                                <?php
                                    }
                                ?>
                              </td>
                            </tr>
                 <?php
                          }
                      }
                    else
                      {
                        echo "<tr valign=middle height=32><td align=left colspan=5 class=\"arial size16 darkred\">No Referrals</td></tr>";
                      }
                 ?>
               </table>
             </form>
           </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>&nbsp;
<?php
  $response= new stdClass();
  $response->success     = "TRUE";
  $response->CurrentPage = $Page;
  $response->TotalPages  = $TotalPages;
  $response->IndexValue  = $indexValue;
  $response->Limit       = $limit;
?>
