<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1>Candidate Short List</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'doctor') ?>">Doctor</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'doctor/shortlist') ?>">Shortlist/Job Post</a></li>        
        <li class="active">Candidate</li>
    </ol>
</section>

<section class="content">       
    <div class="box">            
        <?php if($canidates){ ?>
        
        <div class="box-header with-border text-center">
            <h2 style="margin:0 0 10px 0"><?= $post->title; ?></h2>              
            <p class="text-bold">Posted by: <?= "{$post->first_name} {$post->last_name}"; ?>, on: <?= $post->date; ?></p>  
            <p class="text-bold">Email: <?= $post->email; ?></p>  
        </div>            
        <div class="box-body">                         
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th> 
                            <th>Photo</th>
                            <th>Name </th>
                            <th>ID Number</th>
                            <th>Occupation </th>
                            <th>Country of Origin </th>
                            <th>Phone</th>
                            <th>Status </th>
                            <th>Remarks </th>
                            <th>Listed At </th>
                            <th width="150" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($canidates as $can) { ?>
                        <tr id="stu_<?= $can->student_id; ?>">
                                <td><?php echo ++$start; ?></td>                                 
                                <td><?php echo getPhoto_v3($can->photo, $can->gender, $can->fname, 60, 60); ?></td>                                
                                <td>
                                    <a href="<?= site_url( Backend_URL . 'doctor/timeline/' . $can->student_id); ?>">
                                        <?php echo "{$can->title} {$can->fname} {$can->lname}"; ?><br/>                                    
                                        <?php echo "{$can->email}"; ?>
                                    </a>                                    
                                </td>
                                <td><?php echo $can->number_type .'-'. $can->gmc_number; ?></td>
                                <td><?php echo $can->occupation; ?></td> 
                                <td><?php echo $can->country; ?></td>                                
                                <td><?php echo "+{$can->phone_code}{$can->phone}"; ?></td>  
                                <td>
                                    <select name="status_<?= $can->id?>" id="status_<?= $can->id?>" class="form-control" onchange="statusUpdate('<?= $can->id ?>')">  
                                        <?php echo Tools::status($can->status); ?>
                                    </select>
                                </td>          
                                <td><?php echo $can->remarks; ?></td>          
                                <td><?php echo globalDateTimeFormat($can->created_at); ?></td>          
                                <td class="text-center">
                                    <?php
                                        echo anchor(
                                            site_url(Backend_URL . 'doctor/timeline/' . $can->student_id), 
                                            '<i class="fa fa-fw fa-user-md"></i> View Profile', 
                                            'class="btn btn-xs btn-primary", target="_blank"'
                                        );                           
                                    ?>
                                    <span id="<?= $can->student_id; ?>" title="Click to Remove" class="btn btn-danger btn-xs remove_student">
                                        <i class="fa fa-times"></i>                                        
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="row">                
                <div class="col-md-6">                    
                    <a href="<?= site_url(Backend_URL . 'doctor/shortlisted') ?>" class="btn btn-default">
                        <i class="fa fa-long-arrow-left"></i> 
                        Back
                    </a>
                    <?php if(in_array($role_id, [1,2])){ ?>
                    <a href="<?= site_url(Backend_URL . 'doctor/report') ?>" class="btn btn-warning">
                        <i class="fa fa-long-arrow-left"></i> 
                        Back to Admin Report Page 
                    </a>
                    <?php } ?>
                </div>
                <div class="col-md-6 text-right">
                    <?= $pagination; ?>
                </div>                
            </div>
        </div>
        
        <?php } else { ?>
        <p class="ajax_notice"> No Post/Shortlist Found </p>
        <?php } ?>
    </div>
</section>
<input type="hidden" id="post_id" value="<?= $post_id; ?>"/>
<script type="text/javascript">
    $('.remove_student').on('click', function(e){
        e.preventDefault();
        var cofirm = confirm('Confirm Removing.');        
        var student_id,post_id;
        student_id  = $(this).attr('id');
        post_id     = $('#post_id').val();
        
        if(cofirm){
            $.ajax({
                url: 'admin/doctor/delete_candidate',
                type: 'POST',
                dataType: "json",
                data: { post_id: post_id, student_id: student_id  },
                beforeSend: function(){
                    $('#stu_'+student_id).css('background-color','red');
                },
                success: function ( jsonRespond ) {
                    if (jsonRespond.Status === 'OK') {
                        $('#stu_'+student_id).fadeOut();
                        toastr.success("Removed Successfully");
                    }else if(jsonRespond.Status === 'FAIL'){
                        toastr.error("Removing Failed");
                        $('#stu_'+student_id).css('background-color','inherit');
                    }else{
                        toastr.error("Something went wrong please try report to developer");
                    }
                }
            }); 
        }                
    });
    
    
    function statusUpdate(id){
        var status = $('#status_'+id).val();
      
        $.ajax({
            url: 'admin/doctor/set_status',
            type: 'POST',
            dataType: "json",
            data: { id: id, status: status  },
            beforeSend: function(){
                toastr.warning("Please Updating...");
            },
            success: function ( jsonRespond ) {
                if (jsonRespond.Status === 'OK') {
                    toastr.success("Status successfully updated");
                }else if(jsonRespond.Status === 'FAIL'){
                    toastr.error("Status Could not be updated");
                }else{
                    toastr.error("Something went wrong please try again");
                }
            }
        });   
    }            
</script>  