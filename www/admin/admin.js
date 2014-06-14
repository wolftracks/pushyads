
function openMember(mbrid)
  {
    var windowName=getUniqueWindowName("Member");
    var leftmargin = rand(0,10)*4;
    var topmargin  = rand(0,10)*4;

    var url="/admin/index.php?op=OpenMember&in_member_id="+mbrid;

    win=window.open(url,windowName,
       'width=840,height=800,top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=yes,directories=no,status=no,menubar=yes,toolbar=yes,resizable=yes');
    win.focus();
  }


function openWindow(url)
  {
    var leftmargin = 0;
    var topmargin  = 0;
    win=window.open(url,"PushyAdsAdmin",
       'width=840,height=800,top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=yes,directories=no,status=no,menubar=yes,toolbar=yes,resizable=yes');
    win.focus();
  }

