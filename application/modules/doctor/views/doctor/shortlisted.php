<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Recruitment Short-list</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'doctor') ?>">Doctor</a></li>
        <li class="active">Short-list</li>
    </ol>
</section>

<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            Recruitment Short-list
        </div>
        <div class="panel-body">

            <?php if($shortlisted){ ?>
            <div id="respond"></div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th> 
                            <th>Job Title</th>
                            <th>Short-listed </th>
                            <th>Status</th>
                            <th width="250" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($shortlisted as $short) { ?>
                            <tr id='row_<?= $short->id; ?>'>
                                <td><?php echo ++$start; ?></td>  
                                <td><?php echo $short->post; ?></td>                                
                                <td><?php echo $short->total_candidate; ?></td>          
                                <td><?php echo Tools::statusWiseCount($short->id);?></td>          
                                <td class="text-center">
                                    <?php
                                        echo anchor(
                                            site_url(Backend_URL . 'doctor/shortlist/' . $short->id), 
                                            '<i class="fa fa-fw fa-info"></i> Details', 
                                            'class="btn btn-xs btn-primary"'
                                        );                           
                                    ?>
                                    <span class="btn btn-xs btn-danger" onclick="return deleteShortList(<?= $short->id; ?>);">
                                        <i class="fa fa-times"></i>
                                        Delete List
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p class="ajax_notice"> No Post/Shortlist Found </p>
        <?php } ?>
        </div>
    </div>
</section>


<script type="text/javascript">
    function deleteShortList( id ){
        var yes = confirm('Once you delete it can not be rollback. Do you really want to delete?');
        if( yes ){
            $.ajax({
                type: 'POST',
                data: { id: id },
                url: '<?= site_url( 'admin/doctor/del_shortlist'); ?>',
                dataType: 'json',
                beforeSend: function () {
                    $('#respond').html('<p class="ajax_process">Please wait deleting....</p>').css('display','block');
                    $('#row_'+ id ).css('background-color','red');
                },
                success: function (respond) {
                    $('#respond').html( `<p class="ajax_success">${respond.Msg}</p>`);
                    if (respond.Status === 'OK') {
                        $('#row_'+ id ).fadeOut('slow');    
                        setTimeout(function () { $('#respond').slideUp(); }, 3000);
                    }
                }
            });
        }
    }
</script>    