<?php

include("initialize.php");

$alert_messages = array(
  105    => "Hello"
);

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<!-- LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css" -->
<!-- script type="text/javascript" src="/local-js/modal/subModal.js"></script -->
<!-- script type="text/javascript" src="/local-js/common.js"></script -->
<!-- script type="text/javascript" src="/local-js/jsutils.js"></script -->
</head>
<body>
<?php
   if (isNumeric($msg=$_REQUEST["msg"]) && isset($alert_messages[$msg]))
     {
       $width  = 480;
       $height = 220;
       if (isNumeric($_REQUEST["w"])) $width  = (int) $_REQUEST["w"];
       if (isNumeric($_REQUEST["h"])) $height = (int) $_REQUEST["h"];

       $width  -= 36;
       $height -= 36;
?>
       <table align=left valign=middle width=<?php echo $width?> height=<?php echo $height?> cellpadding=0 cellspacing=0 border=0>
         <tr valign=middle>
            <td align=left class="arial size14">
               <p>
                  Now is the time for all good men to come to the aid of their country.
               </p>

               <p>
                  Four score and seven years ago our forefathers brought forth to this cooly continent
                  a new nation, conceived in Liberty and dedicated to the poposition that all
                  men are created equal.
               </p>
            </td>
         </tr>
       </table>
<?php
     }
?>
</body>
</html>
