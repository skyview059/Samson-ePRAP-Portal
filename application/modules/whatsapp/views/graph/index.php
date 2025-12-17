<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('whatsapp', 'css'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1> Country  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>whatsapp">Whatsapp</a></li>
        <li class="active">Country</li>
    </ol>
</section>


<script type="text/javascript" src="assets/lib/plugins/Highcharts/highcharts.src.min.js"></script>
<script type="text/javascript" src="assets/lib/plugins/Highcharts/jquery.highchartTable.js"></script>
<script type="text/javascript"> 
    $(function () {
        $('table.highchart').highchartTable(); 
    });
</script>
<style> .highchart-container {width: 98%;} </style>



<section class="content personaldevelopment">
    <?= waCountryTabs('graph'); ?>
    <div class="box box-primary">            
        <div class="box-header with-border">                                   
            Graph Chart of Student
        </div>

        <div class="box-body">            
            <div class="row">
                <div class="col-md-6">                    
                    <div class="highchart-OriginQty"></div>
                    <div style="clear:both;"></div>
                    <table class="highchart" 
                           data-graph-container=".highchart-OriginQty" 
                           data-graph-type="pie"
                           style="display:none">
                        <thead>
                            <tr>                       
                                <th>Country of Origin</th>
                                <th>Candidate</th>
                            </tr>
                        </thead>
                        <tbody>                    
                            <?php foreach($OriginQty as $org ){ ?>
                            <tr>
                                <td><?php echo $org->name; ?></td>                        
                                <td><?php echo $org->Qty; ?></td>
                            </tr>
                            <?php } ?>                    
                        </tbody>
                    </table>
                    <h3 class="text-center">As Country of Origin</h3>                    
                </div>
                
                
                <div class="col-md-6">
                    <div class="highchart-PresentQty"></div>
                    <div style="clear:both;"></div>
                    <table class="highchart" 
                           data-graph-container=".highchart-PresentQty" 
                           data-graph-type="pie"
                           style="display:none">
                        <thead>
                            <tr>                       
                                <th>Country of Origin</th>
                                <th>Candidate</th>
                            </tr>
                        </thead>
                        <tbody>                    
                            <?php foreach($PresentQty as $pq ){ ?>
                            <tr>
                                <td><?php echo $pq->name; ?></td>                        
                                <td><?php echo $pq->Qty; ?></td>
                            </tr>
                            <?php } ?>                    
                        </tbody>
                    </table>
                    <h3 class="text-center">As Current Location</h3>
                    
                </div>
            </div>
        </div>        
    </div>        
</section>