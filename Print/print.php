
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Print Demo</title>
        <!-- Tell the browser to be responsive to screen width -->  
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <base href="http://localhost/FlickCMS/"/>
        <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/lib/ajax.css">
        <style type="text/css">
            .show_on_print { display: none; }            
            .table>tfoot>tr>td, .table>tfoot>tr>th, 
            .table>thead>tr>td, .table>thead>tr>th {
                border: 0 !important;
            }

            @media print {    
                * { -webkit-print-color-adjust: exact; }
                    

                @page {
                    size: A4;
                    margin: 2mm;
                    padding:0;
                }

                .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, 
                .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                    border-top: 0;
                }

                
                .hide_on_print {
                    display: none;
                }    

                .show_on_print {
                    display: block;
                }

                table#print_tbl td, 
                table#print_tbl th {
                    border:1px solid #000 !important;        
                    padding: 3px 3px;
                }        
            } 

            small {
                font-size: 80%;
                color: #aaa;
            }
        </style>
    </head>
    <body>



        <div class="container">
            <table class="table table-condensed no-border">
                <thead>
                    <tr>
                        <td class="text-center">                            
                            <h1>Common Header for Every Page</h1>                                             
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 0;">

                            <table id="print_tbl" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                    <?php for($a=0;$a<20;$a++){ ?>   
                                        <th class="text-center">HH</th>
                                    <?php } ?>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php 
                                    $sl = 1;
                                    for($i=1;$i<55;$i++){ ?>   
                                    <tr>
                                        <?php for($y=0;$y<20;$y++){ ?>   
                                        <td class="text-center"><?= $sl++; ?></td>  
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>                                
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center">                            
                            <p>Guest Booking System Developed by Flick Media.<br/> 
                                Web: www.flickmedialtd.com 
                                Email: hello@flickmedialtd.com 
                                Tel: 020 3740 2750                                
                            </p>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </body>
</html>