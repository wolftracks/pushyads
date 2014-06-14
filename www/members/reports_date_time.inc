<?php
$dateArray=getDateTimeAsArray();
$dateToday = dateAsText($dateArray);
$timeNow   = timeAsText($dateArray);
?>
<table width="680" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="35">
      <table width="100%" align=center border="0" cellspacing="0" cellpadding="0" class="text red">
        <tr>
          <td width="55%"><em class="required">&nbsp;<?php echo $dateToday?> </em> <span class="text red"><i>(<?php echo $timeNow?> MST)</i></span></td>
          <td width="20%" align=right valign=bottom >&nbsp;</td>
          <td width="25%" align=right valign=bottom style="padding-right: 14px;">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
