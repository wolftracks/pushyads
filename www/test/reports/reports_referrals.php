<?php

$dateArray=getDateTimeAsArray();
$dateToday = dateAsText($dateArray);
$timeNow   = timeAsText($dateArray);

?>

  <table width="645" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100%" height="35">
        <table width="100%" align=center border="0" cellspacing="0" cellpadding="0" class="text red">
          <tr>
            <td width="55%"><em class="required">&nbsp;<?php echo $dateToday?> </em> <span class="text red"><i>(<?php echo $timeNow?> MST)</i></span></td>
            <td width="20%" align=right valign=bottom >&nbsp;</td>
            <td width="25%" align=right valign=bottom style="padding-right: 14px;">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table width=645 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
    <tr>
      <td bgcolor="#FFFFFF">
        <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
          <tr>
            <td class="text">

              Those who you have personally referred to <b class=darkred>Pushy</b>&#8482, and who have signed up as members will show up as your referrals in the list below.
              Additional information on your referrals can be found by either clicking on their name (which will show member's products [coming soon]),
              or by downloading a CSV file with their full contact information.

             <p>A <b class=darkred>Pushy</b>&#8482 icon is shown below for every website your members list with <b class=darkred>Pushy</b>&#8482 on it.</p>

             <p align=center class="darkred bold">WHAT? Do you see a member without <b class=darkred>Pushy</b>&#8482 on their website? </P>

             <p>Give 'em a call, or send 'em an email, telling them how much money they're losing out on by not having <b class=darkred>Pushy</b>&#8482 on their website
             (<b>HINT</b>: by doing so, you could get more traffic to your product ads when they add <b class=darkred>Pushy</b>&#8482 to their website - or what if they have 2
             or 3 or 4 websites? <b class="darkred bold">YEE HAW!</b> Do you hear that stampede of traffic in the distance?)</p>


             <div ID="REPORT_PAGE">
               <form action=NULL>
                 <input type="hidden" name="mid" value="<?php echo $mid?>">
                 <input type="hidden" name="sid" value="<?php echo $sid?>">
                 <input type="hidden" name="" value="">
                 <input type="hidden" name="" value="">
                 <table align=center border=0 width=100%>
                   <tr><td height=10 class=size10>&nbsp;</td></tr>
                   <tr>
                     <td width="40%" align=left class=text>
                       <b>Total Personal Referrals: &nbsp; <SPAN id=TOTALREFERRALS>3</SPAN></b>
                     </td>
                     <td width="60%" align=right class=text>
                       Page <b><SPAN id=CURRENTPAGE class="red bold">1</SPAN></b>&nbsp;of <b><SPAN id=TOTALPAGES>1</SPAN></b>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <span class=smalltext><a href=javascript:void(0)>Display Page:</a></span> &nbsp;
                       <input name=page_number type=text class=form_input size=2 maxlength=3 value="">
                     </td>
                   </tr>
                   <tr>
                     <td width="40%" align=left class=smalltext><a href=javascript:void(0)>Download CSV File</a></td>
                     <td width="60%" align=right class=smalltext>
                       <a href=javascript:void(0)>First</a>&nbsp; <a href=javascript:void(0)>Prev</a>&nbsp; &nbsp;
                       <a href=javascript:void(0)>Next</a>&nbsp; <a href=javascript:void(0)>Last</a>&nbsp;
                     </td>
                   </tr>
                 </table>

                 <table align=center width=100% class=smallgridb1 border=0 cellspacing=0 cellpadding=0>
                   <tr valign="middle" bgcolor="#D0D6DF">
                     <td width="14%" align=left   class=smalltext><a href=javascript:referral_sortby('Date')><b>Date</b></a></td>
                     <td width="38%" align=left   class=smalltext><a href=javascript:referral_sortby('Name')><b>Name</b></a></td>
                     <td width="10%" align=left   class=smalltext><a href=javascript:referral_sortby('Member')><b>Member</b></a></td>
                     <td width="8%"  align=center class=smalltext><b>Email</b></td>
                     <td width="30%" colspan=2   class=smalltext><b>Widgets on Websites</b></td>
                   </tr>
                   <tr valign="middle">
                     <td width="14%" align=left   class=smalltext>2008-03-20</td>
                     <td width="38%" align=left   class=smalltext><a href="">Bob Smith</a></td>
                     <td width="10%" align=left   class=smalltext>VIP</td>
                     <td width="8%"  align=center class=smalltext><img src="http://ibmt3.net/images/email2.png" title="bob@email.com"></td>
                     <td width="30%" colspan=2 class=smalltext>
                       <a href="http://google.com" target="new"><img src="http://pds1106.s3.amazonaws.com/images/favicon.ico" alt="www.bobs-website.com" title="www.bobs-website.com"></a></td>
                   </tr>
                   <tr valign="middle">
                     <td width="14%" align=left   class=smalltext>2008-03-18</td>
                     <td width="38%" align=left   class=smalltext><a href="">Mary Jones</a></td>
                     <td width="10%" align=left   class=smalltext>ELITE</td>
                     <td width="8%"  align=center class=smalltext><img src="http://ibmt3.net/images/email2.png" title="mary341@hotmail.com"></td>
                     <td width="30%" colspan=2 class=smalltext>&nbsp;</td>
                   </tr>
                   <tr valign="middle">
                     <td width="14%" align=left   class=smalltext>2008-03-17</td>
                     <td width="38%" align=left   class=smalltext><a href="">Henry Targon</a></td>
                     <td width="10%" align=left   class=smalltext>PRO</td>
                     <td width="8%"  align=center class=smalltext><img src="http://ibmt3.net/images/email2.png" title="henri897@yahoo.com"></td>
                     <td width="30%" colspan=2 class=smalltext>
                       <a href="http://google.com" target="new"><img src="http://pds1106.s3.amazonaws.com/images/favicon.ico" alt="www.some-website.com" title="www.henrys-website.com"></a>
                       &nbsp;<a href="http://yahoo.com" target="new"><img src="http://pds1106.s3.amazonaws.com/images/favicon.ico" alt="www.henrys-second-url.com" title="www.henrys-second-url.com"></a></td>
                   </tr>
                 </table>
               </form>
             </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
