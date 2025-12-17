<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?php echo 'Scenario Ref ID: #' . $reference_number; ?></title>
        
        <base href="<?= site_url(); ?>"/>
        <link rel="icon" href="assets/theme/images/favicon.jpg">
        <link rel="stylesheet" href='assets/lib/font-awesome/font-awesome.min.css' type='text/css' media='all' />
        <?php load_module_asset('scenario', 'css', 'print.css.php'); ?>
        <script src="assets/lib/plugins/jQuery/jquery-2.2.3.min.js"></script>
    </head>

    <body class="margin">
        <h3 style="text-align: center;">Patient Information</h3>
        <p>

            Ref. No. <?php echo $reference_number; ?>



            <span class="font_size_controller">Adjust font size 
                <span id="main_area1">
                    <span id="button1" class="control" onclick="changeFontSize(this)">
                        <i class="fa fa-search-plus"></i>
                        Zoom In                        
                    </span>
                </span>
                <span id="main_area2">
                    <span id="button2" class="control" onclick="changeFontSize(this)">
                        <i class="fa fa-search-minus"></i>
                        Zoom Out
                    </span>
                </span>
                <span id="main_area2">
                    <span id="button3" class="control" onclick="changeFontSize(this)">
                        <i class="fa fa-random"></i>
                        Reset
                    </span>
                </span>
            </span>

        </p>
        <hr/>
        <div id="content">
        <?php echo $patient_information; ?>
        </div>
    </body>
    <script>
    //    $(document).ready(function () {
    //        window.print();
    //    });

        function changeFontSize(target) {
            var demo = document.getElementById("content");
            var computedStyle = window.getComputedStyle
                    ? getComputedStyle(demo) // Standards
                    : demo.currentStyle;     // Old IE
            var fontSize;

            if (computedStyle) { // This will be true on nearly all browsers
                fontSize = parseFloat(computedStyle && computedStyle.fontSize);

                if (target === document.getElementById("button1")) {
                    fontSize += 5;
                } else if (target === document.getElementById("button2")) {
                    fontSize -= 5;
                } else if (target === document.getElementById("button3")) {
                    fontSize = 16;
                }
                demo.style.fontSize = fontSize + "px";
            }
        }
    </script>
</html>