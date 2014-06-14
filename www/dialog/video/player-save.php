<html>
<head>

<LINK type="text/css" rel="stylesheet" href="/local-css/styles.css">
<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>

<script type="text/javascript">

//======= Player ==========

function init_player()
  {
    loadVideo('http://pds1106.s3.amazonaws.com/video/int/pushy_big.flv');
  }


function loadVideo(file)
  {
    var clip =
      {
        url: file,
        autoPlay: false,
        fadeInSpeed: 2000,
        fadeOutSpeed: 2000,
        autoBuffering: true
      }
    flowplayer("vplayer").play(clip);
  }


function initialize()
  {
    var player =
      {
        // our Flash component
        src: "http://pds1106.s3.amazonaws.com/player/flowplayer-3.1.2.swf",

        // we need at least this Flash version
        version: [9,115],

        wmode: 'transparent',

        // older versions will see a custom message
        onFail: function()
          {
            var msg  = "You need to upgrade your Flash version for this site.\n";
                msg += "Your version is " + this.getVersion() + "\n";
            alert(msg);
          }
      }

   var controls =
     {
       autoHide:'always',
       backgroundGradient: 'high',
       volumeSliderGradient: 'none',
       buttonColor: '#000000',
       timeColor: '#69CDFA',
       bufferGradient: 'none',
       progressGradient: 'none',
       sliderColor: '#C9C9C9',
       progressColor: '#FFA000',
       bufferColor: '#C78000',
       backgroundColor: '#929292',
       durationColor: '#ffffff',
       sliderGradient: 'none',
       volumeSliderColor: '#FFA000',
       buttonOverColor: '#FFA000',
       timeBgColor: '#262626',
       height: 20,
       hideDelay: 2500,
       borderRadius: '15',
       width: '90%',
       bottom: 3,
       left: '50%',
       opacity: 0.7,
       // tooltips configuration
       tooltips: {
           // enable english tooltips on all buttons
           buttons: true,
           // customized texts for buttons
           play: 'Play',
           pause: 'Pause',
           fullscreen: 'Full Screen'
       },

          // background color for all tooltips
       tooltipColor: '#C9C9C9',
          // text color
       tooltipTextColor: '#000000'
     }

    var settings =
      {
         onLoad: function()
           {    // called when player has finished loading
              this.setVolume(80);    // set volume property
           },

         clip:
           {
             // url: "http://content.bitsontherun.com/videos/3ta6fhJQ.flv",
             url: "http://pds1106.s3.amazonaws.com/video/coming-soon.png",
             autoPlay: false,
             wmode:'transparent',
             fadeInSpeed: 0,
             fadeOutSpeed: 0,
             autoBuffering: true,
             metaData: false,
               // Setting which defines how video is scaled on the video screen. Available options are:
               //  * fit:   Fit to window by preserving the aspect ratio encoded in the file's metadata.
               //  * half:  Half-size (preserves aspect ratio)
               //  * orig:  Use the dimensions encoded in the file. If too big - then 'scale'
               //  * scale: (DEFAULT) Scale the video to fill all available space. Ignores dimensions in metadata
             scaling: 'scale'
           },


         plugins:
           {
             controls: null,

             dummy:null
           }
      }

    flowplayer("vplayer", player, settings);

    var f = function() { init_player(); }
    setTimeout(f, 2000);
  }

</script>
</head>
<body topmargin=0 bgcolor="#0000CC" onLoad="initialize()" style="margin-top:0; margin-left:0">


<table cellpadding=0 cellspacing=0 border=0 width=>
  <tr>
    <td>
      <div id="vplayer" style="z-index:0; width:720px; height:406px; background-color:#990000"></div>
    </td>
  </tr>
</table>

</body>
</html>
