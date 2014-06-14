<?php
  $width=680;
?>
<div align=right style="margin: -41px 0 0 606px;">
  <a href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/coming-soon.png') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim2.gif"></a>
</div>

<table width="679" border=0 cellspacing=0 cellpadding=0  bgcolor=#FFFFFF>
  <tr>
    <td>

    <!------------------------ Traffic Report ---------------------->

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width:679px;">
      <a href="#" rel="toggle[traffic_pane]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png"  style="vertical-align: -6px; margin:3px 10px 0"></a>
      <a href="javascript:animatedcollapse.toggle('traffic_pane')" style="text-decoration: none;" class="largetext bold">Traffic Report</a>
    </div>

    <div id="traffic_pane" align=right>
    <div style="width:100%; height: 480px; overflow-y: scroll; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width="100%" border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td align=left class="text black">

            <?php
              include("reports_traffic_pane.php");
            ?>

          </td>
        </tr>
      </table>

    </div>
    </div>

    <!------------------------ Affiliate Offers -------------------->

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width:679px;">
      <a href="#" rel="toggle[affiliates_pane]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png" style="vertical-align: -6px; margin:3px 10px 0" /></a>
      <a href="javascript:animatedcollapse.toggle('affiliates_pane')" style="text-decoration: none;" class="largetext bold">Affiliate Offers</a>
    </div>

    <div id="affiliates_pane" align=right>
    <div style="width:100%; height: 280px; overflow-y: scroll; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width="100%" height=280 border=0 cellspacing=0 cellpadding=0>
        <tr bgcolor=#FFFBF2>
          <td align=center class="text black bold">

            <?php
              include("reports_affiliates_pane.php");
            ?>

          </td>
        </tr>
      </table>

    </div>
    </div>

    <!------------ Personal Widget Categories ------------------>

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width:679px;">
      <a href="#" rel="toggle[per_widget_categories_pane]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png" style="vertical-align: -6px; margin:3px 10px 0" /></a>
      <a href="javascript:animatedcollapse.toggle('per_widget_categories_pane')" style="text-decoration: none;" class="largetext bold">Personal Widget Categories</a>
    </div>

    <div id="per_widget_categories_pane" align=right>
    <div style="width:100%; height: 600px; overflow-y: scroll; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width="100%" height=600 border=0 cellspacing=0 cellpadding=0>
        <tr bgcolor=#FFFBF2>
          <td align=center class="text black bold">

            <?php
              include("reports_per_widget_categories_pane.php");
            ?>

          </td>
        </tr>
      </table>

    </div>
    </div>


    <!------------ Referral Widget Categories ------------------>

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width:679px;">
      <a href="#" rel="toggle[ref_widget_categories_pane]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png" style="vertical-align: -6px; margin:3px 10px 0" /></a>
      <a href="javascript:animatedcollapse.toggle('ref_widget_categories_pane')" style="text-decoration: none;" class="largetext bold">Referral Widget Categories</a>
    </div>

    <div id="ref_widget_categories_pane" align=right>
    <div style="width:100%; height: 600px; overflow-y: scroll; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width="100%" height=600 border=0 cellspacing=0 cellpadding=0>
        <tr bgcolor=#FFFBF2>
          <td align=center class="text black bold">

            <?php
              include("reports_ref_widget_categories_pane.php");
            ?>

          </td>
        </tr>
      </table>

    </div>
    </div>

    </td>
  </tr>
</table>

<?php
  $response= new stdClass();
  $response->success      = "TRUE";
  $response->DisplayMonth = $MonthName;
  $response->Week         = $week;
?>
