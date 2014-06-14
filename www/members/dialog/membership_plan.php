<?php
$user_level = $memberRecord["user_level"];
// printf("<br>USER_LEVEL=%d<br>\n",$user_level);

$YOU_ARE_IMAGE = "http://pds1106.s3.amazonaws.com/images/you-are.png";

// $PUSHY_LEVEL_VIP
// $PUSHY_LEVEL_PRO
// $PUSHY_LEVEL_ELITE

$display_vip="";
$display_pro="";
$display_elite="";
if ($user_level == $PUSHY_LEVEL_VIP)
 {
   $display_pro="none";
   $display_elite="none";
 }
else
if ($user_level == $PUSHY_LEVEL_PRO)
 {
   $display_vip="none";
   $display_elite="none";
 }
else
if ($user_level == $PUSHY_LEVEL_ELITE)
 {
   $display_vip="none";
   $display_pro="none";
 }
?>
<html>
<title>My Pushy Backoffice</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">


<style>
.dialog
  {
    font-family: Tahoma,Verdana,Arial;
  }
</style>

<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.json-2.2.min.js"></script>

<script type="text/javascript">

var mid="<?php echo $mid?>";
var sid="<?php echo $sid?>";

function orderTabClicked(option)
 {
   for (var i=1; i<=3; i++)
     {
       var id  = 'order_option_'+i;
       var el  =  document.getElementById(id);
       var bid = 'order_button_'+i;
       var bel =  document.getElementById(bid);
        if (el && (bel))
         {
           if (i==option && (el.style.display == 'none'))
             {
               el.style.display='';
               bel.value='CLOSE';
             }
           else
             {
               el.style.display='none';
               bel.value='OPEN';
             }
         }
     }
 }

function downgrade(fromLevel,toLevel)
 {
   var target_level=0;
   var msg="";
   if (toLevel=="VIP")
     {
       msg  = " WARNING! You have requested to downgrade your membership to VIP status. \n\n";

       msg += " Doing so will eliminate all of your "+fromLevel+" benefits and privileges, \n";
       msg += " beginning on the anniversary date of your last monthly membership charge. \n\n";

       msg += " Are you absolutely sure you want to do this?\n\n";

       msg += " Click OK to perform the Downgrade to VIP\n\n";
       msg += " Otherwise, Click CANCEL to cancel this request\n";

       target_level=0;
     }
   else
   if (toLevel=="PRO")
     {
       msg  = " WARNING! You have requested to downgrade your membership to PRO status. \n\n";

       msg += " Doing so will eliminate all of your "+fromLevel+" benefits and privileges, \n";
       msg += " beginning on the anniversary date of your last monthly membership charge. \n\n";

       msg += " Are you absolutely sure you want to do this?\n\n";

       msg += " Click OK to perform the Downgrade to PRO\n\n";
       msg += " Otherwise, Click CANCEL to cancel this request\n";

       target_level=1;
     }
   else
     return;

   var resp=confirm(msg);
   if (!resp) return;


   var data = {
                tp:            "user_downgrade",
                mid:           mid,
                sid:           sid,
                target_level:  target_level
              }

   $.ajax({
      type:     "POST",
      url:      "ajax.php",
      data:     data,
      dataType: "json",
      cache:    false,
      error:    function (XMLHttpRequest, textStatus, errorThrown)
                {
                  // typically only one of textStatus or errorThrown will have info
                  var httpStatus=XMLHttpRequest.status;
                  if (httpStatus==401)
                    {
                      top.location.href="/index.php?SessionExpired";
                    }
                  else
                    {
                      alert("Request Failed - HTTP Status:"+ httpStatus);
                    }
                },
      success:  function(response, textStatus)
                {
                  var status=response.status;
                  if (status != 0)
                    {
                      alert( response.message );
                    }
                  else
                    {
                      var data = response.data;
                      var msg  = "Your DownGrade Request to "+toLevel+" has been Submitted\n\n";
                          msg += "The Downgrade is currently scheduled to take effect on "+data.target_date+"\n\n";
                      alert(msg);
                    }
                }
   });

 }


function upgrade(toLevel)
 {
   var tp="upgrade_pro";
   if (toLevel=="ELITE")
     {
       tp="upgrade_elite";
     }

   var orderPage = "<?php echo SECURE_SERVER?>/link.php?tp="+tp+"&mid="+mid+"&sid="+sid;
   window.top.hidePopWin(false);
   top.location.href=orderPage;
 }

</script>
</head>
<body topmargin=0 class=dialog>
<table align=center width=720 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td width=720 valign=top  class=size14>
      <br>

      <font size=4><b>Upgrade</b> (or downgrade) <b>Your Membership Plan</b></font>

      <p>Simply click on the "OPEN" button next to the Membership Plan you would like to establish.</p

      <p>If you <b>downgrade</b> your membership, you will be able to continue utilizing your current
         membership benefits until your next billing date, at which time your billing will change according to the downgrade
         option you selected.
      </p>

      <p>If you <b>upgrade</b> your membership, your benefits for the membership plan you select will be available
      immediately after your order is placed. If you are currently a <b>PRO</b> member, upgrading to an <b>ELITE</b> member, we will prorate your membership
      fee by subtracting the number of days left in your <b>PRO</b> membership (approximately $1.57 p/day) from the total cost of your <b>ELITE</b>
      membership.
      </p>

      <br>

      <table valign=top width="710" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="100%">
            <table width=100% align=center cellpadding=1 cellspacing=4 bgcolor="#FFEECC" class="bgborder">
              <tr valign=bottom bgcolor="#FFEECC" >
                <td width="14%" align=left   border=0 height=8></td>
                <td width="74%" align=center border=0></td>
                <td width="12%" align=center border=0></td>
              </tr>

              <!---------------------- VIP Membership ----------------------->
              <tr valign=middle height=32>

                <?php
                   $img_you_are="&nbsp;";
                   if ($user_level == $PUSHY_LEVEL_VIP)
                     {
                       $img_you_are="<img src=\"$YOU_ARE_IMAGE\">";
                     }
                ?>
                <td width=14% align=left style="border:0px; background-color:#FFEECC;"><?php echo $img_you_are?></td>

                <td width=74% align=left valign=middle style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:10;">
                  <img src="http://pds1106.s3.amazonaws.com/images/member-vip_sm.gif"  alt="VIP Pushy Membership" style="vertical-align:-5px;">&nbsp;
                  <b class=size18>VIP <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px">  Member</b>
                    <span style="margin-left: 30px; margin-top: 7px;" class=size14>Network Exposure: <b class=red>LIMITED</b></span>
                </td>

                <td width=12% align=center valign="absmiddle">
                  <input id='order_button_1' style="vertical-align: -6px;" type="button" class=bigbutton value="OPEN" onClick=javascript:orderTabClicked(1)>
                </td>
              </tr>

              <tr id='order_option_1' style='display:<?php echo $display_vip?>'>
                <td colspan=3>
                  <table align=right width=575 cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin: 5px;">
                    <tr valign=bottom height=70>
                      <td style="border:0px; background-color:#FFC757;">
                        <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                          <tr height=70 bgcolor="#FFFFFF">
                            <td width=20% align=center valign=top>
                              <img src="http://pds1106.s3.amazonaws.com/images/member-vip.png" height=100 alt="VIP PUSHY! Member">
                            </td>

                            <td width=80% class=largetext>
                              As a <b>VIP</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px"><sup>tm</sup>
                              <b>Member</b>, you get a 30 day <b class=green>FREE</b> trial membership, along with a two line ad that you can have him display through his
                              network of widgets on other member websites. You also earn 30% commission on all your direct referral membership sales for as long as
                              you are a member in good standing. You can upgrade your membership to a <b>PRO</b> or <b>Elite</b> member at any time before or after
                              your 30 day trial expires. <span class=size11>
                                <a href="javascript:openPopup('/members/popup.php?tp=pop-compare&sid=<?php echo $sid?>&mid=<?php echo $mid?>',630,700,true)">
                                <img src="http://pds1106.s3.amazonaws.com/images/compare.gif" style="vertical-align: -3px"></a></span>
                            </td>
                          </tr>

                          <tr bgcolor="#FFFFFF">
                            <td width=100% colspan=2>
                              <div align=center style="margin: 5px 0 5px 0;">
                                <?php
                                if ($user_level == $PUSHY_LEVEL_VIP)
                                  {
                                ?>
                                   <a href=javascript:upgrade("PRO")><img src="http://pds1106.s3.amazonaws.com/images/upgrade-pro_sm.png" alt="Upgrade to PRO Pushy Member"></a> &nbsp;
                                   <a href=javascript:upgrade("ELITE")><img src="http://pds1106.s3.amazonaws.com/images/upgrade-elite_sm.png" alt="Upgrade to ELITE Pushy Member"></a>
                                <?php
                                  }
                                else
                                if ($user_level == $PUSHY_LEVEL_PRO)
                                  {
                                ?>
                                   <a href=javascript:downgrade("PRO","VIP")><img src="http://pds1106.s3.amazonaws.com/images/downgrade-vip_sm.png" alt="Downgrade to VIP Pushy Member"></a> &nbsp;
                                <?php
                                  }
                                else
                                if ($user_level == $PUSHY_LEVEL_ELITE)
                                  {
                                ?>
                                   <a href=javascript:downgrade("ELITE","VIP")><img src="http://pds1106.s3.amazonaws.com/images/downgrade-vip_sm.png" alt="Downgrade to VIP Pushy Member"></a> &nbsp;
                                <?php
                                  }
                                ?>
                              </div>
                            </td>
                          </tr>

                        </table>
                      </td>
                    </tr>

                  </table>
                </td>
              </tr>

              <tr valign=bottom bgcolor="#FFEECC" >
                <td width="14%" align=left   border=0 height=8></td>
                <td width="74%" align=center border=0></td>
                <td width="12%" align=center border=0></td>
              </tr>

                <!---------------------- PRO Pushy Member  ----------------------->
              <tr valign=middle height=32>

                <?php
                   $img_you_are="&nbsp;";
                   if ($user_level == $PUSHY_LEVEL_PRO)
                     {
                       $img_you_are="<img src=\"$YOU_ARE_IMAGE\">";
                     }
                ?>
                <td width=14% align=left style="border:0px; background-color:#FFEECC;"><?php echo $img_you_are?></td>

                <td width=74% align=left valign=middle style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:10;">
                  <img src="http://pds1106.s3.amazonaws.com/images/member-pro_sm.gif" alt="PRO Pushy Membership" style="vertical-align:-5px;">&nbsp;
                  <b class=size18>PRO <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px">  Member</b>
                    <span style="margin-left: 24px; margin-top: 7px;" class=size14>Network Exposure: <b class=red>FULL</b></span>
                </td>

                <td width=12% align=center valign="absmiddle">
                  <input id='order_button_2' style="vertical-align: -6px;" type="button" class=bigbutton value="OPEN" onClick=javascript:orderTabClicked(2)>
                </td>
              </tr>

              <tr id='order_option_2' style='display:<?php echo $display_pro?>'>
                <td colspan=3>
                  <table align=right width=575 cellspacing=2 cellpadding=2  class=bgborder bgcolor="#FFC757" style="margin:5px;">
                    <tr valign=bottom height=70>
                      <td style="border:0px; background-color:#FFC757;">
                        <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                          <tr height=70 bgcolor="#FFFFFF">
                            <td width=20% align=center valign=top>
                              <img src="http://pds1106.s3.amazonaws.com/images/member-pro.png" alt="PRO PUSHY! Membership"><br>
                              <b class=size20><sup>$</sup></b><b class=size26>47</b><b class=size12><i>p/mo</i></b>
                            </td>

                            <td width=80% class=largetext>
                              As a <b>PRO</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"><sup>tm</sup>
                              <b>Member</b> you receive 5 levels deep of network exposure for your product or service, which will be displayed on all widgets of member
                              websites in your entire network (no limit to the volume of traffic going through it). You receive full access to the reporting system and backoffice
                              features. You also earn 50% commission on all your direct referral membership sales for as long as you are a member in good standing.
                                 <span class=size11>
                                <a href="javascript:openPopup('/members/popup.php?tp=pop-compare&sid=<?php echo $sid?>&mid=<?php echo $mid?>',630,700,true)">
                                <img src="http://pds1106.s3.amazonaws.com/images/compare.gif" style="vertical-align: -3px"></a></span>

                            </td>
                          </tr>

                          <tr bgcolor="#FFFFFF">
                            <td width=100% colspan=2>
                              <div align=center style="margin: 5px 0 5px 0;">
                                <?php
                                if ($user_level == $PUSHY_LEVEL_VIP)
                                  {
                                ?>
                                    <a href=javascript:upgrade("PRO")><img src="http://pds1106.s3.amazonaws.com/images/upgrade-pro.png" alt="Upgrade to PRO Pushy Member"></a> &nbsp;
                                <?php
                                  }
                                else
                                if ($user_level == $PUSHY_LEVEL_PRO)
                                  {
                                ?>
                                    <a href=javascript:upgrade("ELITE")><img src="http://pds1106.s3.amazonaws.com/images/upgrade-elite_sm.png" alt="Upgrade to ELITE Pushy Member"></a>
                                    <a href=javascript:downgrade("PRO","VIP")><img src="http://pds1106.s3.amazonaws.com/images/downgrade-vip_sm.png" alt="Downgrade to VIP Pushy Member"></a> &nbsp;
                                <?php
                                  }
                                else
                                if ($user_level == $PUSHY_LEVEL_ELITE)
                                  {
                                ?>
                                    <a href=javascript:downgrade("ELITE","PRO")><img src="http://pds1106.s3.amazonaws.com/images/downgrade-pro_sm.png" alt="Downgrade to PRO Pushy Member"></a> &nbsp;
                                <?php
                                  }
                                ?>
                              </div>
                            </td>
                          </tr>

                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <tr valign=bottom bgcolor="#FFEECC" >
                <td width="14%" align=left   border=0 height=8></td>
                <td width="74%" align=center border=0></td>
                <td width="12%" align=center border=0></td>
              </tr>

                <!---------------------- ELITE Pushy Membership ----------------------->
              <tr valign=middle height=32>

                <?php
                   $img_you_are="&nbsp;";
                   if ($user_level == $PUSHY_LEVEL_ELITE)
                     {
                       $img_you_are="<img src=\"$YOU_ARE_IMAGE\">";
                     }
                ?>
                <td width=14% align=left style="border:0px; background-color:#FFEECC;"><?php echo $img_you_are?></td>

                <td width=74% align=left valign=middle style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:10;">
                  <img src="http://pds1106.s3.amazonaws.com/images/member-elite_sm.gif" alt="ELITE Pushy Membership" style="vertical-align:-5px;">&nbsp;
                  <b class=size18>ELITE <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px">  Member</b>
                    <span style="margin-left:11px; margin-top: 7px;" class=size14>Network Exposure: <b class=red>UNLIMITED</b></span>
                </td>

                <td width=12% align=center valign="absmiddle">
                   <input id='order_button_3' style="vertical-align: -6px;" type="button" class=bigbutton value="OPEN" onClick=javascript:orderTabClicked(3)></td>
              </tr>

              <tr id='order_option_3' style='display:<?php echo $display_elite?>'>
                <td colspan=3>
                  <table align=right width=575 cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin:5px;">
                    <tr valign=bottom height=70>
                      <td style="border:0px; background-color:#FFC757;">
                        <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                          <tr height=70 bgcolor="#FFFFFF">
                            <td width=20% align=center valign=top>
                              <img src="http://pds1106.s3.amazonaws.com/images/member-elite.png" alt="ELITE Pushy Membership">
                              <b class=size20><sup>$</sup></b><b class=size26>97</b><b class=size12><i>p/mo</i></b>
                             </td>

                             <td width=80% class=largetext>
                              As an <b>ELITE</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"><sup>tm</sup> <b>Member</b>
                              you receive unlimited* levels deep of network exposure for your product or service, which will be displayed on all widgets of member
                              websites in your network, plus other networks of websites  (no limit to the volume of traffic going
                              through all networks). You receive full access to the reporting system and backoffice features. You
                              also earn 50% commission on all direct referral membership sales, and 20% on indirect referral sales,
                              unlimited* network levels deep for as long as you are a member in good standing. Your product will
                              also be viewed in the sidebar of membership backoffice, unlimited* network levels deep.
                                <span class=size11>
                                <a href="javascript:openPopup('/members/popup.php?tp=pop-compare&sid=<?php echo $sid?>&mid=<?php echo $mid?>',630,700,true)">
                                <img src="http://pds1106.s3.amazonaws.com/images/compare.gif" style="vertical-align: -3px"></a></span>

                             </td>
                           </tr>

                           <tr bgcolor="#FFFFFF">
                             <td width=100% colspan=2>
                               <div align=center style="margin: 5px 0 5px 0;">
                                <?php
                                if ($user_level == $PUSHY_LEVEL_VIP)
                                  {
                                ?>
                                    <a href=javascript:upgrade("ELITE")><img src="http://pds1106.s3.amazonaws.com/images/upgrade-elite.png" alt="Upgrade to ELITE Pushy Member"></a>
                                <?php
                                  }
                                else
                                if ($user_level == $PUSHY_LEVEL_PRO)
                                  {
                                ?>
                                    <a href=javascript:upgrade("ELITE")><img src="http://pds1106.s3.amazonaws.com/images/upgrade-elite.png" alt="Upgrade to ELITE Pushy Member"></a>
                                <?php
                                  }
                                else
                                if ($user_level == $PUSHY_LEVEL_ELITE)
                                  {
                                ?>
                                  <a href=javascript:downgrade("ELITE","PRO")><img src="http://pds1106.s3.amazonaws.com/images/downgrade-pro_sm.png" alt="Downgrade to PRO Pushy Member"></a> &nbsp;
                                  <a href=javascript:downgrade("ELITE","VIP")><img src="http://pds1106.s3.amazonaws.com/images/downgrade-vip_sm.png" alt="Downgrade to VIP Pushy Member"></a> &nbsp;
                                <?php
                                  }
                                ?>
                               </div>
                             </td>
                           </tr>

                         </table>
                       </td>
                     </tr>
                   </table>
                 </td>
              </tr>

              <tr><td></td></tr>
              <tr><td></td></tr>

            </table>

            <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=680 height=31></div>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
