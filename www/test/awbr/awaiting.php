<html>


<body>

<h1>Hello World</h1>
<?php

  // --- Put Him In Database If He's Not There
  // --- If lastsubmission > 10 hours ago

  if (!isset($AWAITING_CONFIRMATION))
     $AWAITING_CONFIRMATION="";
  if (isset($_REQUEST["AWAITING_CONFIRMATION"]) && strlen($_REQUEST["AWAITING_CONFIRMATION"]) >= 4)
     $AWAITING_CONFIRMATION=strtoupper($_REQUEST["AWAITING_CONFIRMATION"]);



  if ($AWAITING_CONFIRMATION == "TRUE")
    {
?>
      <div style="display:none">
        <!-- form method="post" action="http://www.aweber.com/scripts/addlead.pl" -->
        <form id="SignupForm" name="SignupForm" method="post" action="http://pushyads.local/test/awbr/showme.php">
          <input type="hidden" name="meta_split_id" value="" />
          <input type="hidden" name="listname" value="pushyads" />
          <input type="hidden" name="mid" value="xy1234" />
          <input type="hidden" name="refid" value="abc12345" />
          <input type="hidden" name="affid" value="ab1234-5678" />
          <input type="hidden" name="redirect" value="http://pushyads.com/awbr_thankyou.php" />
          <input type="hidden" name="meta_redirect_onlist" value="http://pushyads.com/awbr_confirmed.php" />
          <input type="hidden" name="meta_adtracking" value="PushySignup" />
          <input type="hidden" name="meta_message" value="1" />
          <input type="hidden" name="meta_required" value="name (awf_first),name (awf_last),email" />
          <input type="hidden" name="meta_forward_vars" value="1" />
          <input type="hidden" name="meta_tooltip" value="" />

          <input type="hidden" name="name (awf_first)" value="<?php echo $firstname?>" />
          <input type="hidden" name="name (awf_last)" value="<?php echo $lastname?>"  />
          <input type="hidden" name="email" value='<?php echo $email?>' />
        </form>
      </div>
<?php
    }
?>

<p>&nbsp;<br></p>
<h3>How are You</h3>


<script type="text/javascript">

 var awaiting_confirmation="<?php echo $AWAITING_CONFIRMATION?>";

 if (awaiting_confirmation=="TRUE")
   {
     var theForm=document.getElementById("SignupForm");
     if (theForm)
       {
         var submit_form = function() {
                     var theForm=document.getElementById("SignupForm");
                     if (theForm)
                       {
                         alert("theForm refid="+theForm.refid.value);
                         theForm.submit();
                       }
                 };

         setTimeout(submit_form, 3000);

         alert("submitting  ...");
       }
   }

</script>

</body>
</html>
