<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="assets/theme/images/eprap-icon.png">

    <title><?php echo getSettingItem( 'SiteTitle' ); ?></title>
    <meta name="description" content="Plab Manager" />
    <meta name="keywords" content="Plab Manager" />

    <base href="<?php echo base_url(); ?>" />
    <link rel="icon" href="assets/theme/images/eprap-icon.png">

    <link href="https://fonts.googleapis.com/css?family=Philosopher:400,400i,700,700i&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.min.css" type='text/css' media='all' />
    <link rel="stylesheet" href='assets/lib/font-awesome/font-awesome.min.css' type='text/css' media='all' />
    <link rel="stylesheet" href="assets/theme/css/layout.css">
    <link rel="stylesheet" href="assets/theme/css/style.css">
    <link rel="stylesheet" href="assets/admin/dist/css/color.css">
    <link rel="stylesheet" href="assets/theme/css/my_account.css">
    <link rel="stylesheet" href="assets/admin/dist/css/color-scheme.css">
    <link rel="stylesheet" href="assets/theme/css/responsive.css">
    <link rel="stylesheet" href="assets/lib/ajax.css">
    <link rel="stylesheet" href="assets/lib/loader/jquery.loading.min.css">
    <link href="assets/lib/toast/toastr.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="assets/lib/plugins/select2/select2.min.css">
    <!--<link rel="stylesheet" type="text/css" href="assets/theme/css/owl.carousel.min.css">-->
    <!--<link rel="stylesheet" type="text/css" href="assets/theme/css/owl.theme.default.min.css">-->

    <script src="assets/lib/plugins/jQuery/jquery-2.2.3.min.js" type="text/javascript"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="assets/lib/plugins/datepicker/datepicker3.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.6/holder.min.js" type="text/javascript"></script>

    <script>(function(w){w.fpr=w.fpr||function(){w.fpr.q = w.fpr.q||[];w.fpr.q[arguments[0]=='set'?'unshift':'push'](arguments);};})(window);
        fpr("init", {cid:"5a6hgk3l"});
        fpr("click");
    </script>
    <script src="https://cdn.firstpromoter.com/fpr.js" async></script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4SE9JP8LD2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-4SE9JP8LD2');
    </script>
</head>
<body>

<section class="header" <?= $display_none; ?>>
    <div class="container">
        <div class="row headers">
            <div class="hidden-xs col-md-8 col-xs-12">
                <div class="logo">
                    <a href="https://www.eprap.com/">
                        <img alt="" src="assets/theme/images/logo.png" class="img-responsive">
                    </a>
                </div>
            </div>
            <div class="hidden-xs col-md-4 col-xs-6">
                <div class="header-right clearfix">
                    <div class="header-btn-top hidden-xs">
                        <i class="fa fa-phone phone-align"> </i>
                        <a href="tel: +4408000096888"> +44(0) 8000 096 888</a> <br>

                        <i class="fa fa-circle phone-align" style="color: #FFC107"></i>
                        <a href="" style="text-transform:none"> Students: <?php echo totalStudentCount(); ?></a>&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-circle phone-align" style="color: #198754"></i>
                        <a href="" style="text-transform:none"> Online: <?php echo onlineStudentCount(); ?></a> <br>
                    </div>
                </div>
            </div>
            <div class="visible-xs col-xs-12 header-contact">
                <div class="header-btn-top">
                    <i class="fa fa-phone"></i>
                    <a href="tel:+4408000096888">+44(0) 8000 096 888</a>
                </div>

                <div class="header-btn-top">
                    <i class="fa fa-circle phone-align" style="color: #FFC107"></i>
                    <a href="" style="text-transform:none"> Students: <?php echo totalStudentCount(); ?></a>&nbsp;&nbsp;&nbsp;
                    <i class="fa fa-circle phone-align" style="color: #198754"></i>
                    <a href="" style="text-transform:none"> Online: <?php echo onlineStudentCount(); ?></a> <br>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-primary">
        <div class="container">
            <div class="navbar-header">
                <div class="visible-xs col-md-9 col-xs-9">
                    <div class="logo">
                        <a href="https://www.eprap.com/">
                            <img alt="" src="assets/theme/images/logo.png" class="img-responsive">
                        </a>
                    </div>
                </div>
                <div class="visible-xs col-md-3">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myHeaderNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="myHeaderNavbar">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="https://www.eprap.com/">Home</a>
                    </li>
                    <li>
                        <a href="https://www.eprap.com/about-us">About Us</a>
                    </li>
                    <li>
                        <a href="https://www.eprap.com/osce-assessment">OSCE Assessment</a>
                    </li>
                    <li>
                        <a href="https://www.eprap.com/contact-us">Contact Us</a>
                    </li>

                    <li class="<?=$this->uri->segment( 1 ) == 'book-course' ? 'active' : '';?>">
                        <a href="<?=base_url('book-course');?>" target="_self"> Book Course </a>
                    </li>

                    <?php
                    if ( !getLoginStudentData( 'student_id' ) ): ?>
                        <li class="<?=$this->uri->segment( 1 ) == 'login' ? 'active' : '';?>">
                            <a href="<?=base_url();?>login" target="_self">Login</a>
                        </li>
                        <li class="<?=$this->uri->segment( 1 ) == 'sign-up' ? 'active' : '';?>">
                            <a href="<?=base_url();?>sign-up" target="_self">Register</a>
                        </li>
                        
                    <?php else: ?>
                        <li class="hidden-xs <?=$this->uri->segment( 1 ) == 'profile' ? ' active' : '';?>">
                            <a href="<?=base_url();?>profile" target="_self">My Account</a>
                        </li>
                        <li class="hidden-xs <?=$this->uri->segment( 1 ) == 'messages' ? ' active' : '';?>">
                            <a href="<?=base_url();?>student-messages" target="_self">Messages</a>
                        </li>
                        <li class="hidden-xs <?=$this->uri->segment( 1 ) == 'messages' ? ' active' : '';?>">
                            <a href="<?=base_url();?>student-messages/find-a-study-partner" target="_self">Find a study Partner</a>
                        </li>
                    <?php endif;?>

                </ul>
            </div>
        </div>
    </nav>
</section>