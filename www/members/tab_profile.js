function account_UpdateAccount(theForm)
  {
    if (!account_VerifyAccountInfo(theForm))
      return false;

    var firstname      = ucfirst(striplt(theForm.firstname.value), true);
    var lastname       = ucfirst(striplt(theForm.lastname.value), true);
    var email          = striplt(theForm.email.value);
    var company_name   = striplt(theForm.company_name.value);
    var password       = striplt(theForm.password.value.toLowerCase());

    var address1       = striplt(theForm.address1.value);
    var address2       = striplt(theForm.address2.value);
    var city           = striplt(theForm.city.value);
    var state          = striplt(theForm.state.value);
    var country        = striplt(theForm.country.value);
    var zip            = striplt(theForm.zip.value);
    var phone          = striplt(theForm.phone.value);
    var phone_ext      = striplt(theForm.phone_ext.value);

    var taxid          = striplt(theForm.taxid.value);
    var payable_to     = striplt(theForm.payable_to.value);
    var paypal_email   = striplt(theForm.paypal_email.value);

    var data    = {
                     sid:            sid,
                     mid:            mid,
                     tp:             'profile_update',
                     password:       password,
                     firstname:      firstname,
                     lastname:       lastname,
                     company_name:   company_name,
                     address1:       address1,
                     address2:       address2,
                     city:           city,
                     state:          state,
                     zip:            zip,
                     country:        country,
                     phone:          phone,
                     phone_ext:      phone_ext,
                     email:          email,
                     taxid:          taxid,
                     payable_to:     payable_to,
                     paypal_email:   paypal_email
                   }

    $.ajax({
       type:     "POST",
       url:      "ajax.php",
       data:     data,
       cache:    false,
       dataType: "json",
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
                       if (status==101) // Update Was OK - Must Redirect to Await Confirmation
                         {
                           window.location=response.data.url;
                         }
                       else
                         {
                           alert( response.message );
                         }
                     }
                   else
                     {
                       alert("Profile Updated");
                     }
                 }
    });
  }


function account_VerifyAccountInfo(theForm)
  {
    theForm.firstname.value         =  ucfirst(striplt(theForm.firstname.value), true);
    theForm.lastname.value          =  ucfirst(striplt(theForm.lastname.value), true);
    theForm.email.value             =  stripa(theForm.email.value);
    theForm.company_name.value      =  striplt(theForm.company_name.value);
    theForm.password.value          =  stripa(theForm.password.value);

    theForm.address1.value          =  striplt(theForm.address1.value);
    theForm.address2.value          =  striplt(theForm.address2.value);
    theForm.city.value              =  striplt(theForm.city.value);
    theForm.state.value             =  striplt(theForm.state.value);
    theForm.country.value           =  striplt(theForm.country.value);
    theForm.zip.value               =  striplt(theForm.zip.value);
    theForm.phone.value             =  striplt(theForm.phone.value);

    theForm.taxid.value             =  striplt(theForm.taxid.value);
    theForm.payable_to.value        =  striplt(theForm.payable_to.value);
    theForm.paypal_email.value      =  striplt(theForm.paypal_email.value);

    if (theForm.email.value.length == 0)
     {
       alert("Please enter your \"Email Address\".");
       theForm.email.focus();
       return (false);
     }

    if ((!isValidEmailAddress(theForm.email.value)))
     {
       alert("Email Address invalid: Please Re-enter your Email Address.");
       theForm.email.focus();
       return (false);
     }

    if (theForm.firstname.value.length == 0)
     {
       alert("Please enter your First Name.");
       theForm.firstname.focus();
       return (false);
     }

    if (theForm.lastname.value.length == 0)
     {
       alert("Please enter your Last Name.");
       theForm.lastname.focus();
       return (false);
     }

    if (theForm.password.value.length == 0)
     {
       alert("Please create your \"Password\".");
       theForm.password.value="";
       theForm.password.focus();
       return (false);
     }

    if (!isValidPW(theForm.password.value))
     {
       alert("Your Password must be 6 to 20 alphanumeric characters (a-z,0-9), at least 1 of which Must be Numeric");
       theForm.password.value="";
       theForm.password.focus();
       return (false);
     }

    //--- Warning Only ------
    if (theForm.paypal_email.value.length==0 ||
        theForm.taxid.value.length==0        ||
        theForm.payable_to.value.length==0)
     {
       var msg  =  "As a PushyAds.com member, you have the ability to earn money as a PushyAds affiliate.\n\n";
           msg +=  "While you are not required to participate in the Affiliate Program, we want you\n";
           msg +=  "to be aware that the following 3 items must be completed before we can issue\n";
           msg +=  "any payments to our affiliates:\n\n";
           msg +=  "  - PayPal Email Address\n";
           msg +=  "  - Tax ID Number\n";
           msg +=  "  - Payable To name\n";
           msg +=  "\n";
       alert(msg);
     }

    if (theForm.paypal_email.value.length>0)
     {
       if ((!isValidEmailAddress(theForm.paypal_email.value)))
         {
           alert("PayPal Email Address invalid: Please Re-enter your Paypal Email Address.");
           theForm.paypal_email.focus();
           return (false);
         }
     }

    return true;
  }


function account_TestAffiliateSite(aff_site)
  {
    var wWidth  = 950;
    var wHeight = 700;

    var topmargin  = 0;
    var leftmargin = 0;

    window.open(aff_site,"AffiliateWebsite",
       'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=yes,toolbar=yes,resizable=yes');
  }

function tab_profile_loaded(response)
  {

  }
