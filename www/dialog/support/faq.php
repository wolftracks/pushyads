<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_jsontools.inc");

$DEBUG=FALSE;

$db = getPushyDatabaseConnection();
?>
<html>
<head>

<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.json-2.2.min.js"></script>

<script type="text/javascript" src="support.js"></script>
<LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css">
<script type="text/javascript" src="/local-js/modal/subModal.js"></script>

<style>
body {
  margin:  0;
  padding: 0;
}
</style>

<script type="text/javascript">
</script>

</head>
<body style="margin:0px; padding:0px;">

<table align=left width=740 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
  <tr>
     <td width=740 valign=top>
       <div id=CANVAS>

         <!--------------------- START CONTENT ----------------------->
          <?php
            include("page_faq.php");
          ?>
         <!--------------------- END CONTENT ------------------------->

       </div>
     </td>
  </tr>
</table>

</body>
</html>
