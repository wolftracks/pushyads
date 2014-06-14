//   ---- UNSELECTED               SELECTED ---------------
//   "reports_referrals.png",   "reports_referrals_active.png"
//   "reports_network.png",     "reports_network_active.png",
//   "reports_traffic.png",     "reports_traffic_active.png",
//   "reports_credit.png",      "reports_credit_active.png",
//   "reports_sales.png",       "reports_sales_active.png",
//   "reports_status.png",      "reports_status_active.png",


var reports_image_base_names = ['referrals', 'network', 'traffic', 'credit', 'sales', 'status'];

var mid='<?php echo $mid?>';
var sid='<?php echo $sid?>';

var reports_imageHost="http://pds1106.s3.amazonaws.com/images/";
var reports_image_selected={};
var reports_image_unselected={};
var reports_image_hover={};

if (document.images)
{
  for (var i=0; i<reports_image_base_names.length; i++)
    {
      var reports_basename = reports_image_base_names[i];
      reports_image_unselected[reports_basename] = reports_imageHost+'reports_'+reports_basename+'.png';
      reports_image_selected[reports_basename]   = reports_imageHost+'reports_'+reports_basename+'_active.png';
      reports_image_hover[reports_basename]      = reports_imageHost+'reports_'+reports_basename+'_hover.png';
    }
}

var traffic_current_week=5;

var reportsBusy=false;
var CurrentPage=1;
var TotalPages=1;
var SortBy='Date';
var reports_current='referrals';
function reports_tabClicked(reports_tab,initialize)
 {
   if (reportsBusy) return;

   var init=false;
   if (arguments.length == 2)
     {
       if (initialize)
         {
           init=true;
           reports_current='referrals';
         }
       CurrentPage=1;
       TotalPages=1;
       SortBy='Date';
     }
   else
     if (reports_tab==reports_current)
       {
         return;
       }

   var currentTabElement = document.getElementById('img-rpt-'+reports_current);
   var newTabElement     = document.getElementById('img-rpt-'+reports_tab);    //clicked

   var currentTabURL     = reports_image_unselected[reports_current];
   var newTabURL         = reports_image_selected[reports_tab];

   var resetCurrent=true;
   if (reports_tab==reports_current)
      resetCurrent=false;

   reports_current=reports_tab;

   reports_getReport(reports_tab);

   if (resetCurrent)
     {
       currentTabElement.src=currentTabURL;
     }
   newTabElement.src=newTabURL;
 }


function reports_over(reports_tab)
 {
   if (reports_tab==reports_current)
     {
       return;
     }

   var tabElement = document.getElementById('img-rpt-'+reports_tab);
   var tabURL     = reports_image_hover[reports_tab];
   tabElement.src = tabURL;
 }

function reports_out(reports_tab)
 {
   if (reports_tab==reports_current)
     {
       return;
     }

   var tabElement = document.getElementById('img-rpt-'+reports_tab);
   var tabURL     = reports_image_unselected[reports_tab];
   tabElement.src = tabURL;
 }

function reports_getReport(report)
 {
   var canvas            = document.getElementById("REPORT");

   var data = {
                tp:       "reports",
                mid:      mid,
                sid:      sid,
                report:   report,
                page:     CurrentPage,
                sort:     SortBy
              }


   // dumpObject(data);

   reportsBusy=true;
   $.ajax({
      type:     "POST",
      url:      "ajax.php",
      data:     data,
      dataType: "json",
      cache:    false,
      error:    function (XMLHttpRequest, textStatus, errorThrown)
                {
                  reportsBusy=false;
                  // typically only one of textStatus or errorThrown will have info
                  var httpStatus=XMLHttpRequest.status;
                  if (httpStatus==401)
                    {
                      top.location.href="/index.php?SessionExpired";
                    }
                  else
                    {
                      canvas.innerHTML="Request Failed - HTTP Status:"+ httpStatus;
                    }
                },
      success:  function(response, textStatus)
                {
                  reportsBusy=false;
                  if (typeof response == "object" && typeof response.status != "undefined")
                    {
                      var status=response.status;
                      if (status != 0)
                        {
                          alert( response.message );
                        }
                      else
                        {
                          var data = response.data;
                          CurrentPage=data.CurrentPage;
                          TotalPages =data.TotalPages;
                          canvas.innerHTML = data.html;

                          var s="reports_"+report+"_loaded(response)";
                          // alert("s="+s);
                          eval(s);

                        }
                    }
                  else
                    {
                      canvas.innerHTML = response;
                    }
                }
   });
 }


function referrals_sortBy(field)
 {
   CurrentPage=1; // not required - but makes sense

   SortBy=field;
   reports_getReport('referrals');
 }


function referrals_getPage(page)
 {
   if (page == 'Init')
     {
       reports_tabClicked('referrals',true);
       return;
     }
   if (page == 'TabClicked')
     {
       reports_tabClicked('referrals',false);
       return;
     }
   if (page == 'First' && CurrentPage != 1)
     {
       CurrentPage=1;
       reports_getReport('referrals');
       return;
     }
   if (page == 'Prev' && CurrentPage > 1)
     {
       CurrentPage--;
       reports_getReport('referrals');
       return;
     }
   if (page == 'Next' && CurrentPage < TotalPages)
     {
       CurrentPage++;
       reports_getReport('referrals');
       return;
     }
   if (page == 'Last' && CurrentPage != TotalPages)
     {
       CurrentPage=TotalPages;
       reports_getReport('referrals');
       return;
     }
   if (page == 'Page')
     {
       var theForm=document.REPORTS_REFERRALS_FORM;
       var pg = parseInt(theForm.page_number.value);
       if (pg != CurrentPage && pg >= 1  && pg <= TotalPages)
         {
            CurrentPage=pg;
            reports_getReport('referrals');
            return;
         }
     }
 }


function referrals_DisplayPushyWebsite(url)
  {
    var wWidth  = 700;
    var wHeight = 550;

    var topmargin  = 0;
    var leftmargin = 0;

    // alert("URL="+product_url);

    var win=window.open(url,"PushyWebsite",
       'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
  }


function status_UpdatePaymentInfo(theForm)
  {
    if (!status_VerifyPaymentInfo(theForm))
      return false;

    var cc_holdername  = striplt(theForm.cc_holdername.value);

    var cc_number='';
    if (theForm.cc_number.value.startsWith('xxxx-'))
       cc_number=getDigits(theForm.hold_cc_number.value);
    else
       cc_number=getDigits(theForm.cc_number.value);

    var cc_expmmyyyy   = striplt(theForm.cc_expmmyyyy.value);
    var cc_address     = striplt(theForm.cc_address.value);
    var cc_zip         = striplt(theForm.cc_zip.value);
    var cc_cvv2        = striplt(theForm.cc_cvv2.value);

    var data    = {
                     sid:            sid,
                     mid:            mid,
                     tp:             'reports_payment_record_update',
                     cc_holdername:  cc_holdername,
                     cc_number:      cc_number,
                     cc_expmmyyyy:   cc_expmmyyyy,
                     cc_address:     cc_address,
                     cc_zip:         cc_zip,
                     cc_cvv2:        cc_cvv2
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
                       alert("Payment Information Updated");
                     }
                 }
    });
  }


function status_VerifyPaymentInfo(theForm)
  {
    theForm.cc_expmmyyyy.value    = striplt(theForm.cc_expmmyyyy.value);
    theForm.cc_holdername.value   = striplt(theForm.cc_holdername.value);
    theForm.cc_address.value      = striplt(theForm.cc_address.value);
    theForm.cc_zip.value          = striplt(theForm.cc_zip.value);
    theForm.cc_cvv2.value         = striplt(theForm.cc_cvv2.value);

//  if (theForm.cc_type.selectedIndex == 0)
//    {
//      alert("Please Indicate Which Credit Card You Are Using");
//      theForm.cc_type.focus();
//      return false;
//    }

    if (!theForm.cc_number.value.startsWith('xxxx-'))
      {
        theForm.cc_number.value = getDigits(theForm.cc_number.value);
        if (theForm.cc_number.value.length < 15  ||
            theForm.cc_number.value.length > 16)
          {
            alert("Please verify Credit Card Number\n(Expecting 15 or 16 Digits)");
            theForm.cc_number.focus();
            return false;
          }
        var firstDigit=theForm.cc_number.value.charAt(0);
        if (firstDigit != '3' &&
            firstDigit != '4' &&
            firstDigit != '5' &&
            firstDigit != '6' &&
            firstDigit != '9')
          {
            alert("Credit Card Number is Invalid - Please Re-Enter");
            theForm.cc_number.focus();
            return false;
          }
      }

    if (theForm.cc_expmmyyyy.value.length != 7 || (theForm.cc_expmmyyyy.value.substring(2,3)!='-'))
      {
        alert("Please enter expiration date as mm-yyyy");
        theForm.cc_expmmyyyy.focus();
        return false;
      }

    var expmm  =theForm.cc_expmmyyyy.value.substring(0,2);
    var expyyyy=theForm.cc_expmmyyyy.value.substring(3);

    if (!isNumeric(expmm) || !isNumeric(expyyyy))
      {
        alert("Please enter expiration date as mm-yyyy");
        theForm.cc_expmmyyyy.focus();
        return false;
      }

    var mm  = parseInt(expmm);
    var yyyy= parseInt(expyyyy);

    if (mm==0 || mm > 12)
      {
        alert("Expiration Month is invalid (01-12)");
        theForm.cc_expmmyyyy.focus();
        return false;
      }
    if (yyyy < 2009 || yyyy > 2019)
      {
        alert("Expiration Year is invalid");
        theForm.cc_expmmyyyy.focus();
        return false;
      }


    if (theForm.cc_holdername.value.length < 2)
      {
        alert("Please enter Credit Card Holder Name");
        theForm.cc_holdername.focus();
        return false;
      }
    if (theForm.cc_address.value.length < 2)
      {
        alert("Please enter Credit Card Holder Street Address");
        theForm.cc_address.focus();
        return false;
      }
    if (theForm.cc_zip.value.length < 3)
      {
        alert("Please enter Credit Card Holder Zip Code");
        theForm.cc_zip.focus();
        return false;
      }
    if (theForm.cc_cvv2.value.length < 3 || theForm.cc_cvv2.value.length > 4 || (!isNumeric(theForm.cc_cvv2.value)))
      {
        alert("Please enter the CVV2 code that is printed on your card");
        theForm.cc_cvv2.focus();
        return false;
      }

    return true;
  }

function ccNumberPressed(theObject)
  {
    if (theObject.value.startsWith('xxxx-'))
       theObject.value='';
  }


function traffic_week(op)
 {
   if (reportsBusy) return;

   if (op=="first")
     {
       if (traffic_current_week==1) return;
       traffic_current_week=1;
     }
   else
   if (op=="prev")
     {
       if (traffic_current_week==1) return;
       traffic_current_week--;
     }
   else
   if (op=="next")
     {
       if (traffic_current_week==5) return;
       traffic_current_week++;
     }
   else
   if (op=="last")
     {
       if (traffic_current_week==5) return;
       traffic_current_week=5;
     }

   traffic_getReport(traffic_current_week);
 }


function traffic_getReport(week)
 {
   var canvas            = document.getElementById("REPORT");

   var data = {
                tp:       "reports",
                mid:      mid,
                sid:      sid,
                report:   'traffic',
                week:     week
              }


   // dumpObject(data);

   reportsBusy=true;
   $.ajax({
      type:     "POST",
      url:      "ajax.php",
      data:     data,
      dataType: "json",
      cache:    false,
      error:    function (XMLHttpRequest, textStatus, errorThrown)
                {
                  reportsBusy=false;
                  // typically only one of textStatus or errorThrown will have info
                  var httpStatus=XMLHttpRequest.status;
                  if (httpStatus==401)
                    {
                      top.location.href="/index.php?SessionExpired";
                    }
                  else
                    {
                      canvas.innerHTML="Request Failed - HTTP Status:"+ httpStatus;
                    }
                },
      success:  function(response, textStatus)
                {
                  reportsBusy=false;
                  if (typeof response == "object" && typeof response.status != "undefined")
                    {
                      var status=response.status;
                      if (status != 0)
                        {
                          alert( response.message );
                        }
                      else
                        {
                          var data = response.data;
                          var DisplayMonth=data.DisplayMonth;
                          traffic_current_week=data.Week;
                          canvas.innerHTML = data.html;
                        }
                    }
                  else
                    {
                      canvas.innerHTML = response;
                    }
                }
   });
 }

function reports_network_expand(id)
 {
   document.getElementById(id).style.display='';
 }

function reports_network_collapse(id)
 {
   document.getElementById(id).style.display='none';
 }




function sales_report_monthClicked(count,yymm,previous_yymm,current_yymm)
  {
    // alert("MONTH: Count="+count+"  yymm="+yymm+"  Previous="+previous_yymm+"  Current="+current_yymm);
    for (var i=0; i<24; i++)
      {
        var id = 'Month-'+i;
        var el =  document.getElementById(id);
        if (el)
          {
            if (i==count && (el.style.display == 'none'))
              el.style.display = '';
            else
            if (i==count && (el.style.display != 'none'))
              el.style.display = 'none';
            else
              el.style.display = 'none';
          }
        else
          break;
      }
  }


function sales_report_dailyClicked(count,yymm,previous_yymm,current_yymm)
  {
    /*- images -*/
    var idImageDaily  ="Image-Daily-"+count;
    var idImageSummary="Image-Summary-"+count;

    var imgDaily  =document.getElementById(idImageDaily);
    var imgSummary=document.getElementById(idImageSummary);


    /*- data -*/
    var idDaily   = "Daily-"+count;
    var idSummary = "Summary-"+count;

    var elDaily  =document.getElementById(idDaily);
    var elSummary=document.getElementById(idSummary);


    /*- flip -*/

    imgDaily.src  ="http://pds1106.s3.amazonaws.com/images/sales_daily_active.png";
    imgSummary.src="http://pds1106.s3.amazonaws.com/images/sales_summary_inactive.png";

    elSummary.style.display='none';
    elDaily.style.display='';

    // alert("DAILY: Count="+count+"  yymm="+yymm+"  Previous="+previous_yymm+"  Current="+current_yymm);
  }


function sales_report_summaryClicked(count,yymm,previous_yymm,current_yymm)
  {
    /*- images -*/
    var idImageDaily  ="Image-Daily-"+count;
    var idImageSummary="Image-Summary-"+count;

    var imgDaily  =document.getElementById(idImageDaily);
    var imgSummary=document.getElementById(idImageSummary);


    /*- data -*/
    var idDaily   = "Daily-"+count;
    var idSummary = "Summary-"+count;

    var elDaily  =document.getElementById(idDaily);
    var elSummary=document.getElementById(idSummary);


    /*- flip -*/

    imgDaily.src  ="http://pds1106.s3.amazonaws.com/images/sales_daily_inactive.png";
    imgSummary.src="http://pds1106.s3.amazonaws.com/images/sales_summary_active.png";

    elDaily.style.display='none';
    elSummary.style.display='';

    // alert("SUMMARY: Count="+count+"  yymm="+yymm+"  Previous="+previous_yymm+"  Current="+current_yymm);
  }


//---- Personal Widget Category Report Navigation -----------
var per_wcat_last=5;
function per_wcat_week(wk) {
  if (wk < 1 || wk > 5) return;
  if (wk != per_wcat_last)
    {
      per_wcat_last=wk;

      for (var w=1; w<=5; w++)
        {
          var id="PER_WCAT_"+w;
          var el=document.getElementById(id);
          if (el)
            {
              if (w==wk)
                el.style.display="";
              else
                el.style.display="none";
            }
        }
    }
}


//---- Referral Widget Category Report Navigation -----------
var ref_wcat_last=5;
function ref_wcat_week(wk) {
  if (wk < 1 || wk > 5) return;
  if (wk != ref_wcat_last)
    {
      ref_wcat_last=wk;

      for (var w=1; w<=5; w++)
        {
          var id="REF_WCAT_"+w;
          var el=document.getElementById(id);
          if (el)
            {
              if (w==wk)
                el.style.display="";
              else
                el.style.display="none";
            }
        }
    }
}


//---- Ad Category Report Navigation -----------
var acat_last=5;
function acat_week(wk) {
  if (wk < 1 || wk > 5) return;
  if (wk != acat_last)
    {
      acat_last=wk;

      for (var w=1; w<=5; w++)
        {
          var id="ACAT"+w;
          var el=document.getElementById(id);
          if (el)
            {
              if (w==wk)
                el.style.display="";
              else
                el.style.display="none";
            }
        }
    }
}


//---- Reports Tab Loaded -----------

function tab_reports_loaded(response) {
}


//---- Individual Reports Loaded -----------

function reports_referrals_loaded(response) {
}

function reports_network_loaded(response) {
}

function reports_credit_loaded(response) {
}

function reports_sales_loaded(response) {
}

function reports_status_loaded(response) {
}

function reports_traffic_loaded(response) {
  animatedcollapse.addDiv('traffic_pane', 'fade=9,speed=400,group=traffic_report')
  animatedcollapse.addDiv('affiliates_pane', 'fade=9,speed=400,group=traffic_report,persist=1,hide=1')
  animatedcollapse.addDiv('per_widget_categories_pane', 'fade=9,speed=400,group=traffic_report,persist=1,hide=1')
  animatedcollapse.addDiv('ref_widget_categories_pane', 'fade=9,speed=400,group=traffic_report,persist=1,hide=1')

  animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
     //$: Access to jQuery
     //divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
    //state: "block" or "none", depending on state
  }

  animatedcollapse.init()
}
