<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Login Logs <small>Graph view</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . '/users/login_history') ?>"> Login History</a></li>
        <li class="active">Graph view</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border row">
                    <div class="col-md-3">
                        <h3 class="box-title">Student Statistics</h3>
                    </div>

                    <?php $filter = $this->input->get('filter');?>

                    <div class="col-md-7 col-md-offset-2" style="float: right; direction: rtl">
                        <?php $url = base_url() . $this->uri->uri_string(); ?>

                        <a class="btn btn-sm <?php echo $filter == 'total_student'? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=total_student"> Total Student </a>
                        <a class="btn btn-sm <?php echo $filter == 'today' || $filter == '' ? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=today"> Today </a>
                        <a class="btn btn-sm <?php echo $filter == 'yesterday' ? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=yesterday"> Yesterday </a>
                        <a class="btn btn-sm <?php echo $filter == 'last_week' ? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=last_week"> Last Week </a>
                        <a class="btn btn-sm <?php echo $filter == 'last_month' ? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=last_month"> Last Month </a>
                        <a class="btn btn-sm <?php echo $filter == 'last_6_months' ? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=last_6_months"> Last 6 Months </a>
                        <a class="btn btn-sm <?php echo $filter == 'last_12_months' ? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=last_12_months"> Last 12 Months </a>
                        <a class="btn btn-sm <?php echo $filter == 'last_year' ? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=last_year"> Last Year (<?= date('Y') - 1;?>) </a>
                        <a class="btn btn-sm <?php echo $filter == 'this_year' ? 'btn-info' : 'btn-primary'; ?>" href="<?php echo $url; ?>?filter=this_year"> This Year (<?= date('Y');?>) </a>
                    </div>
                </div>

                <div class="box-body">
                    <canvas id="student_line" height="425px"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Browsers</h3>
                </div>
                <div class="box-body">
                    <div class="box-body text-center">
                        <div class="browsers" data-width="400px" data-height="425px"></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Devices</h3>
                </div>
                <div class="box-body">
                    <div class="box-body text-center">
                        <div class="devices" data-width="400px" data-height="425px">
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script src="assets/lib/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- page script -->
<script>
    // Device pic chart
    $(function () {
        var mydevices = [<?php
            if ($devices):
                $test1 = '';
                foreach ($devices as $device):
                    $test1 .= $device->devices . ',';
                endforeach;
                echo rtrim_fk($test1, ',');
            endif;
            ?>];
        $('.devices').sparkline(mydevices, {
            type: 'pie',
            width: '475px',
            height: '415px',
            sliceColors: ['#9de49d', '#9074b1', 'purple', '#5d3092', '#4dc9ec', '#dd4477', '#0099c6', '#990099'],
            borderWidth: 7,
            borderColor: '#f5f5f5',
            tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)',
            tooltipValueLookups: {
                names: {
        <?php
        if ($devices): $i1 = 0;
        foreach ($devices as $device_lev):
        ?>
        <?php echo $i1; ?>:
        '<?php echo $device_lev->device; ?>',
        <?php $i1++;
        endforeach;
        endif; ?>
        // Add more here
    }
    }
    })
    });

    // Browser pic chart
    $(function () {
        var myBrowsers = [<?php
            if ($browsers):
                $test1 = '';
                foreach ($browsers as $browser):
                    $test1 .= $browser->count . ',';
                endforeach;
                echo rtrim_fk($test1, ',');
            endif;
            ?>];
        $('.browsers').sparkline(myBrowsers, {
            type: 'pie',
            width: '475px',
            height: '415px',
            sliceColors: ['#4dc9ec', '#dd4477', '#0099c6', '#990099', '#9de49d', '#9074b1', 'purple', '#5d3092'],
            borderWidth: 7,
            borderColor: '#f5f5f5',
            tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:names}} ({{percent.1}}%)',
            tooltipValueLookups: {
                names: {
        <?php
        if ($browsers): $i2 = 0;
        foreach ($browsers as $browser_lev):
        ?>
        <?php echo $i2; ?>:
        '<?php echo $browser_lev->browser; ?>',

        <?php $i2++;
        endforeach;
        endif; ?>
        // Add more here
    }
    }
    })
    });

    // Student statistics bar chart
    $(function (){
        const statistics        = <?php echo json_encode($statistics); ?>;
        const labels            = statistics.map(entry => entry.country_name);
        const total_student     = statistics.map(entry => entry.total_student);
        const today             = statistics.map(entry => entry.today);
        const yesterday         = statistics.map(entry => entry.yesterday);
        const last_week         = statistics.map(entry => entry.last_week);
        const last_month        = statistics.map(entry => entry.last_month);
        const last_year         = statistics.map(entry => entry.last_year);
        const last_12_months    = statistics.map(entry => entry.last_12_months);
        const last_6_months     = statistics.map(entry => entry.last_6_months);
        const this_year         = statistics.map(entry => entry.this_year);

        const colors = [
            '#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#1abc9c',
            '#9b59b6', '#34495e', '#d35400', '#27ae60', '#c0392b'
        ];

        let ticksStyle  = {fontColor: '#495057', fontStyle: 'bold'}
        let mode        = 'index'
        let intersect   = true
        let $salesChart = $('#student_line')
        let datasets    = [];

        datasets.push({
            backgroundColor: colors,
            borderColor: colors,
            data: <?php echo $this->input->get('filter') ? $this->input->get('filter') : 'today'; ?>
        });

        new Chart($salesChart, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {mode: mode, intersect: intersect},
                hover: {mode: mode, intersect: intersect},
                legend: {display: false},
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        });
    })
</script>
