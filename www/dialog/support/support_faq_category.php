<?php
 include("support_faq_data.inc");

 if (!isset($category))
   {
     $category=1;
     if (isset($_REQUEST["category"]))
        $category = (int) $_REQUEST["category"];
   }
 $faq = $faq_data[$category];

 // printf("<PRE>");
 // printf("CATEGORY=%s\n\n",$category);
 // print_r($_REQUEST);
 // ***** print_r($faq_data);
 // print_r($faq);
 // printf("</PRE>");
 // exit;
?>

<table width=100% border="0" cellpadding=0 cellspacing=0 class="text">
  <?php
    foreach($faq AS $k=>$v)
      {
        if (substr($k,0,1)=="Q")
          {
            $q_number = (int) substr($k,1);
  ?>
            <tr valign="middle" height=24 valign=top>
              <td valign="top" width="4%" style="padding-bottom: 10px"><?php echo $q_number?>.&nbsp;</td>
              <td valign="top" width="96%" style="padding-bottom: 10px"><a style="text-decoration:none;" href=javascript:support_getAnswer('<?php echo $k?>')><?php echo $v?></a></td>
            </tr>
 <?php
          }
      }
 ?>
</table>
