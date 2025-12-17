<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
        <title><?php echo  getSettingItem('SiteTitle'); ?></title>   
        <meta name="description" content="Plab Manager | Admin Part" />
        <meta name="keywords" content="Plab Manager | Admin Part" />  
        <base href="<?php echo base_url(); ?>"/>        
        <link rel="shortcut icon" href="assets/theme/images/eprap-icon.png">        
        <!-- Tell the browser to be responsive to screen width -->  
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        
        <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.min.css">
        
        <!-- Theme style -->
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="assets/admin/dist/css/skins/_all-skins.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="assets/admin/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="assets/admin/dist/css/style.css">
        <link rel="stylesheet" href="assets/admin/dist/css/color.css">
        <link rel="stylesheet" href="assets/admin/dist/css/color-scheme.css">
        
        

        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/lib/font-awesome/font-awesome.min.css">

        <!-- jQuery 2.2.3 -->
        <script src="assets/lib/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="assets/lib/plugins/jQueryUI/jquery-ui.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/admin/flick_cms.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="assets/lib/plugins/select2/select2.min.css">   
        <script type='text/javascript' src="assets/lib/plugins/select2/select2.min.js"></script>

        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800" rel="stylesheet">

        <link rel="stylesheet" href="assets/lib/plugins/jquery-toggles/toggles.css">  
        <link rel="stylesheet" href="assets/lib/plugins/jquery-toggles/toggles-full.css">  
        <script type='text/javascript' src="assets/lib/plugins/jquery-toggles/toggles.min.js"></script>
        <link rel="stylesheet" href="assets/lib/iCheck/flat/green.css">
        <link rel="stylesheet" href="assets/lib/plugins/datepicker/datepicker3.css">
        <link href="assets/lib/toast/toastr.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="assets/lib/ajax.css">
        <link rel="stylesheet" href="assets/print.css">
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.6/holder.min.js" type="text/javascript"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-4SE9JP8LD2"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-4SE9JP8LD2');
        </script>

        <style>
            .glyphicon-refresh-animate {
                -animation: spin .7s infinite linear;
                -webkit-animation: spin2 .7s infinite linear;
            }

            @-webkit-keyframes spin2 {
                from { -webkit-transform: rotate(0deg);}
                to { -webkit-transform: rotate(360deg);}
            }

            @keyframes spin {
                from { transform: scale(1) rotate(0deg);}
                to { transform: scale(1) rotate(360deg);}
            }

            .loader{
                display: none;
            }
        </style>
    </head>
    
    <body class="skin-blue-light sidebar-mini">
    <!--<body class="skin-blue sidebar-mini sidebar-open">-->
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->

                <a href="<?php echo base_url('admin'); ?>" class="logo">
                    <span class="logo-lg">
                        <img src="assets/theme/images/logo.png" title="Logo" style="max-height:41px;">
                    </span>
                    <span class="logo-mini">PM</span>
                </a>
                
                
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <span class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </span>
                    

                    <div class="navbar-custom-menu">

                        <ul class="nav navbar-nav">
                            <li>
                                <a id="clear_cache">
                                    <i class="fa fa-eraser loader-toggle"></i>
                                    <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate loader loader-toggle"></i>
                                    Clear Cache
                                </a>
                            </li>

                            <?php
                                echo top_nav('Setting & Announcement', 'admin/settings', 'settings', 'fa-bullhorn');
                                echo top_nav('Calendar', 'admin/calendar', 'calendar', 'fa-calendar');
                                echo top_nav('View Student Portal', site_url(), 'users', 'fa-home'); 
                                echo top_nav('Start Mock Exam', 'admin/assess/search_student', 'assess/search_student', 'fa-clock-o'); 
                                echo top_nav('Manage Exam Name', 'admin/exam/name', 'exam/name', 'fa-edit');
                            ?>                            
                            
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="assets/admin/dist/img/avatar5.png" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo getLoginUserData('name'); ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="assets/admin/dist/img/avatar5.png" class="img-circle" alt="User Image">
                                        <p> 
                                            <?php echo getLoginUserData('name'); ?> - 
                                            <?php echo getRoleName(getLoginUserData('role_id')); ?> 
                                            <small><?php echo getLoginUserData('user_mail'); ?></small>
                                        </p>
                                    </li>

                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?php echo site_url('admin/profile'); ?>" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li> 
                        </ul>
                    </div>
                </nav>
            </header>


            
