<?php
// include_once("pushy_common.inc");
// include_once("pushy_commonsql.inc");
// include_once("pushy.inc");
//
// $db=getPushyDatabaseConnection();
// $mid='epr1277';

$products=array();
$product_count=0;

$sql  = "SELECT product_id,product.product_name,product.product_title,count(*) FROM ads JOIN product USING (product_id)";
$sql .= " WHERE ads.member_id != product.org_product_owner";
$sql .= " AND product.org_product_owner='$mid'";
$sql .= " GROUP BY product_id";

//printf("SQL: %s\n",$sql);
//printf("ERR: %s\n",mysql_error());

$res=mysql_query($sql,$db);
if (($res) && ($product_count=mysql_num_rows($res))>0)
  {
    while ($myrow=mysql_fetch_array($res))
      {
        $product_id    = $myrow[0];
        $product_name  = stripslashes($myrow[1]);
        $product_title = stripslashes($myrow[2]);
        $resellers     = $myrow[3];

        // printf("%-12s  %-20s %-20s  %4d\n", $product_id,
        //                                     $product_name,
        //                                     $product_title,
        //                                     $resellers);

        $products[$product_id] =
            array(
                   "product_name"  => $product_name,
                   "product_title" => $product_title,
                   "resellers"     => $resellers
                 );
      }
  }

// print_r($products);
?>

<table width=100% height=280 valign=top cellspacing=0 cellpadding=0 style="border-bottom: 1px solid #FFCC00;">
  <tr>
    <td bgcolor="#FFFFFF" valign=top>
      <table width=100% align=center valign=top cellspacing=0 cellpadding=15>
        <tr>
          <td class="text">

            This report shows you how many products you have designated as <b>Affiliate Offers</b> inside <b>MY ADS</b> tab, <b>*AND*</b> which have one or more 
            <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -2px">&#8482 member currently advertising them on the 
            <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -2px">&#8482 Network.

            <br>&nbsp;
            <div align=center>
            <table width=100% valign=top border=0 cellspacing=0 cellpadding=0 class="text gridb1">
              <tr bgcolor=#D0D6DF height=35>
                <td width="35%" align="left"><b>&nbsp;Product Name</b></td>
                <td width="40%" align="left"><b>&nbsp;Product Title</b></td>
                <td width="25%" align="center"><b>Members</b></td>
              </tr>

              <?php
                 if ($product_count == 0)
                   {
              ?>
                     <tr bgcolor=#FFFAF0 height=35>
                       <td colspan=3 width="100%" style="color:#404040">&nbsp;No products have been selected by other members as their <b>Affiliate Offer</b></td>
                     </tr>
              <?php
                   }
                 else
                   {
                     $total_resellers=0;
                     foreach ($products AS $product_id => $product_info)
                       {
                         $product_name     = $product_info["product_name"];
                         $product_title    = $product_info["product_title"];
                         $resellers        = $product_info["resellers"];
                         $total_resellers += $resellers;
              ?>
                         <tr>
                           <td align=left   bgcolor=#FFFAF0><?php echo $product_name?></td>
                           <td align=left   bgcolor=#F0FAFF><?php echo $product_title?></td>
                           <td align=center bgcolor=#F7FCF7><?php echo $resellers?></td>
                         </tr>
              <?php
                       }
              ?>
                     <tr>
                       <td align=left   bgcolor=#FFFAF0 colspan=2><b>(Total Resellers)</b></td>
                       <td align=center bgcolor=#F7FCF7><b><?php echo $total_resellers?></b></td>
                     </tr>
              <?php
                   }
              ?>
            </table>
            </div>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
