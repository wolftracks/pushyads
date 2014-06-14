<html>
<head>
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/admin/admin.js"></script>

<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.json-2.2.min.js"></script>

<script type="text/javascript">
function launchAffiliateSignupUrl(theForm)
  {
    var url=theForm.affiliate_signup_url.value;

    var wWidth  = 700;
    var wHeight = 550;

    var topmargin  = 0;
    var leftmargin = 0;

    // alert("URL="+product_url);

    var win=window.open(url,"Signup",
       'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
  }


function openSalesURL(url)
  {
    var wWidth  = 700;
    var wHeight = 550;

    var topmargin  = 0;
    var leftmargin = 0;

    // alert("URL="+product_url);

    var win=window.open(url,"SalesURL",
       'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
  }
</script>
<title>PushyAds Administration - Ads</title>
</head>

<body>
<div align="center"><center>
<table border="0" cellpadding="4" cellspacing="0" width="90%">
  <tr valign=top>
    <td width="30%" class="arial size16 bold">Ads</td>
    <td width="40%" align=center>
       <span class="text bold darkred size18">PushyAds</span>
    </td>
    <td width="30%" class="arial size16"><b>Date:</b>&nbsp;&nbsp;<?php echo getDateToday()?></td>
  </tr>
</table>
</center>
</div>

<span class=normaltext>
  <a href=javascript:window.location.reload();> Refresh </a>&nbsp;<br>&nbsp;<br>
</span>

<?php
   $sql  = "SELECT * from ads LEFT JOIN member USING(member_id) LEFT JOIN product USING(product_id) ";

   if (isset($_REQUEST["SortBy"]) && strlen($_REQUEST["SortBy"])>0)
     $sql .= " ORDER BY ".$_REQUEST["SortBy"];
   else
     $sql .= " ORDER BY date_created DESC";
   $result = mysql_query($sql,$db);

// printf("SQL: %s<br>\n",$sql);
// printf("ERR: %s<br>\n",mysql_error());
// printf("ROWS: %s<br>\n",mysql_num_rows($result));
?>


<table border="1" cellspacing="1" width="1050" bgcolor="#FFFFFF" bordercolor="#C0C8EE">
  <tr bgcolor="#C0C8EE">
    <td align="left" class="smalltext bold">&nbsp; <a href="index.php?SortBy=date_created DESC">Created</a></td>
    <td align="left" class="smalltext bold">&nbsp; <a href="index.php?SortBy=lastname,firstname">Member Name</td>
    <td align="left" class="smalltext bold">&nbsp; <a href="index.php?SortBy=member_id">MemberId</a></td>
    <td align="left" class="smalltext bold">&nbsp; <a href="index.php?SortBy=refid">Referer</a></td>
    <td align="left" class="smalltext bold">&nbsp;                                   Level</td>
    <td align="left" class="smalltext bold">&nbsp; <a href="index.php?SortBy=reseller_listing">Role</td>
    <td align="left" class="smalltext bold">&nbsp; <a href="index.php?SortBy=product_owner">Product Owner</td>
    <td align="left" class="smalltext bold">&nbsp; <a href="index.php?SortBy=product_name">Product Name</td>
    <td align="left" class="smalltext bold">&nbsp; <a href="index.php?SortBy=product_title">Product Title</td>
    <td align="left" class="smalltext bold">Pend</td>
    <td align="left" class="smalltext bold">Placed</td>
    <td align="left" class="smalltext bold">&nbsp; (Internal Use)</td>
  </tr>

<?php
   if (($result) && ($count=mysql_num_rows($result))>0)
     {
       while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
         {
           $product_id      = $myrow["product_id"];
           $product_name    = stripslashes($myrow["product_name"]);
           $product_title   = stripslashes($myrow["product_title"]);
           $member_id       = $myrow["member_id"];
           $refid           = $myrow["refid"];
           $memberName      = stripslashes($myrow["firstname"])." ".stripslashes($myrow["lastname"]);
           $user_level      = $myrow["user_level"];
           $user_level_name = $UserLevels[$user_level];

           $isPending = isProductApprovalPending($db,$product_id);

           // printf("<tr><td colspan=8>Pending: %s %sopenSalesURL </td></tr>",$product_id, $product_name);

           if (TRUE || !$isPending)
             {
               $ad_id                       = $myrow["ad_id"];
               $product_url                 = $myrow["product_url"];
               $affiliate_signup_url        = $myrow["affiliate_signup_url"];
               $pushy_network               = $myrow["pushy_network"];
               $elite_bar                   = $myrow["elite_bar"];
               $elite_pool                  = $myrow["elite_pool"];
               $product_list                = $myrow["product_list"];
               $date_created                = $myrow["date_created"];
               $last_modified               = $myrow["last_modified"];
               $reseller_listing            = $myrow["reseller_listing"];
               $existing_products_requested = $myrow["existing_products_requested"];

               //---- PRODUCT
               $product_owner               = $myrow["product_owner"];
               $product_submit_date         = $myrow["product_submit_date"];
               $product_title               = stripslashes($myrow["product_title"]);
               $product_description         = stripslashes($myrow["product_description"]);
               $product_categories          = $myrow["product_categories"];

               $media_type                  = $myrow["media_type"];
               $media_format                = $myrow["media_format"];
               $media_width                 = $myrow["media_width"];
               $media_height                = $myrow["media_height"];
               $media_size                  = $myrow["media_size"];
               $media_original_width        = $myrow["media_original_width"];
               $media_original_height       = $myrow["media_original_height"];
               $media_server                = $myrow["media_server"];

               $image_url                   = _get_MediaURL($product_id,$media_server,$media_format);

               $isPlaced = TRUE;    // Is "Designated" - always True for Vip/Pro - 1 ad
               if ($user_level == $PUSHY_LEVEL_ELITE)
                 {
                   if ($pushy_network   == 0 &&
                       $elite_bar       == 0 &&
                       $elite_pool      == 0 &&
                       $product_list    == 0)
                    {
                      $isPlaced=FALSE;
                    }
                 }

               if ($isPending)
                 {
                   $pendClass = "bold darkred";
                   $pendValue = "YES";
                 }
               else
                 {
                   $pendClass = "green";
                   $pendValue = "No";
                 }

               if ($isPlaced)
                 {
                   $placedClass = "green";
                   $placedValue = "Yes";
                 }
               else
                 {
                   $placedClass = "bold darkred";
                   $placedValue = "NO";
                 }
?>



               <tr>
                 <td align="left" class=smalltext>&nbsp; <?php echo $date_created?></td>
                 <td align="left" class=smalltext>&nbsp; <a href=javascript:openMember('<?php echo $member_id?>')><?php echo $memberName?></a></td>
                 <td align="left" class=smalltext>&nbsp; <a href=javascript:openMember('<?php echo $member_id?>')><?php echo $member_id?></a></td>
                 <td align="left" class=smalltext>&nbsp; <?php echo $refid?></td>
                 <td align="left" class=smalltext>&nbsp; <?php echo $user_level_name?></td>
                 <td align="left" class=smalltext>&nbsp; <?php echo ($reseller_listing==0)?"Owner":"Reseller"?></td>
                 <td align="left" class=smalltext>&nbsp; <?php echo $product_owner?></td>
                 <td align="left" class=smalltext>&nbsp; <a href=javascript:openSalesURL('<?php echo $product_url?>')><?php echo $product_name?></a></td>
                 <td align="left" class=smalltext>&nbsp; <?php echo $product_title?></td>
                 <td align="left" class="smalltext <?php echo $pendClass?>">&nbsp;<?php echo $pendValue?></td>
                 <td align="left" class="smalltext <?php echo $placedClass?>">&nbsp;<?php echo $placedValue?></td>
                 <td align="left" class=smalltext>&nbsp; <?php echo $ad_id?>:<?php echo $product_id?></td>
               </tr>
<?php

       //  printf("<tr><td colspan=8><PRE>\n%s\n</PRE>\n</td></tr>",print_r($myrow,TRUE));

             }
         }
     }
?>
</table>

</body>
</html>
