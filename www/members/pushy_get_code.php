<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");

include("scaling.inc");

//  echo "<PRE> ";
//  print_r($_REQUEST);
//  echo "</PRE> ";


$mid="";
$sid="";
$widget_key="";

$audit=0;
if (isset($_REQUEST["mid"]))
  {
    $mid=$_REQUEST["mid"];
    if (isset($_REQUEST["sid"]))
      {
        $sid=$_REQUEST["sid"];
        $db=getPushyDatabaseConnection();

        list($rc, $isAdminSession) = getSession($db, $sid, $mid, FALSE);
        if ($rc==0)
          {
            $memberRecord=getMemberInfo($db,$mid);
            if (is_array($memberRecord) && strcasecmp($mid,$memberRecord["member_id"])==0)
              {
                $firstname    = $memberRecord["firstname"];
                $affiliate_id = $memberRecord["affiliate_id"];
                $SIGNED_IN=TRUE;
                if (isset($_REQUEST["key"]))
                  {
                    $widget_key=$_REQUEST["key"];
                    $widget=getWidget($db, $widget_key);
                  }
              }
          }
      }
  }

if (!$SIGNED_IN)
  {
    echo "Must Be Signed In to Use This Function";
    exit;
  }
if (!is_array($widget))
  {
    echo "Widget Not Found";
    exit;
  }


$width = $widget["width"];
if (isset($WIDGET_SCALE[$width]))
  {
    $widgetArray = $WIDGET_SCALE[$width];
    $height      = $widgetArray["height"];
  }

?>

<html>
<title>My Pushy Backoffice</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript">
</script>

</head>

<!---------------------------------- ?php $maxWidth=700;? ------------------------->

<body bgcolor="#FFEECC" style="padding:14px; 0px; 3px; 8px; margin:0px">

<table bgcolor=#FFFFFF align=left valign=top width=100% style="padding: 5px; margin:0px; border: 1px solid #FFCC00;">
  <tr>
    <td width=100% align=left valign=top>
       <table align=left valign=top width=100% cellpadding=0 cellspacing=0 style="border: 1px dotted #CC0000; margin-bottom: 5px; padding: 10px" bgcolor=#F5FFF5>
          <tr>
            <td class="tahoma size16">
               <b>The following line defines <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px">'s
               HOME</b> <span class=size14>(<i>where you place him on your web page</i>). <br><b>NOTE:</b> It is NOT REQUIRED for the HOVER Action!</span
            </td>
          </tr>
          <tr height=30>
            <td class="arial size12" style="padding: 15px 0  5px 0">
               Copy this line, and Paste it into your HTML where your want <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">
               to appear on your web page.
            </td>
          </tr>
          <tr>
            <td class="arial size12">
               <textarea rows="1" style="width:100%; font-size:11px; background-color: #FFFBF5;">&lt;div id="PUSHY_HOME" style="width:<?php echo $width;?>px; height:<?php echo $height;?>px"&gt;&lt;/div&gt;</textarea>
               <!-- textarea rows="1" cols="80">&lt;div id="PUSHY_HOME"&gt;&lt;/div&gt;</textarea -->
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td>
       <table align=left valign=top width=100% cellpadding=0 cellspacing=0 style="border: 1px dotted #CC0000;  5px; padding: 10px" bgcolor=#FFFEDE>
          <tr>
            <td class="tahoma size16">
               <b>The following is required for ALL <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"> installations.
            </td>
          </tr>
          <tr height=20>
            <td class="arial size12" style="padding: 15px 0  5px 0">
               Copy these lines and Paste them into your HTML immediately before the &nbsp;<b class=size14>&lt;/body&gt;</b>&nbsp; tag.
            </td>
          </tr>
          <tr>
            <td align="left"   width="<?php echo $maxWidth?>"  class="arial size12" colspan=2>
               <textarea rows="3" style="width:100%; font-size:11px; background-color: #FFFBF5;">&lt;script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/<?php echo $widget_key?>.js?tracker=YOUR_TRACKING_ID"&gt;&lt;/script&gt;</textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td>
       <table align=left valign=top width=100% cellpadding=0 cellspacing=10>
          <tr>
            <td class="tahoma size14">
               <b>IMPORTANT:</b> In the URL above, change  <b>YOUR_TRACKING_ID</b> to whatever name you wish to use for tracking your
               <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> impressions and clicks. These impressions and clicks will appear
               inside your <b>Traffic Report</b> under the name you select. The name you choose consists of Aphabetic, Numeric, Hyphen, or Underscore ONLY and should be
               unique for each web page you install <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> on. The maximum length is
               16 characters. <br>(Example: blog-category_14)
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>

<?php
//  echo "<PRE> ";
//  print_r($widget);
//  echo "</PRE> ";
?>

</body>
</html>
