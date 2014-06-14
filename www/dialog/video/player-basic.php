<html>
<head>
<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>
</head>
<body topmargin=0 bgcolor="#000000" style="margin-top:0; margin-left:0">

<a id="player"
   href="http://pds1106.s3.amazonaws.com/video/int/pushy_big.flv" style="display:block;width:720px;height:406px;"></a>

<!-- this script block will install Flowplayer inside previous A tag -->
<script type="text/javascript">
   flowplayer("player", "http://pds1106.s3.amazonaws.com/player/flowplayer-3.1.2.swf",  {
      clip: {
          autoPlay: false,
          autoBuffering: true // <- do not place a comma here
      }
   });
</script>

</body>
</html>
