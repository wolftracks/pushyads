<?php
// $bgCanvas="#F0F4FA";
$bgCanvas="#FFFFFF";
$windowHeight=1024;

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

// echo "<PRE>";
// printf("REQUEST_METHOD=%s\n",$_SERVER["REQUEST_METHOD"]);
// print_r($_REQUEST);
// echo "</PRE>";
// exit;

$widget_key=$_REQUEST["widget_key"];

$pst = $_REQUEST["pst"];
$org = $_REQUEST["org"];
$mtn = $_REQUEST["mtn"];
$trn = $_REQUEST["trn"];
$wth = $_REQUEST["wth"];
$hgt = $_REQUEST["hgt"];
$spd = $_REQUEST["spd"];
$wig = $_REQUEST["wig"];
$dly = $_REQUEST["dly"];
$pau = $_REQUEST["pau"];
$widget_top = $_REQUEST["widget_top"];
$widget_lft = $_REQUEST["widget_lft"];



include("scaling.inc");
if (!isset($WIDGET_SCALE[$wth]))
  {
    exit;
  }
$attributes = $WIDGET_SCALE[$wth];


$width              = $attributes["width"];
$height             = $attributes["height"];

$top_width          = $attributes["top_width"];
$top_height         = $attributes["top_height"];

$left_width         = $attributes["left_width"];
$left_height        = $attributes["left_height"];

$client_width       = $attributes["client_width"];

$right_width        = $attributes["right_width"];
$right_height       = $attributes["right_height"];

$bottom_width       = $attributes["bottom_width"];
$bottom_height      = $attributes["bottom_height"];

if ($width <= 200)
  $font_size=12;
else
if ($width <= 230)
  $font_size=13;
else
if ($width <= 260)
  $font_size=14;
else
if ($width <= 290)
  $font_size=15;
else
if ($width <= 320)
  $font_size=16;
else
  $font_size=18;

?>
<html>
<head>
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<style>
body {
  margin:  0;
  padding: 0;
}
</style>

<script type="text/javascript">
var widget_top=<?php echo $widget_top?>;
var widget_lft=<?php echo $widget_lft?>;

function movePushy(id,x,y)
  {
    var el=document.getElementById(id);
    if (el)
      {
        el.style.left=x+'px';
        el.style.top =y+'px';
      }
  }
function getAttributes(id)
  {
    var el=document.getElementById(id);
    if (el)
      {
        var isHidden=false;
        if (el.style.display=="none")
          {
            isHidden=true;
            el.style.display="";
          }
        var attr = {
                     id:     id,
                     el:     el,
                     top:    el.offsetTop,
                     left:   el.offsetLeft,
                     bottom: el.offsetTop+el.offsetHeight,
                     right:  el.offsetLeft+el.offsetWidth,
                     height: el.offsetHeight,
                     width:  el.offsetWidth
                   }
        if (isHidden)
          {
            el.style.display="none";
          }
        return attr;
      }
    return null;
  }

function displayAttributes(id)
  {
    var attr=getAttributes(id);
    if (attr)
      {
        var msg =   "";
            msg +=  "ID:     "+attr.id+"\n";
            msg +=  "top:    "+attr.top+"\n";
            msg +=  "left:   "+attr.left+"\n";
            msg +=  "bottom: "+attr.bottom+"\n";
            msg +=  "right:  "+attr.right+"\n";
            msg +=  "height: "+attr.height+"\n";
            msg +=  "width:  "+attr.width+"\n";
        alert(msg);
      }
  }

function init()
  {
    parent.onExit=onExit;

    var attr=getAttributes("PUSHY_PREVIEW_INIT");

    if (widget_top != -1 && widget_lft != -1)
      {
         movePushy("PUSHY_PREVIEW_HOME", widget_lft, widget_top);
      }
    else
      {
         movePushy("PUSHY_PREVIEW_HOME", attr.left, attr.top);
      }
//  displayAttributes("PUSHY_PREVIEW_INIT");
//  displayAttributes("PUSHY_PREVIEW_HOME");
  }


function onExit()
  {
    var attr=getAttributes("PUSHY_PREVIEW_HOME");
    parent.widget_top=attr.top;
    parent.widget_lft=attr.left;
    parent.exit('EDIT');
  }

</script>
</head>
<body style="margin:0px; padding:0px;">
<script type="text/javascript" src="/local-js/wz_dragdrop/wz_dragdrop.js"></script>

<table align=center height="<?php echo $windowHeight?>" width="100%" cellpadding=0 cellspacing=0 border=0  bgcolor="<?php echo $bgCanvas?>">
  <tr "<?php echo $windowHeight?>" valign=top>
    <td width="100%" align="center">
      <br>
      <p>
        <span class="arial size16">
          Drag Pushy to the location where you would like him on your Web Page
        </span>
      </p>
      <p>
        <span class="arial size14">
           Then Click the <b><a href=javascript:parent.run()>Start Simulation</a></b> button to see Pushy in Action
        </span>
      </p>
      <p style="width:500px;">
        <span class="arial size14" style="width:400px;">
           <b>NOTE: </b>Pushy's motions are significantly slower when running in the simulator. His motion
           will be substantially quicker when running live on your website.
        </span>
      </p>

      &nbsp;<br>

      <div id="PUSHY_PREVIEW_INIT" style="position:relative; width:<?php echo $wth?>px; height:<?php echo $hgt?>px;"></div>

      <br>
    </td>
  </tr>
</table>

<div id="PUSHY_PREVIEW_HOME" style="position:absolute; width:<?php echo $wth?>px; height:<?php echo $hgt?>px;">
  <table width=<?php echo $wth?> height=<?php echo $hgt?> cellpadding=0 cellspacing=0 border=0>
    <tr><td colspan=3><img src="<?php echo $PUSHY_IMAGE_NAME_TOP?>"  border=0 width="<?php echo $top_width?>" height="<?php echo $top_height?>"></td></tr>
    <tr>
       <td><img src="<?php echo $PUSHY_IMAGE_NAME_LEFT?>"  border=0 width="<?php echo $left_width?>"  height="<?php echo $left_height?>"></td>

       <td align=center width="<?php echo $client_width?>" bgcolor="#ffffff" class="arial size<?php echo $font_size?>">
           I am exactly the size    <br>
           you asked me to be       <br><span style="line-height:(<?php echo $font_size?>px">&nbsp;<br></span>
           Now, Click and Drag      <br>
           me to my HOME location   <br><span style="line-height:<?php echo $font_size?>px">&nbsp;<br></span>
                  Then click  <?php /* echo $font_size */ ?> <br><span style="line-height:<?php echo (int)($font_size/3)?>px">&nbsp;<br></span>
           <b><a href=javascript:parent.run()>Start Simulation</a></b>  <br>
       </td>
       <td><img src="<?php echo $PUSHY_IMAGE_NAME_RIGHT?>" border=0 width="<?php echo $right_width?>" height="<?php echo $right_height?>"></td>
    </tr>
    <tr><td colspan=3><img src="<?php echo $PUSHY_IMAGE_NAME_BOTTOM?>"  border=0 width="<?php echo $bottom_width?>" height="<?php echo $bottom_height?>"></td></tr>
  </table>
</div>
<script type="text/javascript">
  init();
  SET_DHTML('PUSHY_PREVIEW_HOME'+CURSOR_MOVE);
</script>
</body>
</html>
