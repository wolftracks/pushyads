var _pushy_={};

if (!_PUSHY_CONFIG.nostart)
  {   // Start Up pushy when loaded unless the nostart option has been specified
    pushy_initialize();
  }


function _get_pushy_clicked_()
  {
       // var url = "http://pushyads.com/success";
    var url = _PUSHY_CONFIG.affiliate_website;
    _pushy_open_affiliate_page_(url);
  }

function _pushy_open_affiliate_page_(url)
  {
    var top   =0;
    var left  =0;
    var width =650;
    var height=600;
    var args='width='+width+',height='+height+',top='+top+',left='+left+
             ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
    window.open(url,"PushyAds",args);
  }


function pushy_setUserConfiguration()
  {
     _PUSHY_CONFIG.docType = {};
     _PUSHY_CONFIG.docType.isPresent = false;
     _PUSHY_CONFIG.docType.language  = "HTML"; //default

     var pid,sid,val,lng,ver,typ;
     if (document.doctype == null || (document.doctype.publicId == null)  ||
        (typeof document.doctype == "undefined") || (typeof document.doctype.publicId == "undefined") )
       {
          // nothing to do
       }
     else
       {
          try {
            pid=String(document.doctype.publicId);
            sid=String(document.doctype.systemId);
          }
          catch(err) {
            val=String(document.body.parentNode.parentNode.firstChild.nodeValue);
            pid=val.replace(/^[^\"]*\"([^\"]+)\".*/,"$1");
            sid=((/http/).test(val))?val.replace(/^.*\"\s\"(http.*)/,"$1"):null;
          }
          lng=((/xhtml/i).test(pid+sid))?"XHTML":"HTML";
          typ=((/strict/i).test(pid+sid))?"Strict":((/trans|loose/i).test(pid+sid))?"Transitional":((/frame/i).test(pid+sid))?"Frameset":((/basic/i).test(pid+sid))?"Basic":((/mobile/i).test(pid+sid))?"Mobile":null;
          ver=((/html\s*\d+\.?\d*/i).test(pid))?pid.replace(/^.*html\s*(\d+\.?\d*)\D*/i,"$1"):null;

          // return {publicId:pid, systemId:sid, language:lng, type:typ, version:ver, set:lng+" "+typ+" "+ver}

          _PUSHY_CONFIG.docType.isPresent = true;
          _PUSHY_CONFIG.docType.pid       = pid;
          _PUSHY_CONFIG.docType.systemId  = sid;
          _PUSHY_CONFIG.docType.language  = lng;
          _PUSHY_CONFIG.docType.type      = typ;
          _PUSHY_CONFIG.docType.version   = ver;
          _PUSHY_CONFIG.docType.set       = lng+" "+typ+" "+ver;
       }

     //----------- Browser Info
     var version=parseInt(navigator.appVersion);
     if (navigator.appVersion.indexOf('5.')>-1){version=5};
     if (navigator.appVersion.indexOf('6.')>-1){version=6};
     if (navigator.appVersion.indexOf('7.')>-1){version=7};
     var browser='OTHER';
     if (navigator.appName=='Netscape') {browser='NS'+version;}
     if (navigator.appName=='Microsoft Internet Explorer') {browser='MSIE'+version;}
     if (navigator.appVersion.indexOf('MSIE 3')>0) {browser='MSIE3';}
     if (browser == 'NS5') {browser='NS6'};

     _PUSHY_CONFIG.browser           = {}
     _PUSHY_CONFIG.browser.name      = browser;
     _PUSHY_CONFIG.browser.version   = version;
     _PUSHY_CONFIG.browser.is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
     _PUSHY_CONFIG.browser.is_ns     = (navigator.appName=='Netscape');
     _PUSHY_CONFIG.browser.is_ff     = (_PUSHY_CONFIG.browser.is_ns && (!_PUSHY_CONFIG.browser.is_chrome));
     _PUSHY_CONFIG.browser.is_ie     = (navigator.appName=='Microsoft Internet Explorer');
     _PUSHY_CONFIG.browser.is_ie6    = ((_PUSHY_CONFIG.browser.is_ie) && version=='6');
     _PUSHY_CONFIG.browser.is_ie7    = ((_PUSHY_CONFIG.browser.is_ie) && version=='7');
     _PUSHY_CONFIG.browser.is_ie8    = ((_PUSHY_CONFIG.browser.is_ie) && version=='8');
  }


function pushy_initialize()
  {
    if (_pushy_.initialized) // Already initialized - don't do it again!
      return;
    _pushy_.initialized=true;

    pushy_setUserConfiguration();

    //---------------------------------------------------
    _pushy_.wiggle_repeats  = -1;       // Need to be configured dynamically ??  ... -1 = continuous
    //---------------------------------------------------

    _pushy_.wiggle_interval = _PUSHY_CONFIG.wiggle_interval;      // interval between wiggles in seconds   = 0=No Wiggle
    _pushy_.wiggle_interval *= 1000;                              // in milliseconds
    _pushy_.wiggle_paused    = false;

    if (arguments.length > 0)
      {
        var s = arguments[0];
        if (pushy_type_of(s)=="string")
          _pushy_.argument=s;
      }

    _pushy_.delay=_PUSHY_CONFIG.WidgetDelay;    // in seconds
    _pushy_.delay *= 1000;                      // in milliseconds
    _pushy_.pause=_PUSHY_CONFIG.WidgetPause;    // in seconds
    _pushy_.pause *= 1000;                      // in milliseconds

    _pushy_.env = new Object();
    _pushy_.env.widget_posture_configuration    = _PUSHY_CONFIG.WidgetPosture;
    _pushy_.env.widget_motion_configuration     = _PUSHY_CONFIG.WidgetMotion;
    _pushy_.env.widget_transition_configuration = _PUSHY_CONFIG.WidgetTransition;
    _pushy_.env.widget_origin_configuration     = _PUSHY_CONFIG.WidgetOrigin;
    _pushy_.env.widget_speed_configuration      = _PUSHY_CONFIG.WidgetSpeed;

    _pushy_.env.logging_enabled                 = true;
    _pushy_.env.log_message_count               = 0;
    _pushy_.env.max_log_messages                = 200;

    if (_pushy_.env.widget_posture_configuration == 1) // Hover
      {
    //  _pushy_.delay = 0;
    //  _pushy_.pause = 0;
      }

    if (_pushy_.delay > 0)
      {
        var f = function() {pushy_init();};
        setTimeout (f, _pushy_.delay);
      }
    else
      {
        pushy_init();
      }
  }


function pushy_init()
  {
    var offsetLeft = 0;
    var offsetTop  = 0;
    if (_PUSHY_CONFIG.WidgetPosture == _PUSHY_CONFIG.WIDGET_POSTURE_HOVER)
      {                // PUSHY_HOME is defined by User "Origin"
        var mybody  = document.getElementsByTagName("body")[0];
        var mydiv   = document.createElement("div");
        pushy_home  = mydiv;
        //-- Do not Change -- pushy_home.style.position = "relative";
      }
    else
      {                // Pushy Must Have a  PUSHY_HOME  DIV Target on the Page
        pushy_home = document.getElementById("PUSHY_HOME");
        if (pushy_home)
          {
             //-- Do not Change -- pushy_home.style.position = "relative";
             var mybody  = document.getElementsByTagName("body")[0];
             var mydiv   = document.createElement("div");

             pushyPosition=pushy_findPos(pushy_home);
             if (pushyPosition)
               {
                 offsetLeft = pushyPosition[0];
                 offsetTop  = pushyPosition[1];
               }
          }
      }

    if (!pushy_home)
      {
        return;  // No HOME defined
      }

    pushy_home.style.width  = _PUSHY_CONFIG.width  + 'px';
    pushy_home.style.height = _PUSHY_CONFIG.height + 'px';

    var pos=pushy_home.style.position;
    var posChanged=false;

    var pushyHome = new Object();

    // alert("pos="+pos);

    if (pos == "absolute" ||
        pos == "relative")
      {
        // use as is
      }
    else
      {
         posChanged=true;
         pushy_home.style.position = "relative";
      }


    /*--------------------------------------------------------------*/
    /*  alert("PUSHY_HOME: Left:"+offsetLeft+"  Top:"+offsetTop);   */
    /*  return;                                                     */
    /*--------------------------------------------------------------*/

    pushyHome.top     = offsetTop;
    pushyHome.left    = offsetLeft;
    pushyHome.width   = pushy_home.offsetWidth;
    pushyHome.height  = pushy_home.offsetHeight;

    if (posChanged)
       pushy_home.style.position = pos;


    mydiv.setAttribute("id", _PUSHY_CONFIG.WidgetName);
    mydiv.style.position = "absolute";

    var mytable     = document.createElement("table");
    var mytablebody = document.createElement("tbody");

    mytable.setAttribute("border", "0");
    mytable.setAttribute("width" , _PUSHY_CONFIG.width);
    mytable.setAttribute("height", _PUSHY_CONFIG.height);
    mytable.setAttribute("cellPadding", 0);
    mytable.setAttribute("cellSpacing", 0);


    /*** Top --------------------------------------------------------------------------------------------------------------------***/
    var tmpRow   = document.createElement("tr");
    var tmpCell  = document.createElement("td");
    var tmpImage = document.createElement("img");
    tmpImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_TOP);
    tmpImage.setAttribute("border", "0");
    tmpImage.setAttribute("width",  _PUSHY_CONFIG.top_width);
    tmpImage.setAttribute("height", _PUSHY_CONFIG.top_height);

    tmpCell.setAttribute("colSpan","3");
    tmpCell.appendChild(tmpImage);
    tmpRow.appendChild(tmpCell);
    mytablebody.appendChild(tmpRow);

    /*** Middle ---------------------------------------------------------------------------------------------------------------------***/
    var tmpRow   = document.createElement("tr");

    var tmpCell  = document.createElement("td");
    var tmpImage = document.createElement("img");
    tmpImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_LEFT);
    tmpImage.setAttribute("border", "0");
    tmpImage.setAttribute("width",  _PUSHY_CONFIG.left_width);
    tmpImage.setAttribute("height", _PUSHY_CONFIG.left_height);

    tmpCell.appendChild(tmpImage);
    tmpRow.appendChild(tmpCell);

    var displayCell  = document.createElement("td");
    displayCell.setAttribute("width",   _PUSHY_CONFIG.client_width);
    displayCell.setAttribute("bgColor", "#FFFFFF");
    tmpRow.appendChild(displayCell);

    var tmpCell  = document.createElement("td");
    var tmpImage = document.createElement("img");
    tmpImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_RIGHT);
    tmpImage.setAttribute("border", "0");
    tmpImage.setAttribute("width",  _PUSHY_CONFIG.right_width);
    tmpImage.setAttribute("height", _PUSHY_CONFIG.right_height);

    tmpCell.appendChild(tmpImage);
    tmpRow.appendChild(tmpCell);

    mytablebody.appendChild(tmpRow);


    /*** Bottom -----------------------------------------------------------------------------------------------------------------***/
    var tmpRow   = document.createElement("tr");
    var tmpCell  = document.createElement("td");
    var tmpImage = document.createElement("img");

    tmpImage.setAttribute("border", "0");

       if (_PUSHY_CONFIG.GET_PUSHY_LINK)
         {
           /*------------------------------------------------------------------*/
           tmpImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_BOTTOM);
           tmpImage.style.cursor = "pointer";
           tmpImage.onclick = _get_pushy_clicked_;
           /*------------------------------------------------------------------*/
         }
       else
         {
           /*------------------------------------------------------------------*/
           tmpImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_BOTTOM_BLANK);
           /*------------------------------------------------------------------*/
         }

    tmpImage.setAttribute("width",  _PUSHY_CONFIG.bottom_width);
    tmpImage.setAttribute("height", _PUSHY_CONFIG.bottom_height);


    tmpCell.setAttribute("colSpan","3");
    tmpCell.appendChild(tmpImage);
    tmpRow.appendChild(tmpCell);
    mytablebody.appendChild(tmpRow);


    mytable.appendChild(mytablebody);

    /**-------------------------------------------------------------**/
    ifrm = document.createElement("IFRAME");
    ifrm.setAttribute("src", _PUSHY_CONFIG.DISPLAY_URL);
    ifrm.setAttribute("frameBorder", 0);
    ifrm.setAttribute("scrolling", "no");
    ifrm.style.width  = _PUSHY_CONFIG.ifrm_width  +"px";
    ifrm.style.height = _PUSHY_CONFIG.ifrm_height +"px";
    displayCell.appendChild(ifrm);
    /**-------------------------------------------------------------**/

    var shellImage = document.createElement("img");
    shellImage.setAttribute("id", "PUSHY_SHELL_IMAGE");
    shellImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_SHELL);
    shellImage.setAttribute("border", "0");
    shellImage.setAttribute("width",  _PUSHY_CONFIG.width);
    shellImage.setAttribute("height", _PUSHY_CONFIG.height);

    var shellImage8Bit = document.createElement("img");
    shellImage8Bit.setAttribute("id", "PUSHY_SHELL_IMAGE_8BIT");
    shellImage8Bit.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_SHELL_8BIT);
    shellImage8Bit.setAttribute("border", "0");
    shellImage8Bit.setAttribute("width",  _PUSHY_CONFIG.width);
    shellImage8Bit.setAttribute("height", _PUSHY_CONFIG.height);

    var spacerImage = document.createElement("img");
    spacerImage.setAttribute("id", "PUSHY_SPACER_IMAGE");
    spacerImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_SPACER);
    spacerImage.setAttribute("border", "0");
    spacerImage.setAttribute("width",  _PUSHY_CONFIG.width);
    spacerImage.setAttribute("height", _PUSHY_CONFIG.height);

    _pushy_.mybody         = mybody;
    _pushy_.mydiv          = mydiv;
    _pushy_.mytable        = mytable;
    _pushy_.shellImage     = shellImage;
    _pushy_.shellImage8Bit = shellImage8Bit;
    _pushy_.spacerImage    = spacerImage;
    _pushy_.pushy_home     = pushy_home;
    _pushy_.hover          = {x:0, y:0, cb:null};


    _pushy_.HoverVars     = {
                               mydiv:            null,
                               verticalOrigin:   '',
                               verticalOffset:   0,
                               horizontalOrigin: '',
                               horizontalOffset: 0,
                               transition:       0,
                               widget:           null,
                               pushyHome:        null,

                               block:            false,
                               originalWidth:    _PUSHY_CONFIG.width,
                               originalHeight:   _PUSHY_CONFIG.height,
                               ftlObj:           null,
                               width:            0,
                               height:           0,
                               hasLayers:        document.layers,
                               ns:               (navigator.appName.indexOf("Netscape") != -1),
                               startX:           3,
                               startY:           5,
                               hdelta:           4,
                               vdelta:           4,
                               LastX:           -1,
                               LastY:           -1
                            };



    // pushy_log(pushy_object_to_string(pushyHome));

    var widget = new Object();
    widget.top             = 0;
    widget.left            = 0;
    widget.width           = pushyHome.width;
    widget.height          = pushyHome.height;
    widget.loop            = 0;
    widget.recursing       = false;
    widget.vDistance       = 0;
    widget.hDistance       = 0;
    widget.trajectory      = 0;
    widget.direction       = 0;
    widget.speed           = 0;
    widget.bounceCount     = 0;
    widget.deflating       = false;
    widget.inflating       = false;
    widget.goHome          = false;
    widget.goHomeSignalled = false;

    widget.divChild=null;
    widget.bodyChild=null;


    pushy_run(pushyHome,widget);
  }



function pushy_run(pushyHome,widget)
  {
     // pushy_log("pushy_run");
        // Start with any motion - and chain:  motion->transition->posture
     pushy_motion(pushyHome,widget);
  }



function pushy_motion(pushyHome,widget)
  {
      // pushy_log("pushy_motion");

      //------------- MOTION --------------------

      if (_PUSHY_CONFIG.WidgetMotion == _PUSHY_CONFIG.WIDGET_MOTION_FLOAT)
        {
          var blackScreen = document.createElement("img");
          blackScreen.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_BLACK);
          blackScreen.setAttribute("border", "0");
          blackScreen.setAttribute("width",  _PUSHY_CONFIG.width);
          blackScreen.setAttribute("height", _PUSHY_CONFIG.height);

          widget.divChild  = blackScreen;
          _pushy_.mydiv.appendChild(widget.divChild);

          widget.bodyChild = _pushy_.mydiv;
          _pushy_.mybody.appendChild(widget.bodyChild);

          widget.image=blackScreen;
          pushy_motion_float(pushyHome,widget);     // links to pushy_transition()
        }
      else
      if (_PUSHY_CONFIG.WidgetMotion == _PUSHY_CONFIG.WIDGET_MOTION_BOUNCE)
        {
           var pushyGuyImage = document.createElement("img");
           pushyGuyImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_PUSHYGUY);
           pushyGuyImage.setAttribute("border", "0");
           pushyGuyImage.setAttribute("width",  _PUSHY_CONFIG.width);
           pushyGuyImage.setAttribute("height", _PUSHY_CONFIG.height);

           widget.divChild  = pushyGuyImage;
           _pushy_.mydiv.appendChild(widget.divChild);

           widget.bodyChild = _pushy_.mydiv;
           _pushy_.mybody.appendChild(widget.bodyChild);

           // pushy_log("Start: W="+_pushy_.mydiv.offsetWidth+"  H="+_pushy_.mydiv.offsetHeight);
           // pushy_log(pushy_object_to_string(pushyHome));


           widget.image=pushyGuyImage;
           pushy_motion_bounce(pushyHome,widget);     // links to pushy_transition()
        }
      else
        {
           pushy_transition(pushyHome,widget);
        }
  }




function pushy_transition(pushyHome,widget)
  {
      // pushy_log("pushy_transition");

      //------------- TRANSITION ----------------

       if (_PUSHY_CONFIG.WidgetTransition == _PUSHY_CONFIG.WIDGET_TRANSITION_ZOOM)
         {
            if (_pushy_.env.widget_posture_configuration == 0) //-- IF STATIC --  Transition is Deferred for Hover
              {
                 var w = parseInt(pushyHome.width  * .05);
                 var h = parseInt(pushyHome.height * .05);

                 widget.image  = _pushy_.shellImage;
                 widget.image.style.width    = w + 'px';
                 widget.image.style.height   = h + 'px';

                 _pushy_.mydiv.style.width   = w + 'px';
                 _pushy_.mydiv.style.height  = h + 'px';

                 _pushy_.mydiv.style.top     = pushyHome.top  + parseInt((pushyHome.height - h)/2) + 'px';
                 _pushy_.mydiv.style.left    = pushyHome.left + parseInt((pushyHome.width  - w)/2) + 'px';

                 if (widget.divChild)
                   {
                     if (widget.divChild != widget.image)
                       {
                          _pushy_.mydiv.removeChild(widget.divChild);
                          widget.divChild=null;
                       }
                   }

                 _pushy_.mydiv.appendChild(widget.image);
                 widget.divChild=widget.image;

                 if (!widget.bodyChild)
                   {
                     widget.bodyChild = _pushy_.mydiv;
                     _pushy_.mybody.appendChild(widget.bodyChild);
                   }

                 var f = function() {pushy_zoom_in(pushyHome,widget,true);};
                 setTimeout (f, 300);
              }
            else
              {
                 if (widget.divChild)
                   {
                     if (widget.divChild != widget.image)
                       {
                          _pushy_.mydiv.removeChild(widget.divChild);
                          widget.divChild=null;
                       }
                   }

                 if (!widget.bodyChild)
                   {
                     widget.bodyChild = _pushy_.mydiv;
                     _pushy_.mybody.appendChild(widget.bodyChild);
                   }

                 pushy_posture(pushyHome,widget);  // We've done all we can do to Prepare for Hover - Proceed to Posture
              }
         }
       else
       if (_PUSHY_CONFIG.WidgetTransition == _PUSHY_CONFIG.WIDGET_TRANSITION_MORPH)
         {
            if (_pushy_.env.widget_posture_configuration == 0) //-- IF STATIC --  Transition is Deferred for Hover
              {
                var morphImage = document.createElement("img");
                morphImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_MORPH);
                morphImage.setAttribute("border", "0");
                morphImage.setAttribute("width",  _PUSHY_CONFIG.width);
                morphImage.setAttribute("height", _PUSHY_CONFIG.height);

                widget.image  = morphImage;

                _pushy_.mydiv.style.width   = pushyHome.width    + 'px';
                _pushy_.mydiv.style.height  = pushyHome.height   + 'px';

                _pushy_.mydiv.style.top     = pushyHome.top      + 'px';
                _pushy_.mydiv.style.left    = pushyHome.left     + 'px';

                if (widget.divChild)
                  {
                    if (widget.divChild != widget.image)
                      {
                         _pushy_.mydiv.removeChild(widget.divChild);
                         widget.divChild=null;
                      }
                  }

                _pushy_.mydiv.appendChild(widget.image);
                widget.divChild=widget.image;

                if (!widget.bodyChild)
                  {
                    widget.bodyChild = _pushy_.mydiv;
                    _pushy_.mybody.appendChild(widget.bodyChild);
                  }

                var f = function() {pushy_posture(pushyHome,widget);};
                setTimeout (f, 1800);
              }
            else
              {
                if (widget.divChild)
                  {
                    if (widget.divChild != widget.image)
                      {
                         _pushy_.mydiv.removeChild(widget.divChild);
                         widget.divChild=null;
                      }
                  }

                if (!widget.bodyChild)
                  {
                    widget.bodyChild = _pushy_.mydiv;
                    _pushy_.mybody.appendChild(widget.bodyChild);
                  }

                pushy_posture(pushyHome,widget);  // We've done all we can do to Prepare for Hover - Proceed to Posture
              }
         }
       else
       if (_PUSHY_CONFIG.WidgetTransition == _PUSHY_CONFIG.WIDGET_TRANSITION_FADE)
         {
            if (_pushy_.env.widget_posture_configuration == 0) //-- IF STATIC --  Transition is Deferred for Hover
              {
                pushy_posture(pushyHome,widget);
              }
            else
              {
                pushy_posture(pushyHome,widget);
              }
         }
       else
         {   //----  DEFAULT : No Transition
            pushy_posture(pushyHome,widget);
         }
  }



function pushy_posture(pushyHome,widget)
  {
     // pushy_log("pushy_posture");

      //------------- POSTURE -------------------
     if (widget.divChild)
       {
         if (widget.divChild != _pushy_.mytable)
           {
              _pushy_.mydiv.removeChild(widget.divChild);
              widget.divChild=null;
           }
       }
     if (widget.bodyChild)
       {
          _pushy_.mybody.removeChild(widget.bodyChild);
          widget.bodyChild=null;
       }


      if (_PUSHY_CONFIG.WidgetPosture == _PUSHY_CONFIG.WIDGET_POSTURE_HOVER)
        {
           // _pushy_.mydiv.appendChild(_pushy_.mytable);
           // _pushy_.mybody.appendChild(_pushy_.mydiv);
           pushy_posture_hover(pushyHome,widget);
        }
      else
        {   // DEFAULT : STATIC
           if (_pushy_.env.widget_transition_configuration == 3) // FADE IN
             {
                var opacity=0;
                var millisec=1800;
                var from  = 0;
                var to    = 100;
                var image_id = "PUSHY_SHELL_IMAGE_8BIT";

                _pushy_.pushy_home.innerHTML="";
                var object = _pushy_.shellImage8Bit;
                _pushy_.mydiv.style.position = "static";
                _pushy_.mydiv.appendChild(object);
                _pushy_.pushy_home.appendChild(_pushy_.mydiv);

                object.style.opacity = (opacity / 100);
                object.style.MozOpacity = (opacity / 100);
                object.style.KhtmlOpacity = (opacity / 100);
                object.style.filter = "alpha(opacity=" + opacity + ")";

                pushy_fade(image_id,millisec,from,to,function() { _pushy_.mydiv.removeChild(object); _pushy_.mydiv.appendChild(_pushy_.mytable); pushy_wiggle(pushyHome,widget); });
             }
          else
             {
                _pushy_.pushy_home.innerHTML="";


                //-- changed - 11/13/09
                _pushy_.mydiv.style.position = "static";

                //-- changed - 11/13/09 -- interferes with POPUP Dialog
                // _pushy_.mydiv.style.position = "relative";
                // _pushy_.mydiv.style.top    = 0 + 'px';
                // _pushy_.mydiv.style.left   = 0 + 'px';
                // _pushy_.mydiv.style.width  = pushyHome.width  + 'px';
                // _pushy_.mydiv.style.height = pushyHome.height + 'px';
                //--

                _pushy_.mydiv.appendChild(_pushy_.mytable);
                _pushy_.pushy_home.appendChild(_pushy_.mydiv);
                pushy_wiggle(pushyHome,widget);
             }
        }
  }


function pushy_posture_hover(pushyHome,widget)
 {
   // pushy_log("pushy_posture_hover");

   var mybody        = _pushy_.mybody;
   var mydiv         = _pushy_.mydiv;
   var mytable       = _pushy_.mytable;
   var shellImage    = _pushy_.shellImage;
   var pushy_home    = _pushy_.pushy_home;

   // alert("Transition="+_pushy_.env.widget_transition_configuration);

   try {
         if (_pushy_.env.widget_transition_configuration > 0)
           {
             _pushy_.HoverVars.pushyHome = pushyHome;
             _pushy_.HoverVars.widget    = widget;
             _pushy_.hover.cb=pushy_hover_transition;
           }
         else
           {
             _pushy_.hover.cb=null;
           }

           if ( _PUSHY_CONFIG.WidgetOrigin == _PUSHY_CONFIG.WIDGET_ORIGIN_TOP_LEFT)
             {
   // pushy_log("pushy_posture_hover TopLeft");
                pushy_hover_div(mydiv,"fromtop",0,"fromleft",0, _pushy_.env.widget_transition_configuration);
                pushy_hover_wiggle();
             }
           else
           if ( _PUSHY_CONFIG.WidgetOrigin == _PUSHY_CONFIG.WIDGET_ORIGIN_BOTTOM_LEFT)
             {
   // pushy_log("pushy_posture_hover BottomLeft");
                pushy_hover_div(mydiv,"frombottom",0,"fromleft",0, _pushy_.env.widget_transition_configuration);
                pushy_hover_wiggle();
             }
           else
           if ( _PUSHY_CONFIG.WidgetOrigin == _PUSHY_CONFIG.WIDGET_ORIGIN_TOP_RIGHT)
             {
   // pushy_log("pushy_posture_hover TopRight");
                pushy_hover_div(mydiv,"fromtop",0,"fromright",0, _pushy_.env.widget_transition_configuration);
                pushy_hover_wiggle();
             }
           else
           if ( _PUSHY_CONFIG.WidgetOrigin == _PUSHY_CONFIG.WIDGET_ORIGIN_BOTTOM_RIGHT)
             {
   // pushy_log("pushy_posture_hover BottomRight");
                pushy_hover_div(mydiv,"frombottom",0,"fromright",0, _pushy_.env.widget_transition_configuration);
                pushy_hover_wiggle();
             }
       }
   catch (e)
       {

   // pushy_log("EXCEPTION ---- ");

       }

 }



function pushy_motion_float(pushyHome,widget)
 {
   var mybody        = _pushy_.mybody;
   var mydiv         = _pushy_.mydiv;
   var mytable       = _pushy_.mytable;
   var shellImage    = _pushy_.shellImage;
   var pushy_home    = _pushy_.pushy_home;

   try {
         // Where is the center of Pushy Home Div on the Viewable Page
         var ph_hcenter   = pushyHome.left + parseInt(mydiv.offsetWidth  / 2);
         var ph_vcenter   = pushyHome.top  + parseInt(mydiv.offsetHeight / 2);

         // Where is the center of the viewable page
         var pg_hcenter   = parseInt(pushy_page_width()  / 2);
         var pg_vcenter   = parseInt(pushy_page_height() / 2);

         if (ph_vcenter <= pg_vcenter) {  // Pushy Home is in Top Half of Viewable Page
             if (ph_hcenter <= pg_hcenter) {  // Pushy Home is in Left Half of Viewable Page
                 // Pushy Home is in Top Left Quadrant of Viewable Page - Enter Top Right
                 widget.top  = pushy_pos_top();
                 widget.left = pushy_pos_right()  - widget.width;
                 widget.origin="Enter TopRight - Home=>TopLeft";
                 widget.direction  = 4;
             }
             else {                           // Pushy Home is in Right Half of Viewable Page
                 // Pushy Home is in Top Right Quadrant of Viewable Page - Enter Top Left
                 widget.top  = pushy_pos_top();
                 widget.left = pushy_pos_left();
                 widget.origin="Enter TopLeft - Home=>TopRight";
                 widget.direction  = 1;
             }
         }
         else {                           // Pushy Home is in Bottom Half of Viewable Page
             if (ph_hcenter <= pg_hcenter) {  // Pushy Home is in Left Half of Viewable Page
                 // Pushy Home is in Bottom Left Quadrant of Viewable Page - Enter Bottom Right
                 widget.top  = pushy_pos_bottom() - widget.height;
                 widget.left = pushy_pos_right()  - widget.width;
                 widget.origin="Enter BottomRight - Home=>BottomLeft";
                 widget.direction  = 3;
             }
             else {                           // Pushy Home is in Right Half of Viewable Page
                 // Pushy Home is in Bottom Right Quadrant of Viewable Page - Enter Bottom Left
                 widget.top  = pushy_pos_bottom() - widget.height;
                 widget.left = pushy_pos_left();
                 widget.origin="Enter BottomLeft - Home=>BottomRight";
                 widget.direction  = 2;
             }
         }

         /**--- SPEED ----**/
         widget.speed = 30;
         if (_pushy_.env.widget_speed_configuration == _PUSHY_CONFIG.WIDGET_SPEED_MEDIUM)
             widget.speed = 30;
         else
         if (_pushy_.env.widget_speed_configuration == _PUSHY_CONFIG.WIDGET_SPEED_SLOW)
             widget.speed = 50;
         else
         if (_pushy_.env.widget_speed_configuration == _PUSHY_CONFIG.WIDGET_SPEED_FAST)
            widget.speed = 10;

         // alert('widget.speed:'+widget.speed+'  speed-selected:'+_pushy_.env.widget_speed_configuration);

         _pushy_.mydiv.style.top  = widget.top  + 'px';
         _pushy_.mydiv.style.left = widget.left + 'px';

         pushy_compute_env(pushyHome,widget);

         _pushy_.mydiv.style.zIndex = 1000;

         var f = function() {pushy_go_home(pushyHome,widget);};
         if (_pushy_.pause > 0)
           {
              setTimeout (f, _pushy_.pause);
           }
         else
           {
              setTimeout (f, 50);
           }
       }
   catch (e)
       {

       }
 }




function pushy_motion_bounce(pushyHome,widget)
 {
   var mybody        = _pushy_.mybody;
   var mydiv         = _pushy_.mydiv;
   var mytable       = _pushy_.mytable;
   var shellImage    = _pushy_.shellImage;
   var pushy_home    = _pushy_.pushy_home;

   try {
         // Where is the center of Pushy Home Div on the Viewable Page
         var ph_hcenter   = pushyHome.left + parseInt(mydiv.offsetWidth  / 2);
         var ph_vcenter   = pushyHome.top  + parseInt(mydiv.offsetHeight / 2);

         // Where is the center of the viewable page
         var pg_hcenter   = parseInt(pushy_page_width()  / 2);
         var pg_vcenter   = parseInt(pushy_page_height() / 2);

         if (ph_vcenter <= pg_vcenter) {  // Pushy Home is in Top Half of Viewable Page
             if (ph_hcenter <= pg_hcenter) {  // Pushy Home is in Left Half of Viewable Page
                 // Pushy Home is in Top Left Quadrant of Viewable Page - Enter Top Right
                 widget.top  = pushy_pos_top();
                 widget.left = pushy_pos_right()  - widget.width;
                 widget.origin="Enter TopRight - Home=>TopLeft";
                 widget.direction  = 4;
             }
             else {                           // Pushy Home is in Right Half of Viewable Page
                 // Pushy Home is in Top Right Quadrant of Viewable Page - Enter Top Left
                 widget.top  = pushy_pos_top();
                 widget.left = pushy_pos_left();
                 widget.origin="Enter TopLeft - Home=>TopRight";
                 widget.direction  = 1;
             }
         }
         else {                           // Pushy Home is in Bottom Half of Viewable Page
             if (ph_hcenter <= pg_hcenter) {  // Pushy Home is in Left Half of Viewable Page
                 // Pushy Home is in Bottom Left Quadrant of Viewable Page - Enter Bottom Right
                 widget.top  = pushy_pos_bottom() - widget.height;
                 widget.left = pushy_pos_right()  - widget.width;
                 widget.origin="Enter BottomRight - Home=>BottomLeft";
                 widget.direction  = 3;
             }
             else {                           // Pushy Home is in Right Half of Viewable Page
                 // Pushy Home is in Bottom Right Quadrant of Viewable Page - Enter Bottom Left
                 widget.top  = pushy_pos_bottom() - widget.height;
                 widget.left = pushy_pos_left();
                 widget.origin="Enter BottomLeft - Home=>BottomRight";
                 widget.direction  = 2;
             }
         }

         /**--- SPEED ----**/
         widget.speed = 40;
         if (_pushy_.env.widget_speed_configuration == _PUSHY_CONFIG.WIDGET_SPEED_MEDIUM)
             widget.speed = 40;
         else
         if (_pushy_.env.widget_speed_configuration == _PUSHY_CONFIG.WIDGET_SPEED_SLOW)
             widget.speed = 65;
         else
         if (_pushy_.env.widget_speed_configuration == _PUSHY_CONFIG.WIDGET_SPEED_FAST)
            widget.speed = 15;

         // alert('widget.speed:'+widget.speed+'  speed-selected:'+_pushy_.env.widget_speed_configuration);

         _pushy_.mydiv.style.top  = widget.top  + 'px';
         _pushy_.mydiv.style.left = widget.left + 'px';

         widget.minHeight = parseInt(widget.height * .30);
         pushy_compute_env(pushyHome,widget);

         _pushy_.mydiv.style.zIndex = 1000;

         var f = function() {pushy_schedule_moveAbout(pushyHome,widget);};
         if (_pushy_.pause > 0)
           {
              setTimeout (f, _pushy_.pause);
           }
         else
           {
              setTimeout (f, 50);
           }
       }
   catch (e)
       {

       }
 }



function pushy_move_about(pushyHome,widget)
  {
    pushy_compute_env(pushyHome,widget);
    var vdelta = parseInt(pushyHome.height / 8);
    var hdelta = widget.trajectory * vdelta;

    var pageTop      = pushy_pos_top();
    var pageBottom   = pushy_pos_bottom() - widget.height;
    var pageLeft     = pushy_pos_left();
    var pageRight    = pushy_pos_right()  - widget.width;


    var clearance = pushy_pos_bottom() - widget.height - pushy_pos_top();
    if (clearance < 150)
      {
        pushy_go_home(pushyHome,widget); // Not enough clearance in the browser window to bounce - just go home
        return;
      }


//  widget.loop++;
//  if (widget.loop > 500)
//    return;

    //-- DIRECTION
    //                                     t- l-           t+ l-
    //                            *     *   (3)             (4)
    //      \                   /         \                      /
    //        \               /             \                  /
    //     (1)  \           /   (2)           \              /
    //    t+ l+   \       /    t- l+            \          /
    //             *                                     *
    //
    //---------------

    // printf("--- ENTER -- DIR=%s  TOP=%s  LEFT=%s  VDELTA=%s  HDELTA=%s\n",widget.direction, (int) widget.top, (int) widget.left, vdelta, hdelta);
    // print_r(widget);

    if (widget.direction==1) // TopLeft to Botton Right (T is increasing, L is increasing)
      {
        if (widget.left + hdelta >= pushyHome.left)
          {
            if (widget.left >= pushyHome.left)
              {
                pushy_go_home(pushyHome,widget);
                return;
              }
            hdelta = pushyHome.left - widget.left;
          }
        if (widget.bounceCount==3)
          {
            pushy_go_home(pushyHome,widget);
            return;
          }
        if (widget.top + vdelta >= pageBottom)
          {
            widget.top=pageBottom;
            pushy_move_widget(widget);
            widget.bounceCount++;
            widget.direction=2;
            pushy_schedule_bounce(pushyHome,widget);
            return;
          }
        widget.top  += vdelta;
        widget.left += hdelta;
      }
    else
    if (widget.direction==2)  // BottomLeft to Top Right (T is decreasing, L is increasing)
      {
        if (widget.left + hdelta >= pushyHome.left)
          {
            if (widget.left >= pushyHome.left)
              {
                pushy_go_home(pushyHome,widget);
                return;
              }
            hdelta = pushyHome.left - widget.left;
          }
        if (widget.bounceCount==3)
          {
            pushy_go_home(pushyHome,widget);
            return;
          }
        if (widget.top - vdelta <= pageTop)
          {
            widget.top=pageTop;
            pushy_move_widget(widget);
            widget.bounceCount++;
            widget.direction=1;
            pushy_schedule_bounce(pushyHome,widget);
            return;
          }
        widget.top  -= vdelta;
        widget.left += hdelta;
      }
    else
    if (widget.direction==3)  // BottomRight to Top Left (T is decreasing, L is decreasing)
      {
        if (widget.left - hdelta <= pushyHome.left)
          {
            if (widget.left <= pushyHome.left)
              {
                pushy_go_home(pushyHome,widget);
                return;
              }
            hdelta = widget.left - pushyHome.left;
          }
        if (widget.bounceCount==3)
          {
            pushy_go_home(pushyHome,widget);
            return;
          }
        if (widget.top - vdelta <= pageTop)
          {
            widget.top=pageTop;
            pushy_move_widget(widget);
            widget.bounceCount++;
            widget.direction=4;
            pushy_schedule_bounce(pushyHome,widget);
            return;
          }
        widget.top -= vdelta;
        widget.left -= hdelta;
      }
    else
    if (widget.direction==4)  // TopRight to BottomLeft (T is increasing, L is decreasing)
      {
        if (widget.left - hdelta <= pushyHome.left)
          {
            if (widget.left <= pushyHome.left)
              {
                pushy_go_home(pushyHome,widget);
                return;
              }
            hdelta = widget.left - pushyHome.left;
          }
        if (widget.bounceCount==3)
          {
            pushy_go_home(pushyHome,widget);
            return;
          }
        if (widget.top + vdelta >= pageBottom)
          {
            widget.top=pageBottom;
            pushy_move_widget(widget);
            widget.bounceCount++;
            widget.direction=3;
            pushy_schedule_bounce(pushyHome,widget);
            return;
          }
        widget.top += vdelta;
        widget.left -= hdelta;
      }

    // printf("--- EXIT -- DIR=%s  TOP=%s  LEFT=%s  VDELTA=%s  HDELTA=%s\n",dir, (int) widget.top, (int) widget.left, vdelta, hdelta);
    // print_r(widget);

    pushy_schedule_moveAbout(pushyHome,widget);

  }

function pushy_schedule_moveAbout(pushyHome,widget)
  {
    pushy_move_widget(widget);
    var f = function() {pushy_move_about(pushyHome,widget);};
    setTimeout (f, widget.speed);
  }

function pushy_schedule_bounce(pushyHome,widget)
  {
    var f = function() {pushy_deflate(pushyHome,widget);};
    setTimeout (f, widget.speed);
  }


function pushy_deflate(pushyHome,widget)
  {
    pushy_compute_env(pushyHome,widget);
    var vdelta = parseInt(pushyHome.height / 10);
    var hdelta = widget.trajectory * vdelta;

    var pageTop      = pushy_pos_top();
    var pageBottom   = pushy_pos_bottom();
    var pageLeft     = pushy_pos_left();
    var pageRight    = pushy_pos_right();

    widget.deflating=true;
    if (widget.height-vdelta <= widget.minHeight)
      {
        widget.height  = widget.minHeight;
        widget.image.style.height   = widget.minHeight + 'px';
        if (widget.direction==2 || widget.direction==3)    // Deflating At Bottom of Screen
          {
            widget.top             =  pageBottom - widget.minHeight;
            widget.image.style.top = (pageBottom - widget.minHeight)  + 'px';
          }
        else
          {
            widget.top             = pageTop;
            widget.image.style.top = pageTop  + 'px';
          }

        _pushy_.mydiv.style.top    = widget.top    + 'px';
        _pushy_.mydiv.style.height = widget.height + 'px';

        widget.deflating=false;
        pushy_inflate(pushyHome,widget);
        return;
      }

    if (widget.direction==1 || widget.direction==2)
      widget.left++;
    else
      widget.left--;
    pushy_move_widget(widget);

    if (widget.direction==2 || widget.direction==3)    // Deflating At Bottom of Screen
      {
        widget.height -= vdelta;
        widget.top     = pageBottom - widget.height;

        widget.image.style.height   = (widget.image.offsetHeight-vdelta)       + 'px';
        widget.image.style.top      = (pageBottom - widget.image.offsetHeight) + 'px';

        _pushy_.mydiv.style.top    = widget.top    + 'px';
        _pushy_.mydiv.style.height = widget.height + 'px';

        var f = function() {pushy_deflate(pushyHome,widget);};
        setTimeout (f, 5);
      }
    else                      // Deflating At Top of Screen
      {
        widget.height -= vdelta;
        widget.top     = pageTop;

        widget.image.style.height   = (widget.image.offsetHeight-vdelta) + 'px';
        widget.image.style.top      = pageTop  + 'px';

        _pushy_.mydiv.style.top    = widget.top    + 'px';
        _pushy_.mydiv.style.height = widget.height + 'px';

        var f = function() {pushy_deflate(pushyHome,widget);};
        setTimeout (f, 5);
      }
  }


function pushy_inflate(pushyHome,widget)
  {
    pushy_compute_env(pushyHome,widget);
    var vdelta = parseInt(pushyHome.height / 10);
    var hdelta = widget.trajectory * vdelta;


 // var pageTop      = 0;
 // var pageBottom   = pushy_scroll_height() - widget.height;
 // var pageLeft     = 0;
 // var pageRight    = pushy_scroll_width()  - widget.width;

    var pageTop      = pushy_pos_top();
    var pageBottom   = pushy_pos_bottom();
    var pageLeft     = pushy_pos_left();
    var pageRight    = pushy_pos_right();

    widget.inflating=true;
    if (widget.height+vdelta >= pushyHome.height)
      {
        widget.height  = pushyHome.height;
        widget.image.style.height   = pushyHome.height + 'px';
        if (widget.direction==2 || widget.direction==3)    // Inflating At Bottom of Screen
          {
            widget.top             = pageBottom - pushyHome.height;
            widget.image.style.top = (pageBottom - pushyHome.height)  + 'px';
          }
        else
          {
            widget.top             = pageTop;
            widget.image.style.top = pageTop + 'px';
          }

        _pushy_.mydiv.style.top    = widget.top    + 'px';
        _pushy_.mydiv.style.height = widget.height + 'px';

        widget.inflating=false;
        var f = function() {pushy_move_about(pushyHome,widget);};
        setTimeout (f, 5);
        return;
      }

    if (widget.direction==1 || widget.direction==2)
      widget.left++;
    else
      widget.left--;
    pushy_move_widget(widget);

    if (widget.direction==2 || widget.direction==3)    // Inflating At Bottom of Screen
      {
        widget.height += vdelta;
        widget.top     = pageBottom - widget.height;

        widget.image.style.height   = (widget.image.offsetHeight+vdelta)        + 'px';
        widget.image.style.top      = (pageBottom - widget.image.offsetHeight)  + 'px';

        _pushy_.mydiv.style.top    = widget.top    + 'px';
        _pushy_.mydiv.style.height = widget.height + 'px';

        var f = function() {pushy_inflate(pushyHome,widget);};
        setTimeout (f, 5);
      }
    else                      // Inflating At Top of Screen
      {
        widget.height += vdelta;
        widget.top     = pageTop;

        widget.image.style.height   = (widget.image.offsetHeight+vdelta) + 'px';
        widget.image.style.top      = pageTop + 'px';

        _pushy_.mydiv.style.top    = widget.top    + 'px';
        _pushy_.mydiv.style.height = widget.height + 'px';

        var f = function() {pushy_inflate(pushyHome,widget);};
        setTimeout (f, 5);
      }
  }



function pushy_compute_env(pushyHome,widget)
  {
      if ( _PUSHY_CONFIG.WidgetMotion == _PUSHY_CONFIG.WIDGET_MOTION_FLOAT)
        {
         widget.vDistance   = pushy_max(1,pushy_absolute_value(widget.top  - pushyHome.top));
         widget.hDistance   = pushy_max(1,pushy_absolute_value(widget.left - pushyHome.left));
         if (widget.vDistance > 0)
           widget.trajectory  = widget.hDistance / widget.vDistance;
         else
           widget.trajectory  = 0;

         var hdelta=0;
         var vdelta=0;
         if (widget.vDistance > widget.hDistance)
           {
             var t = widget.hDistance / widget.vDistance;
             vdelta=pushy_min(6,parseInt(widget.vDistance));
             hdelta=parseInt(vdelta*t);
     //  pushy_log("A compute_env: vDistance="+widget.vDistance+"  hDistance="+widget.hDistance+"  trajectory="+t+"  hdelta="+hdelta+"  vdelta="+vdelta);
           }
         else
         if (widget.vDistance < widget.hDistance)
           {
             var t = widget.vDistance / widget.hDistance;
             hdelta=pushy_min(6,parseInt(widget.hDistance));
             vdelta=parseInt(hdelta*t);
     //  pushy_log("B compute_env: vDistance="+widget.vDistance+"  hDistance="+widget.hDistance+"  trajectory="+t+"  hdelta="+hdelta+"  vdelta="+vdelta);
           }
         else
           {
             vdelta=pushy_max(0,pushy_min(6,parseInt(widget.vDistance)));
             hdelta=vdelta;
     //  pushy_log("C compute_env: vDistance="+widget.vDistance+"  hDistance="+widget.hDistance+"  hdelta="+hdelta+"  vdelta="+vdelta);
           }

         // pushy_log("compute_env: vDistance="+widget.vDistance+"  hDistance="+widget.hDistance+"  trajectory="+widget.trajectory+"  hdelta="+hdelta+"  vdelta="+vdelta);


         return( { vdelta: vdelta,  hdelta: hdelta } );
        }
      else
      if ( _PUSHY_CONFIG.WidgetMotion == _PUSHY_CONFIG.WIDGET_MOTION_BOUNCE)
        {
         widget.vDistance   = ((3-widget.bounceCount) * (pushy_page_height() - widget.height)) + pushy_absolute_value(widget.top - pushyHome.top);
         widget.hDistance   = pushy_absolute_value(widget.left - pushyHome.left);
         if (widget.vDistance > 0)
           widget.trajectory  = widget.hDistance / widget.vDistance;
         else
           widget.trajectory  = 0;

         var vdelta=parseInt(pushyHome.height / 8);
         var hdelta=parseInt(widget.trajectory * vdelta);

         return( { vdelta: vdelta,  hdelta: hdelta } );
        }
  }


function pushy_go_home(pushyHome,widget)
  {
    var result=pushy_compute_env(pushyHome,widget);
    var vdelta = result.vdelta;
    var hdelta = result.hdelta;

    // pushy_log("go_home: vdelta="+vdelta+"  hdelta="+hdelta);

    if (arguments.length==2)
      {
        widget.image.style.width     = widget.width  + 'px';
        widget.image.style.height    = widget.height + 'px';
        _pushy_.mydiv.style.width    = widget.width  + 'px';
        _pushy_.mydiv.style.height   = widget.height + 'px';
      }

    //alert("Going Home: top="+widget.top+" left="+left+" pushyHome.top="+pushyHome.top+" pushyHome.left="+pushyHome.left+" vdelta="+vdelta+" hdelta="+hdelta);

    var difTop  = parseInt(pushy_absolute_value(widget.top - pushyHome.top));
    var difLeft = parseInt(pushy_absolute_value(widget.left - pushyHome.left));
    if (difTop == 0)
      hdelta=parseInt(pushyHome.width  / 20);
    if (difLeft == 0)
      vdelta=parseInt(pushyHome.height / 20);

    if ( (difTop  <= parseInt(vdelta) && difLeft <= parseInt(hdelta)) ||
         (difTop + difLeft <= 4))
      {    // or append the widget to the DIV containiong the script ?
        widget.top  = pushyHome.top;
        widget.left = pushyHome.left;
        pushy_move_widget(widget);

        var f = function() {pushy_zoom_out(pushyHome,widget);};
        setTimeout (f, 300);

        return;
      }

    if (widget.top > pushyHome.top)
      {
        if (widget.top - pushyHome.top < vdelta)
          vdelta=widget.top - pushyHome.top;
        widget.top -= vdelta;
        pushy_move_widget(widget);
      }
    else
    if (widget.top < pushyHome.top)
      {
        if (pushyHome.top - widget.top < vdelta)
          vdelta=pushyHome.top - widget.top;
        widget.top += vdelta;
        pushy_move_widget(widget);
      }

    if (widget.left > pushyHome.left)
      {
        if (widget.left - pushyHome.left < hdelta)
          hdelta=widget.left - pushyHome.left;
        widget.left -= hdelta;
        pushy_move_widget(widget);
      }
    else
    if (widget.left < pushyHome.left)
      {
        if (pushyHome.left - widget.left < hdelta)
          hdelta=pushyHome.left - widget.left;
        widget.left += hdelta;
        pushy_move_widget(widget);
      }

    var f = function() {pushy_go_home(pushyHome,widget);};
    setTimeout (f, widget.speed);
  }



function pushy_zoom_out(pushyHome,widget)
  {
    var oldWidth  = widget.image.offsetWidth;
    var oldHeight = widget.image.offsetHeight;

    var newWidth  = parseInt(oldWidth  * .90);
    var newHeight = parseInt(oldHeight * .90);

    var newTop  = _pushy_.mydiv.offsetTop  + parseInt((oldHeight - newHeight) / 2);
    var newLeft = _pushy_.mydiv.offsetLeft + parseInt((oldWidth  - newWidth) / 2);

    widget.image.style.width  = newWidth   + 'px';
    widget.image.style.height = newHeight  + 'px';

    _pushy_.mydiv.style.width  = newWidth  + 'px';
    _pushy_.mydiv.style.height = newHeight + 'px';

    if (newWidth < 10 || newHeight < 10)
      {
        _pushy_.mydiv.removeChild(widget.divChild);
        widget.image  = _pushy_.spacerImage;
        widget.divChild = widget.image;
         _pushy_.mydiv.appendChild(widget.image);
        widget.image.style.width    = pushyHome.width  + 'px';
        widget.image.style.height   = pushyHome.height + 'px';
        _pushy_.mydiv.style.width   = pushyHome.width  + 'px';
        _pushy_.mydiv.style.height  = pushyHome.height + 'px';
        _pushy_.mydiv.style.top     = pushyHome.top    + 'px';
        _pushy_.mydiv.style.left    = pushyHome.left   + 'px';
        var f = function() {pushy_transition(pushyHome,widget);};
        setTimeout (f, 10);
        return;
      }

    _pushy_.mydiv.style.top    = newTop  + 'px';
    _pushy_.mydiv.style.left   = newLeft + 'px';

    var f = function() {pushy_zoom_out(pushyHome,widget);};
    setTimeout (f, 20);

    return;
  }


function pushy_zoom_in(pushyHome,widget)
  {
    var oldWidth  = widget.image.offsetWidth;
    var oldHeight = widget.image.offsetHeight;

    var newWidth  = Math.round(oldWidth  / .90);
    var newHeight = Math.round(oldHeight / .90);

    if (newWidth  >= pushyHome.width  ||
        newHeight >= pushyHome.height )
      {
        widget.image.style.width    = pushyHome.width    + 'px';
        widget.image.style.height   = pushyHome.height   + 'px';
        _pushy_.mydiv.style.width   = pushyHome.width    + 'px';
        _pushy_.mydiv.style.height  = pushyHome.height   + 'px';
        _pushy_.mydiv.style.top     = pushyHome.top      + 'px';
        _pushy_.mydiv.style.left    = pushyHome.left     + 'px';
        var f = function() {pushy_posture(pushyHome,widget);};
        setTimeout (f, 200);
        return;
      }

    widget.image.style.width   = newWidth  + 'px';
    widget.image.style.height  = newHeight + 'px';
    _pushy_.mydiv.style.width  = newWidth  + 'px';
    _pushy_.mydiv.style.height = newHeight + 'px';

    var newTop  = _pushy_.mydiv.offsetTop  - Math.round((newHeight - oldHeight) / 2);
    var newLeft = _pushy_.mydiv.offsetLeft - Math.round((newWidth  - oldWidth) / 2);


    if (newTop    <= pushyHome.top  ||
        newLeft   <= pushyHome.left )
      {
        widget.image.style.width    = pushyHome.width   + 'px';
        widget.image.style.height   = pushyHome.height  + 'px';
        _pushy_.mydiv.style.width   = pushyHome.width   + 'px';
        _pushy_.mydiv.style.height  = pushyHome.height  + 'px';
        _pushy_.mydiv.style.top     = pushyHome.top     + 'px';
        _pushy_.mydiv.style.left    = pushyHome.left    + 'px';
        var f = function() {pushy_posture(pushyHome,widget);};
        setTimeout (f, 200);
        return;
      }

    _pushy_.mydiv.style.top    = newTop  + 'px';
    _pushy_.mydiv.style.left   = newLeft + 'px';

    var f = function() {pushy_zoom_in(pushyHome,widget);};
    setTimeout (f, 20);

    return;
  }


function pushy_move_widget(widget) {
  var z=parseInt(_pushy_.mydiv.style.zIndex);
  if (z<1000)
    z=1000;

  _pushy_.mydiv.style.zIndex = z+1;
  _pushy_.mydiv.style.top    = widget.top    + 'px';
  _pushy_.mydiv.style.left   = widget.left   + 'px';
}


function pushy_size_widget(widget) {
  _pushy_.mydiv.style.width  = widget.width  + 'px';
  _pushy_.mydiv.style.height = widget.height + 'px';
}


function pushy_wiggle(pushyHome,widget)
  {
    if (_pushy_.wiggle_interval == 0 || _pushy_.wiggle_repeats == 0)
      return; // No Wiggle

    var f = function() {pushy_shake(0, pushyHome, widget, _pushy_.wiggle_repeats, _pushy_.wiggle_interval);};
    setTimeout (f, _pushy_.wiggle_interval);
  }


function pushy_pause_wiggle()
  {
    _pushy_.wiggle_paused=true;
  }
function pushy_resume_wiggle()
  {
    _pushy_.wiggle_paused=false;
  }


function pushy_shake(count,pushyHome,widget,repeats,interval)
  {
    var wiggle_degree = 4;       // Degree of  Wiggle  - this is a constant - not tunable  2 to 12 are good numbers where 12=most wiggle and 2=least wiggle
    var shakes_per_wiggle = 24;  // Shakes per Wiggle  - this is a constant - not tunable  MUST BE EVEN NUMBER - 14,16,18,20,22,24,26,28,30 are good numbers

    if (count==0)
      {
                if (_pushy_.wiggle_paused)
                  {
                    if (repeats != 0)
                      {
                        var f = function() {pushy_shake(0,pushyHome,widget,repeats,interval);};
                        setTimeout (f, interval);
                      }
                    else
                      {
                        _pushy_.wiggle_interval=0;
                      }
                    return;
                  }

                //-- changed - 11/13/09 -- interferes with POPUP Dialog
                   _pushy_.mydiv.style.position = "relative";
                   _pushy_.mydiv.style.top    = 0 + 'px';
                   _pushy_.mydiv.style.left   = 0 + 'px';
                   _pushy_.mydiv.style.width  = pushyHome.width  + 'px';
                   _pushy_.mydiv.style.height = pushyHome.height + 'px';
                //--
      }

    if (repeats!=0 && (count%2==0))  // repeats = -1  ->  continuous
      {
        _pushy_.mydiv.style.width  = (pushyHome.width  - (2*wiggle_degree))  + 'px';
        _pushy_.mydiv.style.height = (pushyHome.height - (2*wiggle_degree))  + 'px';
        _pushy_.mydiv.style.top    = wiggle_degree  + 'px';
        _pushy_.mydiv.style.left   = wiggle_degree  + 'px';
        var f = function() {pushy_shake(count+1,pushyHome,widget,repeats,interval);};
        setTimeout (f, 10);
      }
    else
      {
        _pushy_.mydiv.style.top    = 0  + 'px';
        _pushy_.mydiv.style.left   = 0  + 'px';
        _pushy_.mydiv.style.width  = pushyHome.width  + 'px';
        _pushy_.mydiv.style.height = pushyHome.height + 'px';
        if (count < shakes_per_wiggle)
          {
            var f = function() {pushy_shake(count+1,pushyHome,widget,repeats,interval);};
            setTimeout (f, 10);
          }
        else
          {
                //-- changed - 11/13/09 -- interferes with POPUP Dialog
                   _pushy_.mydiv.style.position = "static";
                   _pushy_.mydiv.style.top    = 0 + 'px';
                   _pushy_.mydiv.style.left   = 0 + 'px';
                   _pushy_.mydiv.style.width  = pushyHome.width  + 'px';
                   _pushy_.mydiv.style.height = pushyHome.height + 'px';
                //--
            if (repeats > 0)
              repeats--;
            if (repeats != 0)
              {
                var f = function() {pushy_shake(0,pushyHome,widget,repeats,interval);};
                setTimeout (f, interval);
              }
            else
              {
                _pushy_.wiggle_interval=0;
              }
          }
      }
  }


function pushy_change_opacity (opacity, id)
  {
    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";
  }


function pushy_fade(id,millisec,from,to,cb)
 {
   var speed = Math.round(millisec / 100);
   var timer = 0;
   if (from < to)
     {
       for (i=from; i<=to; i++)
         {
           setTimeout("pushy_change_opacity(" + i + ",'" + id + "')",(timer * speed));
           timer++;
         }
       if (arguments.length >= 5 && (cb))
          setTimeout(cb,((timer+5) * speed));
     }
   else
   if (from > to)
     {
       for (i=from; i>=to; i--)
         {
           setTimeout("pushy_change_opacity(" + i + ",'" + id + "')",(timer * speed));
           timer++;
         }
       if (arguments.length >= 5 && (cb))
          setTimeout(cb,((timer+5) * speed));
     }
 }


function pushy_fade_out_in(id,millisec)
 {
   pushy_fade(id,millisec,100,10, function(){pushy_fade(id,millisec,10,100,function(){pushy_fade_complete(id);});});
 }

function pushy_fade_out(id,millisec)
 {
   pushy_fade(id,millisec,100,0);
 }

function pushy_fade_in(id,millisec)
 {
   pushy_fade(id,millisec,0,100);
 }

function pushy_fade_complete(id)
 {
   // alert("fade complete ID="+id);

//         _pushy_.mydiv.removeChild(_pushy_.mytable);
//         _pushy_.mydiv.appendChild(_pushy_.mytable);

//         _pushy_.mybody.removeChild(_pushy_.mydiv);
//         _pushy_.mybody.appendChild(_pushy_.mydiv);

//    alert("fade complete ID="+id);
 }




function pushy_hover_prep()
  {
     if (_pushy_.HoverVars.verticalOrigin=="frombottom")
       {
         if (document.all)
           _pushy_.HoverVars.hdelta=4;
         else
           {
             if ( document.body.scrollWidth > document.body.clientWidth )
               _pushy_.HoverVars.hdelta=20;
             else
               _pushy_.HoverVars.hdelta=4;
           }
       }
     if (_pushy_.HoverVars.horizontalOrigin=="fromright")
       {
         if (document.all)
           _pushy_.HoverVars.vdelta=4;
         else
           {
             if ( document.body.scrollHeight > document.body.clientHeight )
               _pushy_.HoverVars.vdelta=20;
             else
               _pushy_.HoverVars.vdelta=4;
           }
       }
     var el=_pushy_.HoverVars.mydiv;
     _pushy_.HoverVars.width =el.offsetWidth;
     _pushy_.HoverVars.height=el.offsetHeight;
     if(_pushy_.HoverVars.hasLayers) el.style=el;
     // el.setPos=function(x,y) {this.style.left=x+'px';this.style.top=y+'px'; _pushy_.hover.x=x; _pushy_.hover.y=y; if (arguments.length==2 && _pushy_.hover.cb!=null) {_pushy_.hover.cb('L0',_pushy_.hover.x,_pushy_.hover.y);} };
     el.setPos=_set_pos_;
     el.setSize=function(w,h){this.style.width=w+'px';this.style.height=h+'px'; _pushy_.hover.w=w; _pushy_.hover.h=h;};
     if (_pushy_.HoverVars.horizontalOrigin=="fromleft")
        el.x = pushy_pos_left() + _pushy_.HoverVars.startX;
     else
        el.x = pushy_pos_left() + _pushy_.HoverVars.startX + (pushy_page_width() - _pushy_.HoverVars.width - _pushy_.HoverVars.vdelta - _pushy_.HoverVars.horizontalOffset);
     if (_pushy_.HoverVars.verticalOrigin=="fromtop")
       el.y = _pushy_.HoverVars.startY;
     else
       {
         el.y = (_pushy_.HoverVars.ns ? pageYOffset + innerHeight : document.body.scrollTop + document.body.clientHeight) - _pushy_.HoverVars.hdelta;
         el.y -= _pushy_.HoverVars.height;
       }

     _pushy_.hover.x=el.offsetLeft; _pushy_.hover.y=el.offsetTop;
     if (_pushy_.hover.cb!=null) {_pushy_.hover.cb('L1',_pushy_.hover.x,_pushy_.hover.y);}
     return el;
  }


function _set_pos_(x,y)
  {
     this.style.left= x+'px';
     this.style.top = y+'px';
     _pushy_.hover.x=x;
     _pushy_.hover.y=y;
     if (arguments.length==2 && _pushy_.hover.cb!=null)
       {
         _pushy_.hover.cb('L0',_pushy_.hover.x,_pushy_.hover.y);
       }
     // pushy_inspect(this);
  }


// var _m_=0;
function pushy_inspect(elm){
  var str = "";
  // _m_++;
  // if ((_m_%67)!=0) return;
  for (var i in elm){
    if (i.length>2 && i.substring(0,2)=='on')
      {}
    else
      {
        var s = ''+elm.getAttribute(i);
        if (s.length > 0)
           str += i + ": " + s + "\n";
      }
  }
  // alert(str);
}



function pushy_hover_stayTopLeft()
  {
     if (!_pushy_.HoverVars.block)
       {
         if (_pushy_.HoverVars.verticalOrigin=="frombottom")
           {
             if (document.all)
               _pushy_.HoverVars.hdelta=4;
             else
               {
                 if ( document.body.scrollWidth > document.body.clientWidth )
                   _pushy_.HoverVars.hdelta=20;
                 else
                   _pushy_.HoverVars.hdelta=4;
               }
           }
         if (_pushy_.HoverVars.horizontalOrigin=="fromright")
           {
            if (document.all)
               _pushy_.HoverVars.vdelta=4;
             else
               {
                 if ( document.body.scrollHeight > document.body.clientHeight )
                   _pushy_.HoverVars.vdelta=20;
                 else
                   _pushy_.HoverVars.vdelta=4;
               }
           }
         if (_pushy_.HoverVars.horizontalOrigin=="fromright")
            _pushy_.HoverVars.ftlObj.x = pushy_pos_left() + _pushy_.HoverVars.startX + (pushy_page_width() - _pushy_.HoverVars.width - _pushy_.HoverVars.vdelta - _pushy_.HoverVars.horizontalOffset);
         else
            _pushy_.HoverVars.ftlObj.x = pushy_pos_left() + _pushy_.HoverVars.startX;

         // divisor=1 - Quickest   divisor=2 - Slower   ....  divisor=8 Slowest
         var divisor=8;

         if (_pushy_.HoverVars.verticalOrigin=="fromtop")
           {
             var pY = _pushy_.HoverVars.ns ? pageYOffset : document.body.scrollTop;
             _pushy_.HoverVars.ftlObj.y += (pY + _pushy_.HoverVars.startY + _pushy_.HoverVars.verticalOffset - _pushy_.HoverVars.ftlObj.y)/divisor;
           }
         else
           {
             var pY = (_pushy_.HoverVars.ns ? pageYOffset + innerHeight : document.body.scrollTop + document.body.clientHeight) - _pushy_.HoverVars.hdelta;
             _pushy_.HoverVars.ftlObj.y += (pY - (_pushy_.HoverVars.startY + _pushy_.HoverVars.height + _pushy_.HoverVars.verticalOffset) - _pushy_.HoverVars.ftlObj.y)/divisor;
           }
         _pushy_.HoverVars.ftlObj.setPos(_pushy_.HoverVars.ftlObj.x, _pushy_.HoverVars.ftlObj.y);
       }

     var pace = 50;  // milliseconds  -  larger=slower

     setTimeout("pushy_hover_stayTopLeft()", pace);
  }



function pushy_hover_div(mydiv,verticalOrigin,verticalOffset,horizontalOrigin,horizontalOffset,transition)
  {
    _pushy_.HoverVars.mydiv              = mydiv;
    _pushy_.HoverVars.verticalOrigin     = verticalOrigin;
    _pushy_.HoverVars.verticalOffset     = verticalOffset;
    _pushy_.HoverVars.horizontalOrigin   = horizontalOrigin;
    _pushy_.HoverVars.horizontalOffset   = horizontalOffset;
    _pushy_.HoverVars.transition         = transition;

    _pushy_.HoverVars.ftlObj = pushy_hover_prep(_pushy_.HoverVars.mydiv);

    if (_pushy_.HoverVars.transition > 0)
      {
         _pushy_.HoverVars.mydiv.appendChild(_pushy_.spacerImage);
         _pushy_.mybody.appendChild(_pushy_.HoverVars.mydiv);
      }
    else
      {
         _pushy_.HoverVars.mydiv.appendChild(_pushy_.mytable);
         _pushy_.mybody.appendChild(_pushy_.HoverVars.mydiv);
      }

    _pushy_.HoverVars.ftlObj = pushy_hover_prep(_pushy_.HoverVars.mydiv);

    // pushy_log("pushy_hover_div:");

    pushy_hover_stayTopLeft();
  }


function pushy_log(msg)
  {
    if (_pushy_.env.logging_enabled)
      {
        var log_el=document.getElementById("PUSHY_SCRATCH_PAD");
        if ((log_el) && _pushy_.env.log_message_count < _pushy_.env.max_log_messages)
          {
            _pushy_.env.log_message_count++;

            var msgnum="";
            if (_pushy_.env.log_message_count<10)
              msgnum = "  ";
            else
            if (_pushy_.env.log_message_count<100)
              msgnum = " ";
            msgnum += _pushy_.env.log_message_count;

            if (_pushy_.env.log_message_count==1)
              log_el.value = "";

            log_el.value += (""+msgnum+")  "+msg+"\n");
          }
        else
          _pushy_.env.logging_enabled=false;
      }
    else
      _pushy_.env.logging_enabled=false;
  }




function pushy_hover_transition(org, x, y)
  {
    // 0=NONE
    // 1=ZOOM
    // 2=MORPH
    // 3=FADE


    // pushy_log("ORG: "+org+"    X: "+parseInt(x)+"  Y: "+parseInt(y));


    if (_pushy_.env.widget_transition_configuration == 0)
      {
        _pushy_.hover.cb=null; // No transition -  Take Yourself out of the loop
        return;
      }


    if (_pushy_.HoverVars.LastY == -1)
      {
        _pushy_.HoverVars.LastY=y;
        return;
      }


    var LAST_Y=_pushy_.HoverVars.LastY;
    _pushy_.HoverVars.LastY=y;

    /*---------- ZOOM -----------*/
    if (_pushy_.env.widget_transition_configuration == 1)
      {
        if (pushy_absolute_value(LAST_Y - y) < 10)
          {
            _pushy_.env.widget_transition_configuration=0;  // Do this Once Only
            _pushy_.hover.cb=null; // Take Yourself out of the loop

            _pushy_.HoverVars.block=true;

            var offsetTop    = _pushy_.HoverVars.ftlObj.offsetTop;
            var offsetLeft   = _pushy_.HoverVars.ftlObj.offsetLeft;
            var offsetWidth  = _pushy_.HoverVars.ftlObj.offsetWidth;
            var offsetHeight = _pushy_.HoverVars.ftlObj.offsetHeight;

            var w = Math.round(offsetWidth  * .05);
            var h = Math.round(offsetHeight * .05);

            _pushy_.HoverVars.widget.image  = _pushy_.shellImage;

            _pushy_.HoverVars.widget.image.style.width  = w + 'px';
            _pushy_.HoverVars.widget.image.style.height = h + 'px';

            _pushy_.HoverVars.mydiv.style.width   = w + 'px';
            _pushy_.HoverVars.mydiv.style.height  = h + 'px';

            var x = offsetLeft + Math.round((offsetWidth  - w)/2);
            var y = offsetTop  + Math.round((offsetHeight - h)/2);

            _pushy_.HoverVars.mydiv.style.left    = x+'px';
            _pushy_.HoverVars.mydiv.style.top     = y+'px';

            _pushy_.HoverVars.mydiv.removeChild(_pushy_.spacerImage);
            _pushy_.HoverVars.mydiv.appendChild(_pushy_.HoverVars.widget.image);

            var org = {
                        w:  offsetWidth,
                        h:  offsetHeight,
                        x:  offsetLeft,
                        y:  offsetTop,
                        nw: w,
                        nh: h,
                        nx: x,
                        ny: y
                      }

            // pushy_log("** ZOOM: **\n"+pushy_object_to_string(org));

            pushy_hover_zoom_in(org);
          }

        return;
      }




    /*---------- MORPH -----------*/
    if (_pushy_.env.widget_transition_configuration == 2)
      {
        if (pushy_absolute_value(LAST_Y - y) < 10)
          {
            _pushy_.env.widget_transition_configuration=0;  // Do this Once Only
            _pushy_.hover.cb=null; // Take Yourself out of the loop

            var fStart=function() {
                  var morphImage = document.createElement("img");
                  morphImage.setAttribute("src", _PUSHY_CONFIG.PUSHY_IMAGE_NAME_MORPH);
                  morphImage.setAttribute("border", "0");
                  morphImage.setAttribute("width",  _PUSHY_CONFIG.width);
                  morphImage.setAttribute("height", _PUSHY_CONFIG.height);

                  _pushy_.HoverVars.mydiv.removeChild(_pushy_.spacerImage);
                  _pushy_.HoverVars.mydiv.appendChild(morphImage);
                  setTimeout (function(){ _pushy_.HoverVars.mydiv.removeChild(morphImage); _pushy_.HoverVars.mydiv.appendChild(_pushy_.mytable);}, 2000);
            };

            setTimeout (fStart, 200);
          }
        return;
      }




    /*---------- FADE -----------*/
    if (_pushy_.env.widget_transition_configuration == 3)
      {
        if (pushy_absolute_value(LAST_Y - y) < 10)
          {
            _pushy_.env.widget_transition_configuration=0;  // Do this Once Only
            _pushy_.hover.cb=null; // Take Yourself out of the loop


            var f=function()
               {
                  var opacity=0;
                  var millisec=1800;
                  var speed = Math.round(millisec / 100);
                  var from  = 0;
                  var to    = 100;
                  var timer = 0;
                  var image_id="PUSHY_SHELL_IMAGE_8BIT";

                  var object=_pushy_.shellImage8Bit.style;

                  object.opacity = (opacity / 100);
                  object.MozOpacity = (opacity / 100);
                  object.KhtmlOpacity = (opacity / 100);
                  object.filter = "alpha(opacity=" + opacity + ")";

                  _pushy_.HoverVars.mydiv.removeChild(_pushy_.spacerImage);
                  _pushy_.HoverVars.mydiv.appendChild(_pushy_.shellImage8Bit);

                  pushy_fade(image_id,millisec,from,to, function(){ _pushy_.HoverVars.mydiv.removeChild(_pushy_.shellImage8Bit); _pushy_.HoverVars.mydiv.appendChild(_pushy_.mytable); });
               }

            setTimeout(f, 10);
          }
        return;
      }


  }


function pushy_hover_zoom_in(org)
  {
    var offsetTop  = _pushy_.HoverVars.ftlObj.offsetTop;
    var offsetLeft = _pushy_.HoverVars.ftlObj.offsetLeft;
    var oldWidth   = _pushy_.HoverVars.ftlObj.offsetWidth;
    var oldHeight  = _pushy_.HoverVars.ftlObj.offsetHeight;
    var ratio      = _pushy_.HoverVars.originalHeight / _pushy_.HoverVars.originalWidth;

    var newWidth   = Math.round(oldWidth  / .90);
    var newHeight  = Math.round(ratio * newWidth);

    var newTop     = _pushy_.HoverVars.mydiv.offsetTop  - Math.round((newHeight - oldHeight) / 2);
    var newLeft    = _pushy_.HoverVars.mydiv.offsetLeft - Math.round((newWidth  - oldWidth) / 2);

    if (newWidth >  org.w || newHeight >  org.h ||
        newTop   <  org.y || newLeft   <  org.x)
      {
        pushy_SetMyDivSize(org.w, org.h);
        pushy_SetMyDivPos(org.x, org.y);

        _pushy_.HoverVars.mydiv.removeChild(_pushy_.shellImage);
        _pushy_.HoverVars.mydiv.appendChild(_pushy_.mytable);
        _pushy_.HoverVars.block=false;

        // var f=function(){ pushy_hover_zoom_in_complete(); };
        // setTimeout (f, 100);
        return;
      }

    pushy_SetMyDivSize(newWidth,newHeight);
    pushy_SetMyDivPos(newLeft,newTop);

    var f = function() {pushy_hover_zoom_in(org);};
    setTimeout (f, 20);
    return;
  }



function pushy_hover_zoom_in_complete()
  {
    _pushy_.HoverVars.mydiv.removeChild(_pushy_.shellImage);
    _pushy_.HoverVars.mydiv.appendChild(_pushy_.mytable);
    _pushy_.HoverVars.block=false;
  }



function pushy_hover_wiggle()
  {
    if (_pushy_.wiggle_interval == 0 || _pushy_.wiggle_repeats == 0)
      return; // No Wiggle

    var f = function() {pushy_hover_shake(0,_pushy_.wiggle_repeats);};

    setTimeout (f, _pushy_.wiggle_interval);
  }



function pushy_hover_shake(count,repeats)
  {
    if (count==0)
      {
        if (_pushy_.wiggle_paused)
          {
            if (repeats != 0)
              {
                var f = function() { pushy_hover_shake(0,repeats); };
                setTimeout (f, _pushy_.wiggle_interval);
              }
            else
              {
                _pushy_.wiggle_interval=0;
              }
            return;
          }

        _pushy_.HoverVars.block=true;
        _pushy_.HoverVars.tempOffsetTop  = _pushy_.HoverVars.ftlObj.offsetTop;
        _pushy_.HoverVars.tempOffsetLeft = _pushy_.HoverVars.ftlObj.offsetLeft;
      }
    var wiggle_degree = 6;       // Degree of  Wiggle  - this is a constant - not tunable  2 to 12 are good numbers where 12=most wiggle and 2=least wiggle
    var shakes_per_wiggle = 24;  // Shakes per Wiggle  - this is a constant - not tunable  MUST BE EVEN NUMBER - 14,16,18,20,22,24,26,28,30 are good numbers
    if (repeats!=0 && (count%2==0))  // repeats = -1  ->  continuous
      {
        pushy_SetFtlSize(_pushy_.HoverVars.originalWidth-(2*wiggle_degree), _pushy_.HoverVars.originalHeight-(2*wiggle_degree));

        if (_pushy_.env.widget_origin_configuration == 0) // Top-Left
            pushy_SetFtlPos(_pushy_.HoverVars.tempOffsetLeft + wiggle_degree, _pushy_.HoverVars.ftlObj.offsetTop + wiggle_degree, true);
        else
        if (_pushy_.env.widget_origin_configuration == 1) // Top-Right
            pushy_SetFtlPos(_pushy_.HoverVars.tempOffsetLeft - wiggle_degree, _pushy_.HoverVars.ftlObj.offsetTop + wiggle_degree, true);
        else
        if (_pushy_.env.widget_origin_configuration == 2) // Bottom-Left
            pushy_SetFtlPos(_pushy_.HoverVars.tempOffsetLeft + wiggle_degree, _pushy_.HoverVars.ftlObj.offsetTop - wiggle_degree, true);
        else
        if (_pushy_.env.widget_origin_configuration == 3) // Bottom-Right
            pushy_SetFtlPos(_pushy_.HoverVars.tempOffsetLeft - wiggle_degree, _pushy_.HoverVars.ftlObj.offsetTop - wiggle_degree, true);

        var f = function() { pushy_hover_shake(count+1,repeats); };
        setTimeout (f, 10);
      }
    else
      {
        pushy_SetFtlSize(_pushy_.HoverVars.originalWidth, _pushy_.HoverVars.originalHeight);
        pushy_SetFtlPos(_pushy_.HoverVars.tempOffsetLeft, _pushy_.HoverVars.tempOffsetTop, true);

        if (count < shakes_per_wiggle)
          {
            var f = function() { pushy_hover_shake(count+1,repeats); };
            setTimeout (f, 10);
          }
        else
          {
            _pushy_.HoverVars.block=false;
            if (repeats > 0)
              repeats--;
            if (repeats != 0)
              {
                var f = function() { pushy_hover_shake(0,repeats); };
                setTimeout (f, _pushy_.wiggle_interval);
              }
            else
              {
                _pushy_.wiggle_interval=0;
              }
          }
      }
  }


function pushy_SetMyDivPos(wleft,wtop)
  {
    // pushy_log("SetMyDivPos: Left(x)="+wleft+" Top(y)=" + wtop);

    _pushy_.HoverVars.mydiv.style.top  = wtop +'px';
    _pushy_.HoverVars.mydiv.style.left = wleft+'px';
  }

function pushy_SetMyDivSize(w,h)
  {
    // pushy_log("SetMyDivSize: W="+w+"  H=" + h);

    _pushy_.HoverVars.widget.image.style.width  = w+'px';
    _pushy_.HoverVars.widget.image.style.height = h+'px';
    _pushy_.HoverVars.mydiv.style.width         = w+'px';
    _pushy_.HoverVars.mydiv.style.height        = h+'px';
  }



function pushy_SetFtlPos(wleft,wtop,bool)
  {
    // var i=_pushy_.env.widget_origin_configuration;
    // var loc = ['TL', 'TR', 'BL', 'BR'];

    // pushy_log("SetFtlPos: [" + loc[i] + "]  Left(x)="+wleft+" Top(y)=" + wtop + '  bool='+bool);

    _pushy_.HoverVars.ftlObj.setPos(wleft,wtop,bool);
  }


function pushy_SetFtlSize(w,h)
  {
    // pushy_log("SetFtlSize: W="+w+"  H=" + h);

    _pushy_.HoverVars.ftlObj.setSize(w,h);
  }


function pushy_absolute_value(n)
  {
    if (n>=0)
      return n;
    return (-n);
  }

function pushy_min(m,n)
  {
    if (m<n)
      return m;
    return n;
  }

function pushy_max(m,n)
  {
    if (m>n)
      return m;
    return n;
  }

function pushy_page_width()
 {
   return window.innerWidth != null? window.innerWidth : document.documentElement && document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body != null ? document.body.clientWidth : null;
 }
function pushy_page_height()
 {
   return  window.innerHeight != null? window.innerHeight : document.documentElement && document.documentElement.clientHeight ?  document.documentElement.clientHeight : document.body != null? document.body.clientHeight : null;
 }
function pushy_pos_left()
 {
   return typeof window.pageXOffset != 'undefined' ? window.pageXOffset :document.documentElement && document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ? document.body.scrollLeft : 0;
 }
function pushy_pos_top()
 {
   return typeof window.pageYOffset != 'undefined' ?  window.pageYOffset : document.documentElement && document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ? document.body.scrollTop : 0;
 }
function pushy_pos_right()
 {
   return pushy_pos_left()+pushy_page_width();
 }
function pushy_pos_bottom()
 {
   return pushy_pos_top()+pushy_page_height();
 }


function pushy_scroll_width()
{
  if (window.innerHeight && window.scrollMaxY) {    // Firefox
    return window.innerWidth + window.scrollMaxX;
  }
  if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac
    return document.body.scrollWidth;
  }
  else { // works in Explorer 6 Strict, Mozilla (not FF) and Safari
    return document.body.offsetWidth;
  }
}

function pushy_scroll_height()
{
  if (window.innerHeight && window.scrollMaxY) {    // Firefox
    return window.innerHeight + window.scrollMaxY;
  }
  if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac
    return document.body.scrollHeight;
  }
  else { // works in Explorer 6 Strict, Mozilla (not FF) and Safari
    return document.body.offsetHeight;
  }
}


function pushy_type_of(value) {
    try {
       var s = typeof value;
       if (s === 'object') {
           if (value) {
               if (typeof value.length === 'number' &&
                       !(value.propertyIsEnumerable('length')) &&
                       typeof value.splice === 'function') {
                   s = 'array';
               }
           } else {
               s = 'null';
           }
       }
       return s;
    } catch (e) {
       return "undefined";
    }
}


function pushy_is_empty(o) {
    var i, v;
    if (pushy_type_of(o) === 'object') {
        for (i in o) {
            v = o[i];
            if (v !== undefined && pushy_type_of(v) !== 'function') {
                return false;
            }
        }
    }
    return true;
}



function pushy_findPos(obj)
 {
   var curleft = curtop = 0;
   if (obj.offsetParent) {
     do {
          curleft += obj.offsetLeft;
          curtop += obj.offsetTop;
        } while (obj = obj.offsetParent);
     return [curleft,curtop];
   }
 }



function pushy_pad_level(level)
 {
   var t='';
   for (var i=0; i<level; i++) t+='    ';
   return t;
 }

function pushy_object_to_string(o)
 {
   var lvl=1;
   if (arguments.length >= 2)
     lvl=arguments[arguments.length-1];
   var text='';
   if (pushy_type_of(o) === 'object' && !pushy_is_empty(o)) {
      var k,v;
      for (k in o) {
        v = o[k];
        if (v !== undefined && pushy_type_of(v) !== 'function') {
          if (pushy_type_of(v) === 'object' && !pushy_is_empty(v)) {
            text+=pushy_pad_level(lvl)+k+':\n';
            text+=pushy_object_to_string(v,lvl+1);
          }
          else {
            text+=pushy_pad_level(lvl)+k+': '+v+'\n';
          }
        }
      }
   }
   return text;
 }


function pushy_dump_object(o) {
   if (arguments.length >= 1 && (pushy_type_of(o) == "object" || pushy_type_of(o) == "function"))
     {
        alert(pushy_object_to_string(o));
     }
}
