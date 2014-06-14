<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

// echo "<PRE>";
// printf("REQUEST_METHOD=%s\n",$_SERVER["REQUEST_METHOD"]);
// print_r($_REQUEST);
// echo "</PRE>";
// exit;

// --- Passed in on Preview Dialog Creation (tab_pushy.js)
$panelHeight = 44;
$frameWidth  = $_REQUEST["frameWidth"];
$frameHeight = $_REQUEST["frameHeight"];


// $PREVIEW_KEY = "p_".getmicroseconds();
$PREVIEW_KEY = "p_1234567890";

$pst = $_REQUEST["pst"];
$org = $_REQUEST["org"];
$mtn = $_REQUEST["mtn"];
$trn = $_REQUEST["trn"];
$wth = $_REQUEST["wth"];
$spd = $_REQUEST["spd"];
$wig = $_REQUEST["wig"];
$dly = $_REQUEST["dly"];
$pau = $_REQUEST["pau"];
$widget_top = $_REQUEST["widget_top"];
$widget_lft = $_REQUEST["widget_lft"];


//--- OverRides ---
$wig=0;
$dly=1;
$pau=2;
//-----------------


//--- Audit -----
if ($pst == '0') // static
  {                  // True for all Motions
    $org=0;
  }
else
if ($pst == '1') // hover
  {
    $mtn=0;
    $pau=0;
    $spd=0;
  }


//-------------------------------------------------------------------------------- TEST ONLY
// $wth=270;
//-------------------------------------------------------------------------------- TEST ONLY

include("scaling.inc");
if (!isset($WIDGET_SCALE[$wth]))
  {
    exit;
  }
$attributes = $WIDGET_SCALE[$wth];
$hgt = $attributes["height"];
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

var elEdit;
var elRun;
var elHover;

var widget_key  = "<?php echo $PREVIEW_KEY?>";

var mid  = "<?php echo $mid?>";
var sid  = "<?php echo $sid?>";
var pst  = "<?php echo $pst?>";
var org  = "<?php echo $org?>";
var mtn  = "<?php echo $mtn?>";
var trn  = "<?php echo $trn?>";
var wth  = "<?php echo $wth?>";
var spd  = "<?php echo $spd?>";
var wig  = "<?php echo $wig?>";
var dly  = "<?php echo $dly?>";
var pau  = "<?php echo $pau?>";
var wth  = "<?php echo $wth?>";
var hgt  = "<?php echo $hgt?>";
var widget_top  = -1;
var widget_lft  = -1;

var baseloc = "/dialog/preview";

var onExit;

function run()
  {
    if (onExit!=null)
      onExit();

    onExit=null;

    var  url  = getUrl(baseloc+"/run.php");

    switchTo("RUN");

    document.getElementById("CANVAS").src = url;
  }

function edit()
  {
    if (onExit!=null)
      onExit();

    onExit=null;

    var  url  = getUrl(baseloc+"/edit.php");

    switchTo("EDIT");

    document.getElementById("CANVAS").src = url;
  }

function hover()
  {
    if (onExit!=null)
      onExit();

    onExit=null;

    var  url  = getUrl(baseloc+"/hover.php");

    switchTo("HOVER");

    document.getElementById("CANVAS").src = url;
  }


function getUrl(base)
  {
     var  url  = base;
          url += "?widget_key=" + widget_key;
          url += "&mid=" + mid;
          url += "&sid=" + sid;
          url += "&pst=" + pst;
          url += "&org=" + org;
          url += "&mtn=" + mtn;
          url += "&trn=" + trn;
          url += "&wth=" + wth;
          url += "&hgt=" + hgt;
          url += "&spd=" + spd;
          url += "&wig=" + wig;
          url += "&dly=" + dly;
          url += "&pau=" + pau;
          url += "&widget_top=" + widget_top;
          url += "&widget_lft=" + widget_lft;
     return url;
  }


function new_origin(obj)
  {
    org=obj.value;
    hover();
  }

function switchTo(id)
  {
    if (id=="RUN")
      {
        elEdit.style.display   = 'none';
        elHover.style.display  = 'none';
        elRun.style.display    = '';
      }
    else
    if (id=="EDIT")
      {
        elRun.style.display    = 'none';
        elHover.style.display  = 'none';
        elEdit.style.display   = '';
      }
    else
    if (id=="HOVER")
      {
        elRun.style.display    = 'none';
        elEdit.style.display   = 'none';
        elHover.style.display  = '';
      }
  }



function init()
  {
    elEdit  = document.getElementById("EDIT");
    elRun   = document.getElementById("RUN");
    elHover = document.getElementById("HOVER");
    if (pst == '1')
      hover();
    else
      edit();
  }


function exit(canvasId)
  {
    // alert("Exit: "+canvasId);
    // alert("widget_top:  "+widget_top+"  widget_lft: "+widget_lft);
  }


function quit()
  {
    window.top.hidePopWin(false);
  }
</script>

</head>
<body style="margin:0px; padding:0px;">
<form action="NULL">
<!-------- EDIT -------->
<table id="EDIT" TARGET="run" align=center cellspacing=0 cellpadding=0 border=0 width="<?php echo $frameWidth-4?>">
   <tr height="<?php echo $panelHeight?>">
     <td width="50%" align=center bgcolor=#F2FFF0 style="border:1px solid #17B000;">
        <input type=button style="margin-top:6px;margin-bottom:6px;" value="Start Simulation"  onClick=javascript:run()>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type=button style="margin-top:6px;margin-bottom:6px;" value="  Exit Preview  "  onclick=javascript:quit()>
     </td>
 </tr>
</table>

<!-------- RUN  -------->
<table id="RUN" style="display:none" align=center cellspacing=0 cellpadding=0 border=0 width="<?php echo $frameWidth-4?>">
   <tr height="<?php echo $panelHeight?>">
     <td width="70%" align=center bgcolor=#F2FFF0 style="border:1px solid #17B000;">
        <input type=button style="margin-top:6px;margin-bottom:6px;" value="Rerun Simulation"  onclick=javascript:run()>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type=button style="margin-top:6px;margin-bottom:6px;" value="Change Pushy Home" onclick=javascript:edit()>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type=button style="margin-top:6px;margin-bottom:6px;" value="  Exit  Preview  " onclick=javascript:quit()>
     </td>
 </tr>
</table>


<!-------- HOVER  -------->
<table id="HOVER" style="display:none" align=center cellspacing=0 cellpadding=0 border=0 width="<?php echo $frameWidth-4?>">
   <tr height="<?php echo $panelHeight?>" valign=middle>
    <td width="50%" align=center bgcolor=#F2FFF0 style="border:1px solid #17B000;">
       <table align=center cellspacing=0 cellpadding=0 border=0 width="100%">
         <tr>
           <td width="20%" align=right class="arial bold size14" style="margin-top:6px;margin-bottom:6px;">Home:&nbsp;&nbsp;</td>
           <td width="40%" align=left  style="margin-top:6px;margin-bottom:6px;">
             <SELECT name="widget_origin" style="margin-top:6px;margin-bottom:6px; width:120px;" onchange=javascript:new_origin(this)>
               <?php
                  for ($i=0; $i<count($WIDGET_ORIGINS); $i++)
                    {
                      $sel="";
                      if ($i==$org)
                        $sel="selected";
                      echo"  <option value=\"$i\" $sel>".$WIDGET_ORIGINS[$i]."</option>\n";
                    }
               ?>
             </SELECT>
           </td>
           <td width="40%" align=left  style="margin-top:6px;margin-bottom:6px;">
              <input type=button style="margin-top:6px;margin-bottom:6px;" value="  Exit  Preview  " onclick=javascript:quit()>
           </td>
         </tr>
       </table>
    </td>
  </tr>
</table>
</form>

<iframe id="CANVAS" name="CANVAS" width="<?php echo $frameWidth-4?>" height="<?php echo $frameHeight-$panelHeight?>" frameborder=0></iframe>

<script type="text/javascript">
   init();
</script>

</body>
</html>
