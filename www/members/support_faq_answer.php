<?php
 $affiliate_id        = $memberRecord["affiliate_id"];
 $affiliate_website   = DOMAIN."/".$affiliate_id;

 include("support_faq_data.inc");

 if (!isset($category))
   {
     $category=1;
     if (isset($_REQUEST["category"]))
        $category = (int) $_REQUEST["category"];
   }
 $faq = $faq_data[$category];

 $question="&nbsp;&nbsp;&nbsp;";
 $answer="&nbsp;&nbsp;&nbsp;";
 if (isset($_REQUEST["question"]))
   {
     $qkey = $_REQUEST["question"];
     if (isset($faq[$qkey]))
       {
         $akey = "A".substr($qkey,1);
         if (isset($faq[$akey]))
           {
             $question=$faq[$qkey];
             $answer  =$faq[$akey];
           }
       }
   }
?>


<table align=center width=710 cellspacing=0 cellpadding=0 class=bgborder bgcolor="#FFEECC">
  <tr valign=bottom>
    <td colspan=3 style="border:0px; background-color:#FFEECC;">
      <table align=center width="96%" style="border:1px solid #339933; margin:15px 12px 5px 14px;" cellspacing=0 cellpadding=10>
        <tr bgcolor="#FBFFFA">
          <td width=100% class="tahoma size14"><b>Question: &nbsp;</b>

            <span class="tahoma darkgreen"><b><?php echo $question?></b></span>

          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr valign=bottom>
    <td colspan=3 style="border:0px; background-color:#FFEECC;">
      <table align=center width="96%" style="border:1px solid #339933; margin:5px 12px 17px 14px;" cellspacing=0 cellpadding=15>
        <tr bgcolor="#FBFFFA">
          <td width=100% class="text"><b>Answer: &nbsp;</b>

            <?php echo $answer?>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
   <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=670 height=31></div>
