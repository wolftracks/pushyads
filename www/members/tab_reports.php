<?php
include_once("tips/reports.tips");
?>
 <font size=5><b>Important Reports for you <?php echo $firstname?></b></font>

 <p style="padding-bottom:8px;" class=largetext>
   Behind each tab is a special report, showing you <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -2px">&#8482 activity. Click on the video icon
   on the top right hand corner of each report for details and instructions on that specific report.
 </p>

 <table width=710 align=left cellpadding=0 cellspacing=0 border=0>
    <tr>
      <td align=left valign=bottom>
<div style="position:relative; top:1px;left:5px;"><a href=javascript:referrals_getPage('TabClicked')><img id='img-rpt-referrals' src="http://pds1106.s3.amazonaws.com/images/reports_referrals_active.png" class=tab2 onmouseover=javascript:reports_over('referrals') onmouseout=javascript:reports_out('referrals')></a><a href=javascript:reports_tabClicked('network')><img id='img-rpt-network' src="http://pds1106.s3.amazonaws.com/images/reports_network.png" class=tab2 onmouseover=javascript:reports_over('network') onmouseout=javascript:reports_out('network')></a><a href=javascript:reports_tabClicked('traffic')><img id='img-rpt-traffic' src="http://pds1106.s3.amazonaws.com/images/reports_traffic.png" class=tab2 onmouseover=javascript:reports_over('traffic') onmouseout=javascript:reports_out('traffic')></a><a href=javascript:reports_tabClicked('credit')><img id='img-rpt-credit' src="http://pds1106.s3.amazonaws.com/images/reports_credit.png" class=tab2 onmouseover=javascript:reports_over('credit') onmouseout=javascript:reports_out('credit')></a><a href=javascript:reports_tabClicked('sales')><img id='img-rpt-sales' src="http://pds1106.s3.amazonaws.com/images/reports_sales.png" class=tab2 onmouseover=javascript:reports_over('sales') onmouseout=javascript:reports_out('sales')></a><a href=javascript:reports_tabClicked('status')><img id='img-rpt-status' src="http://pds1106.s3.amazonaws.com/images/reports_status.png" class=tab2 onmouseover=javascript:reports_over('status') onmouseout=javascript:reports_out('status')></a></div>
      </td>
    </tr>
    <tr>
      <td>
       <table width=100% align=center cellpadding=0 cellspacing=0 bgcolor="#FFEECC" class=bgborder2 style="padding:10px 10px 17px;">
           <tr valign=middle >
             <td align=center valign=top style="background-color:#FFEECC;">

               <div id="REPORT"></div>

             </td>
           </tr>
       </table>
       <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=690 height=31></div>

    </td></tr>
 </table>


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
