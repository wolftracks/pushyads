<?php
$dlg=0;
if (isset($_REQUEST["dlg"]) && $_REQUEST["dlg"]=="1")
  $dlg=1;
?>
<html>
<head>
<title>PushyAds Terms of Service</title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css">
<script type="text/javascript" src="/local-js/modal/subModal.js"></script>

<script type="text/javascript">
function closeWindow()
 {
   <?php
      if ($dlg==1)
         echo "window.top.hidePopWin(false);";
      else
         echo "window.close();";
   ?>
 }
</script>
</head>

<body class="background text">

  <div align=center style="margin: 12px 0;">

      <table width=590 cellspacing=0 cellpadding=0 style="border: 1px solid #666666;" class=text>
        <tr height=25>
          <td align="left" bgcolor=#F9FA7C valign="middle" height="25" style="background-image: url('http://pds1106.s3.amazonaws.com/images/compare-bar.jpg'); background-repeat: repeat-y; border-bottom: 1px solid #000000; padding-left: 16px;" class=size15>

            <table width=100% border=0 cellspacing=0 cellpadding=0>
              <tr>
                <td width=50% class=white><b>Pushyads.com</b></td>
                <td width=50% align=right valign=middle><img src="http://pds1106.s3.amazonaws.com/images/favicon.ico"> &nbsp;</td>
              </tr>
            </table>

          </td>
        </tr>

        <tr>
          <td><img src="http://pds1106.s3.amazonaws.com/images/menu-shadow2.jpg" width="620" height="6"></td>
        </tr>
        <tr height=18 valign=bottom bgcolor="#FFFFFF">
          <td align=right class="smalltext" style="padding-right: 20px"><a href="javascript:closeWindow()">Close Window</a></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF" style="padding: 5px 20px 15px 20px">

<center> <span class="largetext bold darkred">PushyAds Terms of Service</span></center>

<p>This agreement describes the PushyAds terms and conditions of sale, and incorporates the PushyAds <a href="/pop-terms.php?dlg=<?php echo $dlg?>">Terms of Use</a>, and
 any other terms associated therein. In this agreement, the term "Client" refers to you, and "Company" or “PushyAds” refers to PushyAds.com.

<p><b>Advertising Services:</b> Client understands that PushyAds provides advertising services based on monthly membership, and agrees to be charged each month on the anniversary date of the original purchase at whatever membership level was selected at the time of purchase. Client may downgrade membership at any time before the anniversary date and either not be charged, or incur a lesser charge for a lower membership level. PushyAds provides Client with a means to Upgrade or Downgrade their membership at any time inside their PushyAds backoffice. Client is also provided with a Status Report for updating credit or debit card information, which will be held securely by PushyAds.

<p><b>Copyrighted material:</b> As the Client, you are solely responsible for ensuring that all of your marketing materials obey all applicable copyright and other laws. You must have express permission to use another party's copyrighted material. The Company will not be responsible if you use another party's copyrighted material in violation of the law.

<p><b>Inappropriate Material:</b> Client agrees not to use any adult, illegal or racist materials when submitting advertising material through PushyAds Services.

<p>The PushyAds software and advertising system uses an algorithm that determines when ads are displayed, based on many criteria, one of which matches audience interest with product category. This is used for aligning audience interests with products matching those interests. However sophisticated the software, there are no guarantees that any traffic to Client’s website, or inquiries made will be by people who are suited for your offer, or that the level of interest indicated by any inquiries will be of a specific nature.

<p><b>Traffic:</b> Traffic to your advertisements in any PushyAds delivery system is derived from visitors to websites having a PUSHY!&#8482 widget placed on them, other PushyAds Members while in their PUSHY!&#8482 backoffice, PUSHY!TM replicated Member websites, or other sources. Because PushyAds is not responsible for, nor in control of the amount of traffic to these 3rd party sites, there is no guarantee of any amount of traffic, known as views, impressions, hits, clicks, or sales of customer’s products or services. Client also holds Pushyads harmless in the event Client’s ads receive more traffic than Client’s hosting service can handle. Client fully understands and agrees that Client is solely responsible for accommodating the amount of traffic provided by PushyAds.

<p><b>Third Party Content:</b> Certain links in this Site connect to other Web Sites maintained by third parties over whom PushyAds has no control. PushyAds makes no representations, does not offer, endorse or guarantee, and assumes no liability for the accuracy, appropriateness or usefulness of any products, services, or information provided by any such third party. The third party is responsible for its site contents, privacy and security practices, and system availability.

<p>This statement includes all third party websites, including those which Client provides in their Pushyads ad submission software for PushyAds to utilize in advertising material for the purpose of directing the Client's prospects to. The Client holds harmless PushyAds for any damages caused by third party sites not being available at the time prospects are redirected to them.

<p>If Client becomes aware that the third party website they have provided inside their member backoffice for the purpose of advertising in the PUSHY!&#8482 Network has become unavailable, Client understands that they are fully responsible for updating any and all website content, including the URL, associated with their advertisement through their backoffice, and will not hold PushyAds responsible for any lack of traffic to their unavailable website. Client understands that they can edit or remove their ad from their PushyAds backoffice if they want traffic to stop to their ad(s).

<p><b>Your Profile:</b> Client understands and agrees that it is their responsibility to maintain accurate contact information, including email address, phone number, and redirect website in their "Profile" inside their member backoffice. If Client's email address changes after their original date of registration, and fails to update their email address in their member Profile, Client understands that they may not receive communication by email from PushyAds, which could include system data, as well as other important information pertinent to their ads or widgets. PushyAds will not be held liable for Client's neglect to keep their backoffice updated with correct information, including their profile, ads, websites, and contact information included.

<p><b>Email Communication:</b> In today's Internet environment of spam, there are many hosting companies, Internet Service Providers (ISP), and software that prevents the receipt of wanted emails. Because of this, often times our emails are blocked or filtered out into other mail boxes on client's computer. Client agrees to be aware and responsible for solving any problems associated with the receipt of emails from the Company, and to hold Company harmless for any lack of receipt of emails due to network issues, Client's ISP, or any other third party companies, or software installed on Client's computer, which would cause Company's emails to be blocked or filtered out into known or unknown mail boxes or folders."

<p>Company strongly recommends that Client check their "spam folder" or "bulk folder" or other folders or mail boxes on their computer software programs to insure their email program is not filtering out Company's emails. This is best achieved by checking their IN box for receipt of a "Welcome" message at the time of registration, or "Receipts" for membership upgrades at the time of placing orders. If an email is not seen in the Client's IN box within 5-10 minutes after registration or placing an order, it is likely that the message was filtered out. Company recommends that Client places Company on their accepted or friendly list of acceptable email recipients.

<p><b>Refund/Cancellation Policy:</b> PushyAds advertising service is based on a month to month membership fee. As such, members are provided a means to upgrade
 or downgrade their level of service through their backoffice. In the event Client wishes to cancel their membership early, and by way of any other method than
 by means of downgrading their membership, it will be considered a Refund Request. All Refund Requests will be considered on a prorated basis for the remaining
 amount of the service not yet used at the time the refund request is reviewed by PushyAds. All refund requests must be submitted via the Support system
 on <b>www.pushyads.com</b> using the Contact link, and must contain “Refund Request” in the Subject of the Support message. All refund requests
 will be reviewed and processed within 72 hours of the request, and will incur a processing fee of $35 against the adjustment. All refunds will be credited back
 using the same payment method the Client used on the last transaction. Refunds will not be given for service that has already been rendered to Client.

<p><b>Grace Period:</b> As a courtesy, but not an obligation, PushyAds will extend to Client a 5 day grace period for payment on each monthly membership fee.
 Client understands, and is fully aware of the anniversary date of their monthly membership fee, and when it will be charged each month for services provided
 by PushyAds. In the event PushyAds attempts to charge Client for monthly membership fees due, and is unsuccessful by way of payment being declined by Client’s
 banking institution, Client understands and agrees that it is Client’s sole responsibility to correct the problem, by providing PushyAds with a different
 payment method inside Client’s backoffice in their Status Report. PushyAds will attempt to charge Client again, up to and including 5 days after the Client’s
 anniversary date. If after PushyAds attempts to charge Client on the 5th day after Client’s anniversary date, Client’s payment method is declined, PushyAds
 will downgrade Client’s to a free VIP membership.

<p><b>Result of Downgrade:</b> Client understands that when membership services are downgraded, either voluntarily or involuntarily, all benefits and privileges
 of the previous paid membership are eliminated at the time cancellation or termination, regardless of cause. This includes all ad copy and images with
 advertisements, credits, commissions, bonuses, and any other benefits and privileges that were a part of the membership being downgraded from. Client
 understands that all advertising copy and material will disappear, where downgraded membership level does not include the benefits of the membership
 level being downgraded from.

<p><b>Indemnification:</b> You agree that the Company assumes no responsibility for your marketing materials. You agree to indemnify and hold the Company harmless from any liability arising out of your marketing materials. This indemnification includes, but is not limited to, legal fees, costs of litigation or judgments arising from any illegal, unauthorized, or illicit marketing materials submitted through the PushyAds system.

      </td>
    </tr>
    <tr height=25 valign=top>
      <td bgcolor="#FFFFFF" >

        <div align=center class="tahoma size10">
          <a href="/pop-terms.php?dlg=<?php echo $dlg?>">Terms of Use</a> ||
          <a href="/pop-disclaimer.php?dlg=<?php echo $dlg?>">Earnings Disclaimer</a> ||
          <a href="/pop-aff-agmt.php?dlg=<?php echo $dlg?>">Affiliate Agreement</a> ||
          <a href="/pop-copyright.php?dlg=<?php echo $dlg?>">Copyright Notice</a> ||
          <a href="/pop-privacy.php?dlg=<?php echo $dlg?>">Privacy Notice</a> ||
          <a href="/pop-comp-plan.php?dlg=<?php echo $dlg?>">Compensation Plan</a>
        </div>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
