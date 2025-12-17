<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?php echo 'Scenario Ref ID: #' . $reference_number; ?></title>
        <link rel="icon" href="assets/theme/images/favicon.jpg">
        <base href="<?= site_url(); ?>"/>
        <?php load_module_asset('scenario', 'css', 'print.css.php'); ?>
        <script src="assets/lib/plugins/jQuery/jquery-2.2.3.min.js"></script>
    </head>

    <body class="margin">
        <table valign="top">
            <tr>
                <td>Name</td>
                <td>:</td>
                <td><?php echo $name; ?></td>
            </tr>
            <tr>
                <td>Reference Number</td>
                <td>:</td>
                <td><?php echo $reference_number; ?></td>
            </tr>
            <tr>
                <td width="150" valign="top">Candidate Instructions</td>
                <td width="5" valign="top">:</td>
                <td><?php echo $candidate_instructions; ?></td>
            </tr>
            <tr>
                <td valign="top">Patient Information</td>
                <td valign="top">:</td>
                <td><?php echo $patient_information; ?></td>
            </tr>
            <tr>
                <td valign="top">Examiner Information</td>
                <td valign="top">:</td>
                <td><?php echo $examiner_information; ?></td>
            </tr>
            <tr>
                <td valign="top">Set up</td>
                <td valign="top">:</td>
                <td><?php echo $setup; ?></td>
            </tr>
            <tr>
                <td valign="top">Exam Findings</td>
                <td valign="top">:</td>
                <td><?php echo $exam_findings; ?></td>
            </tr>
            <tr>
                <td valign="top">Approach</td>
                <td valign="top">:</td>
                <td><?php echo $approach; ?></td>
            </tr>
            <tr>
                <td valign="top">Explanation</td>
                <td valign="top">:</td>
                <td><?php echo $explanation; ?></td>
            </tr>
            
        </table>
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


