<?php /* @var $this BaseForumController */ ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="<?php echo $this->assetsUrl; ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $this->assetsUrl; ?>/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="<?php echo $this->assetsUrl; ?>/css/style.css" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <?php
        /*
        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="../assets/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
         */
        ?>
    </head>

    <body>

        <div id="top-menu" class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo Yii::app()->name; ?></a>
                    <?php if(!Yii::app()->user->isGuest):?>
                    <div class="btn-group pull-right">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-user"></i>
                            <?php echo BKUser::current()->repr()?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Profile</a></li>
                            <li class="divider"></li>
                            <li>
                                <?php
                                    echo CHtml::link(Yii::t('main', 'Logout'),
                                    Yii::app()->createUrl('site/logout', array(
                                        'token'=>Yii::app()->getRequest()->getCsrfToken())));
                                ?>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <?php
                                $items = array(
                                    array('label'=>'Home', 'url'=>array('/site')),
                                    array('label'=>'Forums', 'url'=>array('//forum')),
                                );
                                foreach($items as $item) {
                                    $url = CHtml::normalizeUrl($item['url']);
                            ?>
                            <li <?php if($url == Yii::app()->request->url) { ?>class="active" <?php } ?>>
                                <a href="<?php echo $url; ?>"><?php echo $item['label']; ?></a>
                            </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div id="breadcrumbs">
            <?php $this->renderPartial('application.modules.forum.views.layouts.breadcrumbs'); ?>
        </div>

        <!-- content -->
        <?php echo $content; ?>
        <!--/ content -->

        <hr>

        <footer>
            <p>Copyright &copy; <?php echo date('Y'); ?> by BugKick.<br/>
            All Rights Reserved.<br/>
            </p>
        </footer>

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script type="text/javascript" src="<?php echo $this->assetsUrl; ?>/bootstrap/js/bootstrap.min.js"></script>

    </body>
</html>