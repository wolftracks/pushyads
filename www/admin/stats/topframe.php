<?php
  if (file_exists("/xampp"))
    {
      $pushy="http://pushyads.local";
      $ap="http://tjw.ap.com";
      $bg="#DDDD22";
    }
  else
    {
      $pushy="http://pushyads.com";
      $ap="http://autoprospector.com";
      $bg="#992222";
    }
?>
<html>
<head>
<script language="JavaScript">
<!--
  function linkTo(url)
    {
      parent.main.location=url;
    }
  function linkToTop(url)
    {
      top.location.href=url;
    }
// -->
</script>
<title>Control Center</title>
<base target="main">
</head>
<body BGCOLOR="<?php echo $bg?>">
<form method="POST" action="null">
<div align="center"><center>
<font face="Arial">
<table BORDER="0" CELLSPACING="0" CELLPADDING="0" WIDTH="100%">
 <tr bgcolor="<?php echo $bg?>">
   <td align="center"><input value="    Ledger    "   onClick=linkTo("/admin/ledger/")         type="button" target="main" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"></td>
   <td align="center"><input value="   Products  "    onClick=linkTo("/admin/products/")       type="button" target="main" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"></td>
   <td align="center"><input value="     Ads     "    onClick=linkTo("/admin/ads/")            type="button" target="main" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"></td>
   <td align="center"><input value="   Service   "    onClick=linkTo("/admin/service/")        type="button" target="main" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"></td>
   <td align="center"><input value="   Members   "    onClick=linkTo("/admin/whois.php")       type="button" target="main" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"></td>
   <td align="center"><input value="   Reports   "    onClick=linkTo("/admin/reports/")        type="button" target="main" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"></td>
   <td align="center"><input value=" Affiliates "     onClick=linkTo("/admin/affiliates/")     type="button" target="main" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"></td>
   <td align="center"><input value=" PRS  "           onClick=linkToTop("<?php echo $ap?>/admin/prs/")        type="button" target="main" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"></td>
 </tr>
</table>
</font>
</center></div>
</form>
</body>
</html>
