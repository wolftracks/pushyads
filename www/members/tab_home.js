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

function tab_home_loaded(response)
  {

  }
