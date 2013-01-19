  <?php 
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?>

<script language="javascript">

jQuery(document).ready(function() {
  var at = / at /;
  var dot = / dot /g;
  jQuery('a.mail').each(function () {
    var addr = $(this).text().replace(at,"@").replace(dot,".");
    jQuery(this).after('<a href="mailto:'+ addr +'" title="Send an email">'+ addr +'</a>');
    jQuery(this).remove();
  });
});

    </script>   
 
 <div id="ContentWrapper" class="TwoColumnLayout">
     <div class="FirstColumn">
<?php
                   $session = new Zend_Session_Namespace('userSession');
                    if($session->email != null){
                ?>
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginbox.php';?>

                        </div>
                    </div>

                   

                    <?php }else { ?>

                    <!--Me box Non-authenticated-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                            <?php include 'include/loginNonAuthBox.php';?>
                        </div>
                    </div>

                   

                      <!--Goalface Join Now-->
                    <div class="img-shadow">
                        <div class="WrapperForDropShadow">
                       <a href="<?php echo Zend_Registry::get("contextPath"); ?>/user/register" title="GoalFace Registration">
                           <img border="0" src="<?php echo Zend_Registry::get("contextPath"); ?>/public/images/join_now_green.jpg" style="margin-bottom:-3px;"/>
                        </a>
                        </div>
                    </div>
                    <?php } ?>

              </div><!--end FirstColumnOfThree-->

        <div class="SecondColumn" id="SecondColumnHighlightBox">
            <h1>Terms of Service</h1>

            <div id="SecondColumnHighlightBoxContentFooter">

                <h3>About GoalFace</h3>

           <p>Welcome to GoalFace.  The GoalFace service and network (collectively, "GoalFace" or "the service" or "Web site" or "site") are operated by GoalFace, Inc. and its corporate affiliates (collectively, "us", "we" or "Company"). GoalFace allows Members to create personal profiles to submit, rank, comment on and share their thoughts and opinions on the latest in global football while connecting with friends, teams and fans from around the world.</p>

<p>GoalFace is available to you whether or not you are a "member".  Both "visitors" and "members" are subject to the following Terms of Service ("Terms of Service", "TOS" or "Agreement").  By accessing or using our Web site, and/or any pages thereof at <a href="http://www.goalface.com/" target="_blank">www.goalface.com</a> by personal computer, mobile device or any other means electronic (together the "site") or by posting a Share Button on your site, you (the "User") signify that you have read, understand and agree to be bound by these TOS, whether or not you are a registered member of GoalFace. We reserve the right, at our sole discretion, to change, modify, add, or delete portions of these Terms of Service at any time without further notice. If we do this, we will post the changes to these Terms of Service on this page and will indicate the date these terms were last revised. Your continued use of the service or the site after any such changes constitutes your acceptance of the new Terms of Service. If you do not agree to abide by these or any future Terms of Service, do not use or access (or continue to use or access) the service or the site. Account termination requests can be made by e-mailing the GoalFace community manager (<a class="mail">community at goalface dot com</a>). It is your responsibility to regularly check the site to determine if there have been changes to these Terms of Service and to review such changes.</p>

<h3>Eligibility</h3>

<p>Membership in the service is void where prohibited. <strong>This site is intended solely for users who are thirteen (13) years of age or older. If you are under age 13 years, please do not attempt to register for GoalFace or send any information about yourself to us.</strong> We recommend that minors over the age of 13 ask their parents for permission before registering with GoalFace. Any registration by, use of or access to the site by anyone under 13 is unauthorized, unlicensed and in violation of these Terms of Service.<strong> By using the service or the site, you represent and warrant that you are 13 or older and that you agree to and to abide by all of the terms and conditions of this agreement. </strong> In consideration of your use of the service, you also represent that you are of legal age to form a binding contract and are not a person barred from receiving services under the laws of the United States or other applicable jurisdiction. International users agree to comply with all local rules regarding online conduct and acceptable content, including laws regulating the export of data from the United States or your country of residence. You are solely responsible for your conduct and any data, text, information, photos, video, links and other content ("materials") that you submit, post, and display on this service. By using the site, you represent and warrant that you have the right, authority, and capacity to enter into these Terms of Service and will abide by such Terms of Service.</p> 


<h3>Privacy Policy, Registration Data &amp; Member Account Security</h3>


<p>The site respects your privacy and permits you to control the treatment of your personal information. A complete statement of the site’s current privacy policy can be found by <a href="<?php echo Zend_Registry::get("contextPath"); ?>/privacy">clicking here</a>.</p>

<p>When you are required to open an account to use or access the site or service, you must complete the registration process by providing the complete and accurate information requested on the registration form.  In consideration of your use of the site, you agree to: (a) provide accurate, current and complete information about you as may be prompted by any registration forms on the site ("Registration Data"); (b) maintain the security of your password and identification; (c) maintain and promptly update the Registration Data, and any other information you provide, to keep it accurate, current and complete; and 
(d) be fully responsible for all use of your account and for any actions that take place using your account.  In consideration of your use of the site, you also agree to notify us immediately on any unauthorized use of your account, user name, or password. The Company shall not be liable for any loss that you incur as a result of someone else using your password, either with or without your knowledge. You may be held liable for any losses incurred by us, our affiliates, officers, directors, employees, consultants, agents, and representatives due to someone else’s use of your account or password.</p>


<h3>Proprietary Rights in site Content &amp; Limited License</h3>
                       
<p>GoalFace may make certain content and software available to you from the site. If you download content and software from the site, all content and software on the site and available through the service, including but not limited to designs, text, graphics, pictures, video, information, applications, software, music, sound and other files, and their selection and arrangement (the "site Content"), are the proprietary property of the Company, its users or its licensors with all rights reserved. All trademarks and logos are owned by GoalFace or its licensors and you may not copy or use them in any manner.  No site Content may be modified, copied, distributed, framed, reproduced, republished, downloaded, displayed, posted, transmitted, or sold in any form or by any means, in whole or in part, without the Company's prior written permission, except that the foregoing does not apply to your own User Content (as defined below) that you legally post on the site. Provided that you meet the eligibility requirements to use the site, you are granted a limited license to access and use the site and the site Content and to download or print a copy of any portion of the site Content to which you have properly gained access solely for your personal, non-commercial, home use, provided that you keep all copyright or other proprietary notices intact. Except for your own User Content, you may not sell, redistribute, reproduce the site Content, nor may you recompile, reverse-engineer, disassemble, or otherwise convert or republish the site Content to any Internet, Intranet or Extranet site or incorporate the information in any other database or compilation. Such license is subject to these Terms of Service and does not include use of any data mining, robots or similar data gathering or extraction methods. Any use of the site or the site Content other than as specifically authorized herein, without the prior written permission of Company, is strictly prohibited and will terminate the license granted herein. Such unauthorized use may also violate applicable laws including without limitation copyright and trademark laws and applicable communications regulations and statutes. Unless explicitly stated herein, nothing in these Terms of Service shall be construed as conferring any license to intellectual property rights, whether by estoppel, implication or otherwise. This license is revocable at any time without notice and with or without cause.</p>

<h3>Trademarks</h3>
                        
<p>GoalFace is a registered trademark &#064; of GoalFace, Inc.  GoalFace.com, its respective logos, and taglines, "For Fans of the Beautiful Game" and "Celebrating the Magic and Passion of the Beautiful Game," are trademarks and servicemarks of the Company in the U.S. and/or other countries.</p>
 
<p>Company's trademarks and servicemarks may not be used, including as part of trademarks and/or as part of domain names, in connection with any product or service in any manner that is likely to cause confusion and may not be copied, imitated, or used, in whole or in part, without the prior written permission of the Company. </p>

<h3>User Conduct</h3>
                        
<p>You understand that except for advertising programs offered by us on the site, the service and the site are available for your personal, non-commercial use only. You represent, warrant and agree that no materials of any kind submitted through your account or otherwise posted, transmitted, or shared by you on or through the service will violate or infringe upon the rights of any third party, including copyright, servicemark, trademark, privacy, publicity or other personal or proprietary rights; or contain libelous, defamatory or otherwise unlawful material. </p>

<p>YOU AGREE NOT TO OR ATTEMPT TO SPIDER OR COPY IN ANYWAY ANY DATA, INFORMATION, AND/OR CONTENT from this site, as such acts violate the terms and conditions that govern your use of this site.  If we notice excessive activity from a particular IP address we will be forced to take appropriate measures, which will include, but not be limited to, blocking that IP address. </p>

<p>In addition, you agree not to use the service or the site to: </p>

<ul>
	<li>harvest or collect email addresses or other contact information of other users from the service or the site by electronic or other means for the purposes of sending unsolicited emails or other unsolicited communications;</li>
	<li>use the service or the site in any unlawful manner or in any other manner that could damage, disable, overburden or impair the site;</li>
	<li>use automated scripts to collect information from or otherwise interact with the service or the site;</li>
	<li>upload, post, transmit, share, store or otherwise make available any content that we deem to be harmful, threatening, unlawful, defamatory, infringing, abusive, inflammatory, harassing, vulgar, obscene, fraudulent, invasive of privacy or publicity rights, hateful, or racially, ethnically or otherwise objectionable; </li>
	<li>upload, post, transmit, share, store or otherwise make available any videos other than those of a personal nature that: (i) are of you or your friends, (ii) are taken by you or your friends, or (iii) are original art or animation created by you or your friends;</li>
	<li>register for more than one User account, register for a User account on behalf of an individual other than yourself, or register for a User account on behalf of any group or entity;</li>
	<li>impersonate any person or entity, or falsely state or otherwise misrepresent yourself, your age or your affiliation with any person or entity; </li>
	<li>upload, post, transmit, share or otherwise make available any unsolicited or unauthorized advertising, solicitations, promotional materials, "junk mail," "spam," "chain letters," "pyramid schemes," or any other form of solicitation; </li>
	<li>upload, post, transmit, share, store or otherwise make publicly available on the site any private information of any third party, including, without limitation, addresses, phone numbers, email addresses, Social Security numbers and credit card numbers;</li>
	<li>solicit personal information from anyone under 18 or solicit passwords or personally identifying information for commercial or unlawful purposes;</li>
	<li>upload, post, transmit, share or otherwise make available any material that contains software viruses or any other computer code, files or programs designed to interrupt, destroy or limit the functionality of any computer software or hardware or telecommunications equipment; </li>
	<li>intimidate or harass another; </li>
	<li>upload, post, transmit, share, store or otherwise make available content that would constitute, encourage or provide instructions for a criminal offense, violate the rights of any party, or that would otherwise create liability or violate any local, state, national or international law;</li>
	<li>use or attempt to use another's account, service or system without authorization from the Company, or create a false identity on the service or the site.</li>
	<li>upload, post, transmit, share, store or otherwise make available content that, in the sole judgment of Company, is objectionable or which restricts or inhibits any other person from using or enjoying the site, or which may expose Company or its users to any harm or liability of any type.</li>
</ul>
                        
<p>Without limiting any of the foregoing, you also agree to abide by our "<a href="<?php echo Zend_Registry::get("contextPath"); ?>/guidelines">Rules of the Game</a>" that provides further information regarding the authorized conduct of users on GoalFace.</p>

<h3>SPECIAL ADMONITIONS FOR INTERNATIONAL USE</h3>

<p>Recognizing the global nature of the Internet, you agree to comply with all local rules regarding online conduct and acceptable Content. Specifically, you agree to comply with all applicable laws regarding the transmission of technical data exported from the United States and/or the country in which you reside. </p>

<h3>NO THIRD-PARTY BENEFICIARIES</h3>

<p>You agree that, except as otherwise expressly provided in this Terms of Service, there shall be no third-party beneficiaries to this agreement. </p>

<h3>NOTICE</h3>

<p>Company may provide you with notices, including those regarding changes to the Terms of Service, by email, regular mail or postings on the site.</p>


<h3>User Content Posted on the site</h3>

<p>You are solely responsible for the photos, profiles, messages, notes, text, information, music, video, advertisements, listings, and other content that you upload, publish or display (hereinafter, "post") on or through the service or the site, or transmit to or share with other users (collectively the "User Content"). Whenever you upload material to the site, or use the site to make contact with other users of the site you shall be solely responsible for your own User Content and the consequences of uploading it. You may not post, transmit, or share User Content on the site or service that you did not create or that you do not have permission to post. You warrant, represent and undertake that: (i) you own or have the necessary licences, rights, consents and permissions to use and authorize Company to use all patent, trademark, trade secret, copyright or other proprietary rights in and to any and all User Content to enable inclusion and use of the User Content in the manner contemplated by the site and these Terms of Service; and (ii) you have the written consent, release, and/or permission of each and every identifiable individual person in any User Content to use the name or likeness of each and every such identifiable individual person to enable inclusion and use of the User Content in the manner contemplated by the site and service and these Terms of Service. You understand and agree that the Company may, but is not obligated to, review the site and may delete or remove (without notice) any site Content or User Content in its sole discretion, for any reason or no reason, including without limitation User Content that in the sole judgment of the Company violates this Agreement or Rules of the Game (User Guidelines), which might be offensive, illegal, or that might violate the rights, harm, or threaten the safety of users or others. You are solely responsible at your sole cost and expense for creating backup copies and replacing any User Content you post or store on the site or provide to the Company. </p>

<p>When you post User Content to the site, you authorize and direct us to make such copies thereof as we deem necessary in order to facilitate the posting and storage of the User Content on the site. By posting User Content to any part of the site, you automatically grant, and you represent and warrant that you have the right to grant, to the Company an irrevocable, perpetual, non-exclusive, transferable, fully paid, worldwide license (with the right to sublicense) to use, copy, publicly perform, publicly display, reformat, translate, excerpt (in whole or in part) and distribute such User Content for any purpose on or in connection with the site or the promotion thereof, to prepare derivative works of, or incorporate into other works, such User Content, and to grant and authorize sublicenses of the foregoing. You may remove your User Content from the site at any time. If you choose to remove your User Content, the license granted above will automatically expire, however you acknowledge that the Company may retain archived copies of your User Content. </p>


<h3>Copyright Infringement</h3>

<p>We respect the intellectual property rights of others and it is and we prohibit users from uploading, posting or otherwise transmitting on the GoalFace site or service any materials that violate another party's copyright or intellectual property rights. We have adopted a policy that provides for the immediate suspension and/or termination of any site or service user who repeatedly infringes on the rights of GoalFace or of a third party, or otherwise violated any intellectual property laws or regulations. Contact details for how to contact GoalFace's Designated Agent to Receive Notification of Claimed Infringement ("Designated Agent") are provided at the bottom of this section.</p>


                        <ul>
                            <li>GoalFace Policy <br>
                                It is GoalFace's policy to (i) block access to or remove material that it believes in good faith to be copyrighted material that has been illegally copied and distributed by any of our advertisers, affiliates, Content providers, members or users; and (ii) remove and discontinue service to repeat offenders.   </li>
                         

                            <li>Procedure for Reporting Copyright Infringements  <br>
                                If you have evidence, know, or have a good faith belief that material or Content residing on or accessible through the GoalFace Site or Service infringes your copyright and/or intellectual property rights or the copyright and/or intellectual property rights of a third party, please send a notice of copyright infringement containing the following information to the Designated Agent listed below ("Proper Bona Fide Infringement Notification"):
                                <ol>
                                   <li>A physical or electronic signature of a person authorized to act on behalf of the owner of the copyright that has been allegedly infringed;</li>
									<li>Identification of works or materials being infringed;</li>
									<li>Identification of the material that is claimed to be infringing including information regarding the location of the infringing materials that the copyright owner seeks to have removed, with sufficient detail that we may find it on the website (in most circumstances, we will need a URL);</li>
									<li>Contact information about the notifier including address, telephone number and, if available, email address;</li>
									<li>A statement that the notifier has a good faith belief that the material is not authorized by the copyright or intellectual property owner, its agent, or the law; and</li>
									<li>A statement made under penalty of perjury that the information provided is accurate and the notifying party is authorized to make the complaint on behalf of the copyright or intellectual property owner.</li>
                                </ol>
                            </li>

                            <li>Once Proper Bona Fide Infringement Notification is received by the Designated Agent, it is GoalFace's policy:

                                <ol>
                                    <li>To remove or disable access to the infringing material; </li>
									<li>To notify the Content provider, member or user that it has removed or disabled access to the material; and</li> 
                    				<li>That repeat offenders will have the infringing material removed from the system and that GoalFace will terminate such Content provider's, member's or user's access to the service. </li>

                                </ol>
                            </li>
                            
                            <li>Procedure to Supply a Counter-Notice to the Designated Agent  <br>
If the Content provider, member or user believes that the material that was removed or to which access was disabled is either not infringing, or the Content provider, member or user believes that it has the right to post and use such material from the copyright or intellectual property owner, the its  agent, or pursuant to the law, the Content provider, member or user must send a counter-notice containing the following information to the Designated Agent listed below:



                              <ol>
                                <li>A physical or electronic signature of the Content provider, member or user; </li>
								<li>Identification of the material that has been removed or to which access to has been disabled and the location at which the material appeared before it was removed or disabled; </li>
								<li>A statement that the Content provider, member or user has a good faith belief that the material was removed or disabled as a result of mistake or a misidentification of the material; and </li>
								<li>The Content provider's, member's or user's name, address, telephone number, and, if available, email address and a statement that such person or entity consents to the jurisdiction of the Federal Court for the judicial district in which the Content provider's, member's or user's address is located, or if the Content provider's, member's or user's address is located outside the United States, for any judicial district in which GoalFace is located, and that such person or entity will accept service of process from the person who provided notification of the alleged infringement. </li>
                                </ol>
                             </li>


                            <li>Removal <br>
                           If a counter-notice is received by the Designated Agent, GoalFace may send a copy of the counter-notice to the original complaining party informing that person that it may replace the removed material or cease disabling it in 10 business days. Unless the copyright or intellectual property owner files an action seeking a court order against the Content provider, member or user, the removed material may be replaced or access to it restored in 10 to 14 business days or more after receipt of the counter-notice, at GoalFace's discretion. </li>

                        </ul>

                        <p>GoalFace's Designated Agent to receive notifications of claimed infringement is: </p>
                   
                    
                        <p>ATTN: Copyright Agent<br>
                        c/o GoalFace, Inc.<br>
                        3057 Nutley Street, Suite 152<br>
                        Fairfax, VA 22031<br>
                        United States of America<br>
                        email: <a class="mail">copyright at goalface dot com</a><br>
                        Fax: 877.803.7568 </p>

<p> Please note that only DMCA notices should go to the Copyright Agent; all other communication, feedback, comments, and/or requests for technical support should be sent to GoalFace customer service at <a class="mail">community at goalface dot com</a>. You acknowledge that if you fail to comply with all of the requirements, your DMCA notice may not be valid.</p>

<h3>Repeat Infringement Policy</h3>

<p>In accordance with the Digital Millennium Copyright Act or "DMCA" (posted at <a href="http://www.lcweb.loc.gov/copyright/legislation/dmca.pdf" target="_blank">http://www.lcweb.loc.gov/copyright/legislation/dmca.pdf</a>) and other applicable law, the Company has adopted a policy of terminating, in appropriate circumstances and at Company's sole discretion, members who have or are deemed to have committed repeat infringement. Company may also at its sole discretion limit access to the site and/or terminate the memberships of any users who infringe any intellectual property rights of others, whether or not there is any repeat infringement.</p>
                        
<h3>Links to Third Party Websites and Content</h3>
                        
<p>The site contains (or you may be sent through the site or the service) links to other web sites ("Third Party sites") as well as articles, photographs, text, graphics, pictures, designs, music, sound, video, information, applications, software and other content or items belonging to or originating from third parties (the "Third Party Applications, Software or Content"). Such Third Party sites and Third Party Applications, Software or Content are not investigated, monitored or checked for accuracy, appropriateness, or completeness by us, and we are not responsible or liable and we do not endorse any Third Party sites accessed through the site or any Third Party Applications, Software or Content posted on, available through or installed from the site, including without limitation the content, advertising, accuracy, offensiveness, opinions, reliability, privacy practices or other policies of or contained in the Third Party sites or the Third Party Applications, Software or Content. Inclusion of, linking to or permitting the use or installation of any Third Party site or any Third Party Applications, Software or Content does not imply approval or endorsement thereof by us. If you decide to leave the site and access the Third Party sites or to use or install any Third Party Applications, Software or Content, you do so at your own risk and you should be aware that our terms and policies no longer govern. You should review the applicable terms and policies, including privacy and data gathering practices, of any site to which you navigate from the site or relating to any applications you use or install from the site.</p>


<h3>Send and Share service</h3>

<p>Company offers a feature whereby users of the site can forward and/or share with others or post to their own member profile, videos, articles and other Third Party Applications, Software or Content from, and/or links to, Third Party sites through the service (the "Send and Share service"). You acknowledge and agree that your use of the Send and Share services and all links, User Content or Third Party Applications, Software or Content sent or shared through the Send Share service is subject to, and will fully comply with the user conduct rules set forth above and the other terms and conditions set forth in these Terms of Service.</p>

<h3>Use of Share Links by Online Content Providers</h3>

<p>Subject to the terms and conditions of these Terms of Service, Third Party sites that meet the requirements set forth below may place a Share Link (as described below), in the form approved by Company, on pages of their web sites to facilitate use of the Share service. A Third Party site that posts a Share Link on its web site is referred to herein as an "Online Content Provider" and shall abide and be subject to the applicable sections of these Terms of Service. A "Share Link" is a button and/or a text link appearing on an Online Content Provider's web page that, upon being clicked by a user, enables us to launch a sharing mechanism through which users can share with others or post to their own member profile, links and content from that page. </p>

<p>In the event that the Share Link is a button that contains any icons or other graphic images, trademarks or other proprietary materials of the Company, Online Content Provider is granted permission to use such images, trademarks or other materials solely for the purpose of placing the Share Link on Online Content Provider's site and solely in the current form provided by the Company. In the event that the Share Link is a text link, it must include the word "GoalFace" as part of the link. The rights granted in this paragraph may be revoked by Company at any time with or without cause in its sole discretion, and upon such termination, Online Content Provider agrees to immediately remove all Share Links from its site. </p>

<p>In order for an Online Content Provider to include a Share Link on its pages, the Third Party site must not contain any web content that if shared or posted by a user would be a violation of the user conduct rules set forth above. Without limiting the forgoing, Online Content Provider agrees not to post a Share Link on any web site that contains, and represents and warrants that such web site does not and will not contain, any content that is infringing, harmful, threatening, unlawful, defamatory, abusive, inflammatory, harassing, vulgar, obscene, lewd, fraudulent, or invasive of privacy or publicity rights or that may expose Company or its users to any harm or liability of any type. Upon including of a Share Link, Online Content Provider agrees to defend, indemnify and hold the Company, its subsidiaries and affiliates, and each of their directors, officers, agents, contractors, partners and employees, harmless from and against any loss, liability, claim, demand, damages, costs and expenses, including reasonable attorney's fees, arising out of or in connection with such Share Link, any links, content or other items or materials which may be shared or posted through such Share Link, or any breach or alleged breach of the foregoing representations and warranties. </p>

<p>By including a Share Link, Online Content Provider automatically grants, and represents and warrants that it has the right to grant, to the Company an irrevocable, perpetual, non-exclusive, transferable, fully paid, worldwide license (with the right to sublicense) to use the Share service in order to link to, use, copy, publish, stream, publicly perform, publicly display, reformat, translate, excerpt (in whole or in part), summarize, and distribute the content, links and other materials of any kind residing on any web pages on which Online Content Provider places the Share Link.</p>

<h3>DEALINGS WITH ADVERTISERS AND MERCHANTS </h3>

<p>Your correspondence or business dealings with, or participation in promotions of, any advertisers and merchants found on or through the site and service, including payment for and delivery of related goods or services, and any other terms, conditions, warranties or representations associated with such dealings, are solely between you and such advertiser or merchant. To the fullest extent permitted by applicable law, you agree that: (a) Company shall not be responsible or liable for any loss or damage of any kind incurred as the result of any such dealings or as the result of the presence of such advertisers and merchants on the site and service, and (b) any orders placed by you on, and any product specifications and product availability appearing on, the site and service (including, without limitation, any online store) are subject to confirmation by, and the terms and conditions of business of the relevant advertiser or merchant.</p>

<h3>User Disputes</h3>

<p>You are solely responsible for your interactions with other GoalFace users. We reserve the right, but have no obligation, to monitor disputes between you and other users.</p>




<h3>Privacy</h3>
                      
<p>  We care about the privacy of our users. <a href="<?php echo Zend_Registry::get("contextPath"); ?>/guidelines">Click here</a> to view the GoalFace's Privacy Policy. By using the site or the service, you are consenting to have your personal data transferred to and processed in the United States.</p>

<h3>Disclaimers</h3>

<p>The Company is not responsible or liable in any manner for any User Content or Third Party Applications, Software or Content posted on the site or in connection with the service, whether posted or caused by users of the site, by GoalFace, by third parties or by any of the equipment or programming associated with or utilized in the site or the service. Although we provide rules for user conduct and postings, we do not control and are not responsible for what users post, transmit or share on the site and are not responsible for any offensive, inappropriate, obscene, unlawful or otherwise objectionable content you may encounter on the site or in connection with any User Content or Third Party Applications, Software or Content. The Company is not responsible for the conduct, whether online or offline, of any user of the site or service. </p>

<p>The site and the service may be temporarily unavailable from time to time for maintenance or other reasons. Company assumes no responsibility for any error, omission, interruption, deletion, defect, delay in operation or transmission, communications line failure, theft or destruction or unauthorized access to, or alteration of, User communications. The Company is not responsible for any technical malfunction or other problems of any telephone network or service, computer systems, servers or providers, computer, mobile phone or other electronic equipment, software, failure of email or players on account of technical problems or traffic congestion on the Internet or at any site or combination thereof, including injury or damage to User's or to any other person's computer, mobile phone, or other hardware or software, related to or resulting from using or downloading materials in connection with the Web and/or in connection with the service, including without limitation any Mobile Client software. Under no circumstances will the Company be responsible for any loss or damage, including any loss or damage to any User Content or personal injury or death, resulting from anyone's use of the site or the service, any User Content or Third Party Applications, Software or Content posted on or through the site or the service or transmitted to Users, or any interactions between users of the site, whether online or offline. </p>

<p>THE SITE, THE SERVICE, ANY PLATFORM APPLICATIONS AND THE SITE CONTENT ARE PROVIDED "AS-IS" AND THE COMPANY DISCLAIMS ANY AND ALL REPRESENTATIONS AND WARRANTIES, WHETHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION IMPLIED WARRANTIES OF TITLE, MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE OR NON-INFRINGEMENT. THE COMPANY CANNOT GUARANTEE AND DOES NOT PROMISE ANY SPECIFIC RESULTS FROM USE OF THE SITE AND/OR THE SERVICE AND/OR ANY PLATFORM APPLICATIONS. COMPANY DOES NOT REPRESENT OR WARRANT THAT SOFTWARE, CONTENT OR MATERIALS ON THE SITE, THE SERVICE OR ANY PLATFORM APPLICATIONS ARE ACCURATE, COMPLETE, RELIABLE, CURRENT OR ERROR-FREE OR THAT THE SITE OR SERVICE ITS SERVERS, OR ANY PLATFORM APPLICATIONS ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. THEREFORE, YOU SHOULD EXERCISE CAUTION IN THE USE AND DOWNLOADING OF ANY SUCH SOFTWARE, CONTENT OR MATERIALS AND USE INDUSTRY-RECOGNIZED SOFTWARE TO DETECT AND DISINFECT VIRUSES. WITHOUT LIMITING THE FOREGOING, YOU UNDERSTAND AND AGREE THAT YOU DOWNLOAD OR OTHERWISE OBTAIN CONTENT, MATERIAL, DATA OR SOFTWARE (INCLUDING ANY MOBILE CLIENT) FROM OR THROUGH THE SERVICE AND ANY PLATFORM APPLICATIONS AT YOUR OWN DISCRETION AND RISK AND THAT YOU WILL BE SOLELY RESPONSIBLE FOR YOUR USE THEREOF AND ANY DAMAGES TO YOUR MOBILE DEVICE OR COMPUTER SYSTEM, LOSS OF DATA OR OTHER HARM OF ANY KIND THAT MAY RESULT. </p>

<p>The Company reserves the right to change any and all content, software and other items used or contained in the site and any services and Platform Applications offered through the site at any time without notice. Reference to any products, services, processes or other information, by trade name, trademark, manufacturer, supplier or otherwise does not constitute or imply endorsement, sponsorship or recommendation thereof, or any affiliation therewith, by Company. </p>

<h3>Limitation on Liability</h3>

<p>IN NO EVENT WILL COMPANY OR ITS DIRECTORS, EMPLOYEES OR AGENTS BE LIABLE TO YOU OR ANY THIRD PERSON FOR ANY INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES, INCLUDING FOR ANY LOST PROFITS OR LOST DATA ARISING FROM YOUR USE OF THE SITE OR THE SERVICE, ANY PLATFORM APPLICATIONS OR ANY OF THE SITE CONTENT OR OTHER MATERIALS ON, ACCESSED THROUGH OR DOWNLOADED FROM THE SITE, EVEN IF THE COMPANY IS AWARE OR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. NOTWITHSTANDING ANYTHING TO THE CONTRARY CONTAINED HEREIN, THE COMPANY'S LIABILITY TO YOU FOR ANY CAUSE WHATSOEVER, AND REGARDLESS OF THE FORM OF THE ACTION, WILL AT ALL TIMES BE LIMITED TO THE AMOUNT PAID, IF ANY, BY YOU TO COMPANY FOR THE SERVICE DURING THE TERM OF MEMBERSHIP, BUT IN NO CASE WILL THE COMPANY'S LIABILITY TO YOU EXCEED $300. YOU ACKNOWLEDGE THAT IF NO FEES ARE PAID TO COMPANY FOR THE SERVICE, YOU SHALL BE LIMITED TO INJUNCTIVE RELIEF ONLY, UNLESS OTHERWISE PERMITTED BY LAW, AND SHALL NOT BE ENTITLED TO DAMAGES OF ANY KIND FROM COMPANY, REGARDLESS OF THE CAUSE OF ACTION. </p>

<p>CERTAIN STATE LAWS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS, EXCLUSIONS OR LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MAY HAVE ADDITIONAL RIGHTS. </p>

<h3>Termination</h3>

<p>The Company may terminate your membership, delete your profile and any content or information that you have posted on the site or through any Platform Application and/or prohibit you from using or accessing the service or the site or any Platform Application (or any portion, aspect or feature of the service or the site or any Platform Application) for any reason, or no reason, at any time in its sole discretion, with or without notice, including without limitation if it believes that you are under 13 years of age. When we are notified that a user has died, we will generally, but are not obligated to, keep the user's account active under a special memorialized status for a period of time determined by us to allow other users to post and view comments.</p>

<h3>Copyright.</h3>
                        
<p>All contents of site or service are Copyright &copy; 2009 VG Media Group LLC. All rights reserved.</p>
                        
<h3>Governing Law, Venue and Jurisdiction</h3>

<p>By visiting or using the site and/or the service, you agree that the laws of the State of Virginia, without regard to principles of conflict of laws, will govern these Terms of Service and any dispute of any sort that might arise between you and the Company or any of our affiliates. With respect to any disputes or claims not subject to arbitration (as set forth below), you agree not to commence or prosecute any action in connection therewith other than in the state and federal courts of Virginia, and you hereby consent to, and waive all defenses of lack of personal jurisdiction and forum non conveniens with respect to, venue and jurisdiction in the state and federal courts of Virginia.</p>

<h3>Arbitration</h3>

<p>YOU AND COMPANY AGREE THAT, EXCEPT AS MAY OTHERWISE BE PROVIDED IN REGARD TO SPECIFIC SERVICES ON THE SITE IN ANY SPECIFIC TERMS APPLICABLE TO THOSE SERVICES, THE SOLE AND EXCLUSIVE FORUM AND REMEDY FOR ANY AND ALL DISPUTES AND CLAIMS RELATING IN ANY WAY TO OR ARISING OUT OF THESE TERMS OF SERVICE, THE SITE AND/OR THE SERVICE (INCLUDING YOUR VISIT TO OR USE OF THE SITE AND/OR THE SERVICE) SHALL BE FINAL AND BINDING ARBITRATION, except that: (a) to the extent that either of us has in any manner infringed upon or violated or threatened to infringe upon or violate the other party's patent, copyright, trademark or trade secret rights, or you have otherwise violated any of the user conduct rules set forth above or in the Code of Conduct then the parties acknowledge that arbitration is not an adequate remedy at law and that injunctive or other appropriate relief may be sought; and (b) no disputes or claims relating to any transactions you enter into with a third party through the site may be arbitrated. </p>

<p>Arbitration under this Agreement shall be conducted by the American Arbitration Association (the "AAA") under its Commercial Arbitration Rules and, in the case of consumer disputes, the AAA's Supplementary Procedures for Consumer Related Disputes ( the "AAA Consumer Rules") (collectively the "AAA Rules"). The location of the arbitration and the allocation of costs and fees for such arbitration shall be determined in accordance with such AAA Rules and shall be subject to the limitations provided for in the AAA Consumer Rules (for consumer disputes). If such costs are determined to be excessive in a consumer dispute, the Company will be responsible for paying all arbitration fees and arbitrator compensation in excess of what is deemed reasonable. The arbitrator's award shall be binding and may be entered as a judgment in any court of competent jurisdiction. </p>

<p>To the fullest extent permitted by applicable law, NO ARBITRATION OR CLAIM UNDER THESE TERMS OF SERVICE SHALL BE JOINED TO ANY OTHER ARBITRATION OR CLAIM, INCLUDING ANY ARBITRATION OR CLAIM INVOLVING ANY OTHER CURRENT OR FORMER USER OF THE SERVICE, AND NO CLASS ARBITRATION PROCEEDINGS SHALL BE PERMITTED. In no event shall any claim, action or proceeding by you related in any way to the site and/or the service (including your visit to or use of the site and/or the service) be instituted more than three (3) years after the cause of action arose.</p>

<h3>Indemnity</h3>

<p>You agree to indemnify and hold the Company, its subsidiaries and affiliates, and each of their directors, officers, agents, contractors, partners and employees, harmless from and against any loss, liability, claim, demand, damages, costs and expenses, including reasonable attorney's fees, arising out of or in connection with any User Content, any Third Party Applications, Software or Content you post or share on or through the site (including without limitation through the Share service), your use of the service or the site, your conduct in connection with the service or the site or with other users of the service or the site, or any violation of this Agreement or of any law or the rights of any third party. </p>

<h3>Submissions</h3>

<p>You acknowledge and agree that any questions, comments, suggestions, ideas, feedback or other information about the site or the service ("Submissions"), provided by you to Company are non-confidential and shall become the sole property of Company. Company shall own exclusive rights, including all intellectual property rights, and shall be entitled to the unrestricted use and dissemination of these Submissions for any purpose, commercial or otherwise, without acknowledgment or compensation to you.</p>

<h3>SEVERABILITY WAIVER. </h3>

<p>If, for whatever reason, a court of competent jurisdiction finds any term or condition in these Terms of Service to be unenforceable, all other terms and conditions will remain unaffected and in full force and effect. No waiver of any breach of any provision of these Terms of Service shall constitute a waiver of any prior, concurrent, or subsequent breach of the same or any other provisions hereof, and no waiver shall be effective unless made in writing and signed by an authorized representative of the waiving party.</p>

<h3>NO LICENSE. </h3>

<p>Nothing contained on the site should be understood as granting you a license to use any of the trademarks, service marks, or logos owned by GoalFace or by any third party.</p>

<h3>Questions</h3>

<p>Please visit our Help page for more information</p>

<h3>Other</h3>

<p>These Terms of Service constitute the entire agreement between you and Company regarding the use of the site and/or the service, superseding any prior agreements between you and Company relating to your use of the site or the service. The failure of Company to exercise or enforce any right or provision of these Terms of Service shall not constitute a waiver of such right or provision.</p>

<h3>ACKNOWLEDGEMENT. </h3>

<p>BY USING THE SERVICE OR ACCESSING THE SITE, YOU ACKNOWLEDGE THAT YOU HAVE READ THESE TERMS OF SERVICE AND AGREE TO BE BOUND BY THEM.</p>

<p>Effective Date: March 25, 2010<br />
Last Updated: March 25, 2010</p>


          </div>


            <div id="SecondColumnHighlightBoxContentBottomImage"></div>
        </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->



</div> <!--end ContentWrapper-->
