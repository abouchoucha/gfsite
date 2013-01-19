<?php require_once 'seourlgen.php';      require_once 'Zrad/cFacebook.php';	  require_once 'Zrad/Zrad_Facebook.php';

 	$urlGen = new SeoUrlGen();
 	$session = new Zend_Session_Namespace('userSession');
    $config = Zend_Registry::get ( 'config' );
 
?>
<script language="javascript" type="text/javascript">
<!--

var RecaptchaOptions = {
        theme : 'custom'
 };

function popitup(url) {
	newwindow=window.open(url,'name','height=600,width=850,scrollbars=1');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>

<div id="FormWrapper">

                <h3>Join GoalFace.  Join the fun.</h3>
                <div id="FormWrapperForBottomBackground">

                    <form id="join" name="register_form" method="POST" action="">
                    	<div id="FieldsetWrapper">
                    		<div id="systemWorking" style="text-align:center;display:none">
								<img src='<?php echo Zend_Registry::get("contextPath"); ?>/public/images/ajax-loader.gif'>
							</div>
							<div id="ErrorMessages" class="ErrorMessages">
                    		    <div id="MainErrorMessage">All fields are required.Please enter the fields highlighted below.</div>
								<span class="closemessage"></span>
	                     	</div>
					
							<p style="width:350px;padding-bottom:20px;font-size:12px;font-weight:bold;">Already a member?  <a href="<?php echo Zend_Registry::get("contextPath"); ?>/login" title="Sign In">Click here to log in</a>
							or Sign In using</p>
							
							<div id="fb-root"></div>	
							<div style="position:relative;top:-40px;left:325px;" >
								<div style="position:absolute;">					
									<fb:login-button size="medium" scope="<?php echo cFacebook::getPermission()?>">Facebook</fb:login-button>
								</div>
							</div>

                  <fieldset class="FirstColumnOfTwo" id="joinFieldset">
                  <div id="emailerror" class="ErrorMessageIndividual">You must enter an Email Address</div>
							    <label for="emailaddress">
									<em>* </em>Email Address:.:
								  </label>
								  <input class="text" type="text" id="emailadressRegisterForm" name="email" value="<?php echo $this->email;?>" required="email" autocomplete="off">
								  <br/>
								  <p>
									An email will be sent to this address to activate your account.
								  </p>
								  <div id="cemailerror"" class="ErrorMessageIndividual">You must re-enter your Email Address</div>
                  <label for="confirmemailaddress">
									<em>* </em>
                  Confirm Email:
								  </label>
								  <input class="text" type="text" id="confirmemailaddress" name="cemail" value="<?php echo $this->email;?>" required="email" autocomplete="off">
								  <p></p>
								  <div id="passworderror" class="ErrorMessageIndividual">You must enter a password</div>
								  <label for="password">
									<em>* </em>
                  Choose a Password:
								  </label>
								  <input type="password" class="text" id="password" name="password" size="18" value="<?php echo $this->password;?>" required="min:6">
								   <p></p>
								   <div id="password2error" class="ErrorMessageIndividual">You must re-enter your password</div>
								  <label for="password2">
									<em>*</em>
                  Confirm Password:
								  </label>
								   <input type="password" class="text" id="password2" name="password2" size="18" value="<?php echo $this->password2;?>" required="min:6">
									<br/>

								  <p>Must have a minimum of 6 characters</p>

                  <div id="birth_yearerror" class="ErrorMessageIndividual">Incorrect Date of Birth</div>
								  <label for="dateofBirth">
									<em>* </em>Date of Birth:
								  </label>
								  <select name="birth_month" id="birth_month">
								    <option selected value=''>(Month)
										<option value=01>January
					                    <option value=02>February
					                    <option value=03>March
					                    <option value=04>April
					                    <option value=05>May
					                    <option value=06>June
					                    <option value=07>July
					                    <option value=08>August
					                    <option value=09>September
					                    <option value=10>October
					                    <option value=11>November
					                    <option value=12>December</option>
									</select>
									<select name="birth_day" id="birth_day">
										<option selected value=''>(Day)
					                    <option value=01>01
					                    <option value=02>02
					                    <option value=03>03
					                    <option value=04>04
					                    <option value=05>05
					                    <option value=06>06
					                    <option value=07>07
					                    <option value=08>08
					                    <option value=09>09
					                    <option value=10>10
					                    <option value=11>11
					                    <option value=12>12
					                    <option value=13>13
					                    <option value=14>14
					                    <option value=15>15
					                    <option value=16>16
					                    <option value=17>17
					                    <option value=18>18
					                    <option value=19>19
					                    <option value=20>20
					                    <option value=21>21
					                    <option value=22>22
					                    <option value=23>23
					                    <option value=24>24
					                    <option value=25>25
					                    <option value=26>26
					                    <option value=27>27
					                    <option value=28>28
					                    <option value=29>29
					                    <option value=30>30
					                    <option value=31>31</option>
									</select>

									<?php
                      $year = date("Y");
                      $last_year = $year - 100;
                  ?>
									<select name="birth_year" id="birth_year" required="date:year">
                    <option selected value=''>(Year)
                  <?php  while ($year >= $last_year): ?>
                    <option value="<?php echo $year;?>"><?php echo $year;?>
                  <?php
                      $year--;
                      endwhile;
                    ?>
                  </select>

									<br/>
								  <label for="AllowOthers">

								  </label>
									<fieldset>
										<input class="checkbox" name="dob_check" type="checkbox" value="1" /><label for="AllowOtherToSeeMyBDay">Allow friends to see my birthday</label>
									</fieldset>


								  <p></p>
								  <div id="countryerror" class="ErrorMessageIndividual">Select the country you live in.</div>
								  <label for="Country">
									<em>* </em>Country:
								  </label>
									<select name="country" id="country" class="firstColumn" required="nn">
								  	<option value="">--- select ---</option>
                    				<?php foreach($this->countries as $rows) { ?>
								  	 <option value="<?php echo $rows["country_id"]; ?>"><?php echo $rows["country_name"]; ?></option>
                    <?php } ?>
									</select>


								  <p></p>
                  <div id="languageerror" class="ErrorMessageIndividual">Select your preferred language</div>
								  <label for="Language">
									<em>* </em>Preferred Language:
								  </label>
									<select class="firstColumn" name="language" id="language" required="nn">
                                        <option value="">--- select ---</option>
                                        <option value="1">English</option>
                                        <option value="2">Spanish</option>
                                    </select>

							   <p></p>
                                    <div id="recaptcha_response_fielderror" class="ErrorMessageIndividual">Enter the text that appears in the image below</div>
								  <label for="WordVerification">
									<em>* </em>
                                        Word Verification:
								  </label>
									Type the characters you see in the picture below.
									<p/>
								<div id="recaptcha_container">
								    
								    <input id="recaptcha_response_field" type="text" name="recaptcha_response_field" class="text" required="nn"/>
                                    <br>
								    <div id="recaptcha_image">

                                    </div>

								    <span>Choose Captcha format: <a href="javascript:Recaptcha.switch_type('image');">Image</a> or <a href="javascript:Recaptcha.switch_type('audio');">Audio</a> </span>
								
								    <input class="recaptchabtn" type="button" id="recaptcha_reload_btn" value="Get new words" onclick="Recaptcha.reload();" />
								</div>
								
								<script type="text/javascript" src="http://api.recaptcha.net/challenge?k=<?php echo $this->captchapublickey;?>"></script>
								
								<noscript>
								    <iframe src="http://api.recaptcha.net/noscript?k=<?php echo $this->captchapublickey;?>" height="300" width="500" frameborder="0"></iframe>
								
								    <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
								    <input type="hidden" name="recaptcha_response_field" value="manual_challenge" />
								
								</noscript>
								   <p></p>
                           
                                    <div id="termserror" class="ErrorMessageIndividual"></div>
								   <label for="TermsOfService">
									 Terms of Service:
								   </label>

								   <input type="checkbox" id="registerterms" name="registerterms" value="1" class="checkbox"/>I accept the
								   <a target="_blank" href="<?php echo Zend_Registry::get("contextPath"); ?>/terms" onclick="return popitup('http://www.goalface.com/terms.html')">Terms of Service</a> below.

									<textarea id="TermsOfService" name="TermsOfService" readonly="readonly" style="background:none repeat scroll 0 0 #F2F2F2; border:1px solid #CCCCCC;color:#333333;">
About GoalFace

Welcome to GoalFace.  The GoalFace service and network (collectively, "GoalFace" or "the service" or "Web site" or "site") are operated by GoalFace, Inc. and its corporate affiliates (collectively, "us", "we" or "Company"). GoalFace allows Members to create personal profiles to submit, rank, comment on and share their thoughts and opinions on the latest in global football while connecting with friends, teams and fans from around the world.

GoalFace is available to you whether or not you are a "member". Both "visitors" and "members" are subject to the following Terms of Service ("Terms of Service", "TOS" or "Agreement"). By accessing or using our Web site, and/or any pages thereof at www.goalface.com by personal computer, mobile device or any other means electronic (together the "site") or by posting a Share Button on your site, you (the "User") signify that you have read, understand and agree to be bound by these TOS, whether or not you are a registered member of GoalFace. We reserve the right, at our sole discretion, to change, modify, add, or delete portions of these Terms of Service at any time without further notice. If we do this, we will post the changes to these Terms of Service on this page and will indicate the date these terms were last revised. Your continued use of the service or the site after any such changes constitutes your acceptance of the new Terms of Service. If you do not agree to abide by these or any future Terms of Service, do not use or access (or continue to use or access) the service or the site. Account termination requests can be made by e-mailing the GoalFace community manager (community [at] goalface [dot] com). It is your responsibility to regularly check the site to determine if there have been changes to these Terms of Service and to review such changes.

Eligibility

Membership in the service is void where prohibited. This site is intended solely for users who are thirteen (13) years of age or older. If you are under age 13 years, please do not attempt to register for GoalFace or send any information about yourself to us. We recommend that minors over the age of 13 ask their parents for permission before registering with GoalFace. Any registration by, use of or access to the site by anyone under 13 is unauthorized, unlicensed and in violation of these Terms of Service. By using the service or the site, you represent and warrant that you are 13 or older and that you agree to abide by all of the terms and conditions of this agreement.  In consideration of your use of the service, you also represent that you are of legal age to form a binding contract and are not a person barred from receiving services under the laws of the United States or other applicable jurisdiction. International users agree to comply with all local rules regarding online conduct and acceptable content, including laws regulating the export of data from the United States or your country of residence. You are solely responsible for your conduct and any data, text, information, photos, video, links and other content ("materials") that you submit, post, and display on this service. By using the site, you represent and warrant that you have the right, authority, and capacity to enter into these Terms of Service and will abide by such Terms of Service.

Privacy Policy, Registration Data &amp; Member Account Security

The site respects your privacy and permits you to control the treatment of your personal information. A complete statement of the site’s current privacy policy can be found at http://www.goalface.com/privacy.html .

When you are required to open an account to use or access the site or service, you must complete the registration process by providing the complete and accurate information requested on the registration form.  In consideration of your use of the site, you agree to:

(a) provide accurate, current and complete information about you as may be prompted by any registration forms on the site ("Registration Data");
(b) maintain the security of your password and identification;
(c) maintain and promptly update the Registration Data, and any other information you provide, to keep it accurate, current and complete; and
(d) be fully responsible for all use of your account and for any actions that take place using your account.  In consideration of your use of the site, you also agree to notify us immediately on any unauthorized use of your account, user name, or password. The Company shall not be liable for any loss that you incur as a result of someone else using your password, either with or without your knowledge. You may be held liable for any losses incurred by us, our affiliates, officers, directors, employees, consultants, agents, and representatives due to someone else's use of your account or password.

Proprietary Rights in site Content &amp; Limited License

GoalFace may make certain content and software available to you from the site. If you download content and software from the site, all content and software on the site and available through the service, including but not limited to designs, text, graphics, pictures, video, information, applications, software, music, sound and other files, and their selection and arrangement (the "site Content"), are the proprietary property of the Company, its users or its licensors with all rights reserved. All trademarks and logos are owned by GoalFace or its licensors and you may not copy or use them in any manner.  No site Content may be modified, copied, distributed, framed, reproduced, republished, downloaded, displayed, posted, transmitted, or sold in any form or by any means, in whole or in part, without the Company's prior written permission, except that the foregoing does not apply to your own User Content (as defined below) that you legally post on the site. Provided that you meet the eligibility requirements to use the site, you are granted a limited license to access and use the site and the site Content and to download or print a copy of any portion of the site Content to which you have properly gained access solely for your personal, non-commercial, home use, provided that you keep all copyright or other proprietary notices intact. Except for your own User Content, you may not sell, redistribute, reproduce the site Content, nor may you recompile, reverse-engineer, disassemble, or otherwise convert or republish the site Content to any Internet, Intranet or Extranet site or incorporate the information in any other database or compilation. Such license is subject to these Terms of Service and does not include use of any data mining, robots or similar data gathering or extraction methods. Any use of the site or the site Content other than as specifically authorized herein, without the prior written permission of Company, is strictly prohibited and will terminate the license granted herein. Such unauthorized use may also violate applicable laws including without limitation copyright and trademark laws and applicable communications regulations and statutes. Unless explicitly stated herein, nothing in these Terms of Service shall be construed as conferring any license to intellectual property rights, whether by estoppel, implication or otherwise. This license is revocable at any time without notice and with or without cause.

Trademarks

GoalFace is a registered trademark &#064; of GoalFace, Inc.  GoalFace.com, its respective logos, and taglines, "For Fans of the Beautiful Game" and "Celebrating the Magic and Passion of the Beautiful Game," are trademarks and servicemarks of the Company in the U.S. and/or other countries.

Company's trademarks and servicemarks may not be used, including as part of trademarks and/or as part of domain names, in connection with any product or service in any manner that is likely to cause confusion and may not be copied, imitated, or used, in whole or in part, without the prior written permission of the Company. 

User Conduct

You understand that except for advertising programs offered by us on the site, the service and the site are available for your personal, non-commercial use only. You represent, warrant and agree that no materials of any kind submitted through your account or otherwise posted, transmitted, or shared by you on or through the service will violate or infringe upon the rights of any third party, including copyright, servicemark, trademark, privacy, publicity or other personal or proprietary rights; or contain libelous, defamatory or otherwise unlawful material. 

YOU AGREE NOT TO OR ATTEMPT TO SPIDER OR COPY IN ANYWAY ANY DATA, INFORMATION, AND/OR CONTENT from this site, as such acts violate the terms and conditions that govern your use of this site.  If we notice excessive activity from a particular IP address we will be forced to take appropriate measures, which will include, but not be limited to, blocking that IP address. 

In addition, you agree not to use the service or the site to:

    &#8226;&nbsp;harvest or collect email addresses or other contact information of other users from the service or the site by electronic or other means for the purposes of sending unsolicited emails or other unsolicited communications;
    &#8226;&nbsp;use the service or the site in any unlawful manner or in any other manner that could damage, disable, overburden or impair the site;
    &#8226;&nbsp;use automated scripts to collect information from or otherwise interact with the service or the site;
    &#8226;&nbsp;upload, post, transmit, share, store or otherwise make available any content that we deem to be harmful, threatening, unlawful, defamatory, infringing, abusive, inflammatory, harassing, vulgar, obscene, fraudulent, invasive of privacy or publicity rights, hateful, or racially, ethnically or otherwise objectionable;
    &#8226;&nbsp;upload, post, transmit, share, store or otherwise make available any videos other than those of a personal nature that: (i) are of you or your friends, (ii) are taken by you or your friends, or (iii) are original art or animation created by you or your friends;
    &#8226;&nbsp;register for more than one User account, register for a User account on behalf of an individual other than yourself, or register for a User account on behalf of any group or entity;
    &#8226;&nbsp;impersonate any person or entity, or falsely state or otherwise misrepresent yourself, your age or your affiliation with any person or entity;
    &#8226;&nbsp;upload, post, transmit, share or otherwise make available any unsolicited or unauthorized advertising, solicitations, promotional materials, "junk mail," "spam," "chain letters," "pyramid schemes," or any other form of solicitation;
    &#8226;&nbsp;upload, post, transmit, share, store or otherwise make publicly available on the site any private information of any third party, including, without limitation, addresses, phone numbers, email addresses, Social Security numbers and credit card numbers;
    &#8226;&nbsp;solicit personal information from anyone under 18 or solicit passwords or personally identifying information for commercial or unlawful purposes;
    &#8226;&nbsp;upload, post, transmit, share or otherwise make available any material that contains software viruses or any other computer code, files or programs designed to interrupt, destroy or limit the functionality of any computer software or hardware or telecommunications equipment;
    &#8226;&nbsp;intimidate or harass another;
    &#8226;&nbsp;upload, post, transmit, share, store or otherwise make available content that would constitute, encourage or provide instructions for a criminal offense, violate the rights of any party, or that would otherwise create liability or violate any local, state, national or international law;
    &#8226;&nbsp;use or attempt to use another's account, service or system without authorization from the Company, or create a false identity on the service or the site.
    &#8226;&nbsp;upload, post, transmit, share, store or otherwise make available content that, in the sole judgment of Company, is objectionable or which restricts or inhibits any other person from using or enjoying the site, or which may expose Company or its users to any harm or liability of any type.

Without limiting any of the foregoing, you also agree to abide by our "Rules of the Game" that provides further information regarding the authorized conduct of users on GoalFace.

SPECIAL ADMONITIONS FOR INTERNATIONAL USE

Recognizing the global nature of the Internet, you agree to comply with all local rules regarding online conduct and acceptable Content. Specifically, you agree to comply with all applicable laws regarding the transmission of technical data exported from the United States and/or the country in which you reside.

NO THIRD-PARTY BENEFICIARIES

You agree that, except as otherwise expressly provided in this Terms of Service, there shall be no third-party beneficiaries to this agreement.

NOTICE

Company may provide you with notices, including those regarding changes to the Terms of Service, by email, regular mail or postings on the site.

User Content Posted on the site

You are solely responsible for the photos, profiles, messages, notes, text, information, music, video, advertisements, listings, and other content that you upload, publish or display (hereinafter, "post") on or through the service or the site, or transmit to or share with other users (collectively the "User Content"). Whenever you upload material to the site, or use the site to make contact with other users of the site you shall be solely responsible for your own User Content and the consequences of uploading it. You may not post, transmit, or share User Content on the site or service that you did not create or that you do not have permission to post. You warrant, represent and undertake that: (i) you own or have the necessary licences, rights, consents and permissions to use and authorize Company to use all patent, trademark, trade secret, copyright or other proprietary rights in and to any and all User Content to enable inclusion and use of the User Content in the manner contemplated by the site and these Terms of Service; and (ii) you have the written consent, release, and/or permission of each and every identifiable individual person in any User Content to use the name or likeness of each and every such identifiable individual person to enable inclusion and use of the User Content in the manner contemplated by the site and service and these Terms of Service. You understand and agree that the Company may, but is not obligated to, review the site and may delete or remove (without notice) any site Content or User Content in its sole discretion, for any reason or no reason, including without limitation User Content that in the sole judgment of the Company violates this Agreement or Rules of the Game (User Guidelines), which might be offensive, illegal, or that might violate the rights, harm, or threaten the safety of users or others. You are solely responsible at your sole cost and expense for creating backup copies and replacing any User Content you post or store on the site or provide to the Company.

When you post User Content to the site, you authorize and direct us to make such copies thereof as we deem necessary in order to facilitate the posting and storage of the User Content on the site. By posting User Content to any part of the site, you automatically grant, and you represent and warrant that you have the right to grant, to the Company an irrevocable, perpetual, non-exclusive, transferable, fully paid, worldwide license (with the right to sublicense) to use, copy, publicly perform, publicly display, reformat, translate, excerpt (in whole or in part) and distribute such User Content for any purpose on or in connection with the site or the promotion thereof, to prepare derivative works of, or incorporate into other works, such User Content, and to grant and authorize sublicenses of the foregoing. You may remove your User Content from the site at any time. If you choose to remove your User Content, the license granted above will automatically expire, however you acknowledge that the Company may retain archived copies of your User Content.

Copyright Infringement

We respect the intellectual property rights of others and it is and we prohibit users from uploading, posting or otherwise transmitting on the GoalFace site or service any materials that violate another party's copyright or intellectual property rights. We have adopted a policy that provides for the immediate suspension and/or termination of any site or service user who repeatedly infringes on the rights of GoalFace or of a third party, or otherwise violated any intellectual property laws or regulations. Contact details for how to contact GoalFace's Designated Agent to Receive Notification of Claimed Infringement ("Designated Agent") are provided at the bottom of this section.

    &#8226;&nbsp;GoalFace Policy 
       It is GoalFace's policy to (i) block access to or remove material that it believes in good faith to be copyrighted material that has been illegally copied and distributed by any of our advertisers, affiliates, Content providers, members or users; and (ii) remove and discontinue service to repeat offenders.

    &#8226;&nbsp;Procedure for Reporting Copyright Infringements  
       If you have evidence, know, or have a good faith belief that material or Content residing on or accessible through the GoalFace Site or Service infringes your copyright and/or intellectual property rights or the copyright and/or intellectual property rights of a third party, please send a notice of copyright infringement containing the following information to the Designated Agent listed below ("Proper Bona Fide Infringement Notification"):
       
            (1) A physical or electronic signature of a person authorized to act on behalf of the owner of the copyright that has been allegedly infringed;
            (2) Identification of works or materials being infringed;
            (3) Identification of the material that is claimed to be infringing including information regarding the location of the infringing materials that the copyright owner seeks to have removed, with sufficient detail that we may find it on the website (in most circumstances, we will need a URL);
            (4) Contact information about the notifier including address, telephone number and, if available, email address;
            (5) A statement that the notifier has a good faith belief that the material is not authorized by the copyright or intellectual property owner, its agent, or the law; and
            (6) A statement made under penalty of perjury that the information provided is accurate and the notifying party is authorized to make the complaint on behalf of the copyright or intellectual property owner.

    &#8226;&nbsp;Once Proper Bona Fide Infringement Notification is received by the Designated Agent, it is GoalFace's policy:

           (1) To remove or disable access to the infringing material;
           (2) To notify the Content provider, member or user that it has removed or disabled access to the material; and
           (3) That repeat offenders will have the infringing material removed from the system and that GoalFace will terminate such Content provider's, member's or user's access to the service.

    &#8226;&nbsp;Procedure to Supply a Counter-Notice to the Designated Agent  
       If the Content provider, member or user believes that the material that was removed or to which access was disabled is either not infringing, or the Content provider, member or user believes that it has the right to post and use such material from the copyright or intellectual property owner, the its  agent, or pursuant to the law, the Content provider, member or user must send a counter-notice containing the following information to the Designated Agent listed below:

           (1) A physical or electronic signature of the Content provider, member or user;
           (2) Identification of the material that has been removed or to which access to has been disabled and the location at which the material appeared before it was removed or disabled;
           (3) A statement that the Content provider, member or user has a good faith belief that the material was removed or disabled as a result of mistake or a misidentification of the material; and
           (4) The Content provider's, member's or user's name, address, telephone number, and, if available, email address and a statement that such person or entity consents to the jurisdiction of the Federal Court for the judicial district in which the Content provider's, member's or user's address is located, or if the Content provider's, member's or user's address is located outside the United States, for any judicial district in which GoalFace is located, and that such person or entity will accept service of process from the person who provided notification of the alleged infringement.

   &#8226;&nbsp;Removal
       If a counter-notice is received by the Designated Agent, GoalFace may send a copy of the counter-notice to the original complaining party informing that person that it may replace the removed material or cease disabling it in 10 business days. Unless the copyright or intellectual property owner files an action seeking a court order against the Content provider, member or user, the removed material may be replaced or access to it restored in 10 to 14 business days or more after receipt of the counter-notice, at GoalFace's discretion.

GoalFace's Designated Agent to receive notifications of claimed infringement is: 

ATTN: Copyright Agent
c/o GoalFace, Inc.
3057 Nutley Street, Suite 152
Fairfax, VA 22031
United States of America
email: copyright at goalface dot com
Fax: 877.803.7568 

Please note that only DMCA notices should go to the Copyright Agent; all other communication, feedback, comments, and/or requests for technical support should be sent to GoalFace customer service at <a class="mail">community at goalface dot com</a>. You acknowledge that if you fail to comply with all of the requirements, your DMCA notice may not be valid.

Repeat Infringement Policy

In accordance with the Digital Millennium Copyright Act or "DMCA" (posted at http://www.lcweb.loc.gov/copyright/legislation/dmca.pdf) and other applicable law, the Company has adopted a policy of terminating, in appropriate circumstances and at Company's sole discretion, members who have or are deemed to have committed repeat infringement. Company may also at its sole discretion limit access to the site and/or terminate the memberships of any users who infringe any intellectual property rights of others, whether or not there is any repeat infringement.

Links to Third Party Websites and Content

The site contains (or you may be sent through the site or the service) links to other web sites ("Third Party sites") as well as articles, photographs, text, graphics, pictures, designs, music, sound, video, information, applications, software and other content or items belonging to or originating from third parties (the "Third Party Applications, Software or Content"). Such Third Party sites and Third Party Applications, Software or Content are not investigated, monitored or checked for accuracy, appropriateness, or completeness by us, and we are not responsible or liable and we do not endorse any Third Party sites accessed through the site or any Third Party Applications, Software or Content posted on, available through or installed from the site, including without limitation the content, advertising, accuracy, offensiveness, opinions, reliability, privacy practices or other policies of or contained in the Third Party sites or the Third Party Applications, Software or Content. Inclusion of, linking to or permitting the use or installation of any Third Party site or any Third Party Applications, Software or Content does not imply approval or endorsement thereof by us. If you decide to leave the site and access the Third Party sites or to use or install any Third Party Applications, Software or Content, you do so at your own risk and you should be aware that our terms and policies no longer govern. You should review the applicable terms and policies, including privacy and data gathering practices, of any site to which you navigate from the site or relating to any applications you use or install from the site.

Send and Share service

Company offers a feature whereby users of the site can forward and/or share with others or post to their own member profile, videos, articles and other Third Party Applications, Software or Content from, and/or links to, Third Party sites through the service (the "Send and Share service"). You acknowledge and agree that your use of the Send and Share services and all links, User Content or Third Party Applications, Software or Content sent or shared through the Send Share service is subject to, and will fully comply with the user conduct rules set forth above and the other terms and conditions set forth in these Terms of Service.

Use of Share Links by Online Content Providers

Subject to the terms and conditions of these Terms of Service, Third Party sites that meet the requirements set forth below may place a Share Link (as described below), in the form approved by Company, on pages of their web sites to facilitate use of the Share service. A Third Party site that posts a Share Link on its web site is referred to herein as an "Online Content Provider" and shall abide and be subject to the applicable sections of these Terms of Service. A "Share Link" is a button and/or a text link appearing on an Online Content Provider's web page that, upon being clicked by a user, enables us to launch a sharing mechanism through which users can share with others or post to their own member profile, links and content from that page.

In the event that the Share Link is a button that contains any icons or other graphic images, trademarks or other proprietary materials of the Company, Online Content Provider is granted permission to use such images, trademarks or other materials solely for the purpose of placing the Share Link on Online Content Provider's site and solely in the current form provided by the Company. In the event that the Share Link is a text link, it must include the word "GoalFace" as part of the link. The rights granted in this paragraph may be revoked by Company at any time with or without cause in its sole discretion, and upon such termination, Online Content Provider agrees to immediately remove all Share Links from its site.

In order for an Online Content Provider to include a Share Link on its pages, the Third Party site must not contain any web content that if shared or posted by a user would be a violation of the user conduct rules set forth above. Without limiting the forgoing, Online Content Provider agrees not to post a Share Link on any web site that contains, and represents and warrants that such web site does not and will not contain, any content that is infringing, harmful, threatening, unlawful, defamatory, abusive, inflammatory, harassing, vulgar, obscene, lewd, fraudulent, or invasive of privacy or publicity rights or that may expose Company or its users to any harm or liability of any type. Upon including of a Share Link, Online Content Provider agrees to defend, indemnify and hold the Company, its subsidiaries and affiliates, and each of their directors, officers, agents, contractors, partners and employees, harmless from and against any loss, liability, claim, demand, damages, costs and expenses, including reasonable attorney's fees, arising out of or in connection with such Share Link, any links, content or other items or materials which may be shared or posted through such Share Link, or any breach or alleged breach of the foregoing representations and warranties.

By including a Share Link, Online Content Provider automatically grants, and represents and warrants that it has the right to grant, to the Company an irrevocable, perpetual, non-exclusive, transferable, fully paid, worldwide license (with the right to sublicense) to use the Share service in order to link to, use, copy, publish, stream, publicly perform, publicly display, reformat, translate, excerpt (in whole or in part), summarize, and distribute the content, links and other materials of any kind residing on any web pages on which Online Content Provider places the Share Link.

DEALINGS WITH ADVERTISERS AND MERCHANTS

Your correspondence or business dealings with, or participation in promotions of, any advertisers and merchants found on or through the site and service, including payment for and delivery of related goods or services, and any other terms, conditions, warranties or representations associated with such dealings, are solely between you and such advertiser or merchant. To the fullest extent permitted by applicable law, you agree that: (a) Company shall not be responsible or liable for any loss or damage of any kind incurred as the result of any such dealings or as the result of the presence of such advertisers and merchants on the site and service, and (b) any orders placed by you on, and any product specifications and product availability appearing on, the site and service (including, without limitation, any online store) are subject to confirmation by, and the terms and conditions of business of the relevant advertiser or merchant.

User Disputes

You are solely responsible for your interactions with other GoalFace users. We reserve the right, but have no obligation, to monitor disputes between you and other users.

Privacy

We care about the privacy of our users. By using the site or the service, you are consenting to have your personal data transferred to and processed in the United States.

Disclaimers

The Company is not responsible or liable in any manner for any User Content or Third Party Applications, Software or Content posted on the site or in connection with the service, whether posted or caused by users of the site, by GoalFace, by third parties or by any of the equipment or programming associated with or utilized in the site or the service. Although we provide rules for user conduct and postings, we do not control and are not responsible for what users post, transmit or share on the site and are not responsible for any offensive, inappropriate, obscene, unlawful or otherwise objectionable content you may encounter on the site or in connection with any User Content or Third Party Applications, Software or Content. The Company is not responsible for the conduct, whether online or offline, of any user of the site or service.

The site and the service may be temporarily unavailable from time to time for maintenance or other reasons. Company assumes no responsibility for any error, omission, interruption, deletion, defect, delay in operation or transmission, communications line failure, theft or destruction or unauthorized access to, or alteration of, User communications. The Company is not responsible for any technical malfunction or other problems of any telephone network or service, computer systems, servers or providers, computer, mobile phone or other electronic equipment, software, failure of email or players on account of technical problems or traffic congestion on the Internet or at any site or combination thereof, including injury or damage to User's or to any other person's computer, mobile phone, or other hardware or software, related to or resulting from using or downloading materials in connection with the Web and/or in connection with the service, including without limitation any Mobile Client software. Under no circumstances will the Company be responsible for any loss or damage, including any loss or damage to any User Content or personal injury or death, resulting from anyone's use of the site or the service, any User Content or Third Party Applications, Software or Content posted on or through the site or the service or transmitted to Users, or any interactions between users of the site, whether online or offline.

THE SITE, THE SERVICE, ANY PLATFORM APPLICATIONS AND THE SITE CONTENT ARE PROVIDED "AS-IS" AND THE COMPANY DISCLAIMS ANY AND ALL REPRESENTATIONS AND WARRANTIES, WHETHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION IMPLIED WARRANTIES OF TITLE, MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE OR NON-INFRINGEMENT. THE COMPANY CANNOT GUARANTEE AND DOES NOT PROMISE ANY SPECIFIC RESULTS FROM USE OF THE SITE AND/OR THE SERVICE AND/OR ANY PLATFORM APPLICATIONS. COMPANY DOES NOT REPRESENT OR WARRANT THAT SOFTWARE, CONTENT OR MATERIALS ON THE SITE, THE SERVICE OR ANY PLATFORM APPLICATIONS ARE ACCURATE, COMPLETE, RELIABLE, CURRENT OR ERROR-FREE OR THAT THE SITE OR SERVICE ITS SERVERS, OR ANY PLATFORM APPLICATIONS ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. THEREFORE, YOU SHOULD EXERCISE CAUTION IN THE USE AND DOWNLOADING OF ANY SUCH SOFTWARE, CONTENT OR MATERIALS AND USE INDUSTRY-RECOGNIZED SOFTWARE TO DETECT AND DISINFECT VIRUSES. WITHOUT LIMITING THE FOREGOING, YOU UNDERSTAND AND AGREE THAT YOU DOWNLOAD OR OTHERWISE OBTAIN CONTENT, MATERIAL, DATA OR SOFTWARE (INCLUDING ANY MOBILE CLIENT) FROM OR THROUGH THE SERVICE AND ANY PLATFORM APPLICATIONS AT YOUR OWN DISCRETION AND RISK AND THAT YOU WILL BE SOLELY RESPONSIBLE FOR YOUR USE THEREOF AND ANY DAMAGES TO YOUR MOBILE DEVICE OR COMPUTER SYSTEM, LOSS OF DATA OR OTHER HARM OF ANY KIND THAT MAY RESULT.

The Company reserves the right to change any and all content, software and other items used or contained in the site and any services and Platform Applications offered through the site at any time without notice. Reference to any products, services, processes or other information, by trade name, trademark, manufacturer, supplier or otherwise does not constitute or imply endorsement, sponsorship or recommendation thereof, or any affiliation therewith, by Company.

Limitation on Liability

IN NO EVENT WILL COMPANY OR ITS DIRECTORS, EMPLOYEES OR AGENTS BE LIABLE TO YOU OR ANY THIRD PERSON FOR ANY INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL OR PUNITIVE DAMAGES, INCLUDING FOR ANY LOST PROFITS OR LOST DATA ARISING FROM YOUR USE OF THE SITE OR THE SERVICE, ANY PLATFORM APPLICATIONS OR ANY OF THE SITE CONTENT OR OTHER MATERIALS ON, ACCESSED THROUGH OR DOWNLOADED FROM THE SITE, EVEN IF THE COMPANY IS AWARE OR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. NOTWITHSTANDING ANYTHING TO THE CONTRARY CONTAINED HEREIN, THE COMPANY'S LIABILITY TO YOU FOR ANY CAUSE WHATSOEVER, AND REGARDLESS OF THE FORM OF THE ACTION, WILL AT ALL TIMES BE LIMITED TO THE AMOUNT PAID, IF ANY, BY YOU TO COMPANY FOR THE SERVICE DURING THE TERM OF MEMBERSHIP, BUT IN NO CASE WILL THE COMPANY'S LIABILITY TO YOU EXCEED $300. YOU ACKNOWLEDGE THAT IF NO FEES ARE PAID TO COMPANY FOR THE SERVICE, YOU SHALL BE LIMITED TO INJUNCTIVE RELIEF ONLY, UNLESS OTHERWISE PERMITTED BY LAW, AND SHALL NOT BE ENTITLED TO DAMAGES OF ANY KIND FROM COMPANY, REGARDLESS OF THE CAUSE OF ACTION.

CERTAIN STATE LAWS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS, EXCLUSIONS OR LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MAY HAVE ADDITIONAL RIGHTS.

Termination

The Company may terminate your membership, delete your profile and any content or information that you have posted on the site or through any Platform Application and/or prohibit you from using or accessing the service or the site or any Platform Application (or any portion, aspect or feature of the service or the site or any Platform Application) for any reason, or no reason, at any time in its sole discretion, with or without notice, including without limitation if it believes that you are under 13 years of age. When we are notified that a user has died, we will generally, but are not obligated to, keep the user's account active under a special memorialized status for a period of time determined by us to allow other users to post and view comments.

Copyright.

All contents of site or service are Copyright &copy; 2009 VG Media Group LLC. All rights reserved.

Governing Law, Venue and Jurisdiction

By visiting or using the site and/or the service, you agree that the laws of the State of Virginia, without regard to principles of conflict of laws, will govern these Terms of Service and any dispute of any sort that might arise between you and the Company or any of our affiliates. With respect to any disputes or claims not subject to arbitration (as set forth below), you agree not to commence or prosecute any action in connection therewith other than in the state and federal courts of Virginia, and you hereby consent to, and waive all defenses of lack of personal jurisdiction and forum non conveniens with respect to, venue and jurisdiction in the state and federal courts of Virginia.

Arbitration

YOU AND COMPANY AGREE THAT, EXCEPT AS MAY OTHERWISE BE PROVIDED IN REGARD TO SPECIFIC SERVICES ON THE SITE IN ANY SPECIFIC TERMS APPLICABLE TO THOSE SERVICES, THE SOLE AND EXCLUSIVE FORUM AND REMEDY FOR ANY AND ALL DISPUTES AND CLAIMS RELATING IN ANY WAY TO OR ARISING OUT OF THESE TERMS OF SERVICE, THE SITE AND/OR THE SERVICE (INCLUDING YOUR VISIT TO OR USE OF THE SITE AND/OR THE SERVICE) SHALL BE FINAL AND BINDING ARBITRATION, except that: (a) to the extent that either of us has in any manner infringed upon or violated or threatened to infringe upon or violate the other party's patent, copyright, trademark or trade secret rights, or you have otherwise violated any of the user conduct rules set forth above or in the Code of Conduct then the parties acknowledge that arbitration is not an adequate remedy at law and that injunctive or other appropriate relief may be sought; and (b) no disputes or claims relating to any transactions you enter into with a third party through the site may be arbitrated.

Arbitration under this Agreement shall be conducted by the American Arbitration Association (the "AAA") under its Commercial Arbitration Rules and, in the case of consumer disputes, the AAA's Supplementary Procedures for Consumer Related Disputes ( the "AAA Consumer Rules") (collectively the "AAA Rules"). The location of the arbitration and the allocation of costs and fees for such arbitration shall be determined in accordance with such AAA Rules and shall be subject to the limitations provided for in the AAA Consumer Rules (for consumer disputes). If such costs are determined to be excessive in a consumer dispute, the Company will be responsible for paying all arbitration fees and arbitrator compensation in excess of what is deemed reasonable. The arbitrator's award shall be binding and may be entered as a judgment in any court of competent jurisdiction.

To the fullest extent permitted by applicable law, NO ARBITRATION OR CLAIM UNDER THESE TERMS OF SERVICE SHALL BE JOINED TO ANY OTHER ARBITRATION OR CLAIM, INCLUDING ANY ARBITRATION OR CLAIM INVOLVING ANY OTHER CURRENT OR FORMER USER OF THE SERVICE, AND NO CLASS ARBITRATION PROCEEDINGS SHALL BE PERMITTED. In no event shall any claim, action or proceeding by you related in any way to the site and/or the service (including your visit to or use of the site and/or the service) be instituted more than three (3) years after the cause of action arose.

Indemnity

You agree to indemnify and hold the Company, its subsidiaries and affiliates, and each of their directors, officers, agents, contractors, partners and employees, harmless from and against any loss, liability, claim, demand, damages, costs and expenses, including reasonable attorney's fees, arising out of or in connection with any User Content, any Third Party Applications, Software or Content you post or share on or through the site (including without limitation through the Share service), your use of the service or the site, your conduct in connection with the service or the site or with other users of the service or the site, or any violation of this Agreement or of any law or the rights of any third party.

Submissions

You acknowledge and agree that any questions, comments, suggestions, ideas, feedback or other information about the site or the service ("Submissions"), provided by you to Company are non-confidential and shall become the sole property of Company. Company shall own exclusive rights, including all intellectual property rights, and shall be entitled to the unrestricted use and dissemination of these Submissions for any purpose, commercial or otherwise, without acknowledgment or compensation to you.

SEVERABILITY WAIVER.

If, for whatever reason, a court of competent jurisdiction finds any term or condition in these Terms of Service to be unenforceable, all other terms and conditions will remain unaffected and in full force and effect. No waiver of any breach of any provision of these Terms of Service shall constitute a waiver of any prior, concurrent, or subsequent breach of the same or any other provisions hereof, and no waiver shall be effective unless made in writing and signed by an authorized representative of the waiving party.

NO LICENSE.

Nothing contained on the site should be understood as granting you a license to use any of the trademarks, service marks, or logos owned by GoalFace or by any third party.

Questions

Please visit our Help page for more information

Other

These Terms of Service constitute the entire agreement between you and Company regarding the use of the site and/or the service, superseding any prior agreements between you and Company relating to your use of the site or the service. The failure of Company to exercise or enforce any right or provision of these Terms of Service shall not constitute a waiver of such right or provision.

ACKNOWLEDGEMENT.

BY USING THE SERVICE OR ACCESSING THE SITE, YOU ACKNOWLEDGE THAT YOU HAVE READ THESE TERMS OF SERVICE AND AGREE TO BE BOUND BY THEM.

Effective Date: March 25, 2010
Last Updated: March 25, 2010

 </textarea>
									<p></p>
									<input type="button" class="submit GreenGradient SubmitReg" name="Register" id="registerbutton" value="I accept, Create my account">
                            </fieldset>


                            <div class="SecondColumnOfTwo" id="RightInfoOnForm">
    								<h4>What is GoalFace?</h4>
    									<h4>GoalFace is the premier online community for fans of the Beautiful Game. 
    									<br><br>
                                        When you join GoalFace you can:</h4>
								<!--<h4>Why Join GoalFace</h4>-->
                                <ul>
                                    <li>Customize football news and scores to keep up with your favorite leagues and tournaments</li>
                                     <li>Access detailed profiles for more than 55,000+ players and teams and subscribe to receive updates for the ones you care about most</li>
                                    <li>Create a profile and connect with fans like you from around the world</li>
                                    <li>Invite friends to join and share your passion for the Beautiful Game</li>                                   
                                    <li>And a lot more!</li>
                                </ul>
         

							</div>
							<br class="clearleft"/>
						</div><!-- end of FieldSetWrapper -->
                    </form>
                </div> <!--end FormWrapperForBottomBackground -->
            </div>
<!--end FormWrapper -->

<script type="text/javascript">


jQuery(document).ready(function() {

	//this can be refactored with one function
	jQuery('#confirmemailaddress').blur(function(){
	if(jQuery.trim(jQuery('#confirmemailaddress').val())!=''){
		jQuery('#cemailerror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		jQuery('#ErrorMessages').removeClass('ErrorMessagesDisplay').addClass('ErrorMessages');
		var email1 = jQuery.trim(jQuery('#emailadressRegisterForm').val()); 
		var email2 = jQuery.trim(jQuery('#confirmemailaddress').val());
		var valid2 = true;
		if(email1!= email2){
			jQuery('#cemailerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		    jQuery('#cemailerror').html('The e-mail entries do not match. Please check and re-enter.');
	    	valid2 = false;
	  	}
		if(!valid2){
	  		jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
		    jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    	return;
	  	}
	}
	});
	
	jQuery('#emailadressRegisterForm').blur(function(){
		var email = jQuery('#emailadressRegisterForm').val();
		jQuery.ajax({
			type: 'GET',
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/user/validateemail/email/'+email,
			dataType : 'script',
			success: function(text){
					if(text!= ''){
						jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
           			    jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
           		    }else {
           		    	jQuery('#ErrorMessages').removeClass('ErrorMessagesDisplay').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessages');
           			    jQuery('#emailerror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');;
               		}
			}
			
		})
		if(jQuery.trim(jQuery('#emailadressRegisterForm').val())!=''){
			jQuery('#cemailerror').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
			jQuery('#ErrorMessages').removeClass('ErrorMessagesDisplay').addClass('ErrorMessages');
			var email1 = jQuery.trim(jQuery('#emailadressRegisterForm').val()); 
			var email2 = jQuery.trim(jQuery('#confirmemailaddress').val());
			var valid2 = true;
			if(email2!='' && (email1!= email2)){
				jQuery('#cemailerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
			    jQuery('#cemailerror').html('The e-mail entries do not match. Please check and re-enter.');
		    	valid2 = false;
		  	}
			if(!valid2){
		  		jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
			    jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
		    	return;
		  	}
		}
	});
	
	jQuery('#password').blur(function(){
		jQuery('#password2error').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		jQuery('#ErrorMessages').removeClass('ErrorMessagesDisplay').addClass('ErrorMessages');
		var pass1 =  jQuery.trim(jQuery('#password').val()); 
		var pass2 =  jQuery.trim(jQuery('#password2').val());
		var valid2 = true;
		
	  	if(pass2!='' && (pass1!= pass2)){
	    	jQuery('#password2error').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		    jQuery('#password2error').html('The passwords you entered do not match. Please try again..');
	    	valid2 = false;
	  	}
		if(!valid2){
	  		jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
		    jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    	return;
	  	}
	});	
	
	jQuery('#password2').blur(function(){
		jQuery('#password2error').removeClass('ErrorMessageIndividualDisplay').addClass('ErrorMessageIndividual');
		jQuery('#ErrorMessages').removeClass('ErrorMessagesDisplay').addClass('ErrorMessages');
		var pass1 =  jQuery.trim(jQuery('#password').val()); 
		var pass2 =  jQuery.trim(jQuery('#password2').val()); 
	  	var valid2 = true;
	  	if(pass1!= pass2){
	    	jQuery('#password2error').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		    jQuery('#password2error').html('The passwords you entered do not match. Please try again..');
	    	valid2 = false;
	  	}
		if(!valid2){
	  		jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
		    jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
	    	return;
	  	}
	});	
	

	
	jQuery('#registerbutton').click(function(){
			
		valid = validaNewForm('join');
		var email1 = jQuery.trim(jQuery('#emailadressRegisterForm').val()); 
		var email2 = jQuery.trim(jQuery('#confirmemailaddress').val()); 
		var pass1 =  jQuery.trim(jQuery('#password').val()); 
		var pass2 =  jQuery.trim(jQuery('#password2').val()); 
	  	var valid2 = true;
	  	
	  	if(email1!= email2){
			jQuery('#cemailerror').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		    jQuery('#cemailerror').html('The e-mail entries do not match. Please check and re-enter.');
	    	valid2 = false;
	  	}
	  	if(pass1!= pass2){
	    	jQuery('#password2error').removeClass('ErrorMessageIndividual').addClass('ErrorMessageIndividualDisplay');
		    jQuery('#password2error').html('The passwords you entered do not match. Please try again..');
	    	valid2 = false;
	  	}
	  	var rights = jQuery('#registerterms').is(':checked');;
	  	if(!rights){
	  		jQuery('#termserror').html('You must check the terms and conditions before registering.');
			jQuery('#termserror').addClass('ErrorMessageIndividualDisplay');
			valid2 = false;
		 }
	  	
	  	if(!valid || !valid2){
	  		jQuery('#ErrorMessages').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
		    jQuery('#ErrorMessages').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
		    jQuery('html, body').animate({scrollTop:0}, 'slow');
		    return;
	  	}
		
		jQuery.ajax({
			type: 'POST',
			data: jQuery("#join").serialize(),
			url: '<?php echo Zend_Registry::get("contextPath"); ?>/register',
			dataType : 'script',
			success: function(text){
					if(text!= ''){
           		    	jQuery('#ErrorMessagesFeedBack').removeClass('ErrorMessages').removeClass('ErrorMessagesDisplayBlue').addClass('ErrorMessagesDisplay');
           			    jQuery('#ErrorMessagesFeedBack').html('Ooops, there was a problem with the information your entered.  Please correct the fields highlighted below.');
           		    }else {
						top.location.href = '<?php echo Zend_Registry::get("contextPath"); ?>/activate-account';
           		    }
			}
			
		})
		
	});			
});
</script>



<script> 
	window.fbAsyncInit = function() {

	    FB.init({
	        appId: '<?php echo cFacebook::getAppId(); ?>',
	        cookie: true,
	        xfbml: true,
	        oauth: true
	    });
	    FB.Event.subscribe('auth.login',
	    function(response) {
	        window.location.reload();
	    });
	    FB.Event.subscribe('auth.logout',
	    function(response) {
	        window.location.reload();
	    });
	}; 
		(function() {
		    var e = document.createElement('script');
		    e.async = true;
		    e.src
		    = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		    document.getElementById('fb-root').appendChild(e);
	} ()); 
</script>
