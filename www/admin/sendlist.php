<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css">
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>

<script type="text/javascript">
<!---
var submitted=false;

function submitMail()
  {
    var  theForm=document.SENDMAIL;

    if (theForm.target.selectedIndex==0)
      {
        alert("Please Select the Recipient Target ");
        theForm.target.focus();
        return;
      }

    theForm.fromEmail.value  = stripa(theForm.fromEmail.value);
    theForm.fromName.value   = striplt(theForm.fromName.value);
    theForm.subject.value    = striplt(theForm.subject.value);
    theForm.message.value    = striplt(theForm.message.value);

    if (theForm.fromEmail.value.length == 0)
      {
        alert("Please enter \"FROM Email Address\".");
        theForm.fromEmail.focus();
        return;
      }
    if (theForm.fromName.value.length == 0)
      {
        alert("Please enter \"FROM Name\".");
        theForm.fromName.focus();
        return;
      }
    if (theForm.subject.value.length == 0)
      {
        alert("Please enter \"Subject\".");
        theForm.subject.focus();
        return;
      }
    if (theForm.message.value.length == 0)
      {
        alert("Please enter \"Message\".");
        theForm.message.focus();
        return;
      }

    // alert(theForm.subject.value);
    resp=confirm("Be Sure You Review your Email Carefully before Sending!\nARE YOU ABSOLUTELY CERTAIN You Want To Send This Email NOW?");
    if (!resp)
      {
        return;
      }
//  resp=confirm("Click OK if you want this Email to Go To The PUBLIC ARCHIVE");
//  if (resp)
//    {
//      theForm.in_archive.value = "YES";
//    }

    if (submitted)
      {
        alert("Already Submitted");
        return;  // Only One Submission
      }
    submitted=true;

    var elSubmitButton=document.getElementById("SUBMITBUTTON");
    var elPleaseWait  =document.getElementById("PLEASEWAIT");
    elSubmitButton.style.display="none";          // HIDE
    elPleaseWait.style.display="";                // SHOW

    var  target           = theForm.target.value;
    var  fromName         = theForm.fromName.value;
    var  fromEmail        = theForm.fromEmail.value;
    var  subject          = theForm.subject.value;
    var  message          = theForm.message.value;

    var data    = {
                    target:         target,
                    fromName:       fromName,
                    fromEmail:      fromEmail,
                    subject:        subject,
                    message:        message
                  }

    // dumpObject(data);

    var url="/admin/sendlist_mailer.php";

    $.ajax({
       type:     "POST",
       url:      url,
       data:     data,
       dataType: "json",
       cache:    false,
       error:    function (XMLHttpRequest, textStatus, errorThrown)
                 {
                   var elSubmitButton=document.getElementById("SUBMITBUTTON");
                   var elPleaseWait  =document.getElementById("PLEASEWAIT");
                   elSubmitButton.style.display="";              // SHOW
                   elPleaseWait.style.display="none";            // HIDE

                   // typically only one of textStatus or errorThrown will have info
                   var httpStatus=XMLHttpRequest.status;
                   alert("Request Failed - HTTP Status:"+ httpStatus);

                   // if (textStatus)
                   //   alert("textStatus: "+textStatus);
                   // if (errorThrown)
                   //   alert("errorThrown:\n"+ objectToString(errorThrown));
                   // alert(objectToString(this));  // the options for this ajax request

                 },
       success:  function(response, textStatus)
                 {
                   var elSubmitButton=document.getElementById("SUBMITBUTTON");
                   var elPleaseWait  =document.getElementById("PLEASEWAIT");
                   elSubmitButton.style.display="";              // SHOW
                   elPleaseWait.style.display="none";            // HIDE

                   var status=response.status;
                   if (status != 0)
                     {
                       if (response.message && typeOf(response.message == "string") && response.message.length > 0)
                         {
                           document.close();
                           document.open();
                           document.write(response.message);
                         }
                       alert('Error  ('+status+') - Submission Failed - DO NOT RE-SUBMIT ! ');
                     }
                   else
                     {
                       // alert(objectToString(response));
                       if (response.message && typeOf(response.message == "string") && response.message.length > 0)
                         {
                           document.close();
                           document.open();
                           document.write(response.message);
                         }
                     }
                 }
    });
  }
//-->
</script>

<title>SENDLIST</title>
</head>

<body bgcolor="#C0C0CF">
<table width="700" cellpadding=0 cellspacing=0 border=0>
 <tr>
   <td width="20%><font face="Arial"><big><b>SendList</b></big></td>
   <td width="10%><font face="Arial">&nbsp;</td>
   <td width="35% valign="top"><font face="Arial">
    <table width="100%" cellpadding=0 cellspacing=0 border=0>
      <tr><td valign="top" align="center" colspan="2"><b>( Variables )</b></td></tr>
      <tr valign=top>
         <td valign=top align="left" width="50%">
             %firstname%          <br>
             %lastname%           <br>
             %email%              <br>
         </td>
         <td valign=top align="right" width="50%">
             %affiliate_id%       <br>
             %affiliate_website%  <br>
         </td>
      </tr>
    </table>
   </td>
   <td width="35% valign="top"><font face="Arial">&nbsp;
   </td>
 </tr>
</table>
<FORM name="SENDMAIL" method="POST" action="sendlist_mailer.php" onsubmit="return ValidateForm(this)">
<input type="hidden" name="in_archive"     value="NO">

<pre>
Recipient Target: <select name="target" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 12px;"><option name="null" value="null" selected> ------ SELECT RECIPIENT ------ </option>
                      <option value="AllMembers">ALL Members </option>
                  </select>

From Address:     <input type="hidden"     name="fromEmail" value="<?php echo EMAIL_NOREPLY?>"><span class=smalldarkredbold><?php echo EMAIL_NOREPLY?></span>
From Name:        <input name="fromName"   size="40" value="PushyAds Administration" tabIndex="2">
Subject:          <input name="subject"    size="40" tabIndex="3">

<br>Message:
         1         2         3         4         5         6         7         8
12345678901234567890123456789012345678901234567890123456789012345678901234567890   &lt;=Cols
</pre>

<textarea cols="80" name="message" rows="20"></textarea>

<p>



   <table width="620" border=0>
     <tr><td colspan="3" class="spacer" width="100%">&nbsp;</td></tr>
     <tr height=50 id="SUBMITBUTTON">
       <td width="20%" align="center">&nbsp;</td>
       <td width="60%" align="center">
          <input type="button" name="send"   value="  SEND  " onclick=submitMail()>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
          <input type="button" name="cancel" value=" CANCEL " onclick=history.back()>
       </td>
       <td width="20%"  align="center">&nbsp;</td>
     </tr>
     <tr height=50 id="PLEASEWAIT" style="display:none">
       <td width="20%" align="center"><img src="http://pds1106.s3.amazonaws.com/images/busy_1.gif"></td>
       <td width="60%" align="center" class="normaldarkredbold">Mail Is Being Queued<br>Please Wait</td>
       <td width="20%" align="center"><img src="http://pds1106.s3.amazonaws.com/images/busy_1.gif"></td>
     </tr>
     <tr><td colspan="3" class="spacer" width="100%">&nbsp;</td></tr>
   </table>


</form>
</p></b></b></font>

</body>
</html>
