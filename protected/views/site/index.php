<?php $this->pageTitle = Yii::app()->name; ?>
<div id="intro">
    		<h2>Simple task management</h2>
    		<p>
                BugKick was built for the engineers behind Musopen.org to manage their work. We love it so much, that we decided to make it available to everyone, for free. Sign up and use BugKick free with unlimited users and tickets
    		</p>
    	</div>
    	<div id="main-content">
    		<div id="bug-image"></div>
    		<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/bug.png" alt="BugKick Logo Big" id="bug" />
    		<div class="screenshots">
    			<ul>
    				<li>
                        <a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery/labels.png" rel="prettyPhoto[pp_gal]" title="Set any number of custom labels and colors to your liking.">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery-thumbnail-01.jpg" alt="Alt Text" />
                        </a>
                    </li>
    				
                    <li>
                        <a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery/instant-search.png" rel="prettyPhoto[pp_gal]" title="Like Google Instant to quickly search all your tickets.">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery-thumbnail-02.jpg" alt="Alt Text" />
                        </a>
                    </li>
    				
                    <li>
                        <a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery/projects.jpg" rel="prettyPhoto[pp_gal]" title="Projects are a simple way to group sets of tasks. The project page let's you quickly view all projects and their progress at once.">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery-thumbnail-03.jpg" alt="Alt Text" />
                        </a>
                    </li>
    				
                    <li>
                        <a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery/ticket-view.png" rel="prettyPhoto[pp_gal]" title="Example of a ticket. The original ticket and its labels, status, assignees are on the top, comments below. Changes in ticket status are shown on the top-right side.">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery-thumbnail-04.jpg" alt="Alt Text" />
                        </a>
                    </li>
    				
                    <li>
                        <a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery/drag-and-drop.png" rel="prettyPhoto[pp_gal]" title="Drag-and-drop tickets to set priority or to add/remove/change a label or other filter.">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery-thumbnail-05.jpg" alt="Alt Text" />
                        </a>
                    </li>
    				
                    <li>
                        <a href="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery/gantt-charts.png" rel="prettyPhoto[pp_gal]" title="View project progress using Gantt charts. These let you quickly assess how many tickets are open in a project and how close each is to being complete.">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/landing/gallery-thumbnail-06.jpg" alt="Alt Text" />
                        </a>
                    </li>
    			</ul>
    		</div>
    		<div id="features">
    			<div class="feature-container">
    				<h3>Actually Free</h3>
    				<p>
    					Free and without limits: Unlimited users, 
						tickets, labels, projects, basically unlimited.
    				</p>
    				<ul class="ui-accordion">
    					<li>
                            <a href="#" title="">We ♥ Node.js</a>
                            <p>
                                While we offer email notifications on ticket changes, and settings to modify what you receive, we've also built realtime notifications using Node.
                            </p>
                        </li>
						<li>
                            <a href="#" title="">Set Deadlines</a>
                            <p>
                                Tickets and projects can have deadlines to help keep track of how things are going. Deadlines make sure users stay on track with email reminders and visual cues.
                            </p>
                        </li>
						<li>
                            <a href="#" title="">Simple project management</a>
                            <p>
                                BugKick is designed to organize any type of task quickly. Manage projects, users, goals, deadlines and more from one interface.
                            </p>
                        </li>
						<li>
                            <a href="#" title="">Designed for groups</a>
                            <p>
                                Organize by projects, groups or both. Tickets can be assigned to a group or separately to multiple individuals.
                            </p>
                        </li>
    				</ul>
    			</div>
    			<div class="feature-container">
    				<h3>Focus on Usability</h3>
    				<p>
    					Like collaborative Gmail, optimized for bug tracking.
    				</p>
    				<ul class="ui-accordion">
    					<li>
                            <a href="#" title="">Write tickets quickly</a>
                            <p>
                                Many bug trackers require too much information all at once. BugKick has simplified the creation process so time is spent on work, and not managing it.
                            </p>
                        </li>
						<li>
                            <a href="#" title="">Keyboard shortcuts</a>
                            <p>
                                Create, edit, search, close, delete tickets (and more) with keyboard shortcuts.
                            </p>
                        </li>
						<li>
                            <a href="#" title="">Drag-and-Drop</a>
                            <p>
                                Set priority by dragging tickets above or below others. Drag a ticket or group of them onto filters to add/remove them.
                            </p>
                        </li>
						<li>
                            <a href="#" title="">Close, don’t delete</a>
                            <p>
                                Like Gmail, instead of deleting or changing the status of a ticket, you close them when they are finished. We then keep them safe in a vault for later retrieval.
                            </p>
                        </li>
    				</ul>
    			</div>
    			<div class="feature-container">
    				<h3>BugKick Extensions</h3>
    				<p>
    					Built to help support Musopen.org, and all 
						profits from premium users will be donated.
    				</p>
    				<ul class="ui-accordion">
    					<li>
                            <a href="#" title="">Form submissions</a>
                            <p>
                                Add a JS snippet to your site to let visitors submit bugs directly into your BugKick account.
                            </p>
                        </li>
						<li>
                            <a href="#" title="">BugKick API</a>
                            <p>
                                Integrate BugKick with your own projects, or use our API to submit tickets right into our system.
                            </p>
                        </li>
						<li>
                            <a href="#" title="">Bug submission contact form</a>
                            <p>
                               Offer this next to a contact form so users can.
                            </p>
                        </li>
    				</ul>
    			</div>
                <div class="video-intro">
                    <iframe width="600" height="368" src="https://www.youtube.com/embed/fwHvtTZ5GUU?rel=0&vq=hd1080" frameborder="0" allowfullscreen></iframe>
                </div>
    		</div>
    		<div id="content-footer">
    			<div class="inner">
    				<div class="desc">
    					<p class="accent">Try it free today then upgrade later if you like it.</p>
    					<p>Or use it free indefinitely, with unlimited tickets and users.</p>
    				</div>
    				<a href="<?php echo $this->createUrl('/registration'); ?>" title="Sign Up" class="buttonLandingStyle green fl">Sign Up</a>
    			</div>
    		</div>
    	</div>
<?php
Yii::app()->clientScript->registerScript('add_landing_widgets', '
    (function(){
        $("a[rel^=\'prettyPhoto\']").prettyPhoto({
            overlay_gallery: false,
            social_tools: ""
        });
        $(".ui-accordion").accordion({
            active: false,
            autoHeight: false,
            collapsible: true
        });
    }())();
', CClientScript::POS_END);
?>