var pushy_properties =
 {
   origin:      true,
   motion:      true,
   transition:  true,
   speed:       true,
   wiggle:      true,
   delay:       true,
   pause:       true
 }


function pushy_widgetClicked(inx,widget_key)
 {
   var open=0;
   var total=0;

   if (inx==0) widget_key='none';  // Create New

   for (var i=0; i<999; i++)
     {
       var button_id = 'widget-button-'+i;
       var button_el =  document.getElementById(button_id);
       if (button_el)
         {
           if (i==inx && (button_el.value == 'OPEN'))
             {
               pushy_properties.origin      = true;
               pushy_properties.motion      = true;
               pushy_properties.transition  = true;
               pushy_properties.speed       = true;
               pushy_properties.wiggle      = true;
               pushy_properties.delay       = true;
               pushy_properties.pause       = true;

               button_el.value='CLOSE';          // Open It
               var widget_id = "WidgetDetail-"+i;
               // alert("ID="+widget_id);
               var widget_el = document.getElementById(widget_id);
               pushy_fetchDetail(i,widget_key,widget_el,button_el);
             }
           else
           if (i==inx && (button_el.value == 'CLOSE'))
             {
               button_el.value='OPEN';           // Close it
               var widget_id = "WidgetDetail-"+i;
               var widget_el = document.getElementById(widget_id);
               if (widget_el) widget_el.style.display='none';
             }
           else
             {                                 // everything else should be closed;
               button_el.value='OPEN';
               var widget_id = "WidgetDetail-"+i;
               var widget_el = document.getElementById(widget_id);
               if (widget_el) widget_el.style.display='none';
             }
         }
       else
         break;
     }
 }


/**-- Fetch Widget Detail --**/
function pushy_fetchDetail(inx,widget_key,widget_el,button_el)
 {
   if (widget_el)
     {
        pushy_isBusy=true;
        var detailElement=document.getElementById("PushyDetail-"+inx);
        detailElement.innerHTML = '<br> <img src="http://pds1106.s3.amazonaws.com/images/busy_6.gif" width=20><span class="arial size18">&nbsp;&nbsp;Loading ...</span><br> <br>';
        widget_el.style.display='';

        var data    = {
                        tp:          'pushy_get_widget_detail',
                        mid:         mid,
                        sid:         sid,
                        pushy_index: inx,
                        widget_key:  widget_key
                      }

        $.ajax({
           type:     "GET",
           url:      "ajax.php",
           cache:    false,
           data:     data,
           async:    false,
           error:    function (XMLHttpRequest, textStatus, errorThrown)
                     {
                       pushy_isBusy=false;
                       // typically only one of textStatus or errorThrown will have info
                       var httpStatus=XMLHttpRequest.status;

                       if (httpStatus==401)
                         {
                           top.location.href="/index.php?SessionExpired";
                         }
                       else
                         {
                           detailElement.innerHTML="Request Failed - HTTP Status:"+ httpStatus;
                         }
                     },
           success:  function(response, textStatus)
                     {
                       pushy_isBusy=false;
                       detailElement.innerHTML = response;
                     }
        });
     }
 }



pushy_isBusy=false;
function pushy_createWidget(theForm)
 {
    if (pushy_isBusy) return;
    pushy_busy(true);

    var mid             = theForm.mid.value;
    var sid             = theForm.sid.value;

    theForm.widget_name.value = striplt(theForm.widget_name.value);
    if (theForm.widget_name.value.length == 0)
      {
        alert("Please enter a unique name for Your Pushy Widget.");
        theForm.widget_domain.focus();
        pushy_busy(false);
        return (false);
      }

    if (!isAlphanumeric(theForm.widget_name.value, " "))
      {
        alert("Pushy Names may contain only Alphabetic (a-z), Numeric(0-9) characters or a blank. ");
        theForm.widget_name.focus();
        pushy_busy(false);
        return (false);
      }

    // alert("widget_name="+theForm.widget_name.value);
    var exists=false;
    for (var i=1; i<999; i++)
      {
        var el =  document.getElementById('pushy-widget-name-'+i);
        if (el)
          {
            var wname=striplt(el.innerHTML);
            // alert("wname="+wname);
            if (theForm.widget_name.value == wname)
              {
                alert("You already have a Pushy Widget with this Name\nPlease select another name for this Pushy Widget.");
                theForm.widget_name.focus();
                pushy_busy(false);
                return (false);
              }
          }
        else
          break;
      }

    var domain = striplt(theForm.widget_domain.value).toLowerCase();
    if (domain.length >= 7 && domain.startsWith("http://"))
       domain = domain.substring(7);
    theForm.widget_domain.value = domain;
    if (domain.length == 0)
      {
        alert("Please enter the 2-Part Host Domain Name (e.g. 'mywebsite.com')");
        theForm.widget_domain.focus();
        pushy_busy(false);
        return (false);
      }

    var tokens = domain.split('.');
    if (tokens.length > 3 || domain.indexOf(":")>=0 || domain.indexOf("/")>=0)
      {
        var msg  = "Domain Name Invalid - Please enter the 2-Part Host Domain Name (e.g. 'mywebsite.com')\n";
            msg += "Note: You do not need to specify the Subdomain (e.g. 'www')\n";
        alert(msg);
        theForm.widget_domain.focus();
        pushy_busy(false);
        return (false);
      }


    var product_categories="";
    var numCategoriesSelected=0;
    var len = theForm.widget_categories.length;
    for (var i=0; i<len; i++)
      {
        if (theForm.widget_categories[i].selected)
          {
            product_categories += theForm.widget_categories[i].value+"~";
            numCategoriesSelected++;
          }
      }

    if (numCategoriesSelected == 0 || numCategoriesSelected > 2)
      {
        alert("Please Select Either One or Two Product Categories\ndescribing your audience's interest. ");
        theForm.widget_categories.focus();
        pushy_busy(false);
        return false;
      }
    product_categories = "~"+product_categories;

    if (theForm.widget_size.selectedIndex == 0)
      {
        alert("Please select a Size for your Pushy Widget.");
        theForm.widget_size.focus();
        pushy_busy(false);
        return (false);
      }

    if (theForm.widget_posture.selectedIndex == 0)
      {
        alert("Please make a Posture selection for your Pushy Widget.");
        theForm.widget_posture.focus();
        pushy_busy(false);
        return (false);
      }

    if ((pushy_properties.origin) && theForm.widget_origin.selectedIndex == 0)
      {
        alert("Please Select a Home Location for your Pushy Widget.");
        theForm.widget_origin.focus();
        pushy_busy(false);
        return (false);
      }


    if ((pushy_properties.motion) && theForm.widget_motion.selectedIndex == 0)
      {
        alert("Please make a Motion selection for your Pushy Widget.");
        theForm.widget_motion.focus();
        pushy_busy(false);
        return (false);
      }

    if ((pushy_properties.transition) && theForm.widget_transition.selectedIndex == 0)
      {
        alert("Please make a Transition selection for your Pushy Widget.");
        theForm.widget_transition.focus();
        pushy_busy(false);
        return (false);
      }

    var data    = {
                     tp:                  'pushy_update_widget',
                     sid:                 sid,
                     mid:                 mid,
                     operation:           'create',

                     widget_name:         theForm.widget_name.value,
                     widget_domain:       theForm.widget_domain.value,
                     widget_categories:   product_categories,
                     widget_size:         theForm.widget_size.value,
                     widget_posture:      theForm.widget_posture.value,
                     widget_origin:       theForm.widget_origin.value,
                     widget_motion:       theForm.widget_motion.value,
                     widget_transition:   theForm.widget_transition.value,
                     widget_speed:        theForm.widget_speed.value,
                     widget_wiggle:       theForm.widget_wiggle.value,
                     widget_delay:        theForm.widget_delay.value,
                     widget_pause:        theForm.widget_pause.value
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
                   pushy_busy(false);
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
                       pushy_busy(false);
                     }
                   else
                     {
                       var widget_name=response.data.widget_name;
                       var widget_key=response.data.widget_key;

                       tabClicked("pushy", true, function() { pushy_WidgetCreated(widget_name,widget_key); } );
                     }
                 }
    });
  }


function pushy_WidgetCreated(widget_name,widget_key)
  {
    for (var inx=1; inx<999; inx++)
      {
        var el = document.getElementById('pushy-widget-name-'+inx);
        if (el)
          {
            var wname=striplt(el.innerHTML);
            if (wname == widget_name)
              {
                pushy_widgetClicked(inx, widget_key);

                var y = 200 + (50 * (inx+1));

                // var y1=document.body.scrollHeight;
                // var y2=posTop();
                // var y3=posBottom();
                // var y4=pageHeight();

                // var f = function() { window.scroll(0,y); alert("Pushy Widget Created: "+widget_name+ " Y="+y+" Y1="+y1+" Y2="+y2+" Y3="+y3+" Y4="+y4); }
                var f = function() { window.scroll(0,y); alert("Pushy Widget Created: "+widget_name); }
                setTimeout(f,5);
                pushy_busy(false);
                return;
              }
          }
        else
          break;
      }
  }


function pushy_busy(bool)
  {
    if (bool) // is busy
      {
        pushy_isBusy=true;
        var btn =  document.getElementById('Button_Get_Pushy');
        var img =  document.getElementById('Get_Pushy_Busy');
        if (btn && (img))
          {
            btn.style.display='none';
            img.style.display='';
          }
      }
    else
      {
        var btn =  document.getElementById('Button_Get_Pushy');
        var img =  document.getElementById('Get_Pushy_Busy');
        if (btn && (img))
          {
            btn.style.display='';
            img.style.display='none';
          }
        pushy_isBusy=false;
      }
  }

function pushy_updateWidget(theForm)
 {
    var mid             = theForm.mid.value;
    var sid             = theForm.sid.value;
    var widget_key      = theForm.widget_key.value;

//  ShowFormVariables(theForm);
//  return;

    var product_categories="";
    var numCategoriesSelected=0;
    var len = theForm.widget_categories.length;
    for (var i=0; i<len; i++)
      {
        if (theForm.widget_categories[i].selected)
          {
            product_categories += theForm.widget_categories[i].value+"~";
            numCategoriesSelected++;
          }
      }

    if (numCategoriesSelected == 0 || numCategoriesSelected > 2)
      {
        alert("Please Select Either One or Two Product Categories\ndescribing your audience's interest. ");
        theForm.widget_categories.focus();
        return false;
      }
    product_categories = "~"+product_categories;

    var data    = {
                     tp:                 "pushy_update_widget",
                     sid:                sid,
                     mid:                mid,
                     operation:          'update',
                     widget_key:         widget_key,

                     widget_categories:  product_categories,
                     widget_size:        theForm.widget_size.value,
                     widget_posture:     theForm.widget_posture.value,
                     widget_origin:       theForm.widget_origin.value,
                     widget_motion:      theForm.widget_motion.value,
                     widget_transition:  theForm.widget_transition.value,
                     widget_speed:       theForm.widget_speed.value,
                     widget_wiggle:      theForm.widget_wiggle.value,
                     widget_delay:       theForm.widget_delay.value,
                     widget_pause:       theForm.widget_pause.value
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
                       alert( response.message );
                     }
                   else
                     {
                       var last_modified=response.data.date_last_modified;
                       var el = document.getElementById('LAST_MODIFIED_'+widget_key);
                       if (el) el.innerHTML=last_modified;
                       alert("Pushy Widget Updated");
                     }
                 }
    });
  }


function pushy_removeWidget(theForm)
 {

    var widget_key=theForm.widget_key.value;
    var widget_name=theForm.widget_name.value;

    var msg  = "You have asked to remove Pushy Widget: "+widget_name+" \n";
        msg += "\n";
        msg += "You will need to make sure that there are no pages on your \n";
        msg += "server that contain scripts referencing this Pushy widget  \n";
        msg += "as the widget will no longer work once it is removed. \n";
        msg += "\n";
        msg += "Please Confirm that you wish to remove this Pushy Widget. \n";
        msg += "\n";

    var resp=confirm(msg);
    if (!resp) return;

    var data = {
                 tp:            "pushy_remove_widget",
                 mid:           theForm.mid.value,
                 sid:           theForm.sid.value,
                 widget_name:   widget_name,
                 widget_key:    widget_key
               }

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
                   tabClicked("pushy", true);
                 }
   });
 }



function pushy_posture_changed(theForm,inx)
  {
    // ShowFormVariables(theForm);
    if (theForm.widget_posture.value == '1')   // Hover - HOME is relevant - Show It
      {
        /*-- IN --*/
        var el=document.getElementById('PUSHY_WIDGET_ORIGIN_'+inx);
        if (el) el.style.display='';
        pushy_properties.origin=true;


        /*-- OUT --*/
        var el=document.getElementById('PUSHY_WIDGET_MOTION_'+inx);
        if (el) el.style.display='none';
        pushy_properties.motion=false;

        var el=document.getElementById('PUSHY_WIDGET_SPEED_'+inx);
        if (el) el.style.display='none';
        pushy_properties.speed=false;

        var el=document.getElementById('PUSHY_WIDGET_PAUSE_'+inx);
        if (el) el.style.display='none';
        pushy_properties.pause=false;
      }
    else
      {                                                           // Not Hover - Not Relevant
        /*-- IN --*/
        var el=document.getElementById('PUSHY_WIDGET_MOTION_'+inx);
        if (el) el.style.display='';
        pushy_properties.motion=true;

        var el=document.getElementById('PUSHY_WIDGET_SPEED_'+inx);
        if (el) el.style.display='';
        pushy_properties.speed=true;

        var el=document.getElementById('PUSHY_WIDGET_PAUSE_'+inx);
        if (el) el.style.display='';
        pushy_properties.pause=true;

        /*-- OUT --*/
        var el=document.getElementById('PUSHY_WIDGET_ORIGIN_'+inx);
        if (el) el.style.display='none';
        pushy_properties.origin=false;

      }
  }


function pushy_motion_changed(theForm,inx)
  {
    if (theForm.widget_motion.value == '0')   // No Motion - Speed and Pause are N/A - Hide Them
      {
        var el=document.getElementById('PUSHY_WIDGET_SPEED_'+inx);
        if (el) el.style.display='none';
        pushy_properties.speed=false;

        var el=document.getElementById('PUSHY_WIDGET_PAUSE_'+inx);
        if (el) el.style.display='none';
        pushy_properties.pause=false;

      }
    else
      {                                                           // else - Show Them
        var el=document.getElementById('PUSHY_WIDGET_SPEED_'+inx);
        if (el) el.style.display='';
        pushy_properties.speed=true;

        var el=document.getElementById('PUSHY_WIDGET_PAUSE_'+inx);
        if (el) el.style.display='';
        pushy_properties.pause=true;
      }
  }


function pushy_transition_changed(theForm,inx)
  {
  }


var newWindow = null;
function pushy_getTheCode(theForm)
 {
//  var theForm    = document.PushyForm;
    var mid        = theForm.mid.value;
    var sid        = theForm.sid.value;
    var widget_key = theForm.widget_key.value;


    var wWidth  = 780;
    var wHeight = 500;

    var topmargin  = 0;
    var leftmargin = 0;

    var url = "/members/link.php?tp=pushy_get_code&mid="+mid+"&sid="+sid+"&key="+widget_key;

//  alert("url="+url);
//  return;

    if (!newWindow || newWindow.closed)
      {
         newWindow = window.open(url,"WidgetCode",
            'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
         // ',scrollbars=yes,location=yes,directories=no,status=no,menubar=yes,toolbar=yes,resizable=yes');

            ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');

        var f = function() { newWindow.focus(); }
        setTimeout(f,400);
      }
    else
      {
        var f = function() { newWindow.focus(); }
        setTimeout(f,10);
      }

 }

function tab_pushy_loaded(response)
  {
    if (response.status==0 && (response.data))
      {
        var existingWidgets = response.data.ExistingWidgets;
        if (existingWidgets==0)
           pushy_widgetClicked(0,'');
      }
  }



/*----- DIALOGS -----*/

function pushy_preview(theForm)
 {
    var mid             = theForm.mid.value;
    var sid             = theForm.sid.value;
    var requireAllFields=false;  // certain fields .. name, category, domain not required for simulator ... ?

    if (requireAllFields)
      {
        theForm.widget_name.value = striplt(theForm.widget_name.value);
        if (theForm.widget_name.value.length == 0)
          {
            alert("Please enter a unique name for Your Pushy Widget.");
            theForm.widget_domain.focus();
            pushy_busy(false);
            return (false);
          }

        if (!isAlphanumeric(theForm.widget_name.value, " "))
          {
            alert("Pushy Names may contain only Alphabetic (a-z), Numeric(0-9) characters or a blank. ");
            theForm.widget_name.focus();
            pushy_busy(false);
            return (false);
          }

        // alert("widget_name="+theForm.widget_name.value);
        var exists=false;
        for (var i=1; i<999; i++)
          {
            var el =  document.getElementById('pushy-widget-name-'+i);
            if (el)
              {
                var wname=striplt(el.innerHTML);
                // alert("wname="+wname);
                if (theForm.widget_name.value == wname)
                  {
                    alert("You already have a Pushy Widget with this Name\nPlease select another name for this Pushy Widget.");
                    theForm.widget_name.focus();
                    pushy_busy(false);
                    return (false);
                  }
              }
            else
              break;
          }

        var domain = striplt(theForm.widget_domain.value).toLowerCase();
        if (domain.length >= 7 && domain.startsWith("http://"))
           domain = domain.substring(7);
        theForm.widget_domain.value = domain;
        if (domain.length == 0)
          {
            alert("Please enter the 2-Part Host Domain Name (e.g. 'mywebsite.com')");
            theForm.widget_domain.focus();
            pushy_busy(false);
            return (false);
          }

        var tokens = domain.split('.');
        if (tokens.length > 3 || domain.indexOf(":")>=0 || domain.indexOf("/")>=0)
          {
            var msg  = "Domain Name Invalid - Please enter the 2-Part Host Domain Name (e.g. 'mywebsite.com')\n";
                msg += "Note: You do not need to specify the Subdomain (e.g. 'www')\n";
            alert(msg);
            theForm.widget_domain.focus();
            pushy_busy(false);
            return (false);
          }


        if (theForm.widget_domain.value.length == 0)
          {
            alert("Please enter the Host Domain Name.");
            theForm.widget_domain.focus();
            pushy_busy(false);
            return (false);
          }

        var product_categories="";
        var numCategoriesSelected=0;
        var len = theForm.widget_categories.length;
        for (var i=0; i<len; i++)
          {
            if (theForm.widget_categories[i].selected)
              {
                product_categories += theForm.widget_categories[i].value+"~";
                numCategoriesSelected++;
              }
          }

        if (numCategoriesSelected == 0 || numCategoriesSelected > 2)
          {
            alert("Please Select Either One or Two Product Categories\ndescribing your audience's interest. ");
            theForm.widget_categories.focus();
            pushy_busy(false);
            return false;
          }
        product_categories = "~"+product_categories;

      }

    if (theForm.option.value=="Create")
      {
        if (theForm.widget_size.selectedIndex == 0)
          {
            alert("Please select a Size for your Pushy Widget.");
            theForm.widget_size.focus();
            pushy_busy(false);
            return (false);
          }

        if (theForm.widget_posture.selectedIndex == 0)
          {
            alert("Please make a Posture selection for your Pushy Widget.");
            theForm.widget_posture.focus();
            pushy_busy(false);
            return (false);
          }


        if ((pushy_properties.origin) && theForm.widget_origin.selectedIndex == 0)
          {
            alert("Please Select a Home Location for your Pushy Widget.");
            theForm.widget_origin.focus();
            pushy_busy(false);
            return (false);
          }


        if ((pushy_properties.motion) && theForm.widget_motion.selectedIndex == 0)
          {
            alert("Please make a Motion selection for your Pushy Widget.");
            theForm.widget_motion.focus();
            pushy_busy(false);
            return (false);
          }

        if ((pushy_properties.transition) && theForm.widget_transition.selectedIndex == 0)
          {
            alert("Please make a Transition selection for your Pushy Widget.");
            theForm.widget_transition.focus();
            pushy_busy(false);
            return (false);
          }
      }


    var frameWidth  = 950;
    var frameHeight = 700;

    var url  = '/dialog/preview/start_preview.php?';
        url += 'mid='+mid+'&';
        url += 'sid='+sid+'&';
        url += 'frameWidth='+frameWidth+'&';
        url += 'frameHeight='+frameHeight+'&';
        url += 'pst='+theForm.widget_posture.value+'&';
        url += 'mtn='+theForm.widget_motion.value+'&';
        url += 'org='+theForm.widget_origin.value+'&';
        url += 'trn='+theForm.widget_transition.value+'&';
        url += 'wth='+theForm.widget_size.value+'&';
        url += 'spd='+theForm.widget_speed.value+'&';
        url += 'wig='+theForm.widget_wiggle.value+'&';
        url += 'dly='+theForm.widget_delay.value+'&';
        url += 'pau='+theForm.widget_pause.value;

    showPopWin('Pushy Preview', url, frameWidth, frameHeight, pushy_preview_Response, true, false);
 }


function pushy_preview_Response(returnVal)
 {
    if (returnVal)
      {
        // var msg  = "Function Name : "+returnVal.functionName+"\n";
        //     msg += "Signin ID     : "+returnVal.signin_id+"\n";
        //     msg += "Password      : "+returnVal.password+"\n";
        // alert(msg);
      }
    else
      {
        // alert("onResponse - No Values");
      }
 }

// alert("loaded");
