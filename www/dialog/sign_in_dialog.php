<?php
$last_known_signin_id=$_COOKIE["PUSHYSIGNIN"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<style type="text/css">
body {
   background-color: #dfe8ff;
}

body, html, input {
   font-family:Verdana, Arial, Helvetica, sans-serif;
   font-size:14px;
   color: #333333;
}

.button {
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
}

td {
   font-family: Tahoma,Verdana,Arial;
   font-size:   13px;
   font-weight: normal;
   color : #000000;
}

th {
   font-family: Tahoma,Verdana,Arial;
   font-size:   13px;
   font-weight: normal;
   color : #000000;
}
</style>


<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type=text/javascript>
returnVal = new Object();
function signin(theForm)
 {
   theForm.useremail.value = theForm.useremail.value.trim();
   theForm.password.value  = theForm.password.value.trim();

   if (!signin_Validate(theForm))
     return;

   $.ajax({
      type:     "POST",
      url:      "signin.php",
      data:     {
                   signin_id:         theForm.useremail.value.toLowerCase(),
                   signin_password:   theForm.password.value.toLowerCase()
                },
      cache:    false,
      dataType: "json",
      error:    function (XMLHttpRequest, textStatus, errorThrown)
                {
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
                  var status=response.status;
                  if (status != 0)
                    alert( response.message );
                  else
                    {
                      // alert(objectToString(rersponse));
                      var data=response.data;
                      // alert("Redirecting to("+data.url+")");

                      window.top.hidePopWin(false);

                      top.location=data.url;
                    }
                }
   });
 }


function signin_Validate(theForm)
  {
    if (theForm.useremail.value == "" || theForm.useremail.value == " ")
      {
        alert("Please enter your Email address");
        theForm.useremail.focus();
        return (false);
      }
    if (theForm.password.value == "" || theForm.password.value == " ")
      {
        alert("Please enter your Password ");
        theForm.password.focus();
        return (false);
      }
     return (true);
   }

function forgotPassword()
  {
    alert("Sorry - You're out of luck ");
  }
</script>

<title>Member Sign In</title>
</head>

<form name="SIGNIN">
<table align="center" width="300" cellpadding=0 cellspacing=0 border=0>
  <tr>
    <td align="left">

         <!-- span align=left id=errormessage class="error">&nbsp;<br></span -->

         <TABLE cellSpacing=0 cellPadding=0 width=300 align=center border=0>
           <TBODY>
             <TR>
               <TD width=300>
                 <DIV align=center>
                    <TABLE cellSpacing=0 cellPadding=3 width="100%" align=center border=0>
                      <TBODY>
                      <TR>
                        <TD class=normalbold width="30%">
                          <DIV align=right>Email: </DIV>
                        </TD>
                        <TD class=normaltext width="70%">
                           <INPUT name="useremail" type="text" size=30 maxlength=50 value="<?php echo $last_known_signin_id?>">
                        </TD>
                      </TR>
                      <TR>
                        <TD class=normalbold width="30%">
                           <DIV align=right>Password:</DIV>
                        </TD>
                        <TD class=normaltext width="70%">
                           <INPUT name="password" type="password" size=16 maxlength=20 value="">
                        </TD>
                      </TR>
                      <TR>
                         <td align=center colspan=2 class=tinytext>
                           <input type=button class=button style="margin-top:12px;margin-bottom:0px;" value="  Sign in  " onclick="signin(this.form)">
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           <input type=button class=button style="margin-top:12px;margin-bottom:0px;" value="  Cancel  "  onclick="window.top.hidePopWin(false)">
                         </td>
                      </TR>
                      <TR height=30 valign=bottom>
                         <td align=center colspan=2 class=tinytext><a href=javascript:forgotPassword()>Forget Your Password?</a></td>
                      </TR>
                     </TBODY>
                   </TABLE>
                 </DIV>
               </TD>
             </TR>
           </TBODY>
         </TABLE>
    </td>
  </tr>
</table>
</form>
</body>
</html>
