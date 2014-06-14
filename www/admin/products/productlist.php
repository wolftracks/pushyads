<html>
<head>
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/admin/admin.js"></script>

<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.json-2.2.min.js"></script>

<script type="text/javascript">
var imgdir="images";

function product_toggle(inx)
  {

//  var theForm=document["PRODUCT_FORM_"+inx];
//  alert("FORM: "+"PRODUCT_FORM_"+inx);
//  ShowFormVariables(document["PRODUCT_FORM_"+inx]);
//  return;

    var hidden=isElementHidden("P_"+inx);
    hideall();
    showPreview(false);
    if (hidden)
      {
        var el=document.getElementById("P_"+inx);
        if (el)
          {
            el.style.display="";          // SHOW
            var el=document.getElementById("I_"+inx);
            if (el)
              el.src=imgdir+'/arrow-down.gif';

            var fid="PRODUCT_FORM_"+inx;
            var fel=document.getElementById(fid);
            if (fel)
              {
                myads_preview_refresh(fel);
              }
          }
      }
  }



function hideall()
  {
    var i=1;
    while(true)
      {
        var el=document.getElementById("P_"+i);
        if (el)
          {
            if (!(el.style.display=="none"))  // Not Hidden
              {
                el.style.display="none";          // HIDE
                var el=document.getElementById("I_"+i);
                if (el)
                  el.src=imgdir+'/arrow-right.gif';
              }
            i++;
          }
        else
          break;
      }
  }


//--------------------------------------------------------------------

function UpdateProduct(theForm)
  {
    var member_id          = striplt(theForm.member_id.value);
    var product_id         = striplt(theForm.product_id.value);
    var ad_id              = striplt(theForm.ad_id.value);
    var product_name       = striplt(theForm.product_name.value);
    var product_title      = striplt(theForm.product_title.value);
    var product_description= striplt(theForm.product_description.value);

    /****************** Removing as Modifiable by Admin
    var product_categories="";
    var numCategoriesSelected=0;
    var len = theForm.product_categories.length;
    for (var i=0; i<len; i++)
      {
        if (theForm.product_categories[i].selected)
          {
            product_categories += theForm.product_categories[i].value+"~";
            numCategoriesSelected++;
          }
      }

    if (numCategoriesSelected < 1 || numCategoriesSelected > 7)
      {
        alert("Please Select  1 - 7  Product Categories for this product ");
        theForm.product_categories.focus();
        return false;
      }
    product_categories="~"+product_categories;
    ******************/

    var affiliate_signup_url="";
    if (theForm.affiliate_signup_url_is_modifiable.value == "TRUE")
      {
        affiliate_signup_url = striplt(theForm.affiliate_signup_url.value);
      }

    var data = {
                 product_id:          product_id,
                 ad_id:                ad_id,
                 member_id:           member_id,
                 product_name:        product_name,
                 product_title:       product_title,
             /*  product_categories:  product_categories, */
                 product_description:  product_description,
                 affiliate_signup_url: affiliate_signup_url
               }

    $.ajax({
       type:     "POST",
       url:      "product_update.php",
       data:     data,
       dataType: "json",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   alert("Request Failed - HTTP Status:"+ httpStatus);
                 },
       success:  function(response, textStatus)
                 {
                   var status=response.status;
                   if (status != 0)
                     {
                       alert("Update Failed");
                     }
                   else
                     {
                       alert("Product Updated\n");
                     }
                 }
    });
  }



//--------------------------------------------------------------------

function ApproveProduct(theForm)
  {
    var member_id          = striplt(theForm.member_id.value);
    var product_id         = striplt(theForm.product_id.value);
    var product_name       = striplt(theForm.product_name.value);
    var product_title      = striplt(theForm.product_title.value);
    var product_description= striplt(theForm.product_description.value);
    var ad_id              = striplt(theForm.ad_id.value);
    var disposition        = theForm.disposition.value;  // 0=New Product
    var existing_products_requested = theForm.existing_products_requested.value;


    /****************** Removing as Modifiable by Admin
    var product_categories="";
    var numCategoriesSelected=0;
    var len = theForm.product_categories.length;
    for (var i=0; i<len; i++)
      {
        if (theForm.product_categories[i].selected)
          {
            product_categories += theForm.product_categories[i].value+"~";
            numCategoriesSelected++;
          }
      }

    if (numCategoriesSelected < 1 || numCategoriesSelected > 7)
      {
        alert("Please Select  1 - 7  Product Categories for this product ");
        theForm.product_categories.focus();
        return false;
      }
    product_categories="~"+product_categories;
    ********************/

    var affiliate_signup_url="";
    if (theForm.affiliate_signup_url_is_modifiable.value == "TRUE")
      {
        affiliate_signup_url = striplt(theForm.affiliate_signup_url.value);
      }

    var data = {
                 product_id:           product_id,
                 ad_id:                ad_id,
                 disposition:          disposition,
                 member_id:            member_id,
                 product_name:         product_name,
                 product_title:        product_title,
                 product_description:  product_description,
                 existing_products_requested: existing_products_requested,
                 affiliate_signup_url: affiliate_signup_url
               }

    $.ajax({
       type:     "POST",
       url:      "product_approve.php",
       data:     data,
       dataType: "json",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   alert("Request Failed - HTTP Status:"+ httpStatus);
                 },
       success:  function(response, textStatus)
                 {
                   var status=response.status;
                   if (status != 0)
                     {
                       alert("Approval Failed");
                     }
                   else
                     {
                       alert("Product Approved\n");
                       window.location.reload();
                     }
                 }
    });
  }


//--------------------------------------------------------------------

function DeclineProduct(theForm)
  {
    var member_id   = striplt(theForm.member_id.value);
    var product_id  = striplt(theForm.product_id.value);
    var ad_id       = striplt(theForm.ad_id.value);
    var disposition = theForm.disposition.value;  // 0=New Product
    var existing_products_requested = theForm.existing_products_requested.value;

    var msg =  "Please Provide a Reason For the Decline\n \n";
        msg += "Common Reasons Include:\n";
        msg += "  * Ad did not fit within our criteria for PUSHY!'s Affiliate Offers  \n";
        msg += "  * Violation of our terms, usually relating to improper content  \n";
        msg += "  * The title or description was too botched up to be corrected  \n";
        msg += "  * We were not able to make sense of the image \n \n";

    while (true)
      {
        var reason=prompt(msg,"");
        if (reason==null) return;
        var temp=striplt(reason);
        if (temp.length > 5) break;
      }

    var data = {
                 product_id:                  product_id,
                 ad_id:                       ad_id,
                 disposition:                 disposition,
                 existing_products_requested: existing_products_requested,
                 reason:                      reason,
                 member_id:                   member_id
               }

    $.ajax({
       type:     "POST",
       url:      "product_decline.php",
       data:     data,
       dataType: "json",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   alert("Request Failed - HTTP Status:"+ httpStatus);
                 },
       success:  function(response, textStatus)
                 {
                   var status=response.status;
                   if (status != 0)
                     {
                       alert("Decline Failed");
                     }
                   else
                     {
                       alert("Product Declined\n");
                       window.location.reload();
                     }
                 }
    });
  }


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




var _preview_form_=null;
var _pushy_preview_=null;
function showPreview(bool)
  {
    if (_pushy_preview_== null)
      {
        _pushy_preview_=document.getElementById("PUSHY_PREVIEW");
        JS_Float_Div(_pushy_preview_,"fromtop",100,"fromright",240);
      }
     if (bool)
       _pushy_preview_.style.display='';
     else
       _pushy_preview_.style.display='none';
  }


function isPreviewVisible()
  {
    if (_pushy_preview_== null)
       return(false);
    if (_pushy_preview_.style.display=='none')
       return(false);
    return true;
  }


function JS_Float_Div(mydiv,verticalOrigin,verticalOffset,horizontalOrigin,horizontalOffset)
  {
    var height=0;
    var ns = (navigator.appName.indexOf("Netscape") != -1);
    var d = document;
    var startX = 3;
    var startY = 5;
    var hdelta = 4;
    var vdelta = 4;
    function ml(mydiv)
    {
        if (verticalOrigin=="frombottom")
          {
            if (document.all)
              hdelta=4;
            else
              {
                if ( document.body.scrollWidth > document.body.clientWidth )
                  hdelta=20;
                else
                  hdelta=4;
              }
          }
        if (horizontalOrigin=="fromright")
          {
            if (document.all)
              vdelta=4;
            else
              {
                if ( document.body.scrollHeight > document.body.clientHeight )
                  vdelta=20;
                else
                  vdelta=4;
              }
          }
        var el=mydiv;
        width=el.offsetWidth;
        height=el.offsetHeight;
        if(d.layers)el.style=el;
        el.sP=function(x,y){this.style.left=x;this.style.top=y;};
        if (horizontalOrigin=="fromleft")
           el.x = posLeft() + startX;
        else
           el.x = posLeft() + startX + (pageWidth() - width - vdelta - horizontalOffset);
        if (verticalOrigin=="fromtop")
          el.y = startY;
        else{
          el.y = (ns ? pageYOffset + innerHeight : document.body.scrollTop + document.body.clientHeight) - hdelta;
          el.y -= height;
        }
        return el;
    }
    window.stayTopLeft=function()
    {
        if (verticalOrigin=="frombottom")
          {
            if (document.all)
              hdelta=4;
            else
              {
                if ( document.body.scrollWidth > document.body.clientWidth )
                  hdelta=20;
                else
                  hdelta=4;
              }
          }
        if (horizontalOrigin=="fromright")
          {
           if (document.all)
              vdelta=4;
            else
              {
                if ( document.body.scrollHeight > document.body.clientHeight )
                  vdelta=20;
                else
                  vdelta=4;
              }
          }
        if (horizontalOrigin=="fromright")
           ftlObj.x = posLeft() + startX + (pageWidth() - width - vdelta - horizontalOffset);
        else
           ftlObj.x = posLeft() + startX;
        if (verticalOrigin=="fromtop"){
          var pY = ns ? pageYOffset : document.body.scrollTop;
          //ftlObj.y += (pY + startY + verticalOffset - ftlObj.y);       // do it quickly
          ftlObj.y += (pY + startY + verticalOffset - ftlObj.y)/2;  // do it slowly - can be a problem
        }
        else{
          var pY = (ns ? pageYOffset + innerHeight : document.body.scrollTop + document.body.clientHeight) - hdelta;
          //ftlObj.y += (pY - (startY+height+verticalOffset) - ftlObj.y);  // do it quickly
          ftlObj.y += (pY - (startY+height+verticalOffset) - ftlObj.y)/2; // do it slowly - can be a problem
        }
        ftlObj.sP(ftlObj.x, ftlObj.y);
        setTimeout("stayTopLeft()", 10);
    }
    ftlObj = ml(mydiv);
    stayTopLeft();
  }



function myads_preview_refresh(theForm)
 {
   var product_name        = theForm.product_name.value;
   var product_title       = theForm.product_title.value;
   var product_description = theForm.product_description.value;
   var product_url         = theForm.product_url.value;

   // var msg  = "ProductName ="+product_name+"\n";
   //     msg += "ProductTitle="+product_title+"\n";
   //     msg += "Description ="+product_description+"\n";
   //     msg += "ProductURL  ="+product_url+"\n";
   //
   // alert(msg);

   if (theForm.pushy_user_level.value=='VIP')
     {
       var elHTML=document.getElementById("PUSHY_PREVIEW_VIP_HTML");
     }
   else
     {
       var elHTML=document.getElementById("PUSHY_PREVIEW_HTML");
     }
   var elContent=document.getElementById("PUSHY_PREVIEW_CONTENT");

   if ((elHTML) && (elContent))
     {
       var html = elHTML.innerHTML;

       html = html.replace(/@PRODUCT_TITLE@/g,       uc_words(product_title));
       html = html.replace(/@PRODUCT_DESCRIPTION@/g, product_description);
       html = html.replace(/@PRODUCT_SALES_URL@/g,   product_url);

       elContent.innerHTML=html;

       showPreview(true);

       return(product_url);
     }
 }


function uc_words(s)
 {
   if (s.length==0) return s;
   var s2='';
   var word;
   var words = s.split(' ');

   var j=0;
   for (var i=0; i<words.length; i++)
     {
       if (words[i].length > 0)
         {
           j++;
           word=ucfirst(words[i],true);
           if (j==1)
             s2=word;
           else
             s2+=' '+word;
         }
     }
   return s2;
 }

</script>
<title>PushyAds Administration - Product Review</title>
</head>

<body>



<DIV ID="PUSHY_PREVIEW" style="position:absolute; display:none; top:0 px; left:0px; width:240px; height:296px;">
<table width="240" border="0" cellpadding="0" cellspacing="0" height="296"><tbody>
<tr><td colspan="3"><img src="http://pds1106.s3.amazonaws.com/widgets/images/pushy-top.png" width="240" border="0" height="86"></td></tr>
<tr>
<td><img src="http://pds1106.s3.amazonaws.com/widgets/images/pushy-left.png" width="23" border="0" height="170"></td>

<!-- This TD Contains the Pushy Display Area -->
<td width="194" bgcolor="#ffffff">
<div style="width: 194px; height: 170px;">

<span id="PUSHY_PREVIEW_CONTENT" style="height:170px"><table height=170 cellpadding=0 cellspacing=0 border=0><tr><td>&nbsp;</td></tr></table></span>

<!-- This is the Product Substitution Data -->
<span id="PUSHY_PREVIEW_HTML" style="display:none">
<table border=0 cellspacing=0 cellpadding=0 syle="background-color: #FFFF30;" height=170>
<tr>
<td valign=top>
 <div style="margin-right:9px; height:73px; width:52px; overflow:hidden;">
   <a href=javascript:openSalesURL('@PRODUCT_SALES_URL@')><img src='http://pds1106.s3.amazonaws.com/images/your_product_image_blue.png' height=73 width=52 border=0></a>
 </div>
</td>
<td valign=top>
 <div style="font:17px arial; height:22px; overflow:hidden;">
  <a href=javascript:openSalesURL('@PRODUCT_SALES_URL@') style="color:#CC0000;"><b>@PRODUCT_TITLE@</b></a>
 </div>
 <div style="font:15px/16px Arial; margin-left:1px; height:48px; margin-bottom:11px; overflow:hidden;">@PRODUCT_DESCRIPTION@</div>
</td>
</tr>
</table>
</span>
<!-- End Product Substitution Data -->


<!-- This is the Product Substitution Data -->
<span id="PUSHY_PREVIEW_VIP_HTML" style="display:none">
<table border=0 cellspacing=0 cellpadding=0" height=170>
<tr>
<td valign=top colspan=2>
 <div style="font:15px arial; height:35px; overflow:hidden; margin-bottom:11px;" class=viplink>
  <a href=javascript:openSalesURL('@PRODUCT_SALES_URL@')><b>@PRODUCT_TITLE@</b></a>
  <span style="font:15px/16px Arial;">@PRODUCT_DESCRIPTION@</span>
 </div>
</td>
</tr>
</table>
</span>
<!-- End Product Substitution Data -->

</div>
</td>
<!-- End Pushy Display Area -->

<td><img src="http://pds1106.s3.amazonaws.com/widgets/images/pushy-right.png" width="23" border="0" height="170"></td>
</tr>
<tr><td colspan="3"><img src="http://pds1106.s3.amazonaws.com/widgets/images/pushy-bottom.png" width="240" border="0" height="40"></td></tr>
</tbody></table>
</DIV>



<div align="center"><center>

<table border="0" cellpadding="4" cellspacing="0" width="90%">
  <tr valign=top>
    <td width="30%" class="arial size16 bold">Product Submissions - PENDING</td>
    <td width="40%" align=center>
       <span class="text bold darkred size18">PushyAds</span><br>
       <span class="text bold darkblue size16">Product Review</span></td>
    <td width="30%" class="arial size16"><b>Date:</b>&nbsp;&nbsp;<?php echo getDateToday()?></td>
  </tr>
</table>
</center></div>

<span class=normaltext>
  <a href=javascript:window.location.reload();> Refresh </a>&nbsp;<br>&nbsp;<br>
</span>


<?php
   $sql  = "SELECT * FROM product_pending";
   $sql .= " ORDER BY ts_submitted";
   $result = exec_query($sql,$db);
?>


<table border="1" cellspacing="1" width="1050" bgcolor="#FFFFFF" bordercolor="#C0C8EE">
  <tr bgcolor="#C0C8EE">
    <td width="4%"  align="left">&nbsp;</td>
    <td width="12%" align="left" class="smalltext bold">&nbsp; Submitted</td>
    <td width="12%" align="left" class="smalltext bold">&nbsp; Type</td>
    <td width="22%" align="left" class="smalltext bold">&nbsp; Member Name</td>
    <td width="6%"  align="left" class="smalltext bold">&nbsp; Level</td>
    <td width="16%" align="left" class="smalltext bold">&nbsp; Product Name</td>
    <td width="16%" align="left" class="smalltext bold">&nbsp; Product Title</td>
    <td width="12%" align="left" class="smalltext bold">&nbsp; (Internal Use)</td>
  </tr>


<?php
   if (($result) && ($count=mysql_num_rows($result))>0)
     {
        $inx=0;
        while ($myrow  = mysql_fetch_array($result))
          {
            $dtmSubmitted        = formatDateTime($myrow["ts_submitted"]);

            $disposition         = $myrow["disposition"];
            $existing_products_requested  = $myrow["existing_products_requested"];

            $disposition_display = "";

            if ($disposition <= 5)
              {
                if ($disposition == 0)
                   $disposition_display = "NEW";
                else
                if ($disposition == 1)
                   $disposition_display = "UPDATE";

                if ($existing_products_requested)
                   $disposition_display .= " : XPL REQ";
              }
            else
              {
                if ($disposition == 6)
                  {
                    $existing_products_requested=1;
                    $disposition_display = "XPL REQ";
                  }
              }

            $product_id          = $myrow["replaces_product_id"];
            $member_id           = $myrow["product_owner"];
            $product_name        = stripslashes($myrow["product_name"]);
            $product_title       = stripslashes($myrow["product_title"]);
            $product_description = stripslashes($myrow["product_description"]);
            $product_categories  = stripslashes($myrow["product_categories"]);
            $product_url         = stripslashes($myrow["product_url"]);

            $memberRecord        = getMemberInfo($db,$member_id);
            $memberName          = stripslashes($memberRecord["firstname"])." ".stripslashes($memberRecord["lastname"]);
            $user_level          = $memberRecord["user_level"];
            $user_level_name     = $UserLevels[$user_level];

            $sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
            $sql .= " WHERE ads.member_id='$member_id' ";
            $sql .= " AND product.product_id='$product_id' ";
            $res = mysql_query($sql,$db);
            if (($res) && ($myrow = mysql_fetch_array($res, MYSQL_ASSOC)))
              {
                $inx++;
                $ad_id                 = $myrow["ad_id"];
                $product_url           = $myrow["product_url"];
                $media_type            = $myrow["media_type"];
                $media_format          = $myrow["media_format"];
                $media_width           = $myrow["media_width"];
                $media_height          = $myrow["media_height"];
                $media_size            = $myrow["media_size"];
                $media_original_width  = $myrow["media_original_width"];
                $media_original_height = $myrow["media_original_height"];
                $media_server          = $myrow["media_server"];

                $affiliate_signup_url  = $myrow["affiliate_signup_url"];

                $image_url             = _get_MediaURL($product_id,$media_server,$media_format);
?>


                <tr>
                  <td width="4%"  align="left">&nbsp; <a href=javascript:product_toggle(<?php echo $inx?>)><img ID="I_<?php echo $inx?>" src="<?php echo $imgdir?>/arrow-right.gif" border=0></a></td>
                  <td width="12%" align="left" class=smalltext>&nbsp; <?php echo $dtmSubmitted?></td>
                  <td width="12%" align="center" class=smalltext>&nbsp; <?php echo $disposition_display?></td>
                  <td width="22%" align="left" class=smalltext>&nbsp; <a href=javascript:openMember('<?php echo $member_id?>')><?php echo $memberName?></a></td>
                  <td width="6%"  align="left" class=smalltext>&nbsp; <?php echo $user_level_name?></td>
                  <td width="16%" align="left" class=smalltext>&nbsp; <?php echo $product_name?></td>
                  <td width="16%" align="left" class=smalltext>&nbsp; <?php echo $product_title?></td>
                  <td width="12%" align="left" class=smalltext>&nbsp; <?php echo $ad_id?>:<?php echo $product_id?></td>
                </tr>
                <tr valign=top id="P_<?php echo $inx?>" style="display:none" bgcolor="#E8E8E8">
                  <td colspan=8 align=left>

                    <form id="PRODUCT_FORM_<?php echo $inx?>"  name="PRODUCT_FORM_<?php echo $inx?>" method=POST action="NULL">
                      <input type=hidden name="member_id"                    value="<?php echo $member_id?>">
                      <input type=hidden name="ad_id"                        value="<?php echo $ad_id?>">
                      <input type=hidden name="product_id"                   value="<?php echo $product_id?>">
                      <input type=hidden name="user_level"                   value="<?php echo $user_level?>">
                      <input type=hidden name="pushy_user_level"             value="<?php echo strtoupper($user_level_name)?>">
                      <input type=hidden name="product_url"                  value="<?php echo $product_url?>">
                      <input type=hidden name="image_url"                    value="<?php echo $image_url?>">
                      <input type=hidden name="disposition"                  value="<?php echo $disposition?>">
                      <?php
                         if (strlen($affiliate_signup_url) > 7 && $affiliate_signup_url != "http://")
                           {
                      ?>
                              <input type=hidden name="affiliate_signup_url_is_modifiable"  value="TRUE">
                      <?php
                           }
                         else
                           {
                      ?>
                              <input type=hidden name="affiliate_signup_url_is_modifiable"  value="FALSE">
                      <?php
                           }
                      ?>
                      <input type=hidden name="existing_products_requested"  value="<?php echo $existing_products_requested?>">

                      <table align=left cellpadding=0 cellspacing=0>
                        <tr valign=top>
                          <td width="150" align=left>
                            <br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=button style="width:75px;height:24px;" value=" Approve " onClick=javascript:ApproveProduct(this.form)>&nbsp; &nbsp; &nbsp;
                            <br>&nbsp;
                            <br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=button style="width:75px;height:24px;" value="  Decline  " onClick=javascript:DeclineProduct(this.form)>&nbsp; &nbsp; &nbsp;
                          </td>

                          <td align=left>
                             <table width="600" align=left cellpadding=12 cellspacing=0>
                                <tr>
                                  <td align="right" valign="middle" width=180><b>Product Name:</b></td>
                                  <td>
                                    <input class=form_input style="background-color: #FFFDF7; text-transform:capitalize" type="text" name="product_name" size="25" maxlength="20" value="<?php echo $product_name?>">
                                    20 characters max
                                  </td>
                                </tr>
                                <tr>
                                  <td align="right" valign="middle"><b>Product Title:</b></td>
                                  <td>
                                    <input class=form_input type="text" name="product_title" size="25" maxlength="20" style="text-transform:capitalize; background-color: #FFFDF7" value="<?php echo $product_title?>" onblur=javascript:myads_preview_refresh(this.form) onfocus=javascript:myads_preview_refresh(this.form) onkeyup=javascript:myads_preview_refresh(this.form)>
                                    20 characters max
                                  </td>
                                </tr>
                                <tr height=60>
                                  <td align="right" valign="top"><b>Product Description:</b></td>
                                  <td>
                                     &nbsp;<textarea class="textform darkgreen" name="product_description" rows=2
                                            style="background-color: #FFFDF7; width: 258px; overflow: hidden;" onkeyup=javascript:myads_preview_refresh(this.form) onkeyup=javascript:myads_preview_refresh(this.form) onblur=javascript:myads_preview_refresh(this.form)><?php echo $product_description?></textarea>
                                  </td>
                                </tr>




                           <!--- Removing as Modifiable By Admin --- Display Only
                                <tr>
                                  <td align="right" valign="top"><b>Product Categories:</b></td>
                                  <td>
                                    <?php
                                       $categories=array();
                                       $tarray=explode("~",$product_categories);
                                       for ($i=0; $i<count($tarray); $i++)
                                         {
                                           if (strlen($tarray[$i]) > 0)
                                             {
                                               $k=$tarray[$i];
                                               if (isset($ProductCategories[$k]))
                                                 {
                                                   $categories[$k]=TRUE;
                                                 }
                                             }
                                         }

                                    ?>
                                    &nbsp;<SELECT NAME="product_categories"  class="textform darkgreen" size=10 multiple style="background-color: #FFFDF7; width:260px;">
                                      <?php
                                         asort($ProductCategories);
                                         foreach ($ProductCategories AS $cat => $ctitle)
                                           {
                                             $sel="";
                                             if (isset($categories[$cat]))
                                                 $sel="selected";
                                             echo "  <OPTION VALUE=\"$cat\" $sel>$ctitle</OPTION>\n";
                                           }
                                      ?>
                                    </SELECT>&nbsp;&nbsp;
                                  </td>
                                </tr>
                           ---------------------------------------------------->



                           <!--- Display Only --->
                                <tr>
                                  <td align="right" valign="top"><b>Product Categories:</b></td>
                                  <td>
                                    <?php
                                       $categories=array();
                                       $tarray=explode("~",$product_categories);
                                       for ($i=0; $i<count($tarray); $i++)
                                         {
                                           if (strlen($tarray[$i]) > 0)
                                             {
                                               $k=$tarray[$i];
                                               if (isset($ProductCategories[$k]))
                                                 {
                                                   $categories[$k]=TRUE;
                                                 }
                                             }
                                         }

                                    ?>
                                      <?php
                                         asort($ProductCategories);
                                         foreach ($ProductCategories AS $cat => $ctitle)
                                           {
                                             if (isset($categories[$cat]))
                                               {
                                                 echo "<span class=\"arial size14 darkblue bold\">$ctitle</span><br>\n";
                                               }
                                           }
                                      ?>
                                  </td>
                                </tr>
                           <!---------------------->


                                <?php
                                if (strlen($affiliate_signup_url) > 7 && $affiliate_signup_url != "http://")
                                  {
                                ?>
                                    <tr height=60 valign=middle>
                                      <td align="right" valign="middle"><b>Affiliate Signup URL:</b></td>
                                      <td>
                                          <input name="affiliate_signup_url" class=form_input style="background-color: #FFFDF7; type="text" size="35" maxlength="60" value="<?php echo $affiliate_signup_url?>">
                                          &nbsp;
                                          <input type=button class=button value="Test URL" onClick=javascript:launchAffiliateSignupUrl(this.form)>
                                      </td>
                                    </tr>
                                <?php
                                  }
                                ?>
                                <?php
                                if ($user_level != !PUSHY_LEVEL_VIP)
                                  {
                                ?>
                                    <tr valign=middle>
                                      <td align="right" valign="middle"><b>Product Image:</b></td>
                                      <td>
                                          <img src="<?php echo $image_url?>" width=100>
                                      </td>
                                    </tr>

                                    <tr valign=middle>
                                      <td align="right" valign="middle"><b>Image Data:</b></td>
                                      <td><PRE><?php
                                                   echo "<PRE>\n\n";
                                                   echo "FORMAT:    ".$media_format."\n";
                                                   echo "WIDTH:     ".$media_width."\n";
                                                   echo "HEIGHT:    ".$media_height."\n";
                                                   echo "SIZE:      ".$media_size."\n";
                                                   echo "UP_WIDTH:  ".$media_original_width."\n";
                                                   echo "UP_HEIGHT: ".$media_original_height."\n";
                                                   echo "</PRE>\n";
                                               ?></PRE>
                                      </td>
                                    </tr>


                                      </td>
                                    </tr>
                                <?php
                                  }
                                ?>

                               <tr height=60 valign=middle>
                                 <td align="right" valign="middle"><b>Product URL:</b></td>
                                 <td>
                                     <a href=javascript:openSalesURL('<?php echo $product_url?>')><?php echo $product_url?></a>
                                 </td>
                               </tr>


                                <!-- tr height=80 valign=middle>
                                  <td colspan=2 align=center>
                                      <input type="button" style="width:120px;height:28px;" value=" UPDATE " onClick=javascript:UpdateProduct(this.form)>
                                  </td>
                                </tr -->

                             </table>
                          </td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
<?php
              }
          }
     }
?>


</table>

</body>
</html>
