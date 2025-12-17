<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title><?php echo getSettingItem('SiteTitle'); ?></title>   
        <meta name="description" content="Plab Manager | Admin Part" />
        <meta name="keywords" content="Plab Manager | Admin Part" />  
        <base href="<?php echo base_url(); ?>"/>
        <link rel="shortcut icon" href="assets/theme/images/fav.ico">        
        <!-- Tell the browser to be responsive to screen width -->  
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->

        <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.min.css">

        <link rel="stylesheet" href="assets/admin/dist/css/skins/_all-skins.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="assets/admin/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="assets/admin/dist/css/style.css">
        <link rel="stylesheet" href="assets/admin/dist/css/color.css">
        <link rel="stylesheet" href="assets/admin/dist/css/color-scheme.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/lib/font-awesome/font-awesome.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800" rel="stylesheet">
        <link href="assets/lib/toast/toastr.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/lib/ajax.css">
        <link rel="stylesheet" href="assets/print.css">
    </head>

    <body class="skin-blue-light sidebar-mini">       
        
        <div class="box no-border" style="padding: 10px"    >

            <div class="box-header">
                <div class="row">
                    <div class="col-md-6 col-md-offset-4">

                        <h2 class="no-margin">Exam Name: <?php echo $course_name; ?></h2>
                        <h4>
                            Centre: <?php echo ($centre_name); ?>, <?php echo ($centre_address); ?><br/>
                            Date & Time: <?php echo globalDateTimeFormat($datetime); ?>
                        </h4>                
                    </div>
                </div>        
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" id="text_top">
                    <tr>
                        <th width="50" class="text-center">SL</th>
                        <th>Scenario No, Name & Candidate Instructions</th>                               
                    </tr>
                    <?php foreach ($scenarios as $scenario) { ?>
                        <tr>
                            <td class="text-center"><?php echo sprintf('%02d', ++$sl); ?></td>                            
                            <td>                                
                                <h3 class="no-margin">
                                    <span class="text-red"><?= sprintf('%03d', $scenario->reference_number); ?> | </span>
                                    <?= $scenario->name; ?>
                                </h3>
                                <hr/>
                                <?php echo $scenario->description; ?>
                            
                            </td>                                                
                        </tr>
                    <?php } ?>
                </table>
            </div>                
        </div>
        <script type="text/javascript">
            window.onload = function() { window.print(); };
        </script>
    </body>
</html>