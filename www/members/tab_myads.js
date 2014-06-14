function myads_setMyOwnProduct(theForm)
 {
   myads_hide("CREATE_EXISTING_PRODUCT");
   myads_show("CREATE_MYOWN_PRODUCT");

   var el=document.getElementById("MYOWN_CREATE_EXISTING_PRODUCT_SELECTOR");
   if (el) el.checked=false;
   var el=document.getElementById("MYOWN_CREATE_MYOWN_PRODUCT_SELECTOR");
   if (el) el.checked=true;

   document.CREATE_MYOWN_PRODUCT_FORM.op.value="Create";

   document.CREATE_MYOWN_PRODUCT_FORM.myown_product_name.focus();
   document.CREATE_MYOWN_PRODUCT_FORM.submitted.value='0';

   if (arguments >= 1)
     myads_preview_refresh(theForm);

   showPreview(false);

// dumpObject(_system_);
 }

function myads_setExistingProduct()
 {
   myads_hide("CREATE_MYOWN_PRODUCT");
   myads_show("CREATE_EXISTING_PRODUCT");

   var el=document.getElementById("EXISTING_CREATE_MYOWN_PRODUCT_SELECTOR");
   if (el) el.checked=false;
   var el=document.getElementById("EXISTING_CREATE_EXISTING_PRODUCT_SELECTOR");
   if (el) el.checked=true;

   document.CREATE_EXISTING_PRODUCT_FORM.op.value="Create";

   showPreview(false);
 }

function myads_hide(id)
 {
   var el=document.getElementById(id);
   if (el) el.style.display='none';
 }
function myads_show(id)
 {
   var el=document.getElementById(id);
   if (el) el.style.display='';
 }

function myads_loadHTML(fromID, toID)
 {
   var elFrom=document.getElementById(fromID);
   var elTo  =document.getElementById(toID);

   if ( (elFrom) && (elTo) )
     {
       elTo.innerHTML = elFrom.innerHTML;
     }
 }


function myads_OptionTabClicked(option)
 {
   showPreview(false);

   for (var i=1; i<=3; i++)
     {
       var id  = 'order_option_'+i;
       var el  =  document.getElementById(id);
       var bid = 'order_button_'+i;
       var bel =  document.getElementById(bid);
        if (el && (bel))
         {
           if (i==option && (el.style.display == 'none'))
             {
               el.style.display='';
               bel.value='CLOSE';
             }
           else
             {
               el.style.display='none';
               bel.value='OPEN';
             }
         }
     }

 }


function myads_existingProductSelected(theForm)
 {
   if (theForm.existing_product_id.selectedIndex==0)
     {
        alert("Please select an Affiliate Offer you are interested in Selling. ");
        theForm.existing_product_id.focus();
        return;
     }

   myads_setExistingProduct();
//   alert("product selection="+theForm.existing_product_id.value);
   return;
 }


function myads_textCounter(theForm, field, fieldName, countfield, maxlimit) {
 var maxWordLength=14;
 if ( field.value.length > maxlimit )
   {
     field.value = field.value.substring( 0, maxlimit );
     alert( fieldName + ' can only be '+maxlimit+' characters in length.' );
     return false;
   }
 else
 if (maxWordLengthExceeded(field.value,maxWordLength))
   {
     field.value = field.value.substring( 0, field.value.length-1 );
     var msg  = "  --- A Long Word Has Been Detected ---\n";
         msg += "More than "+maxWordLength+" characters found without a space or linefeed\n\n";
         msg += "The maximum length of a single Word is "+maxWordLength+" characters.\n";
         msg += "You may be missing a Space between 2 words or between\n";
         msg += "comma-separated words.\n\n";
     // alert(msg);
     return false;
   }
 else
   {
     countfield.value = maxlimit - field.value.length;
   }
 myads_preview_refresh(theForm);
}


function myads_new_product_submission_status(theResult)
 {
   // alert("myads_myown_product_sub ission_status: \n\n"+objectToString(theResult));

   var submitButton="SubmitOwnProduct";
   myads_enable_button(submitButton);
   if (theResult.success)
     {
       tabClicked("myads", true, function() { myads_NewProductSubmitted(theResult); } );
     }
   else
     alert("Error: "+theResult.message);
 }



function myads_update_product_submission_status(theResult)
 {
   //--- And Ad has been Updated By the Product Owner - Approval May Be Required
   // alert("myads_myown_product_sub ission_status: \n\n"+objectToString(theResult));

   var submitButton="UpdateOwnProduct";
   myads_enable_button(submitButton);
   if (theResult.success)
     {
       tabClicked("myads", true, function() { myads_UpdatedProductSubmitted(theResult); } );
     }
   else
     alert("Error: "+theResult.message);
 }



function myads_NewProductSubmitted(theResult)
 {
   //--- And New Ad has been Updated By the Product Owner - Approval is Required
   //
   //   success: true
   //   disposition: 1
   //   message:
   //   ApprovalRequired: false
   //
   // var s = objectToString(theResult);
   // var f = function() { window.scroll(0,0); alert(s); }

   var msg="";
   if (theResult.disposition == 0)
     { // New Ad
        msg += "Your Product Ad has been submitted for review and approval.\n";
        msg += "Keep your eyes peeled for an email response within the next\n";
        msg += "24 hours (probably a lot less)\n";
     }
   else
     { // Update
        if (theResult.ApprovalRequired)
          {
            msg += "Your Product Ad Updates have been received.\n\n";
            msg += "One or more of your updates will require approval\n";
            msg += "before the changes can be released.\n";
          }
        else
          {
            msg += "Your Product Ad Updates have been received\n";
            msg += "and Approved.                             \n";
          }
     }

   var f = function() { window.scroll(0,0); alert(msg); }
   setTimeout(f,30);
 }


function myads_UpdatedProductSubmitted(theResult)
 {
   //--- And Ad has been Updated By the Product Owner - Approval May Be Required
   //
   //   success: true
   //   disposition: 1
   //   message:
   //   ApprovalRequired: false
   //
   // var s = objectToString(theResult);
   // var f = function() { window.scroll(0,0); alert(s); }

   var msg="";
   if (theResult.disposition == 0)
     { // New Ad
        msg += "Your Product Ad has been submitted for review and approval.\n";
        msg += "Keep your eyes peeled for an email response within the next\n";
        msg += "24 hours (probably a lot less)\n";
     }
   else
     { // Update
        if (theResult.ApprovalRequired)
          {
            msg += "Your Product Ad Updates have been received.\n\n";
            msg += "One or more of your updates will require approval\n";
            msg += "before the changes can be released.\n";
          }
        else
          {
            msg += "Your Product Ad Updates have been received\n";
            msg += "and Approved.                             \n";
          }
     }

   var f = function() { window.scroll(0,0); alert(msg); }
   setTimeout(f,30);
 }


function myads_disable_button(idButton)
 {
   var button=document.getElementById(idButton);
   button.disabled=true;
 }

function myads_enable_button(idButton)
 {
   var button=document.getElementById(idButton);
   button.disabled=false;
   return false;
 }


function myads_validateMyOwnProduct(theForm,submitButton)
 {
//  var submitButton="SubmitOwnProduct";
//  if (theForm.op.value!="Create")
//     submitButton="UpdateOwnProduct";

    myads_disable_button(submitButton);

    _pushy_preview_form_=theForm;
    var isVisible = isPreviewVisible();

    if (theForm.op.value=="Create" && theForm.submitted.value=='1')
      return myads_enable_button(submitButton);

   // alert("myads_validateMyOwnProduct: "+theForm.mid.value);

    var pushy_user_level=theForm.pushy_user_level.value;

    theForm.myown_product_name.value  = striplt(theForm.myown_product_name.value);
    if (theForm.myown_product_name.value.length==0)
      {
        alert("Please Enter the Product Name");
        theForm.myown_product_name.focus();
        return myads_enable_button(submitButton);;
      }
    if (theForm.myown_product_name.value.length < 5 ||
        theForm.myown_product_name.value.length > 20)
      {
        alert("Product Name Must be at least 5 characters and no more\nthan 20 characters in length.");
        theForm.myown_product_name.focus();
        return myads_enable_button(submitButton);
      }

    if (!myads_audit_Name_Title(theForm,'Product Name',theForm.myown_product_name))
      return myads_enable_button(submitButton);

    theForm.myown_product_title.value = striplt(theForm.myown_product_title.value);
    if (theForm.myown_product_title.value.length==0)
      {
        alert("Please Enter the Product Title");
        theForm.myown_product_title.focus();
        return myads_enable_button(submitButton);
      }
    if (theForm.myown_product_title.value.length < 5 ||
        theForm.myown_product_title.value.length > 20)
      {
        alert("Product Title Must be at least 5 characters and no more\nthan 20 characters in length.");
        theForm.myown_product_title.focus();
        return myads_enable_button(submitButton);
      }

    if (!myads_audit_Name_Title(theForm,'Product Title',theForm.myown_product_title))
      return myads_enable_button(submitButton);

    theForm.myown_product_description.value = massage_ad_copy(theForm.myown_product_description.value);
    if (theForm.myown_product_description.value.length==0)
      {
        alert("Please Enter the Product Description");
        theForm.myown_product_description.focus();
        return myads_enable_button(submitButton);
      }
    if (theForm.myown_product_description.value.length < 20)
      {
        alert("Product Description must be a Minimum of 20 characters");
        theForm.myown_product_description.focus();
        return myads_enable_button(submitButton);
      }

    var product_categories="";
    var numCategoriesSelected=0;
    var len = theForm.myown_product_categories.length;
    for (var i=0; i<len; i++)
      {
        if (theForm.myown_product_categories[i].selected)
          {
            product_categories += theForm.myown_product_categories[i].value+"~";
            numCategoriesSelected++;
          }
      }

    if (numCategoriesSelected == 0 || numCategoriesSelected > 7)
      {
        alert("Please Select  1 - 7  Product Categories for this product ");
        theForm.myown_product_categories.focus();
        return myads_enable_button(submitButton);
      }
    theForm.product_categories.value = "~"+product_categories;

    var product_url  = myads_audit_url(theForm.myown_product_url,"Product Sales URL");
    if (!product_url) return myads_enable_button(submitButton);

    if (pushy_user_level!="VIP")
      {
        if ( (!theForm.IMAGE_UPLOAD_OPTION) ||
             ((theForm.IMAGE_UPLOAD_OPTION) && (theForm.IMAGE_UPLOAD_OPTION[1].checked)) )
          {
            if (theForm.myown_product_image.value == "")
              {
                alert("Please select a Product Image File to Upload.");
                theForm.myown_product_image.focus();
                return myads_enable_button(submitButton);
              }

            var image_file = striplt(theForm.myown_product_image.value);
            if ( !endsWith(image_file, ".gif", false) &&
                 !endsWith(image_file, ".jpg", false) &&
                 !endsWith(image_file, ".png", false) &&
                 !endsWith(image_file, ".bmp", false) )
              {
                var msg  = "The Image File entered is not a supported image file format.\n\n";
                    msg += "Image Files must be one of:  gif, jpg, png, bmp\n\n";
                alert(msg);
                theForm.myown_product_image.focus();
                return myads_enable_button(submitButton);
              }
          }
      }


    if (isVisible)
      {
        showPreview(false);
      }
    else
      {
        myads_preview(theForm);
        var msg  = "PUSHY is displaying your AD to the right for your review.\n\n";
            msg += "In order to streamline the approval process, we ask that you review\n";
            msg += "your Ad carefully to make sure it appears as you intended.       \n\n";
            msg += "If you are satisfied, click OK to continue.\n";
            msg += "Otherwise, Click Cancel and make any necessary changes before\n";
            msg += "submitting your Ad. \n\n";
            resp=confirm(msg);
        if (!resp)
          {
            return myads_enable_button(submitButton);
          }
        showPreview(false);
      }

    if (theForm.op.value=="Create")
      {
        theForm.submitted.value='1';
      }
    return true;
  }


function myads_audit_Name_Title(theForm,elementName,theElement)
  {
    allowedCharacters  = ["-","'","&","?","!"];
    cannotStartWith    = ["-","'","?","!"];
    cannotEndWith      = ["-","'"];
    cannotStandAlone   = ["-","'","?","!"];
    cannotBeSuccessive = ["-","'","&","?","!"];

    var value=theElement.value;
    var new_value="";

    var words = value.split(' ');
    var count=words.length;
    var phrase="";
    for (var i=0; i<count; i++)
      {
        var hyphen=0;
        var hyphenCount=0;
        var alphaNumCount=0;
        var word = words[i];
        if (myads_inArray(word,cannotStandAlone))
          {
            var msg  = "The character  ' "+word+" '  cannot appear by itself as a separate word in "+elementName+"\n";
            alert(msg);
            theElement.focus();
            return false;
          }
        for (var j=0; j<word.length; j++)
          {
            if ( (word.charAt(j) >= '0' && word.charAt(j) <= '9')  ||
                 (word.charAt(j) >= 'A' && word.charAt(j) <= 'Z')  ||
                 (word.charAt(j) >= 'a' && word.charAt(j) <= 'z')  ||
                 (myads_inArray(word.charAt(j),allowedCharacters)))
              {
                if (j==0               && myads_inArray(word.charAt(j),cannotStartWith))
                  {
                    var msg  = "The character  ( "+word.charAt(j)+" )  cannot appear as the first character in a word in "+elementName+"\n";
                    alert(msg);
                    theElement.focus();
                    return false;
                  }
                if (j==(word.length-1) && myads_inArray(word.charAt(j),cannotEndWith))
                  {
                    var msg  = "The character  ( "+word.charAt(j)+" )  cannot appear as the last character in a word in  "+elementName+"\n";
                    alert(msg);
                    theElement.focus();
                    return false;
                  }
                if (j>0 && (word.charAt(j) == word.charAt(j-1)) && (myads_inArray(word.charAt(j),cannotBeSuccessive)))
                  {
                    var msg  = "The character  ( "+word.charAt(j)+" )  cannot appear twice successively in "+elementName+"\n";
                    alert(msg);
                    theElement.focus();
                    return false;
                  }
              }
            else
              {
                var msg  = "The "+elementName+" contains one or more characters that are not allowed. \n";
                    msg += "Characters must be AlphaNumeric or one of: \n     (  ";
                    for (var n=0; n<allowedCharacters.length; n++)
                      {
                        msg += allowedCharacters[n];
                        if (n != allowedCharacters.length-1)
                          msg+="  ";
                      }
                    msg += "  )\n\n";
                alert(msg);
                theElement.focus();
                return false;
              }
          }
        if (word.indexOf("&") >= 0 && word.length>1)
          {
            var msg  = "The character  ( & )  may only be as a single separation character between 2 words in "+elementName+"\n";
            alert(msg);
            theElement.focus();
            return false;
          }
        word=ucfirst(word,true);
        if (i>0)
          new_value += " ";
        new_value += word;
      }

     var n1=new_value.indexOf("&");
     var n2=new_value.lastIndexOf("&");
     if (n1==0 || n2==new_value.length-1)
       {
         var msg  = "The character  ( & )  may only be as a single separation character between 2 words in "+elementName+"\n";
         alert(msg);
         theElement.focus();
         return false;
       }

     theElement.value=new_value;
     return true;
  }


function myads_inArray(s,sarray)
  {
    for (var j=0; j<sarray.length; j++)
      {
        if (s==sarray[j]) return true;
      }
    return false;
  }


function myads_audit_url(obj,fieldDescription)
  {
    obj.value    = striplt(obj.value.toLowerCase());
    var url      = obj.value;
    if (!startsWith(url,"http://",true))
      {
        var msg = fieldDescription+" is invalid - URL must begin with http://";
        alert(msg);
        obj.focus();
        return false;
      }
    if (url.indexOf("/pushyads.com")>0 || url.indexOf(".pushyads.com")>0)
      {
        var msg = fieldDescription+" may not reference the pushyads.com domain. ";
        alert(msg);
        obj.focus();
        return false;
      }
    if (url.length > 12 && url.indexOf(".") > 0)
      return url;
    var msg = fieldDescription+" is invalid - Please re-enter";
    alert(msg);
    obj.focus();
    return false;
  }

function myads_TestSalesURL(theForm, obj)
  {
    var product_url  = myads_audit_url(obj,"Product Sales URL");
    if (!product_url) return false;

    var wWidth  = 700;
    var wHeight = 550;

    var topmargin  = 0;
    var leftmargin = 0;

    // alert("URL="+product_url);

    var win=window.open(product_url,"ProductWebsite",
       'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
  }

function myads_TestAffiliateURL(theForm, obj)
  {
    var product_url  = myads_audit_url(obj,"Affiliate URL");
    if (!product_url) return false;

    var wWidth  = 700;
    var wHeight = 550;

    var topmargin  = 0;
    var leftmargin = 0;

    // alert("URL="+product_url);

    var win=window.open(product_url,"ProductWebsite",
       'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
  }


function myads_TestSignupURL(theForm, obj)
  {
    var signup_url  = myads_audit_url(obj,"Affiliate Signup URL");
    if (!signup_url) return false;

    var wWidth  = 700;
    var wHeight = 550;

    var topmargin  = 0;
    var leftmargin = 0;

    var win=window.open(signup_url,"AffiliateSignupPage",
       'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
  }


function pushyNetworkClicked(inx)
  {
    var i=0;
    while(true)
      {
        var id="pushy_network-"+i;
        var el=document.getElementById(id);
        if (el)
          {
            if (i!=inx)
              el.checked=false;
          }
        else
          break;

        i++;
      }
  }

function eliteBarClicked(inx)
  {
    var i=0;
    while(true)
      {
        var id="elite_bar-"+i;
        var el=document.getElementById(id);
        if (el)
          {
            if (i!=inx)
              el.checked=false;
          }
        else
          break;

        i++;
      }
  }

function elitePoolClicked(inx)
  {
    var i=0;
    while(true)
      {
        var id="elite_pool-"+i;
        var el=document.getElementById(id);
        if (el)
          {
            if (i!=inx)
              el.checked=false;
          }
        else
          break;

        i++;
      }
  }


function productListClicked(theForm,inx,activeProducts)
  {
    for (var i=0; i<activeProducts; i++)
      {
        var id="product_list-"+i;
        var el=document.getElementById(id);
        if (el)
          {
            if (i!=inx)
              el.checked=false;

            var idAffSignup="ProductList_AffiliateSignup_"+i;
            var elAffSignup=document.getElementById(idAffSignup);
            if (elAffSignup)
              {
                if (el.checked)
                  elAffSignup.style.display='';
                else
                  elAffSignup.style.display='none';
              }
          }
      }
  }


function myads_advertise_existing_product(theForm,submitButton)
  {
    myads_disable_button(submitButton);

    if (theForm.existing_product_id.selectedIndex==0)
      {
        alert("Please Select a Product you wish to sell ");
        theForm.existing_product_id.focus();
        return myads_enable_button(submitButton);
      }

    var product_url  = myads_audit_url(theForm.existing_product_url,"Affiliate URL");
    if (!product_url) return myads_enable_button(submitButton);

    var product_id   = theForm.existing_product_id.value;

    var data = {
                 tp:            "myads_add_existing_product",
                 op:            theForm.op.value,
                 mid:           theForm.mid.value,
                 sid:           theForm.sid.value,
                 product_id:    product_id,
                 product_url:   product_url
               }

    $.ajax({
       type:     "POST",
       url:      "ajax.php",
       data:     data,
       dataType: "json",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   if (httpStatus==401)
                     {
                       top.location.href="/index.php?SessionExpired";
                     }
                   else
                     {
                       myads_enable_button(submitButton);
                       alert("Request Failed - HTTP Status:"+ httpStatus);
                     }
                 },
       success:  function(response, textStatus)
                 {
                   var status=response.status;
                   if (status != 0)
                     {
                       myads_enable_button(submitButton);
                       alert( response.message );
                     }
                   else
                     {
                       var data=response.data;
                       var msg;
                       if (data.user_level == 2)
                         {
                           msg  = "IMPORTANT! Your Product Ad will be INACTIVE until you select which\n";
                           msg += "Product Ad Placement you want PUSHY! to use when pushing your ad\n\n";
                         }
                       else
                         {
                           msg  = "Your Product Ad has been submitted, and will soon be seen\n";
                           msg += "throughout PUSHY's Network. \n\n";
                         }
                       tabClicked("myads", true);

                       var f = function() { window.scroll(0,0); alert(msg); }
                       setTimeout(f,100);
                     }
                 }
    });
  }


function myads_update_existing_product(theForm, submitButton)
  {
    myads_disable_button(submitButton);
    if (theForm.existing_product_id.selectedIndex==0)
      {
        alert("Please Select a Product you wish to sell ");
        theForm.existing_product_id.focus();
        return myads_enable_button(submitButton);
      }

    var product_url  = myads_audit_url(theForm.existing_product_url,"Affiliate URL");
    if (!product_url) return myads_enable_button(submitButton);

    var ad_id               = theForm.ad_id.value;  // Safe: This DOES NOT CHANGE AS A RESULT OF EDITING and AFFILIATE OFFER
    var product_id_selected = theForm.existing_product_id.value;

    var data = {
                 tp:            "myads_update_existing_product",
                 op:            theForm.op.value,
                 mid:           theForm.mid.value,
                 sid:           theForm.sid.value,
                 ad_id:         ad_id,
                 product_id:    product_id_selected,
                 product_url:   product_url
               }

    $.ajax({
       type:     "POST",
       url:      "ajax.php",
       data:     data,
       dataType: "json",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   if (httpStatus==401)
                     {
                       top.location.href="/index.php?SessionExpired";
                     }
                   else
                     {
                       myads_enable_button(submitButton);
                       alert("Request Failed - HTTP Status:"+ httpStatus);
                     }
                 },
       success:  function(response, textStatus)
                 {
                   var status=response.status;
                   if (status != 0)
                     {
                       myads_enable_button(submitButton);
                       alert( response.message );
                     }
                   else
                     {
                       var data=response.data;
                       var msg;
                       if (data.user_level == 2)
                         {
                           msg  = "Your Update has been received and Applied.\n\n";
                           msg += "IMPORTANT! If you have not yet selected your Ad Placement for this\n";
                           msg += "Product Ad, please do so at this time! The Product AD will be INACTIVE\n";
                           msg += "until a Prodict Placement selection has been made. \n\n";
                         }
                       else
                         {
                           msg  = "Your Update has been received and Applied. Your change is \n";
                           msg += "now in effect. \n\n";
                         }
                       tabClicked("myads", true);

                       var f = function() { window.scroll(0,600); alert(msg); }
                       setTimeout(f,100);
                     }
                 }
    });
  }


function myads_edit_ad(theForm, inx, ad_id, product_id)
  {
    var data = {
                 tp:         "myads_edit_product",
                 mid:        theForm.mid.value,
                 sid:        theForm.sid.value,
                 ad_id:      ad_id,
                 product_id: product_id
               }

    $.ajax({
       type:     "GET",
       url:      "ajax.php",
       data:     data,
       dataType: "html",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   if (httpStatus==401)
                     {
                       top.location.href="/index.php?SessionExpired";
                     }
                   else
                     {
                       alert("Request Failed - HTTP Status:"+ httpStatus);
                     }
                 },
       success:  function(response, textStatus)
                 {
                   var el=document.getElementById("PRODUCT_EDIT");
                   if (el)
                     {
                       myads_hide("AD_CREATION");
                       myads_hide("MYADS_MY_ADS_LISTS");
                       el.innerHTML=response;
                       el.style.display='';
                       myads_preview_refresh(theForm);
                       showPreview(false);
                       window.scroll(0,360);
                     }
                 }

    });

  }


function myads_remove_ad(theForm, inx, ad_id, product_id)
  {
    var productNameId = "ProductName-"+inx;
    var el = document.getElementById(productNameId);
    var product_name="";
    if (el)
      product_name = striplt(el.innerHTML);

    var msg  = "You have asked to remove the following product from your Approved List: \n";
        msg += "           "+product_name+"        \n";
        msg += "\n";
        msg += "To continue with the removal, click OK.\n";
        msg += "Otherwise, Click Cancel.\n";
        msg += "\n";
    var resp=confirm(msg);
    if (!resp) return;

    var data = {
                 tp:            "myads_remove_product",
                 mid:           theForm.mid.value,
                 sid:           theForm.sid.value,
                 ad_id:         ad_id,
                 product_id:    product_id
               }

    $.ajax({
       type:     "POST",
       url:      "ajax.php",
       data:     data,
       dataType: "json",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   if (httpStatus==401)
                     {
                       top.location.href="/index.php?SessionExpired";
                     }
                   else
                     {
                       alert("Request Failed - HTTP Status:"+ httpStatus);
                     }
                 },
       success:  function(response, textStatus)
                 {
                   var status=response.status;
                   if (status != 0)
                     {
                       alert( response.message );
                     }
                   else
                     {
                       tabClicked("myads", true);
                     }
                 }
    });
  }


function existing_product_selected(theForm,target_id)
  {

    if (theForm.existing_product_id.selectedIndex==0)
      {
        var el=document.getElementById(target_id);
        if (el)
          {
            el.style.display='none';
          }

        if (theForm.op.value == "Update")
          {
            theForm.existing_product_url.value="http://";
          }
        return;
      }

    // Selecting your Own Product from Affiliate Offers List
    if (theForm.existing_product_id.value.startsWith("~")) // Only Occurs For ADD AFFILIATE OFFER Not EDIT
      {
        var msg  = "You are the owner of this offer, and cannot advertise it twice";
        alert(msg);
        theForm.existing_product_id.selectedIndex=0;
        var el=document.getElementById(target_id);
        if (el)
          {
            el.style.display='none';
          }

        if (theForm.op.value == "Update")
          {
            theForm.existing_product_url.value="http://";
          }
        return;
      }

    var product_id = theForm.existing_product_id.value;

    if (theForm.op.value == "Update")
      {
        if (product_id == theForm.save_product_id.value)
           theForm.existing_product_url.value=theForm.save_product_url.value;
        else
           theForm.existing_product_url.value="http://";
      }


    var data = {
                 tp:         "myads_fetch_existing_product",
                 mid:        theForm.mid.value,
                 sid:        theForm.sid.value,
                 operation:  theForm.op.value,
                 product_id: product_id
               }

    $.ajax({
       type:     "GET",
       url:      "ajax.php",
       data:     data,
       dataType: "html",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   if (httpStatus==401)
                     {
                       top.location.href="/index.php?SessionExpired";
                     }
                   else
                     {
                       alert("Request Failed - HTTP Status:"+ httpStatus);
                     }
                 },
       success:  function(response, textStatus)
                 {
                   var el=document.getElementById(target_id);
                   if (el)
                     {
                       el.innerHTML=response;
                       el.style.display='';
                     }
                 }
    });
 }


function myads_update_ad_placement(theForm,activeProducts)
 {
   // ShowFormVariables(theForm);
   // return;

   var data = {
                tp:    "myads_update_ad_properties",
                mid:   theForm.mid.value,
                sid:   theForm.sid.value
              }

   for (var i=0; i<theForm.elements.length; i++)
     {
       if (theForm.elements[i].type == "checkbox" && (pos=theForm.elements[i].name.indexOf('-')) > 0)
         {
           var tarray=theForm.elements[i].name.split("-");
           if (tarray.length==2 && isNumeric(tarray[1]))
             {
               if (tarray[0]=="product_list" && theForm.elements[i].checked)
                 {
                   var id="ProductList_AffiliateSignup_URL_"+tarray[1];
                   var el=document.getElementById(id);
                   var signup_url  = el.value;
                   var signup_url  = myads_audit_url(el,"Affiliate Signup URL");
                   if (!signup_url) return false;
                   // alert("Affiliate URL: "+el.value);
                   // alert(id +  " -- " + tarray[0] +  " -- " + tarray[1]);
                   data["product_list_ad"]=tarray[1];
                   data["product_list_affiliate_url"]=signup_url;
                 }
               var name = "~"+theForm.elements[i].name;
               data[name] = theForm.elements[i].checked;
             }
         }
     }


   // theForm.method="POST";
   // theForm.action="link.php?mid="+mid+"&sid="+sid+"&tp="+data.tp;
   // theForm.submit();
   // return;

   // dumpObject(data);
   // return;


   $.ajax({
      type:     "POST",
      url:      "ajax.php",
      data:     data,
      dataType: "json",
      cache:    false,
      error:    function (XMLHttpRequest, textStatus, errorThrown)
                {
                  // typically only one of textStatus or errorThrown will have info
                  var httpStatus=XMLHttpRequest.status;
                  if (httpStatus==401)
                    {
                      top.location.href="/index.php?SessionExpired";
                    }
                  else
                    {
                      alert("Request Failed - HTTP Status:"+ httpStatus);
                    }
                },
      success:  function(response, textStatus)
                {
                  var status=response.status;
                  if (status != 0)
                    {
                      alert( response.message );
                    }
                  else
                    {
                       var data=response.data;
                       var xpl_pending  = data.xpl_pending;
                       xpl_name         = data.xpl_name;
                       xpl_title        = data.xpl_title;

                       if (xpl_pending==0)
                          alert("Your Ad placements have been updated");
                       else
                         {
                           var msg  = "Your Ad placements have been updated\n\n";

                               msg += "Your Affiliate Offer Ad Selection is being reviewed:\n\n";
                               msg += " -- Product Name:   "+xpl_name+"\n";
                               msg += " -- Product Title  :   "+xpl_title+"\n";
                               msg += "\n";
                               msg += "You will be notified by email when this ad has been approved and it is posted\n";
                               msg += "to the Affiliate Offers List.\n";
                               msg += "\n";
                           alert(msg);
                         }

                      // if (data.existing_products_requested==0)
                      //   {
                      //     var f = function() { existing_products_notice(data); }
                      //     setTimeout(f,500);
                      //   }

                      // Force the refresh just to test ad selection displays correctly
                      tabClicked("myads", true);
                    }
                }
   });
 }


function existing_products_notice(data)
 {
// var msg  = "Hey - This is the First Time You Have Asked for\n";
//     msg += "this Ad to Be Placed into the Affiliate Offers List\n\n";
// alert(msg);
// dumpObject(data);

   // showPopWin('System Message', "/alert.php?msg=105", 480, 220, null);
 }


function openSalesURL()
  {
    var url = myads_preview_refresh();

    if (!startsWith(url,"http://",true) || url.length <=12 || (url.indexOf(".") <= 0))
      {
        alert('Your Sales URL is missing or invalid');
        return;
      }

    var top=0;
    var left=0;
    var width=650;
    var height=600;

    var winHandle=window.open(url,"PushyPreview",
       'width='+width+',height='+height+',top='+top+',left='+left+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
    if (winHandle.opener == null) winHandle.opener = self;
  }


function myads_textFieldClicked(theForm)
 {
   // alert("LEVEL="+theForm.pushy_user_level.value);
   _pushy_preview_form_=theForm;
   if (_pushy_preview_ != null)
     {
       if (_pushy_preview_.style.display=='none')
         {
           myads_preview_refresh(theForm);
           showPreview(true);
         }
     }
 }


function myads_preview(theForm)
 {
   // alert("LEVEL="+theForm.pushy_user_level.value);
   if (_pushy_preview_ != null)
     {
       if (_pushy_preview_.style.display=='none')
         {
           _pushy_preview_form_=theForm;
           myads_preview_refresh(theForm);
           showPreview(true);
           if (theForm.op.value == "Update")
             {
               var el1 = document.getElementById("PREVIEW_UPDATE_1");
               var el2 = document.getElementById("PREVIEW_UPDATE_2");
               if (el1) el1.innerHTML=" Close Preview ";
               if (el2) el2.innerHTML=" Close Preview ";
             }
           else
             {
               var el1 = document.getElementById("PREVIEW_CREATE_1");
               var el2 = document.getElementById("PREVIEW_CREATE_2");
               if (el1) el1.innerHTML=" Close Preview ";
               if (el2) el2.innerHTML=" Close Preview ";
             }
         }
       else
         {
           _pushy_preview_form_=null;
           showPreview(false);
           if (theForm.op.value == "Update")
             {
               var el1 = document.getElementById("PREVIEW_UPDATE_1");
               var el2 = document.getElementById("PREVIEW_UPDATE_2");
               if (el1) el1.innerHTML="Preview Your Ad";
               if (el2) el2.innerHTML="Preview Your Ad";
             }
           else
             {
               var el1 = document.getElementById("PREVIEW_CREATE_1");
               var el2 = document.getElementById("PREVIEW_CREATE_2");
               if (el1) el1.innerHTML="Preview Your Ad";
               if (el2) el2.innerHTML="Preview Your Ad";
             }
         }
     }
 }


function myads_preview_refresh(theForm)
 {
   if (arguments.length >= 1)
     _pushy_preview_form_=theForm;

   if ((typeof _pushy_preview_form_) == "undefined")
     return;

   if (_pushy_preview_form_ != null && _pushy_preview_ != null &&
          typeof theForm.myown_product_name          != "undefined" &&
          typeof theForm.myown_product_title         != "undefined" &&
          typeof theForm.myown_product_description   != "undefined" &&
          typeof theForm.myown_product_url           != "undefined"
      )
     {
       var theForm=_pushy_preview_form_;
       var product_name        = theForm.myown_product_name.value;
       var product_title       = theForm.myown_product_title.value;
       var product_description = massage_ad_copy(theForm.myown_product_description.value);
       var product_url         = theForm.myown_product_url.value;

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
 }

function massage_ad_copy(s)
 {
   // alert(s);
   s = striplt(s);
   s = s.replace(/\r/g, " ");
   s = s.replace(/\n/g, " ");
   s = s.replace(/\t/g, " ");
   // s = s.replace(/[\r\n]+/g, " ");

   while(true)
     {
       t = s.replace(/  /g," ");
       if (t==s) break;
       s=t;
     }

   // alert(s);
   return(s);
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


function maxWordLengthExceeded(value,maxWordLength)
 {
   var words = value.split(' ');
   var count = words.length;
   for (var i=0; i<count; i++)
     {
       var words2 = words[i].split('\n');
       var count2 = words2.length;
       for (var j=0; j<count2; j++)
         {
           if (words2[j].length > maxWordLength)
             return true;
         }
     }
   return false;
 }


function tab_myads_loaded(response)
  {

  }
