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
  <tr valign=top>
    <td width="100%">
       <PRE>
          <textarea cols=100 rows=15 id="PUSHY_SCRATCH_PAD"></textarea>
       </PRE>
    </td>
  </tr>
<?php
  }
?>

  <tr height="<?php echo $windowHeight?>" valign=top>
    <td width="100%" align="center">
      <br> &nbsp;
      <br> &nbsp;
      <br> &nbsp;
      <p>
        <span class="arial size16" style="color:#CC0000">
          Scroll this Page Up and Down<br>
          to see the Pushy Hover Action
        </span>
      </p>
      <br> &nbsp;
      <br> &nbsp;
      <p>
        <span class="arial size14">
          Use the  <b>Home</b>  selector above<br>
          to try a different Home location  <br>
                                            <br>
          Changing the Home Location here   <br>
               <b>Does Not</b><br>
          effect your current               <br>
          configuration setting.            <br>
        </span>
      </p>
      <br>
    </td>
  </tr>
</table>

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
  // echo $preview_url."<br><br>";
  // exit;
?>
<!-- script type="text/javascript">alert('<?php echo $preview_url?>');</script -->
<script type="text/javascript" src="<?php echo $preview_url?>"></script>

</body>
</html>
