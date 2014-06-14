<?php
// $bgCanvas="#F0F4FA";
$bgCanvas="#FFFFFF";
$windowHeight=1024;

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

$DEBUG=FALSE;

// echo "<PRE>";
// printf("REQUEST_METHOD=%s\n",$_SERVER["REQUEST_METHOD"]);
// print_r($_REQUEST);
// echo "</PRE>";


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
?>
<html>
<head>
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<script type="text/javascript">
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
</script>
</head>
<body style="margin:0px; padding:0px;">

<table align=center height="<?php echo $windowHeight?>" width="100%" cellpadding=0 cellspacing=0 border=0  bgcolor="<?php echo $bgCanvas?>">

<?php
if ($DEBUG)
  {
?>
  <tr valign=bottom>
    <td width="100%">
       <PRE>
          <textarea cols=100 rows=15 id="PUSHY_SCRATCH_PAD"></textarea>
       </PRE>
    </td>
  </tr>
<?php
  }
?>

  <tr "<?php echo $windowHeight?>" valign=top><td>&nbsp;</td></tr>
</table>

<div id="PUSHY_HOME" style="position:absolute; top:<?php echo $widget_top?>; left:<?php echo $widget_lft?>; width:<?php echo $wth?>px; height:<?php echo $hgt?>px;">
  <table width=<?php echo $wth?> height=<?php echo $hgt?> bgcolor="#179900"  cellpadding=0 cellspacing=0 border=0>
     <tr valign=middle>
       <td align=center>
         <span class="arial size14">
           <span style="font-weight:bold; color:#FFFF00"> PUSHY </span><br>
                                                                 &nbsp;<br>
           <span style="font-weight:bold; color:#FFFF00"> HOME  </span><br>
         </span>
       </td>
     </tr>
  </table>
</div>


<?php
  $preview_url  = PUSHYWIDGETS;
  $preview_url .= "/control/$widget_key".".js?";
  $preview_url .= "mid=$mid&";
  $preview_url .= "sid=$sid&";
  $preview_url .= "pst=$pst&";
  $preview_url .= "org=$org&";
  $preview_url .= "mtn=$mtn&";
  $preview_url .= "trn=$trn&";
  $preview_url .= "wth=$wth&";
  $preview_url .= "spd=$spd&";
  $preview_url .= "wig=$wig&";
  $preview_url .= "dly=$dly&";
  $preview_url .= "pau=$pau";
// echo $preview_url;
// exit;
?>
<script type="text/javascript" src="<?php echo $preview_url?>"></script>

</body>
</html>
