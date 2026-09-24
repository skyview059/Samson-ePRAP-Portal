<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Exam <small>Delete</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>exam">Exam</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?php echo examTabs($id, 'delete'); ?>
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Preview Before Delete</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <td width="150">Exam Name</td>
                    <td width="5">:</td>
                    <td><?php echo $course_name; ?></td>
                </tr>
                <tr>
                    <td>Exam Date & Time</td>
                    <td>:</td>
                    <td><?php echo globalDateTimeFormat($datetime); ?></td>
                </tr>
                <tr>
                    <td>Centre</td>
                    <td>:</td>
                    <td><?php echo $centre_name; ?></td>
                </tr>
                <tr>
                    <td>Centre Address</td>
                    <td>:</td>
                    <td><?php echo $centre_address; ?></td>
                </tr>

            </table>

            <?php
            $total_related = 0;
            foreach ($relations as $rel) {
                $total_related += $rel['count'];
            }
            ?>
            <h4 class="text-bold" style="margin-top:25px;">
                <i class="fa fa-sitemap"></i> Relational Data (exam_schedule_id = <?php echo $id; ?>)
                <small class="text-muted">— rows that will be removed together with this exam</small>
            </h4>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr class="active">
                        <th width="30">#</th>
                        <th>Data</th>
                        <th>Table</th>
                        <th>Condition</th>
                        <th width="90" class="text-right">Rows</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($relations as $i => $rel) { ?>
                    <tr class="<?php echo ($rel['count'] > 0) ? 'text-danger' : 'text-muted'; ?>">
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo $rel['label']; ?></td>
                        <td><code><?php echo $rel['table']; ?></code></td>
                        <td><small><code><?php echo $rel['condition']; ?></code></small></td>
                        <td class="text-right text-bold"><?php echo $rel['count']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="active">
                        <th colspan="4" class="text-right">Total related rows</th>
                        <th class="text-right"><?php echo $total_related; ?></th>
                    </tr>
                </tfoot>
            </table>

            <?php if (!empty($enrollments)) { ?>
            <p class="text-muted" style="margin-top:-10px;">
                <i class="fa fa-info-circle"></i> Enrollment breakdown by status:
                <?php
                $parts = array();
                foreach ($enrollments as $status => $qty) {
                    $parts[] = "<strong>{$status}</strong> {$qty}";
                }
                echo implode(', ', $parts);
                ?>
            </p>
            <?php } ?>
        </div>
        <div class="box-footer">
            
            <?php 

            
            if($warning == false ){ 

                echo "<p class='text-danger text-bold'>{$students} Student &  {$scenarios} Scenario(s) associated with this exam.</p>";

                echo anchor(
                        site_url(Backend_URL . 'exam/delete_action/' . $id), 
                        '<i class="fa fa-fw fa-trash"></i> Confirm Delete ', 
                        'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'
                    );
                
            } else { ?>
            
            <p class="btn btn-danger disabled text-bold">
                <i class="fa fa-warning"> </i>
                <?php echo $students; ?> 
                Student(s) enrolled for this examination 
                and delete option is now Disabled.
            </p>
            <?php }  ?>
        </div>
    </div>
</section>