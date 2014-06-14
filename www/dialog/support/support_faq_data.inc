<?php

//** EDIT RULES *************************************************************************************
//*
//* 1. Each Q/A are "paired" by Number ... All Numbers Must be Unique, and Must Be Sequential
//*    (i.e. Always Append to the End using the next Available Number in Sequence)
//*
//* 2. NEVER Use DOUBLE QUOTES (") in the Q/A Body - use &quot; when you want to display a double quote
//*
//* 3. Avoid Single Quotes - from a consistently standpoint, we should use &quot; for Button Text Literals
//*    and <b>bold</b> for all Menu Items.
//*
//* 4. Every Q/A Entry must be of the Form:  Note that the text starts with a double quote and ends with
//*    a double quote followed by a comma.
//*
//*    "Qnn"  =>  "xxxx",
//*    "Ann"  =>  "yyyy",
//*
//***************************************************************************************************


$faq_data = array();


/*--------------------- ********************* CATEGORY 1 ***************************------------------*/

$faq_data[1] = array(

/*---------------------- DO NOT EDIT ABOVE THIS LINE  ------------------------------------------------*/


"Q01"   =>   "What is the PUSHY! Network? ",
"A01"   =>   "PUSHY! is the widget that resides on websites, scrolling ads from the bottom to top. Each PUSHY! member can install the PUSHY! widget on as many web pages
              on as many website domains as they choose, as long as they meet PUSHY!'s standards. The entire PUSHY Network consists of all websites containing a PUSHY!
              widget on them, regardless of how few or how many views they receive from their site visitors.
             ",

"Q02"   =>   "Can you explain PUSHY!'s Algorithm? ",
"A02"   =>   "It's a complex sequence of calculations used to determine the placement and display of product ads, based on dynamic member hierarchy, viral components, category
              matches, traffic patterns, and widget density.

              <p>In more simple terms, it's a very sophisticated method for generating a synergistic flow of Viral Traffic for members who use the system. The longer PUSHY! is in action,
              the greater volume of traffic he begins to generate over time, as he crawls more websites, looking for targetted traffic for relevant ads.
             ",

"Q03"   =>   "How do I get paid for placing PUSHY! widgets on my website?",
"A03"   =>   "Well, that's a question found under the &quot;<b>Affiliates</b>&quot; tab, but here's the short answer
              <img src='http://pds1106.s3.amazonaws.com/images/wink.png' style='vertical-align: -4px'>&nbsp;
              You get paid 30% to 50% of the revenue generated from membership fees paid by site visitors who click through the &quot;<b>Get PUSHY!</b>&quot; link on all your PUSHY!
              widgets on every web page on every website you have him displayed on (see <a href=javascript:openPopup('/pop-comp-plan.php',660,700)>Compensation Plan</a> for specific details).
              Oh, and you're paid twice monthly too!
             ",

"Q04"   =>   "What do I need to do in order to place PUSHY! on my website?",
"A04"   =>   "First, you need to configure his behavior inside your <b>PUSHY</b> tab in your backoffice. There are multiple variations of different behaviors which you can
              customize for your spedific website visitors, depending on how flamboyant or subdued you want him to appear. Once you have submitted the <b>Get PUSHY!</b>
              button, you will be presented with a &quot;Get Code&quot; button. Pressing that button will give you a snippet of code to copy and paste onto your website,
              where you will want your visitors to see PUSHY!
             ",

"Q05"   =>   "Can I put more than one PUSHY! widget on my website? ",
"A05"   =>   "Yes! But only one widget per web page. We haven't identified a use for more than one widget per web page at this point. But go ahead and prove us wrong! Let us
              know if you want more than one PUSHY! on one web page by describing your reasons why, and your use case, in detail in a Support Ticket below.
             ",

"Q06"   =>   "Why would I want to place a PUSHY! widget on my website, causing visitors to leave my site? ",
"A06"   =>   "Your visitors are already leaving your site. The question is, &quot;Do you know why, and where they're going?&quot; Here's an even better question, &quot;Are you
              getting paid when they leave your site?&quot;

              <p>Obviously, you don't want all your visitors leaving your website. Unfortunately, no matter how hard you try to engage them with your content, and get them
              to take action in a way that will earn you money, the bad news is that most of them leave without doing squat. The good news is that PUSHY! enables you to generate
              the two things you want most: revenue from visitors who leave your site, and more traffic back to your site. In fact, it's likely that PUSHY! will return more viral and
              organic traffic BACK to your website, than the amount of exit traffic created by having him on your website.
             ",

"Q07"   =>   "Is there anything to be concerned about when placing your javascript code on my website?",
"A07"   =>   "No! Our javascript code enables us to serve advertisements containing text and images through the PUSHY! widget, based on member preferences, along with a
             scrolling feature. It also enables us to display PUSHY!'s personality and behavior which you configure in your backoffice. You are in full control of how you want PUSHY!
             displayed on your website.There are no features that will cause your site to slow down, outside PUSHY!'s entrance, which you can completely eliminate if you wish.
             ",

"Q08"   =>   "What are the widget sizes I can choose from to place on my website?",
"A08"   =>   "This is part of PUSHY!'s configuration inside your backoffice under the <b>PUSHY</b> tab. You can select from 180 pixels wide on up to 360 pixels wide in 10 pixel
              increments. He is approximately 80% as wide as he is high, from the top of his antennae to the bottom of his chin.
             ",

"Q09"   =>   "What are the differences and similarities between &quot;Categories&quot; and &quot;Audience Interest&quot; in terms of targetting?",
"A09"   =>   "&quot;Categories&quot; are selected to best identify the proper section, or tags for your ads. Categories may also depict a classification for your products, services,
              blogs, or opportunity if someone was to search for them.

              <p>&quot;Audience Interest&quot; defines or identifies the type of content a website may contain as it relates to the interests of its viewers (website visitors). PUSHY!
              matches these audiences with the type of products categorized by advertisers to obtain targeting for their ads, creating qualified prospects.
             ",

"Q10"   =>   "Why do you limit the number of &quot;Audience Interest&quot; I can choose to define my site visitors? ",
"A10"   =>   "We understand that it may be difficult to define your site visitor's interest with only one selection from 47 categories. However, giving you no more than
              two selections for describing your  &quot;Audience Interest&quot; enables us to target them with ads that match both your site content, as well as your visitor's interest.
              This will keep your visitors interested in your content, as well as maintain confidence in PUSHY!'s ability to display products & services they are interested in.
             ",

"Q11"   =>   "How many different ads are viewed in a PUSHY! widget? ",
"A11"   =>   "During Pre-Launch, there will be up to 7 different ads scrolling through any PUSHY! widget across the Network of websites. Ad position #1 is reserved for the website
              owner. Ad positions #2 - #6 are reserved for the <b>Personal PUSHY! Networks</b> of members in the referral hierarchy above the site owner, according to PUSHY!'s
              proprietary algorithm. Ad position #7 is reserved for ads in the <b>ELITE Ad Pool</b>. When PUSHY! officially launches his network to the world, ad position #8 will open
              for paid insertion, and fully commissionable by affiliates (more details will follow as PUSHY! nears launch).
             ",

"Q12"   =>   "Do I need to place a different snippet of code on my web page each time I want to change PUSHY!'s size or behavior?",
"A12"   =>   "No! Once you place the code on your website, you can change his behavior as often as you like from inside your PUSHY! backoffice, without ever touching
              the code again.
             ",

"Q13"   =>   "Do I need to place different code on my web page if I want PUSHY! in a different location?",
"A13"   =>   "Well, there are two answers to this question, depending on your desired &quot;Posture&quot; (Static or Hover). The Hover posture won't require any additional
              change in the code you originally paste on your website. If, however, you have chosen his posture to be Static, then you will need to place the snippet of code exactly
              where you want him on your website
             ",

"Q14"   =>   "Do I need a different snippet of code for each web page I place a PUSHY! widget on?",
"A14"   =>   "There are three basic types of information inside a snippet of code that creates a PUSHY! widget: (1) your domain name
              ie, &quot;website.net&quot;, which authenticates you as the owner of the domain, (2) your website audience interests, and (3) PUSHY!'s behavior, identified by the
              variables you select when configuring him, which define his size, posture, motion, action, and various other &quot;personality traits&quot;.

              <p>As long as you have identified the proper domain name inside your PUSHY! configuration (while creating him), you can place that same snippet on as many
              web pages WITHIN that domain as you like. Just remember that the snippet also identifies a certain desired behavior. If you have 10 widgets on 10 different
              web pages, and want a different behavior for each, you'll need to configure a different widget for each page. This will require that you copy and paste a different
              snippet of code for each behavior (configuration), which you will get from clicking the &quot;Get Code&quot; button, after creating or editing him.

              <p>If you want the same behavior for each widget, placed on all web pages on the same domain, and your site visitors (audience) interests are the same on all
              pages, then one snippet of code will suffice for every web page.
             ",

"Q15"   =>   "Can I insert tracking codes inside your snippet of code to track different site statistics",
"A15"   =>   "Yes! We give you a place at the end of the snippet of code you'll place on your website, which looks like this: &quot;tracker=TRACKING_ID&quot;. You have up
              to 16 character spaces to define a unique tracking code, made up of alphabetic (all lowercase), numeric, hyphen &quot;-&quot;, or underscore &quot;_&quot;
              characters. Simply replace &quot;TRACKING_ID&quot; with your unique tracking code and paste the entire snippet in your web page.

              <p>This tracking
              code can be used for your own internal purposes, and will also be seen inside your <b>Traffic Report</b> inside your PUSHY! backoffice, identifying the number of hits
              and clicks for various information on each widget, on a daily basis.
             ",

"Q16"   =>   "Why doesn't PUSHY! appear, or behave the way I configured him to?",
"A16"   =>   "If you have more than one PUSHY! configuration inside your backoffice under the <b>PUSHY</b> tab, it is possible you have copied and pasted a snippet that is
              meant for a different website domain. Each configuration of PUSHY! contains a unique web key inside the snippet of code, designed to identify the website
              domain you associated with it. If you try to place him on a website domain that isn't associated with that specific configuration, he will not appear for his performance
               <img src='http://pds1106.s3.amazonaws.com/images/sad.png' style='vertical-align: -4px'>&nbsp; This is done for both your security, and his.

              <p>If he isn't behaving the way you intended, he shouldn't be disciplined! It's probably because you have copied and pasted a snippet of code that was meant for
              a different PUSHY! configuration. Check your backoffice and click on the EDIT button and then &quot;Get Code&quot; to copy and paste the correct snippet of
              code for the right PUSHY! behavior you want on your website.

              <p>Lastly, it is possible that you do not have the snippet of code placed properly inside your html. Make sure you have followed the instructions given behind the
              &quot;Get Code&quot; button on your PUSHY! configuration.
             ",

/*---------------------- DO NOT EDIT BELOW THIS LINE  ------------------------------------------------*/
"NULL" => "NULL"
);



/*--------------------- ********************* CATEGORY 2 ***************************------------------*/

$faq_data[2] = array(

/*---------------------- DO NOT EDIT ABOVE THIS LINE  ------------------------------------------------*/


"Q01"   =>   "What's the best way to learn all the benefits of my membership, and getting started? ",
"A01"   =>   "First, make sure you watch the video inside the &quot;<b>Introduction & Goodies</b>&quot; section under the
              &quot;<b>MY STUFF</b>&quot; tab. After that, make sure you go through the following steps in order:

              <ol>
                <li style='margin-bottom: 10px'>Take a quick tour through each of the tabs in your backoffice here. Don't spend a whole lot of time in each section just yet. Simply scan
                  through each section to understand the basic layout, and the purpose or information inside. This will help you better understand some of the questions in the FAQ. </li>

                <li style='margin-bottom: 10px'>Read through each section of the the FAQ. This will answer most of your questions about PUSHY!, placing ads, making money, traffic, and
                  how everything works. If you still have more questions, submit them through our Support form below</li>

                <li>Now it's time to make PUSHY! go to work for you. Make sure your <b>Profile</b> is set up with all the pertinent contact
                  information, especially your Paypal email so we can pay you when your referrals start upgrading their memberships. </li>

                <li>If you haven't done so already, consider the huge benefits in greater exposure, higher commissions, more traffic, and increased viral activity to your ads by
                   upgrading your membership.</li>

                <li style='margin-bottom: 10px'>Now you're ready to place an ad on the PUSHY! Network of widgets. That's what the <b>MY ADS</b> section in your backoffice is for. 
                  Make sure you choose the best words that fit within the number of lines available in your ad, and select your best photo for the ad size.</li>

                <li style='margin-bottom: 10px'>If you have one or more of your own website domains, you will benefit so much more from PUSHY!'s algorithm by
                  placing him on your website where you get any traffic. Doing so will get more traffic back to your site. That's what's behind the
                  <b>PUSHY</b> tab.</li>

                <li style='margin-bottom: 10px'>Once you have PUSHY! on your website, and ads submitted, it's time to start referring your friends, associates, downline, and social
                  networks to your PUSHY! affiliate website at <b>$affiliate_website</b>.
                  If you aren't sure what this means in dollars and cents, review the Compensation Plan.</li>

                <li style='margin-bottom: 10px'>When you have created some activity by taking the above steps, you can start viewing your
                  <b>Reports</b>. The more viral traffic components you have activated, the more information and activity will appear on your
                  <b>Traffic Report</b>. And, of course, the more referrals who upgrade their membership plan, the greater your recurring income will be.</li>
              </ol>
             ",

"Q02"   =>   "What if I don't have a product or service? How can I benefit from using PUSHY!'s Network?",
"A02"   =>   "There are so many ways to benefit from PUSHY!'s Network, we're still trying to add them all up. If you have a blog, revenues generated from most blogs
              are done so from advertising. Guess what PUSHY! does? He pushes ads through his widget, producing ad revenues through membership fees he pays 30% to 50% on,
              plus bonuses.

              <p>OK, so what if you don't have a product, service, or a blog? Would you like to make some money? PUSHY! has an affiliate program that is, um, well, it's brain-dead
              simple. All you need to do is tell people to go to your PUSHY! website, and when they buy a membership, you get paid. What PUSHY! website? The one inside your
              <b>Profile</b> tab. It's equiped with everything that a fully automated online business needs (and more). See
              <a href=javascript:openPopup('/pop-comp-plan.php',660,700)>Compensation Plan</a> for details.
             ",

"Q03"   =>   "What are the main differences in PUSHY!'s membership plans?",
"A03"   =>   "Well, there are quite a few, actually. The major differences are in cost & benefits. There are basically 3 plans, ranging from FREE to $97 per month, each giving you more
             access to various viral traffic components in PUSHY!'s powerful algorithm. Oh, and did I say &quot;<i><b>more money</b></i>&quot;?

             <p>Yep! Not only will you get more exposure for your primary products, services, blog, or opportunity, but you'll make higher commissions in the higher membership levels.
             See actual comparisons and specific benefits in the membership upgrade and comparison chart.
             ",

"Q04"   =>   "How can I test PUSHY!'s Network before taking the plunge? ",
"A04"   =>   "That's what our <b>VIP</b> membership is for.  Although it doesn't really give you a a feel for the amount of traffic you would get from all PUSHY!'s viral traffic components, it
              will give you an idea how it all works. You have access to most of the features and can place unlimited PUSHY!s on your website domains.
             ",

"Q05"   =>   "If I upgrade or downgrade my membership level, when will my billing anniversary date be? ",
"A05"   =>   "If you upgrade your account from a <b>VIP</b> to a <b>PRO</b> or <b>ELITE</b> member, your billing anniversary date will occur on the same date each subsequent
              month thereafter. The only exception to this may be if the billing anniversary date of a subsequent month lands on a weekend, or if the subsequent month does not contain
             a date equal to the date of original purchase, as would be the case in months where the date of the 28th through 31st may not exist, in which case, the following calendar
             or business day (whichever is relevant) would be the billing anniversary date for that month only.

             <p>If you upgrade from a <b>PRO</b> to an <b>ELITE</b> member, your old anniversary date will change to the date of your upgrade (which will take affect immediately),
             and will continue in subsequent months as described above. Your membership upgrade will be prorated accordingly.

              <p>If you downgrade from one membership level to another, the downgrade will not take affect until the billing anniversary date of your current membership plan, at which
              time, you will either be billed at the lower rate, or not at all if you downgrade to <b>VIP</b>.
             ",

"Q06"   =>   "What's the difference between a PUSHY! member and a PUSHY affiliate? ",
"A06"   =>   "Other than being pushy? <img src='http://pds1106.s3.amazonaws.com/images/wink.png' style='vertical-align: -4px'>&nbsp; Well, you can be a member without being an
             affiliate, simply by not referring anyone to PUSHY!'s Network. Affiliates get <a href=javascript:openPopup('/pop-comp-plan.php',660,700)>paid handsomely</a> by referring
             everyone they know to their PUSHY! affiliate website. Yours is at <b>$affiliate_website</b>.

             <p>Oh, and one more difference. PUSHY! affiliates have big smiles on their face from all the affiliate commissions they make, month after
             month, after month... just for referring them one time! <img src='http://pds1106.s3.amazonaws.com/images/smile.png' style='vertical-align: -4px'>
             ",

/*---------------------- DO NOT EDIT BELOW THIS LINE  ------------------------------------------------*/
"NULL" => "NULL"
);




/*--------------------- ********************* CATEGORY 3 ***************************------------------*/

$faq_data[3] = array(

/*---------------------- DO NOT EDIT ABOVE THIS LINE  ------------------------------------------------*/

"Q01"   =>   "Can I submit my PUSHY! affiliate site as an ad to run inside the PUSHY! widget? ",
"A01"   =>   "No! As an affiliate, you can place the PUSHY! widget on your website. The &quot;Get PUSHY!&quot; link at the bottom of the widget
              is linked to your PUSHY! affiliate site where YOUR website visitors can click thru and signup as members referred by YOU. If another
              PUSHY! affiliate's ad is running inside YOUR widget, it enables YOUR viewers to click thru to another PUSHY! affiliate's website instead
              of YOURS. Not very cool, right? <img src='http://pds1106.s3.amazonaws.com/images/wink.png' style='vertical-align: -4px'>
             ",

"Q02"   =>   "What is my &quot;Personal PUSHY! Network&quot;?  ",
"A02"   =>   "When you directly refer someone to your PUSHY! affiliate page, you are credited with certain activity from that referral. When your referrals in turn refer
              others to their PUSHY! affiliate page, they create a network of viewers for your ads. This referring activity can continue over and over
              again, creating a viral &quot;network&quot; of referrals that can theoretically grow quite large exponentially.

              <p>This network is referred to as your &quot;<b>Personal PUSHY! Network</b>&quot;. It is created by word-of-mouth advertising which can grow in size, and will
              be shown inside your <b>Reports</b> under the &quot;Network&quot; tab. When these word-of-mouth referrals grow past your 6th level, your &quot;Network&quot;
              report will expand past the 6th level to unlimited depths.

              <p>There are varying degrees of ad exposure and compensation related to PUSHY! affiliates. These benefits are spelled out in detail in the
              <a href=javascript:openPopup('/pop-comp-plan.php',660,700)>Compensation Plan</a>, as well as briefly in the
              <a href=javascript:openPopup('/members/pop-compare.php',630,700)>comparison chart</a>.
             ",

"Q03"   =>   "You refer to multiple levels and networks, when talking about referrals. Is this Network or Multi-Level Marketing?",
"A03"   =>   "In the traditional sense, no! Typically, network marketing companies have a payout structure that is based on multiple levels of compensation. When
              PUSHY! refers to multiple levels and networks, he is referring to the level of eyeballs viewing your ads. Each level or layer of referrals creates a new set
              of eyeballs. The total number of eyeballs residing on any level of referrals in your network is determined by each member or affiliate in the level above it.

              <p>PUSHY!'s method of multiple layer marketing, exposes your ads to 20, 30, 50, or theoretically hundreds of levels of referrals, each level gaining a
              potentially greater number of referrals on it, based on the unique PUSHY! algorithm. This algorithm compensates affiliate's with viewers, by giving their ads
              preference, based on how large their word-of-mouth network is when PUSHY!'s widgets are called to display ads.
             ",

"Q04"   =>   "What is the difference between &quot;actual&quot; and &quot;potential&quot; ad exposure?",
"A04"   =>   "Under the &quot;<b>Reports</b>&quot; tab, inside your &quot;Network&quot; report, you will see a chart showing &quot;Network Levels&quot;. Each level has a
              number of referrals on it, created by PUSHY! members above it. Based on your membership level, the PUSHY! Algorithm creates exposure for your ads to more or less
              network levels.

              <p>If you are a VIP member, your &quot;actual&quot; ad exposure is 2 levels of referrals inside PUSHY! widgets, with no PUSHY! Network exposure. PRO
              member's &quot;actual&quot; ad exposure is 5 levels of referrals inside PUSHY! widgets, with full PUSHY! Network exposure in the Credit Ad Pool. ELITE member's
              &quot;actual&quot; ad exposure is 5 levels of referrals inside PUSHY! widgets all across the entire web, with unlimited PUSHY! Network exposure in all of PUSHY!'s
              viral components, viral ad pools, and random selection of the PUSHY! algorithms in all <b>Viral Traffic</b> activity.

              <p>You could have hundreds, or thousands, or even hundreds of thousands of referrals, indicated by the &quot;<b>Total Ad Exposure</b>&quot; showing your
              &quot;potential&quot; ad exposure. The lower levels of membership limit the &quot;actual&quot; number of people who will view your ad. The higher your
              membership level, the closer your &quot;actual&quot; ad exposure will align with your &quot;potential&quot; ad exposure.
             ",

"Q05"   =>   "What are &quot;Ad Pools&quot;",
"A05"   =>   "Currently there are two &quot;Ad Pools&quot; that are part of the PUSHY! Algorithm which determines how often, when, and where ads are displayed
              inside PUSHY! widgets across the web.

              <p>The <b>Credit Ad Pool</b> is accessible only by <b>PRO</b> and <b>ELITE</b> members. Credits are associated with different activity created by the member,
              and differ in value according to the type of activity seen on the &quot;Credit&quot; tab inside your <b>Reports</b>. These credits represent a percentage of traffic
              in relation to all other <b>PRO</b> and <b>ELITE</b> members who have earned credits in this Ad Pool, which will be distributed to all qualifying members during
              the 7 days following the week it was earned. Traffic associated with this Ad Pool produces ads for members in positions 2 through 6 in PUSHY! widgets across the Network.

              <p>The <b>ELITE Ad Pool</b> is accessible only by <b>ELITE</b> members. All members of this pool share an equal percentage of the pool. Traffic associated
              with this Ad Pool produces ads for <b>ELITE</b> members in ad position #7 in PUSHY! widgets across the Network. Because there are fewer ELITE members
              than any other members, and because there is one position devoted solely to this part of PUSHY!'s Algorithm, this Ad Pool has the potential for a lot of traffic.
             ",

"Q06"   =>   "What is meant by &quot;Viral Components&quot;? ",
"A06"   =>   "There are a number of components designed into PUSHY!'s algorithm which cause traffic to be exponentially <b>drawn to</b>, as well as <b>drawn from</b> the PUSHY! Network.
              Ya, I know, this sounds like hocus pocus, or guru voodoo. The truth of the matter is that it's a proprietary algorithm (and quite the genius one, I might add ;-) that's
              based on virtually every successful traffic generation technique used on the Internet (including viral, targeted, hierarchical, and others). So don't believe for a second
              that PUSHY!'s just going to give it up to you, just for the asking <img src='http://pds1106.s3.amazonaws.com/images/silence.png' style='vertical-align: -4px'>

              <p>What's visible of these viral components are the Ad Pools, Ad Placement options, multiple layer marketing networks, incentives, and a myriad of secret
              algorithm techniques. OK, truth be known, there is a little guru voodoo involved here <img src='http://pds1106.s3.amazonaws.com/images/wink.png' style='vertical-align: -4px'>&nbsp;
              These are all combined to create perpetual leveraging of simple one-time submissions or entries, generating an exponential growth pattern in traffic. Add this all together
              and what you have are <b>viral components</b> creating <b>Viral Traffic</b> in PUSHY!'s vast Network of websites across the entire web, which are harder to stop
              (once started) than a steaming locomotive. And I'm absolutely serious!.
             ",

"Q07"   =>   "How do I get paid for doing things like placing PUSHY! widgets on my website, or referring people to PUSHY!'s Network?",
"A07"   =>   "Basically, you get paid 30% to 50% of the revenue generated from membership fees paid by your referrals. People you refer are
              sent to your own PUSHY! affiliate website, by emails, hyperlinks on your website, or by site visitors who click through the &quot;<b>Get PUSHY!</b>&quot; link on all
              your PUSHY! widgets displayed on web pages of your website. There are also INFINITY bonuses for ELITE Members. See
              <a href=javascript:openPopup('/pop-comp-plan.php',660,700)>Compensation Plan</a> for details.

              <p>Another form of compensation is by generating traffic for your ad(s) by use of <b>viral components</b> built into the PUSHY Algorithm. The best explanation of this is
              described by a snowball increasing in speed and size, on it's way down a hill, by the exponential growth and momentum caused by the force of gravity, at a certain angle.
              In terms of traffic to your product, service, blog, or opportunity, the result could create more traffic BACK to your site, than the traffic leaving your site, as it creates a viral
              effect caused by PUSHY!'s Algorithm.
             ",

"Q08"   =>   "How often do I get paid, and by what means?",
"A08"   =>   "PUSHY! pays twice a month. One payment will be made on the 20th day of the month, paid on all commissions earned on and between the 1st and 15th days prior. The
              other payment will be made on the 5th day of the following month commissions are earned on and between the 16th and last day of the previous month. All commissions
              are paid directly to affiliate's Paypal account. There is currently no other form of payment.
             ",


"Q09"   =>   "How do your cookies work towards helping me get credit for people I refer to PUSHY!? ",
"A09"   =>   "When someone comes to your PUSHY! affiliate website and signs up, they are forever linked to you, giving you credit for any purchases they make during the time they
              are a PUSHY! member. If someone you send to your PUSHY! affiliate site doesn't signup today, a cookie is saved on their computer, should they come back another day, or
              even if they go to the main website (without your affiliate identifier at the end of the URL).

              <p>Even if someone you sent to your affiliate site comes back 6 months from now, by way of a different PUSHY! affiliate website, they would continue to be linked to you
              as long as they didn't delete their cookies from their computer. This cookie is saved on their computer, linking them to you for up to a year later.
             ",

/*---------------------- DO NOT EDIT BELOW THIS LINE  ------------------------------------------------*/
"NULL" => "NULL"
);





/*--------------------- ********************* CATEGORY 4 ***************************------------------*/

$faq_data[4] = array(

/*---------------------- DO NOT EDIT ABOVE THIS LINE  ------------------------------------------------*/

"Q01"   =>   "How many different ads are viewed in a PUSHY! widget? ",
"A01"   =>   "During Pre-Launch, there will be up to 7 different ads scrolling through any PUSHY! widget across the Network of websites. Ad position #1 is reserved for the website
              owner. Ad positions #2 - #6 are reserved for the <b>Personal PUSHY! Networks</b> of members in the referral hierarchy above the site owner, according to PUSHY!'s
              proprietary algorithm. Ad position #7 is reserved for ads in the <b>ELITE Ad Pool</b>. When PUSHY! officially launches his network to the world, ad position #8 will open
              for paid insertion, and fully commissionable by affiliates (more details will follow as PUSHY! nears launch).
             ",

"Q02"   =>   "How many views will my ads get in your Network? ",
"A02"   =>   "That depends on a number of variables, entered into the PUSHY! Algorithm. Specific types of activity by you can cause the algorithm to favor your ads with
              increased traffic, such as increasing your membership level, referring others to PUSHY!, submitting ads in popular categories, selecting certain Product Ad
              Placements, and more.
             ",

"Q03"   =>   "Will all traffic to my ads result in targeted prospects viewing my landing page?",
"A03"   =>   "PUSHY!'s Network is very new. As his network increases in size, so will the number of targeted websites, with targeted visitors in each of his 47 categories.
              While he is still a youngster, his targeting ability won't be as fine tuned as it will be very soon. That's why he's paying such big commissions to affiliates
              who refer certain memberships in the early stages (not to mention, UNLIMITED traffic for affiliates in certain membership levels).
             ",

"Q04"   =>   "How long does it take for my ad submission or update to go live? ",
"A04"   =>   "Once your ad is approved, PUSHY!'s Algorithm begins to go to work immediately, displaying your ad in his vast Network of widgets on websites.
             ",

"Q05"   =>   "How does &quot;Product Ad Placement&quot; work?",
"A05"   =>   "<b>ELITE</b> members have the option to select 4 different Ad Placements for their ads:

              <ol>
                <li style='margin-bottom: 10px'><b>PUSHY! Network</b>, which targets audiences through widgets on PUSHY!'s Network of websites, </li>
                <li style='margin-bottom: 10px'><b>Affiliate Offers</b>, made up of approved affiliate products which any & all PUSHY! members can select as their own product
                  to advertise on the Network, </li>
                <li style='margin-bottom: 10px'><b>ELITE Bar</b>, which scrolls on the right hand column inside the backoffice, and seen by every PUSHY! member when in their backoffice,
                  and </li>
                <li style='margin-bottom: 10px'><b>ELITE Ad Pool</b>, shared equally by all ELITE members in the 7th ad position inside PUSHY! widgets across the Network</li>
              </ol>
             ",

"Q06"   =>   "What is the exact width and height of your containers for my product image?",
"A06"   =>   "Your image Inside the PUSHY! widget will vary in size, depending on which of the 19 different widget sizes it is viewed in. The smallest widget
              being 180 pixels wide, having a maximum image size of 47px wide by 57px high, and the largest widget being 360 pixels wide, having a maximum
              image size of 94px wide by 115px high.

              <p>As an <b>ELITE</b> member, your product image could be viewed inside the PUSHY! member backoffice in the <b>ELITE</b> Bar, seen on the right hand column
              of virtually every page at a maximum of 120px wide by 165px high. If you have an approved affiliate product (as an <b>ELITE</b> member), your image will
              be seen inside <b>MY ADS</b> tab, insde the the &quot;<b>Affiliate Offers</b>&quot; drop down list with a maximum width of 40px by 50px high.
             ",

"Q07"   =>   "What does &quot;aspect&quot; ratio mean in relation to my product images?",
"A07"   =>   "&quot;Aspect Ratio&quot; refers to the width of your image, in relation to its height, and can be arrived at by dividing the image's width
              into its height. For instance, if your image is 220 pixels wide and 320 pixels high, then its &quot;aspect ratio&quot; is 71%
             ",

"Q08"   =>   "When Previewing my ad during the insertion of my ad copy (<b>MY ADS</b>), why doesn't it all show up inside the PUSHY! Preview widget? ",
"A08"   =>   "This is by design. There are only 2 lines of text that VIP ads are allowed, and 3 lines of text, including the title, that PRO &
              ELITE ads are allowed. There are also only so many characters that can fit on each line allocated for your ad copy, inside the PUSHY!
              widget.

              <p>Because of the differing width of each alphabetic character, the number of characters that can fit on each line inside PUSHY!
              varies. When the amount of text reaches the limit on the PUSHY! widget, it wraps to the next line. If a word is longer than what can
              fit at the end of that particular line, it will wrap the entire word to the next line. If it's the 3rd line, the word will disappear, since the
              whole word won't fit on that line.

              <p>Lastly, there are 19 different widget sizes that website owners will choose from when configuring their PUSHY! widget for their website.
              These sizes range from 180 pixels to 360 pixels in width. These widths also vary, in that some will allow more ad copy than others.
              The Preview widget you see when constructing your ad copy shows you the MINIMUM amount of text that will show in ALL widgets,
              no matter what size. The <b>MY ADS</b> submission form allows you more text than what you will see inside the Preview, but only
              because SOME widgets (depending on their size) will allow an extra word or two than the Preview will show you.
             ",

"Q09"   =>   "What are the differences and similarities between &quot;Categories&quot; and &quot;Audience Interest&quot; in terms of targetting?",
"A09"   =>   "&quot;Categories&quot; are selected to best identify the proper section, or tags for your ads. Categories may also depict a classification for your products, services,
              blogs, or opportunity if someone was to search for them.

              <p>&quot;Audience Interest&quot; defines or identifies the type of content a website may contain as it relates to the interests of its viewers (website visitors). PUSHY!
              matches these audiences with the type of products categorized by advertisers to obtain targeting for their ads, creating qualified prospects.
             ",

"Q10"   =>   "Why do you limit the number of &quot;Categories&quot; for my ads? ",
"A10"   =>   "We're actually trying to prevent you from shooting yourself in the foot <img src='http://pds1106.s3.amazonaws.com/images/wink.png' style='vertical-align: -4px'>&nbsp;
             PUSHY! is all about targeting the right audience for your ads. The more &quot;Categories&quot; you select, the broader your audience interest, and less targeted your traffic.
             You may get greater volume by selecting more categories, but your prospect's interests will include a broader range than if you selected only one or two categories.
             ",


/*---------------------- DO NOT EDIT BELOW THIS LINE  ------------------------------------------------*/
"NULL" => "NULL"
);


?>
