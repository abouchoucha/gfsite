 <?php
    $config = Zend_Registry::get ( 'config' );
    $server = $config->server->host;
 ?> 
<script language="javascript">
    
function MakeLink()
 

{
    varID = "sales";

    myElem = document.getElementById(varID);
    var arr = myElem.href.split('#');

    var emailAddress = varID + '@' + arr[arr.length-1];

    myElem.href = 'mailto:' + emailAddress;
}
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
                    <h1>Privacy</h1>
                   
                     <div id="SecondColumnHighlightBoxContent">
                        <h3>GoalFace Principles</h3>
                        <p>We built GoalFace to make it
                        easy to connect and share information with your friends and other football
                        fans around the world with similar interests as you. We understand you
                        may not want everyone in the world to have the information you share
                        on GoalFace; that is why we give you control of your information. Our
                        default privacy settings limit the information displayed in your profile,
                        giving you control to share as much or as little information about yourself
                        as you see fit. </p>

                        <p>GoalFace follows two core principles:</p>
                        <p><b>1. You should have control
                        over your personal information.</b>  <br>
                        GoalFace helps you share information with your friends and other football
                        fans. You choose what information you put in your profile, including
                        contact and personal information, pictures, interests and groups you
                        join. You also control the users with whom you share that information
                        through the privacy settings on your Profile page. </p>
                        <p><b>2. You should have access
                        to the information others want to share.</b>  <br>
                        There is an increasing amount of information available out there, and
                        you may want to know what relates to you, your friends, and fans like
                        you. We want to help you easily get the information members of the site
                        have shared with you and/or the GoalFace community at large. </p>
                        <p>Sharing information should
                        be easy. And we want to provide you with the privacy tools necessary
                        to control how and with whom you share that information. If you have
                        questions or ideas, please send them to <a href="mailto:community@goalface.com" target="_blank">community@goalface.com</a></p>

                        <h3>GoalFace&#39;s Privacy Policy</h3>
                        <p>GoalFace&#39;s Privacy Policy is
                        designed to help you understand how we collect and use the personal
                        information you decide to share, and help you make informed decisions
                        when using GoalFace, located at <a href="http://www.GoalFace.com" target="_blank">www.GoalFace.com</a> and its directly associated
                        domains (collectively, &quot;GoalFace&quot;, "Site", "Service",
                        "Site and Service"  or &quot;Web Site&quot;). </p>
                        <p>By using or accessing GoalFace,
                        you are accepting the practices described in this Privacy Policy. </p>
                        <h3>The Information We Collect</h3>

                        <p>When you visit GoalFace you
                        provide us with two types of information: personal information you knowingly
                        choose to disclose that is collected by us and Web Site use information
                        collected by us as you interact with our Web Site. </p>
                        <p>When you register with GoalFace,
                        you provide us with certain personal information, such as your name,
                        your email address, your country of residence, your gender and any other
                        personal or preference information that you provide to us, select in
                        your profile or post on our Web Site. </p>
                        <p>When you enter GoalFace, we
                        collect your browser type and IP address. This information is gathered
                        for all GoalFace visitors. In addition, we store certain information
                        from your browser using &quot;cookies.&quot; A cookie is a piece of
                        data stored on the user&#39;s computer tied to information about the user.
                        We use session ID cookies to confirm that users are logged in. These
                        cookies terminate once the user closes the browser. By default, we use
                        a persistent cookie that stores your login ID (but not your password)
                        to make it easier for you to login when you come back to GoalFace. You
                        can remove or block this cookie using the settings in your browser if
                        you want to disable this convenience feature. </p>
                        <p>When you use GoalFace, you
                        may set up your personal profile, form relationships, send messages,
                        perform searches and queries, form groups, set up events and transmit
                        information by various other means. We collect this information so that
                        we can provide you a more targeted, relevant, personalized Web Site.
                        In most cases, we retain it so that, for instance, you can return to
                        view prior messages you have sent or easily see your friends list. When
                        you update information, we usually keep a backup copy of the prior version
                        for a reasonable period of time to enable reversion to the prior version
                        of that information. </p>
                        <p>You post User Content (as defined
                        in the GoalFace Terms of Service) on the Web Site at your own risk.
                        Although we allow you to set privacy options that limit access to your
                        pages, please be aware that no security measures are perfect or impenetrable.
                        Although we try our best, <b>we cannot control the actions of others
                        with whom you may choose to share your pages and information. Therefore,
                        we cannot and do not guarantee that User Content you post on the Site
                        will not be viewed by unauthorized persons. We are not responsible for
                        circumvention of any privacy settings or security measures contained
                        on the Site.</b> You understand and acknowledge that, even after removal,
                        copies of User Content may remain viewable in cached and archived pages
                        or if other Users have copied or stored your User Content. </p>

                        <p>Any improper collection or
                        misuse of information provided on GoalFace is a violation of the GoalFace
                        Terms of Service and should be reported to <a href="mailto:community@goalface.com" target="_blank">community@goalface.com</a></p>
                        <p>If you choose to use our invitation
                        service to tell a friend about our site, we will ask you for your friend&#39;s
                        email address. We will automatically send your friend a one-time email
                        inviting him or her to visit the site. GoalFace stores this information
                        to send this one-time email, to register a friend connection if your
                        invitation is accepted, and to track the success of our referral program.
                        Your friend may contact us at <a href="mailto:community@goalface.com" target="_blank">community@goalface.com</a> to request that
                        we remove this information from our database.</p>
                        <p>GoalFace may also collect information
                        about you from other sources, such as messages, comments, blogs, and
                        other users of the Web Site through the operation of the Site
                        (e.g., tags) in order to provide you with more useful information and
                        a more personalized experience. </p>
                        <p>By using GoalFace, you are
                        consenting to have your personal data transferred to and processed in
                        the United States. </p>
                        <h3>Children Under Age 13</h3>
                        <p>GoalFace does not knowingly
                        collect or solicit personal information from anyone under the age of
                        13 or knowingly allow such persons to register. If you are under 13,
                        please do not attempt to register for GoalFace or send any information
                        about yourself to us, including your name, address, telephone number,
                        or email address. No one under age 13 may provide any personal information
                        to or on GoalFace. In the event that we learn that we have collected
                        personal information from a child under age 13 without verification
                        of parental consent, we will delete that information as quickly as possible.
                        If you believe that we might have any information from or about a child
                        under 13, please contact us at <a href="mailto:community@goalface.com" target="_blank">community@goalface.com</a></p>

                        <h3>Children Between the Ages
                        of 13 and 18</h3>
                        <p>We recommend that minors over
                        the age of 13 ask their parents for permission before registering with
                        GoalFace, contacting or sending any information about themselves to
                        anyone on the Web Site over the Internet or by any other means. </p>
                        <h3>Use of Information Obtained
                        by GoalFace</h3>
                        <p>When you register with GoalFace,
                        you create your own profile and privacy settings. Your profile information,
                        as well as your name, email and photo, are displayed to people based
                        on the privacy settings you set on the Profile Page to enable you to
                        connect with people on GoalFace. We may occasionally use your name and
                        email address to send you notifications regarding new services offered
                        by GoalFace that we think you may find valuable. </p>
                        <p>Profile information is used
                        by GoalFace primarily to be presented back to and edited by you when
                        you access the service and to be presented to others permitted to view
                        that information by your privacy settings. In some cases where your
                        privacy settings permit it (e.g., posting to your wall), other GoalFace
                        users may be able to supplement your profile. </p>
                        <p>Profile information you submit
                        to GoalFace will be available to users of GoalFace who can access the
                        information based on the privacy settings on your Profile page.
                        Your name, network names, and profile picture thumbnail will be available
                        in search results across the Web Site and those limited pieces of information
                        may be made available to third party search engines. This is primarily
                        so your friends can find you and send a friend request. People who see
                        your name in searches, however, will not be able to access your profile
                        information unless they have a relationship to you (friend, friend of
                        friend, member of your networks, etc.) that allows such access based
                        on your privacy settings. </p>
                        <p>GoalFace may send you service-related
                        announcements from time to time through the general operation of the
                        service. For example, if a friend sends you a new message or someone
                        posts on your wall, you may receive an email alerting you to that fact. </p>
                        <p>Generally, you may opt out
                        of such emails, though GoalFace reserves the right to send you notices
                        about your account even if you opt out of all voluntary email notifications. </p>
                        <p>GoalFace may use information
                        in your profile without identifying you as an individual to third parties.
                        We do this for purposes such as aggregating how many people like a player
                        or team and personalizing advertisements and promotions so that we can
                        provide to you. We believe this benefits you. You can know more about
                        the football world and, where there are advertisements, they&#39;re more
                        likely to be relevant and of interest to you. For example, if you put
                        a favorite player in your profile, we might serve you an advertisement
                        highlighting a jersey or memorabilia. But we don&#39;t tell the sponsor
                        of the advertisement who you are. </p>

                        <p>We may use information about
                        you that we collect from other sources, including but not limited to
                        such as messages, comments, blogs, other users of the GoalFace service
                        and other Internet sources to supplement your profile. Where such information
                        is used, we generally allow you to specify in your privacy settings
                        that you do not want this to be done or to take other actions that limit
                        the connection of this information to your profile. </p>
                        <h3>Sharing Your Information
                        with Third Parties</h3>
                        <p>GoalFace is about sharing information
                        with others &#8212 friends, people in your network and other members with
                        similar to you &#8212 while providing you with privacy settings that restrict
                        other users from accessing your information. We allow you to choose
                        the information you provide through GoalFace. Our network architecture
                        and your privacy settings allow you to make informed choices about who
                        has access to your information. We do not provide contact information
                        to third party marketers without your permission. We share your information
                        with third parties only in limited circumstances where we believe such
                        sharing is 1) reasonably necessary to offer the service, 2) legally
                        required or, 3) permitted by you. For example: </p>
                        <ul type="DISC">
                          <li>Your blog feed may
                          aggregate the information you provide and make it available to your
                          friends and GoalFace members according to your privacy settings. </li>
                          <li>Unlike most sites
                          on the Web, GoalFace limits access to site information by third party
                          search engine &quot;crawlers&quot; (e.g. Google, Yahoo, MSN, Ask). GoalFace
                          takes action to block access by these engines to personal information
                          beyond your name, profile picture, and data about your profile (e.g.
                          favorite teams). </li>
                          <li>We may provide information
                          to service providers to help us bring you the services we offer. Specifically,
                          we may use third parties to facilitate our business, such as to host
                          the service at a co-location facility for servers, to send out email
                          updates about GoalFace, to remove repetitive information from our user
                          lists, to process payments for products or services, or to provide search
                          results or links (including sponsored links). In connection with these
                          offerings and business operations, our service providers may have access
                          to your personal information for use for a limited time in connection
                          with these business activities. Where we utilize third parties for the
                          processing of any personal information, we implement reasonable contractual
                          and technical protections limiting the use of that information for GoalFace-specified
                          purposes.</li>

                          <li>We occasionally
                          provide demonstration accounts that allow non-member users a glimpse
                          into the GoalFace community. Such accounts have only limited views and
                          capabilities.</li>
                          <li>We may be required
                          to disclose user information pursuant to lawful requests, such as subpoenas
                          or court orders, or in compliance with applicable laws. We do not reveal
                          information until we have a good faith belief that an information request
                          by law enforcement or private litigants meets applicable legal standards.
                          Additionally, we may share account or other information when we believe
                          it is necessary to comply with law, to protect our interests or property,
                          to prevent fraud or other illegal activity perpetrated through the GoalFace
                          Site and Service or using the GoalFace name, or to prevent imminent
                          bodily harm. This may include sharing information with other companies,
                          lawyers, agents or government agencies.</li>
                          <li>We let you choose
                          to share information with marketers or electronic commerce providers
                          through on-site offers.</li>
                          <li>We may offer stores
                          or provide services jointly with other companies on GoalFace. You can
                          tell when another company is involved in any store or service provided
                          on GoalFace, and we may share customer information with that company
                          in connection with your use of that store or service.</li>
                          <li>If the ownership
                          of all or substantially all of the GoalFace business, or individual
                          business units owned by GoalFace, Inc., were to change, your user information
                          may be transferred to the new owner so the service can continue operations.
                          In any such transfer of information, your user information would remain
                          subject to the promises made in any pre-existing Privacy Policy.</li>
                        </ul>
                        <p>When you use GoalFace, certain
                        information you post or share with third parties (e.g., a friend or
                        a GoalFace Member), such as personal information, comments, messages,
                        photos, or other information, may be shared with other users in accordance
                        with the privacy settings you select. All such sharing of information
                        is done at your own risk. Please keep in mind that if you disclose personal
                        information in your profile or when posting comments, messages, photos
                        or other items posted on GoalFace, this information may become publicly
                        available.</p>

                        <h3>Links</h3>
                        <p>GoalFace may contain links
                        to other websites. We are of course not responsible for the privacy
                        practices of those other websites. We encourage our users to be aware
                        when they leave our site to read the privacy statements and terms and
                        conditions of each and every web site that collects personally identifiable
                        information. </p>
                        <p>This Privacy Policy applies
                        solely to information collected by GoalFace. </p>
                        <h3>Third Party Advertising</h3>
                        <p>Advertisements that appear
                        on GoalFace are sometimes delivered (or &quot;served&quot;) directly
                        to users by third party advertisers. They automatically receive your
                        IP address when this happens. These third party advertisers may also
                        place cookies on your computer, or use other technologies such as JavaScript
                        and &quot;web beacons&quot; (also known as &quot;1x1 gifs&quot;) to
                        measure the effectiveness of their ads and to personalize advertising
                        content. Doing this allows the advertising network to recognize your
                        computer each time they send you an advertisement in order to measure
                        the effectiveness of their ads and to personalize advertising content.
                        In this way, they may compile information about where individuals using
                        your computer or browser saw their advertisements and determine which
                        advertisements are clicked. GoalFace does not have access to or control
                        of the cookies that may be placed by the third party advertisers. Third
                        party advertisers have no access to your contact information stored
                        on GoalFace unless you choose to share it with them. </p>

                        <p>This privacy policy covers
                        the use of cookies by GoalFace and does not cover the use of cookies
                        or other tracking technologies by any of its advertisers. </p>
                        <h3>Changing or Removing Information</h3>
                        <p>Access and control over most
                        personal information on GoalFace is readily available through the profile
                        editing tools. GoalFace users may modify or delete any of their profile
                        information at any time by logging into their account. Information will
                        be updated immediately. Individuals who wish to deactivate their GoalFace
                        account may do so by sending an email from the email account used to
                        create their profile to <a href="mailto:community@goalface.com" target="_blank">community@goalface.com</a>. Removed information
                        may persist in backup copies for a reasonable period of time, but will
                        not be generally available to members of GoalFace. </p>
                        <p>Where you make use of the communication
                        features of the service to share information with other individuals
                        on GoalFace, however, (e.g., sending a personal message to another GoalFace
                        user) you generally cannot remove such communications. </p>
                        <h3>Security</h3>
                        <p>GoalFace takes appropriate
                        precautions to protect our users&#39; information. Your account information
                        is located on a secured server behind a firewall.  Because email
                        and instant messaging are not recognized as secure communications, we
                        request that you not send private information to us by email or instant
                        messaging services. If you have any questions about the security of
                        GoalFace Web Site, please contact us at <a href="mailto:community@goalface.com" target="_blank">community@goalface.com</a>.</p>

                        <h3>Terms of Service, Notices
                        and Revisions</h3>
                        <p>Your use of GoalFace, and any
                        disputes arising from it, is subject to this Privacy Policy as well
                        as our Terms of Service and all of its dispute resolution provisions
                        including arbitration, limitation on damages and choice of law. We reserve
                        the right to change our Privacy Policy and our Terms of Service at any
                        time. Non-material changes and clarifications will take effect immediately,
                        and material changes will take effect within 30 days of their posting
                        on this site. If we make changes, we will post them. We encourage you
                        to refer to this policy on an ongoing basis so that you understand our
                        current privacy policy. Unless stated otherwise, our current privacy
                        policy applies to all information that we have about you and your account. </p>
                        <h3>Contacting GoalFace</h3>
                        <p>If you have any questions about
                        this privacy policy, please contact us at <a href="mailto:community@goalface.com" target="_blank">community@goalface.com</a>.</p>
                        <p>You may also contact us by
                        mail at: 3057 Nutley Street, Fairfax, Virginia, 22031, United States
                        of America</p>
                         <p><em>Last updated Aug 25, 2009.</em></p>
                    </div>
                    <div id="SecondColumnHighlightBoxContentBottomImage"></div>                            
                </div><!--end SecondColumnOfTwo and #SecondColumnHighlightBox-->
                
                
                 
             </div> <!--end ContentWrapper-->
