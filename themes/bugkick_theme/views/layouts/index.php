<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BugKick - Kicking bugs for charity. Bug tracking and project management.</title>
<link rel="shortcut icon" href="favicon.ico"/>
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/home/layout.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/home/prettyphoto.css"/>

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/home/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/home/ie8.css" />
<![endif]-->

<?php
	$csrfToken=Yii::app()->request->csrfToken;
    echo
    GoogleApi::init(null, true),
    CHtml::script(
        CGoogleApi::load('jquery')
        . "var YII_CSRF_TOKEN='$csrfToken';"
    );
?>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/home/jquery-reveal.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/home/jquery-accordion.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/home/jquery-prettyphoto.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/home/jquery-form.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/home/jquery-enquire.js"></script>
</head>

<body>
<div id="wrapper">
  <div id="container">

    <!-- Header -->
    <?php
        $bugCount = number_format(BugBase::model()->count());
    ?>
    <div class="navigation">
      <a href="<?php echo $this->createUrl('/'); ?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/logo.png" alt="BugKick)" /></a>
      <h2 class="right"><span></span><?php echo $bugCount?> bugs kicked</h2>
    </div>
    <!-- //End -->

    <!-- Splash -->
    <div class="splash">
      <div class="splash-content">
        <h1>Bug tracking for the rest of us</h1>
        <p>BugKick was built for the engineers behind Musopen.org. We think others might enjoy what we've created and have decided to make it a separate service. BugKick is free even with unlimited users and tickets. We offer a pay plan but 100% of the profits from these plans will support charity.</p>
        <span class="call-to-action-1"><a href="#" data-reveal-id="myModal" data-animation="fade">Compare Plans</a></span>
        <span class="call-to-action-2"><a href="<?php echo $this->createUrl('/registration'); ?>">Sign Up</a></span>
      </div>
        <span class="splash-screenshot"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/screenshot.png" width="435" height="274" class="screenshot" alt="example" /></span>
        <span class="clouds"></span>
    </div>
    <!-- //End -->

      <!-- Gallery -->
      <div class="gallery">
        <ul class="gallery-list">
          <li><a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery/labels.png" rel="gallery-set[1]" title="Set any number of custom labels and colors to your liking."><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery-thumbnail-01.jpg" width="128" height="93" alt="example" /></a></li>
          <li><a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery/instant-search.png" rel="gallery-set[1]" title="Like Google Instant to quickly search all your tickets."><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery-thumbnail-02.jpg" width="128" height="93" alt="example" /></a></li>
          <li><a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery/projects.jpg" rel="gallery-set[1]" title="Projects are a simple way to group sets of tasks. The project page let's you quickly view all projects and their progress at once. "><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery-thumbnail-03.jpg" width="128" height="93" alt="example" /></a></li>
          <li><a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery/ticket-view.png" rel="gallery-set[1]" title="Example of a ticket. The original ticket and its labels, status, assignees are on the top, comments below. Changes in ticket status are shown on the top-right side."><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery-thumbnail-04.jpg" width="128" height="93" alt="example" /></a></li>
          <li><a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery/drag-and-drop.png" rel="gallery-set[1]" title="Drag-and-drop tickets to set priority or to add/remove/change a label or other filter."><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery-thumbnail-05.jpg" width="128" height="93" alt="example" /></a></li>
          <li><a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery/gantt-charts.png" rel="gallery-set[1]" title="View project progress using Gantt charts. These let you quickly assess how many tickets are open in a project and how close each is to being complete."><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/gallery-thumbnail-06.jpg" width="128" height="93" alt="example" /></a></li>
        </ul>
      </div>
      <!-- //End -->

    <!-- Feature -->
    <div class="features">
      <div class="feature-01">
        <h2>Actually Free</h2>
        <h3>Free and without limits: Unlimited users, tickets, labels, projects, basically unlimited. </h3>
        <ul id="feature-01">
          <li><a>We ♥ Node.js</a><p>While we offer email notifications on ticket changes, and settings to modify what you receive, we've also built realtime notifications using Node.</p></li>
          <li><a>Set Deadlines</a><p>Tickets and projects can have deadlines to help keep track of how things are going. Deadlines make sure users stay on track with email reminders and visual cues.</p></li>
          <li><a>Simple project management</a><p>BugKick is designed to organize any type of task quickly. Manage projects, users, goals, deadlines and more from one interface.</p></li>
          <li><a>Designed for groups</a><p>Organize by projects, groups or both. Tickets can be assigned to a group or separately to multiple individuals</p></li>
        </ul>
      </div>
      <div class="feature-02">
        <h2>Focus on Usability</h2>
        <h3>Like collaborative Gmail, optimized for bug tracking.</h3>
        <ul id="feature-02">
          <li><a>Write tickets quickly</a><p>Many bug trackers require too much information all at once. BugKick has simplified the creation process so time is spent on work, and not managing it.</p></li>
          <li><a>Keyboard shortcuts</a><p>Create, edit, search, close, delete tickets (and more) with keyboard shortcuts.</p></li>
          <li><a>Drag-and-Drop</a><p>Set priority by dragging tickets above or below others. Drag a ticket or group of them onto filters to add/remove them.</p></li>
          <li><a>Close, don't delete</a><p>Like Gmail, instead of deleting or changing the status of a ticket, you close them when they are finished. We then keep them safe in a vault for later retrieval.</p></li>
        </ul>
      </div>
      <div class="feature-03">
        <h2>BugKick Extensions</h2>
        <h3>BugKick was built to help support Musopen.org, and all profits from premium users will be donated.</h3>
        <ul id="feature-03">
          <li><a>Form submissions</a><p>Add a JS snippet to your site to let visitors submit bugs directly into your BugKick account.</p></li>
          <li><a>BugKick API</a><p>Integrate BugKick with your own projects, or use our API to submit tickets right into our system.</p></li>
          <li><a>Bug submission contact form</a><p>Offer this next to a contact form so users can .</p></li>
          <li><a>sefsefsef</a><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed cursus risus ut ligula ula auctor in rhoncus ante ornare. Quisque ut nulla velit, nec rhoncus dolor.auctor in rhoncus ante ornare. Quisque ut nulla velit, nec rhoncus dolor.</p></li>
        </ul>
      </div>
    </div>
    <!-- //End -->
    <!-- Download -->
    <div class="download">
      <h2>Try it free today then upgrade later if you like it.</h2>
      <div class="clear"></div>
      <p>Or use it free indefinitely, with unlimited tickets and users.</p>
      <span class="call-to-action-1"><a href="<?php echo $this->createUrl('/registration'); ?>">Sign Up</a></span>
    </div>
    <!-- //End -->

  <?php /*
    <!-- Testimonials -->
    <div class="testimonials">
      <h2>See what others are saying</h2>
      <span class="testimonials-top"></span>
      <ul>
        <li><p>This tool is invaluable for my work.</p><p class="testimonial-user">Emily Stark</p></li>
        <li><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae nibh quis augue vulputate tempor eu quis eros. Ut quis dolor nulla.</p><p class="testimonial-user">John Smith - Compare Loans</p></li>
        <li><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae nibh quis augue vulputate tempor eu quis eros. Ut quis dolor nulla.</p><p class="testimonial-user">John Smith - Compare Loans</p></li>
        <li><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae nibh quis augue vulputate tempor eu quis eros. Ut quis dolor nulla.</p><p class="testimonial-user">John Smith - Compare Loans</p></li>
      </ul>
      <span class="testimonials-bottom"></span>
    </div>
    <!-- //End -->

    <div class="clear"></div>
  */ ?>

  </div>
</div>

<div class="clear"></div>

<!-- Footer -->
<div class="footer">
  <div class="footer-content">
      <span class="call-to-action-1 sign-in"><a href="<?php echo $this->createUrl('/site/login'); ?>">Sign In</a></span>
      <div class="copyright">
      <p class="left copyright-text">&copy; <?php echo date('Y') ?> Musopen.org. All rights reserved.</p>
<?php /*
      <ul class="right">
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-digg.png" width="16" height="16" alt="example" /></a></li>
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-facebook.png" width="16" height="16" alt="example" /></a></li>
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-flickr.png" width="16" height="16" alt="example" /></a></li>
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-linkedin.png" width="16" height="16" alt="example" /></a></li>
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-rss.png" width="16" height="16" alt="example" /></a></li>
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-skype.png" width="16" height="16" alt="example" /></a></li>
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-twitter.png" width="16" height="16" alt="example" /></a></li>
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-vimeo.png" width="16" height="16" alt="example" /></a></li>
        <li><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/home/icon-youtube.png" width="16" height="16" alt="example" /></a></li>
      </ul>
 */ ?>
    </div>
    <div class="clear"></div>
  </div>
</div>
<!-- //End -->

<!-- Enquire -->
<div id="myModal" class="reveal-modal">
   <div class="membership_wrapper">
        <ul class="membership">
            <li class="free_membership">
                <h3 class="membership_title">Free</h3>
                <p class="membership_fee">$0 per month</p>
                <p class="membership_descr">Enjoy it free.</p>
                <ul class="membership_ability">
                    <li class="first big">Closed tickets kept <br>for 30 days</li>
                    <li class="first very_big">Up to 3 projects, (each can have unlimited tickets, don't worry)</li>
                    <li>&nbsp;</li>
                    <li>&nbsp;</li>
                    <li>&nbsp;</li>
                </ul>
                <div class="button">
                    <a href="<?php echo $this->createUrl('/registration'); ?>" class="btn_signup">Sign Up</a>
                </div>
            </li>

            <li class="ultimate_membership highlighted">
                <h3 class="membership_title">BugKick Pro</h3>
                <p class="membership_fee">$9.00/month or $98.00/year</p>
                <p class="membership_descr">More features and +20 charisma.</p>
                <ul class="membership_ability">
                    <li class="first big">Closed tickets kept indefinitely</li>
                    <li>Website bug submission</li>
                    <li>API Access</li>
                    <li>GitHub Integration</li>
                    <li>Custom logo, No Ads</li>
                </ul>
                <div class="button">
                    <a href="<?php echo $this->createUrl('/registration/index', array('subscription'=>'pro')); ?>" class="btn_signup">Sign Up</a>
                </div>
            </li>

            <?php /* Old Plans ?>
            <li class="premium_membership">
                <h3 class="membership_title">Premium</h3>
                <p class="membership_fee">$25 per month</p>
                <p class="membership_descr">Nice additional features and feelings of euphoria.</p>
                <ul class="membership_ability">
                    <li class="first big">Closed tickets kept indefinitely</li>
                    <li>Website bug submission</li>
                    <li>API Access</li>
                    <li>No ads</li>
                    <li>&nbsp;</li>
                </ul>
                <div class="button">
                    <a href="<?php echo $this->createUrl('/registration/index', array('subscription'=>'premium')); ?>" class="btn_signup">Sign Up</a>
                </div>
            </li>
            <li class="ultimate_membership highlighted">
                <h3 class="membership_title">Ultimate</h3>
                <p class="membership_fee">$35 per month</p>
                <p class="membership_descr">More features and +20 charisma.</p>
                <ul class="membership_ability">
                    <li class="first big">Closed tickets kept indefinitely</li>
                    <li>Website bug submission</li>
                    <li>API Access</li>
                    <li>Custom logo</li>
                    <li>GitHub Integration</li>
                </ul>
                <div class="button">
                    <a href="<?php echo $this->createUrl('/registration/index', array('subscription'=>'ultimate')); ?>" class="btn_signup">Sign Up</a>
                </div>
            </li>
            <?php */ ?>


        </ul>
   </div><!-- .membership_wrapper -->
</div>
<!-- //End -->

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/home/jquery-config.js"></script>
</body>
</html>
