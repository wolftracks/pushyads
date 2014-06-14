
<html>
<title>My Pushy Backoffice</title>

<?php

  $SYSLOG_DEBUGGER=FALSE;

?>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css">
<style>
#LightsDown {
    opacity: .25;
    filter: alpha(opacity=25);
    /* this hack is so it works in IE */
    background-color:transparent !important;
    background-color: #303030;
    /* this hack is for opera */
    background-image/**/: url("http://pds1106.s3.amazonaws.com/images/maskBG.png") !important; // For browsers Moz, Opera, etc.
    background-image:none;
    background-repeat: repeat;
}
</style>
<script type="text/javascript" src="/local-js/modal/subModal.js"></script>

<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>
<script type="text/javascript" src="/local-js/video_class.js"></script>
<script type="text/javascript" src="/local-js/animatedcollapse.js"></script>

<script type="text/javascript" src="tab_home.js"></script>
<script type="text/javascript" src="tab_profile.js"></script>
<script type="text/javascript" src="tab_myads.js"></script>
<script type="text/javascript" src="tab_pushy.js"></script>
<script type="text/javascript" src="tab_mystuff.js"></script>
<script type="text/javascript" src="tab_reports.js"></script>
<script type="text/javascript" src="tab_support.js"></script>


<script type="text/javascript">

var _system_ = {
   mid: '<?php echo $mid?>',
   sid: '<?php echo $sid?>',
   user_level: <?php echo $memberRecord["user_level"]?>,
   user_level_name: '<?php echo $UserLevels[$memberRecord["user_level"]]?>'
}
_system_.logging_enabled    = true;
_system_.log_message_count  = 0;
_system_.max_log_messages   = 200;

function sys_log(msg)
  {
     <?php
     if ($SYSLOG_DEBUGGER)
        {
     ?>
          if (_system_.logging_enabled)
            {
              var log_el=document.getElementById("MEMBERS_SCRATCH_PAD");
              if ((log_el) && _system_.log_message_count < _system_.max_log_messages)
                {
                  if (typeof msg == 'object') msg=objectToString(msg);

                  _system_.log_message_count++;

                  var msgnum="";
                  if (_system_.log_message_count<10)
                    msgnum = "  ";
                  else
                  if (_system_.log_message_count<100)
                    msgnum = " ";
                  msgnum += _system_.log_message_count;

                  if (_system_.log_message_count==1)
                    log_el.value = "";

                  log_el.value += (""+msgnum+")  "+msg+"\n");
                }
              else
                _system_.logging_enabled=false;
            }
          else
            _system_.logging_enabled=false;
     <?php
        }
     ?>
  }


//   ---- UNSELECTED      SELECTED ---------------
//   "tab_home.png",      "tab_home_active.png"
//   "tab_profile.png",   "tab_profile_active.png"
//   "tab_myads.png",     "tab_myads_active.png"
//   "tab_pushy.png",     "tab_pushy_active.png"
//   "tab_mystuff.png",   "tab_mystuff_active.png"
//   "tab_reports.png",   "tab_reports_active.png"
//   "tab_support.png",   "tab_support_active.png"


var image_base_names = ['home', 'profile', 'myads', 'pushy', 'mystuff', 'reports', 'support'];

var mid='<?php echo $mid?>';
var sid='<?php echo $sid?>';

var imageHost="http://pds1106.s3.amazonaws.com/images/";
var image_selected={};
var image_unselected={};

if (document.images)
{
  for (var i=0; i<image_base_names.length; i++)
    {
      var basename = image_base_names[i];
      image_unselected[basename] = imageHost+'tab_'+basename+'.png';
      image_selected[basename]   = imageHost+'tab_'+basename+'_active.png';
    }
}


var current='home';
function tabClicked(tab)
 {
   var cb=null;
   var forceLoad=false;
   if (arguments.length >= 2)
     {
       forceLoad=arguments[1];
     }
   if (arguments.length >= 3)
     {
       cb=arguments[2];
     }

   if (tab==current)
     {
       if (!forceLoad)
         return;
     }
   else
     {
       var currentTabElement = document.getElementById('img-'+current);
       var newTabElement     = document.getElementById('img-'+tab);    //clicked

       var currentTabURL     = image_unselected[current];
       var newTabURL         = image_selected[tab];
     }

   var canvas = document.getElementById("CANVAS");

   var d = new Date();
   var milliseconds=d.getMilliseconds();

   tabUrl = "/members/ajax.php?tp=tabs&tab="+tab+"&mid="+mid+"&sid="+sid+"&icache="+milliseconds;
   if (arguments.length >= 3)
     {
       var callerArg=arguments[2];
       tabUrl += "&"+callerArg;
     }

   // dumpObject(data);

   $.ajax({
      type:     "GET",
      url:      tabUrl,
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
                      canvas.innerHTML="Request Failed - HTTP Status:"+ httpStatus;
                    }
                },
      success:  function(response, textStatus)
                {
                  if (typeof response == "object" && typeof response.status != "undefined")
                    {
                      var status=response.status;
                      if (status != 0)
                        {
                          alert( response.message );
                        }
                      else
                        {
                          var data = response.data;
                          canvas.innerHTML = data.html;
                        }
                    }
                  else
                    {
                      canvas.innerHTML = response;
                    }
                  if (cb != null)
                    cb(response);
                  if (tab=="reports")
                     referrals_getPage('Init');

                  var s="tab_"+tab+"_loaded(response)";
                  eval(s);
                }
   });


   if (tab!=current)
     {
       newTabElement.src=newTabURL;
       currentTabElement.src=currentTabURL;
     }

   current=tab;
   showPreview(false);
 }

function schedule_elite_bar_refresh()
 {
   var f = function() {refresh_elite_bar();};
   setTimeout (f, 120000);
 }


function refresh_elite_bar()
 {
   var eb = document.getElementById('ELITE_BAR_FRAME');
   if (eb)
     {
       var d  = new Date();
       var ms = d.getTime();
       eb.src='elite_bar.php?mid='+mid+'&sid='+sid+'&ms='+ms;
     }
   schedule_elite_bar_refresh();
 }


//-------------------------------------------------------------------------------------------------------------------
elite_bar_top_inx=0;
elite_bar_y_values=[];
function elite_bar_ad_on_top(inx,ad_count,msgsize)
  {
    elite_bar_top_inx=inx;
    var j=inx;

    for (var i=0; i<ad_count; i++)
      {
        elite_bar_y_values[j] = (msgsize * (i-1));
        j++;
        if (j>8) j=0;
      }

//  var j=inx;
//  var msg="\n";
//  for (var i=0; i<ad_count; i++)
//    {
//      msg += "INX="+j+" Y="+elite_bar_y_values[j]+"\n";
//      j++;
//      if (j>8) j=0;
//    }
//  alert(msg);
  }

elitebar_ContentInfo = "";
topColor = "#485975"
subColor = "#FFFFA8"
var tip_active = 0;
var last_tip_id="";
var ToolTipTimeOut;
var ToolTipStyle;


function elitebar_EnterContent(TTitle, TContent)
 {
    elitebar_ContentInfo = '<table style="border: 1px solid #CC0000;" width="250" cellspacing="0" cellpadding="0">'+
      '<tr><td width="100%" bgcolor="#000000">'+
      '<table border="0" width="100%" cellspacing="0" cellpadding="0">'+
      '<tr><td width="100%" bgcolor='+topColor+' background="http://pds1106.s3.amazonaws.com/images/bg-tooltip-tp.jpg">'+
      '<table border="0" width="100%" cellspacing="0" cellpadding="2" align="center">'+
      '<tr><td width="100%">'+

      '<font class="tooltiptitle">&nbsp;'+TTitle+'</font>'+

      '</td></tr>'+
      '</table>'+
      '</td></tr>'+
      '<tr><td width="100%" bgcolor='+subColor+' background="http://pds1106.s3.amazonaws.com/images/bg-tooltip.jpg">'+
      '<table border="0" width="100%" cellpadding="0" cellspacing="10" align="center">'+
      '<tr><td width="100%">'+

      '<font class="tooltipcontent">'+TContent+'</font>'+

      '</td></tr>'+
      '</table>'+
      '</td></tr>'+
      '</table>'+
      '</td></tr>'+
      '</table>'+
      '<div align="left"><img src="http://pds1106.s3.amazonaws.com/images/shadow.png" width="245" height="20"></div>';
 }



function elitebar_Content(TTitle, TContent)
 {
    elitebar_ContentInfo = ''+
      '<div style="position: absolute;">'+
      '<img style="position:relative; top:0px; left:0px; width:180px;" src="http://pds1106.s3.amazonaws.com/images/pushy-elite-hover.png" border=0>'+
      '<div style="position:relative; top:-156px; left:27px; width:140px;">'+
      '<span class="elitebar-tooltip-title"     style="width:140px; line-height:30px; vertical-align:top">'+TTitle+'</span><br>'+
      '<span class="elitebar-tooltip-paragraph" style="width:140px">'+TContent+'</span>'+
      '</div>'+
      '</div>';
 }



function show_ad_tooltip(obj,title,content,id,x,y,w,h,inx)
 {
   if (tip_active==1 && id == last_tip_id) return;
   tip_active=1;
   last_tip_id=id;

   var el = document.getElementById("ELITE_BAR_FRAME");
   if (el)
     {
        var scrollbarX=findPosX(el);
        var scrollbarY=findPosY(el);
        var scrollbarW=el.offsetWidth;
        var scrollbarH=el.offsetHeight;
        var tooltip = document.getElementById('ToolTip');
        if (tooltip)
          {
            tooltip.style.left = (scrollbarX - 210) + 'px';

            if (y<-50) y=0;

            // tooltip.style.top  = (y+20+scrollbarY)  + 'px';
            tooltip.style.top  = (y-25+scrollbarY)  + 'px';

            var ie = document.all?true:false;
            if ((!ie) && (ToolTipTimeOut))
              {
                clearTimeout(ToolTipTimeOut);
              }

            tip_active = 1;
            tooltip.style.visibility="visible";

            var d_title=decodeURIComponent(title).replace(/\+/g, ' ');
            var d_content=decodeURIComponent(content).replace(/\+/g, ' ');

            // elitebar_EnterContent(d_title, d_content);
            elitebar_Content(d_title, d_content);
            tooltip.innerHTML = elitebar_ContentInfo;
          }
        else
          {
            alert("tooltip not found");
          }
     }
 }



function hide_ad_tooltip(obj,id)
 {
   tip_active=0;
   var ie = document.all?true:false;
   var tooltip = document.getElementById('ToolTip');
   if (tooltip)
     {
       if (ie)
         {
           tooltip.style.visibility='hidden';
         }
       else
         {
           ToolTipStyle=tooltip.style;
           ToolTipTimeOut = setTimeout("ToolTipStyle.visibility='hidden';",400);
         }
     }
 }

//---------------------------------------------------------------------------------------------------------------------------


/*----- DIALOGS -----*/

function membership_plan()
 {
    /*-- mid, sid  are  set above on Page load --*/
    var frameWidth  = 800;
    var frameHeight = 740;

    var url  = '/members/dialog/link.php?';
        url += 'mid='+mid+'&';
        url += 'sid='+sid+'&';
        url += 'tp=membership_plan&';
        url += 'frameWidth='+frameWidth+'&';
        url += 'frameHeight='+frameHeight;

    showPopWin('Membership Plan', url, frameWidth, frameHeight, membership_plan_Response, true, false);
 }


function membership_plan_Response(returnVal)
 {
    if (returnVal)
      {
        // var msg  = "Function Name : "+returnVal.functionName+"\n";
        //     msg += "Signin ID     : "+returnVal.signin_id+"\n";
        //     msg += "Password      : "+returnVal.password+"\n";
        // alert(msg);
      }
    else
      {
        // alert("onResponse - No Values");
      }
 }


schedule_elite_bar_refresh();

</script>
</head>

<body class=background topmargin=0>
<script type="text/javascript" src="/local-js/wz_tooltips.js"></script>

<div id="ToolTip"></div>

<!----------- Video Monitor ------------->
<img id="monitor"       src="http://pds1106.s3.amazonaws.com/images/video-monitor.png" width=574 height=352 style="display:none; position:absolute; top:0px; left:300px; width:574px; height:352px; z-index:2">
<img id="monitor_close" src="http://pds1106.s3.amazonaws.com/images/close.png"   width=24  height=24 onMouseOver=javascript:mouse_over(this) onClick=javascript:closeVideo() style="position:absolute; display:none; top:296px; left:840px; z-index:3; width:24px; height:24px;">
<div id="vplayer"       style="position:absolute; display:none; top:13px; left:350px; width:512px; height:288px; background:#000000;z-index:2"></div>
<!----------- Video Monitor ------------->


<DIV ID="PUSHY_PREVIEW" style="position:absolute; display:none; top:0 px; left:0px; width:240px; height:296px;">
<table width="240" border="0" cellpadding="0" cellspacing="0" height="296"><tbody>
<tr><td colspan="3"><img src="http://pds1106.s3.amazonaws.com/widgets/images/pushy-top.png" width="240" border="0" height="86"></td></tr>
<tr>
<td><img src="http://pds1106.s3.amazonaws.com/widgets/images/pushy-left.png" width="23" border="0" height="170"></td>

<!-- This TD Contains the Pushy Display Area -->
<td width="194" bgcolor="#ffffff">
<div style="width: 194px; height: 170px;">

<span id="PUSHY_PREVIEW_CONTENT" style="height:170px"><table height=170 cellpadding=0 cellspacing=0 border=0><tr><td>&nbsp;</td></tr></table></span>

<!-- This is the Product Substitution Data -->
<span id="PUSHY_PREVIEW_HTML" style="display:none">
<table border=0 cellspacing=0 cellpadding=0 syle="background-color: #FFFF30;" height=170>
<tr>
<td valign=top>
 <div style="margin-right:9px; height:73px; width:52px; overflow:hidden;">
   <a href=javascript:openSalesURL('@PRODUCT_SALES_URL@')><img src='http://pds1106.s3.amazonaws.com/images/your_product_image_blue.png' height=73 width=52 border=0></a>
 </div>
</td>
<td valign=top>
 <div style="font:17px arial; height:22px; overflow:hidden;">
  <a href=javascript:openSalesURL('@PRODUCT_SALES_URL@') style="color:#CC0000;"><b>@PRODUCT_TITLE@</b></a>
 </div>
 <div style="font:15px/16px Arial; margin-left:1px; height:48px; margin-bottom:11px; overflow:hidden;">@PRODUCT_DESCRIPTION@</div>
</td>
</tr>
</table>
</span>
<!-- End Product Substitution Data -->


<!-- This is the Product Substitution Data -->
<span id="PUSHY_PREVIEW_VIP_HTML" style="display:none">
<table border=0 cellspacing=0 cellpadding=0" height=170>
<tr>
<td valign=top colspan=2>
 <div style="font:15px arial; height:35px; overflow:hidden; margin-bottom:11px;" class=viplink>
  <a href=javascript:openSalesURL('@PRODUCT_SALES_URL@')><b>@PRODUCT_TITLE@</b></a>
  <span style="font:15px/16px Arial;">@PRODUCT_DESCRIPTION@</span>
 </div>
</td>
</tr>
</table>
</span>
<!-- End Product Substitution Data -->

</div>
</td>
<!-- End Pushy Display Area -->

<td><img src="http://pds1106.s3.amazonaws.com/widgets/images/pushy-right.png" width="23" border="0" height="170"></td>
</tr>
<tr><td colspan="3"><img src="http://pds1106.s3.amazonaws.com/widgets/images/pushy-bottom.png" width="240" border="0" height="40"></td></tr>
</tbody></table>
</DIV>



<!-- iframe name=FRAME_MYADS_MYOWN_SUBMIT id="FRAME_MYADS_MYOWN_SUBMIT" width=950 height=400 marginwidth=0 marginheight=0 frameborder=0></iframe -->
<iframe name=FRAME_MYADS_MYOWN_SUBMIT id="FRAME_MYADS_MYOWN_SUBMIT" width=0 height=0 marginwidth=0 marginheight=0 frameborder=0></iframe>
<table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td valign=top>

      <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td align=center valign=middle style="padding:15px 0 0px 0;"><img src="http://pds1106.s3.amazonaws.com/images/backofc-pushy.png">&nbsp;<img src="http://pds1106.s3.amazonaws.com/images/backofc-pushy56.png" style="vertical-align: 10px;"></td>
        </tr>
        <tr valign=top>
          <td align=right style="padding:0px 30px 10px 0px;">
            <!-- a style="text-decoration:none; font-family:Tahoma; color:white; font-size:14px;" href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/coming-soon.png')>Video</a> &nbsp;&nbsp;&nbsp;&nbsp; -->
            <!-- a style="text-decoration:none; font-family:Tahoma; color:white; font-size:14px;" href=javascript:membership_plan()>Upgrade</a> &nbsp;&nbsp;&nbsp;&nbsp; -->
            <a style="text-decoration:none; font-family:Tahoma; color:white; font-size:14px;" href="link.php?tp=logout&mid=&sid=">Logout</a></td>
        </tr>

<?php
if ($SYSLOG_DEBUGGER)
  {
?>
  <tr height="80">
    <td width="100%">
       <PRE>
          <textarea cols=100 rows=10 id="MEMBERS_SCRATCH_PAD"></textarea>
       </PRE>
    </td>
  </tr>
<?php
  }
?>

        <tr>
          <td>

            <table border=0 cellpadding=0 cellspacing=0>
              <tr>
                <td>

                  <div class=botabs><a href=javascript:tabClicked('home')><img id='img-home' src="http://pds1106.s3.amazonaws.com/images/tab_home_active.png" class=tabpadding></a><a href=javascript:tabClicked('profile')><img id='img-profile' src="http://pds1106.s3.amazonaws.com/images/tab_profile.png" class=tabpadding></a><a href=javascript:tabClicked('myads')><img id='img-myads' src="http://pds1106.s3.amazonaws.com/images/tab_myads.png" class=tabpadding></a><a href=javascript:tabClicked('pushy')><img id='img-pushy' src="http://pds1106.s3.amazonaws.com/images/tab_pushy.png" class=tabpadding></a><a href=javascript:tabClicked('mystuff')><img id='img-mystuff' src="http://pds1106.s3.amazonaws.com/images/tab_mystuff.png" class=tabpadding></a><a href=javascript:tabClicked('reports')><img id='img-reports' src="http://pds1106.s3.amazonaws.com/images/tab_reports.png" class=tabpadding></a><a href=javascript:tabClicked('support')><img id='img-support' src="http://pds1106.s3.amazonaws.com/images/tab_support.png" class=tabpadding></a></div>
                  <table align=right width=946 border=0 cellpadding=0 cellspacing=0>
                    <tr>
                      <td width=38 height=34 valign=top background="http://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
                      <td width=908 height=34 valign=bottom class=boback></td>
                    </tr>
                    <tr>
                        <td width=38 valign=top class=cellleft>&nbsp;</td>
                        <td width=908 valign=top>

                         <table align=right width=908 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
                           <tr>
                              <td width=788 valign=top>
                                <div id=CANVAS style="margin: -6px 23px 25px 30px ;">

                                  <!--------------------- START CONTENT ----------------------->
                                   <?php
                                     include("tab_home.php");
                                   ?>
                                  <!--------------------- END CONTENT ------------------------->

                                </div>
                              </td>
                              <td align=left width=120 valign=top>
                                <div style="margin: -6px 25px 25px 0;">
                                  <iframe id=ELITE_BAR_FRAME src="elite_bar.php?mid=<?php echo $mid?>&sid=<?php echo $sid?>?ms=<?php getmilliseconds?>" width=120 height=1180 frameborder=0 scrolling="no"></iframe>
                                </div>
                              </td>
                           </tr>
                         </table>

                        </td>
                    </tr>
                    <tr>
                      <td width=38  height=38 background="http://pds1106.s3.amazonaws.com/images/shadow-crnr.png"></td>

                      <td width=908 height=38>
                        <table width=100% border=0 cellpadding=0 cellspacing=0>
                          <tr>
                            <td width=866 height=38 valign=top class=cellbottom></td>
                            <td width=38 height=38 valign=top align=right background="http://pds1106.s3.amazonaws.com/images/shadow-rt.png">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>


<script type="text/javascript">
 var _tab_="<?php echo $_tab_?>";
 if (_tab_.length>0)
   {
     tabClicked(_tab_);
   }
</script>

<div align=center class="arial size12 link6" style="width:100%; text-align:center;">
                <a href=javascript:openPopup('/pop-aff-agmt.php',660,700)>Affiliate Agreement</a>
  &nbsp;|&nbsp; <a href=javascript:openPopup('/pop-comp-plan.php',660,700)>Compensation Plan</a></li>
  &nbsp;|&nbsp; <a href=javascript:openPopup('/pop-copyright.php',660,700)>Copyright Notice</a></li>
  &nbsp;|&nbsp; <a href=javascript:openPopup('/pop-disclaimer.php',660,700)>Earnings Disclaimer</a></li>
  &nbsp;|&nbsp; <a href=javascript:openPopup('/pop-privacy.php',660,700)>Privacy Notice</a></li>
  &nbsp;|&nbsp; <a href=javascript:openPopup('/pop-terms.php',660,700)>Terms of Use</a></li>
  &nbsp;|&nbsp; <a href=javascript:openPopup('/pop-tos.php',660,700)>Terms of Service</a></li>
</div>

<br>&nbsp;

</body>
</html>
