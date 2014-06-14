//   ---- UNSELECTED      SELECTED ---------------
//   "tab_home.png",      "tab_home_active.png"
//   "tab_profile.png",   "tab_profile_active.png"
//   "tab_myads.png",     "tab_myads_active.png"
//   "tab_pushy.png",     "tab_pushy_active.png"
//   "tab_mystuff.png",   "tab_mystuff_active.png"
//   "tab_reports.png",   "tab_reports_active.png"
//   "tab_support.png",   "tab_support_active.png"


var faq_image_base_names = ['', 'category1', 'category2', 'category3', 'category4'];

var mid='<?php echo $mid?>';
var sid='<?php echo $sid?>';

var faq_imageHost="http://pds1106.s3.amazonaws.com/images/";
var faq_image_selected={};
var faq_image_unselected={};
var faq_image_hover={};

if (document.images)
{
  for (var i=1; i<faq_image_base_names.length; i++)
    {
      var faq_basename = faq_image_base_names[i];
      faq_image_unselected[i] = faq_imageHost+'faq_'+faq_basename+'.png';
      faq_image_selected[i]   = faq_imageHost+'faq_'+faq_basename+'_active.png';
      faq_image_hover[i]      = faq_imageHost+'faq_'+faq_basename+'_hover.png';
    }
}


var faq_current=1;
function faq_tabClicked(faq_tab)
 {
   if (faq_tab==faq_current)
     {
       return;
     }

   var currentTabElement = document.getElementById('img-cat-'+faq_current);
   var newTabElement     = document.getElementById('img-cat-'+faq_tab);    //clicked

   var currentTabURL     = faq_image_unselected[faq_current];
   var newTabURL         = faq_image_selected[faq_tab];

   var canvas            = document.getElementById("FAQ_CATEGORY");

   var data = {
                tp:       "support_faq_category",
                mid:      mid,
                sid:      sid,
                category: faq_tab
              }

   faq_current=faq_tab;

   $.ajax({
      type:     "GET",
      url:      "ajax.php",
      cache:    false,
      data:     data,
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
                      canvas.innerHTML="Request Failed - HTTP Status:"+ httpStatus;
                    }
                },
      success:  function(response, textStatus)
                {
                  canvas.innerHTML = response;
                }
   });


   newTabElement.src=newTabURL;
   currentTabElement.src=currentTabURL;
 }

function faq_over(faq_tab)
 {
   if (faq_tab==faq_current)
     {
       return;
     }

   var tabElement = document.getElementById('img-cat-'+faq_tab);
   var tabURL     = faq_image_hover[faq_tab];
   tabElement.src = tabURL;
 }

function faq_out(faq_tab)
 {
   if (faq_tab==faq_current)
     {
       return;
     }

   var tabElement = document.getElementById('img-cat-'+faq_tab);
   var tabURL     = faq_image_unselected[faq_tab];
   tabElement.src = tabURL;
 }

var last_question_category=0;
var last_question="";
function support_getAnswer(question)
 {
    if (question != last_question || faq_current != last_question_category)
      {
        last_question = question;
        last_question_category = faq_current;
        var content = { tp:       "support_faq_answer",
                        sid:       sid,
                        mid:       mid,
                        category:  faq_current,
                        question:  question
                      }

        $.ajax({
           type:     "GET",
           url:      "ajax.php",
           data:     content,
           cache:    false,
           error:    function (XMLHttpRequest, textStatus, errorThrown)
                     {
                       var httpStatus=XMLHttpRequest.status;
                       if (httpStatus==401)
                         {
                           top.location.href="/index.php?SessionExpired";
                         }
                       else
                         {
                           var el=document.getElementById("ANSWER");
                           if (el)
                              el.innerHTML="Request Failed - HTTP Status:"+ httpStatus;
                         }
                     },
           success:  function(response, textStatus)
                     {
                       var el=document.getElementById("ANSWER");
                       if (el)
                          el.innerHTML=response;
                       window.scroll(0,360);
                     }
        });
     }
 }


function support_submitServiceRequest(theForm)
  {
    if (!support_ServiceValidation(theForm))
       return;

    theForm.in_firstname.value = striplt(theForm.in_firstname.value);
    theForm.in_lastname.value  = striplt(theForm.in_lastname.value);
    theForm.in_email.value     = striplt(theForm.in_email.value);
    theForm.in_subject.value   = striplt(theForm.in_subject.value);
    theForm.in_message.value   = striplt(theForm.in_message.value);

    var data    = {
                     tp:             "support_service_message",
                     sid:            theForm.sid.value,
                     mid:            theForm.mid.value,
                     service_id:     theForm.service_id.value,
                     firstname:      theForm.in_firstname.value,
                     lastname:       theForm.in_lastname.value,
                     email:          theForm.in_email.value,
                     subject:        theForm.in_subject.value,
                     message:        theForm.in_message.value
                   }


    // dumpObject(data);

    $.ajax({
       type:     "POST",
       url:      "ajax.php",
       data:     data,
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
                   var el=document.getElementById("ServiceMessage");
                   if (el)
                     el.innerHTML=response;
                 }
    });

    return (true);
  }


function support_ServiceValidation(theForm)
  {
    theForm.in_firstname.value = striplt(theForm.in_firstname.value);
    theForm.in_lastname.value  = striplt(theForm.in_lastname.value);
    theForm.in_email.value     = striplt(theForm.in_email.value);
    theForm.in_subject.value   = striplt(theForm.in_subject.value);
    theForm.in_message.value   = striplt(theForm.in_message.value);

    var filter=/^.+@.+\..{2,4}$/
    if (theForm.in_firstname.value.length == 0)
     {
       alert("Please enter your First Name.");
       theForm.in_firstname.focus();
       return (false);
     }

    if (theForm.in_lastname.value.length == 0)
     {
       alert("Please enter your Last Name.");
       theForm.in_lastname.focus();
       return (false);
     }

    if (theForm.in_email.value.length == 0)
     {
       alert("Please enter your Email Address.");
       theForm.in_email.focus();
       return (false);
     }

    if ((!filter.test(theForm.in_email.value)))
     {
       alert("Email Address invalid: Please Re-enter your Email Address.");
       theForm.in_email.focus();
       return (false);
     }

    if (theForm.in_subject.value.length==0)
      {
        alert("Please provide a descriptive Subject");
        theForm.in_subject.focus();
        return (false);
      }

    if (theForm.in_message.value.length < 5)
      {
        alert("Please enter the message you would like to submit.");
        theForm.in_message.focus();
        return (false);
      }

    return (true);
  }

function tab_support_loaded(response)
  {

  }
