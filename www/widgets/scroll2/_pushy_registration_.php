<?php
$mid=$_REQUEST["mid"];
$countdown = 0;
$confirmed = -1;
if (strlen($mid)>0 && is_array($memberRecord=getMemberInfo($db,$mid)) && ($memberRecord["confirmed"]>0))
  {
    $elapsed = time() - $memberRecord["confirmed"];
    if ($elapsed >= 0 && $elapsed<=3600)
      $countdown=3600-$elapsed;
  }
?>
<html>
<head>
<style>
 body {
   margin:  0;
   padding: 0;
 }
</style>
</head>
<body>
<div style="line-height:30px;">
<span style="padding-top:16px;font-weight:bold;font-family:tahoma;font-size:24px;">&nbsp;&nbsp;ONLY </span>
<script type="text/javascript">
  var mid="<?php echo $mid?>";
  function _quit_()
    {
      var url="<?php echo DOMAIN.'/times_up.php'?>";
      if (mid.length>0)
        url += '?mid='+mid;
      parent.location.href=url;
    }

  TimeoutHandler   = _quit_;
  CountDownElapsed = <?php echo $countdown?>;
  BackColor        = "";
  ForeColor        = "font-weight:bold;font-family:tahoma;font-size:20px;color:#CC0000;";
  CountActive      = true;
  CountStepper     = -1;
  LeadingZero      = true;
  DisplayFormat    = "&nbsp;<br>&nbsp; &nbsp; %%M%% Minutes &nbsp;<br>&nbsp; &nbsp; %%S%% Seconds&nbsp;";
  FinishMessage    = "<br>&nbsp; &nbsp; It is<br>&nbsp; &nbsp; finally here!";
</script>
<script type="text/javascript" src="../local-js/countdown.js"></script>
<br>
<span style="font-weight:bold;font-family:tahoma;font-size:24px;">&nbsp;&nbsp;LEFT</span>
</div>
</body>
</html>
