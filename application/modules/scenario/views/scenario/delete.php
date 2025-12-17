<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1><?php echo getExamName($exam_id); ?> Scenario <small>Delete</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>scenario">Scenario</a></li>
        <li class="active">Delete</li>
    </ol>
</section>

<section class="content personaldevelopment">
    <?php echo scenarioTabs($id, 'delete'); ?>
    <br>
    <div class="panel panel-default">
  <div class="panel-heading">Preview Before Delete</div>
  <div class="panel-body">
      <table class="table table-striped">
            <tr>
                <td width="150">Presentation</td>
                <td width="5">:</td>
                <td><?php echo $presentation; ?></td>
            </tr>
            <tr>
                <td>Diagnosis</td>
                <td>:</td>
                <td><?php echo $name; ?></td>
            </tr>
            <tr>
                <td>Scenario No</td>
                <td>:</td>
                <td><?php echo $reference_number; ?></td>
            </tr>
            <tr>
                <td>Relation Found</td>
                <td>:</td>
                <td><?php echo $relation; ?></td>
            </tr>
        </table>

        <div class="box-footer text-center">
            <?php 
            
                if($relation == 0){
                    echo anchor(
                        site_url(Backend_URL . 'scenario/delete_action/' . $id), 
                        '<i class="fa fa-fw fa-trash"></i> Confrim Delete ', 
                        'class="btn btn-danger" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'
                    );
                } else {
                    echo '<span class="btn btn-danger disabled">
                            <i class="fa fa-fw fa-trash"></i> 
                            Delete Locked
                        </span>';
                }
                 
            ?>
        </div>
  </div>
</div>

</section>