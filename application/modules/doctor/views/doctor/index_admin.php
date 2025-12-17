<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Medical Professional  <small>Search Database</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Doctor</li>
    </ol>
</section>

<section class="content">       
    
    <div class="box">  
        <div class="box-header with-border">
            <?php echo $this->load->view('filter'); ?>
        </div>
        <form method="post" id="suggestion">
            <!--<pre><?php // echo $sql; ?></pre>-->
            <div class="box-body"> 

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th width="70">
                                    <label> 
                                        <input type="checkbox" onclick="checkUncheck();"/> 
                                        S/L
                                    </label>
                                </th> 
                                <th class="text-center">Photo</th>
                                <th>Name & Email </th>
                                <th>Phone & WhatsApp</th>
                                <th>Country of Origin </th>
                                <th>Current Location</th>
                                <th>Latest Stage of Progression(+more) </th>
                                <th>ID Number</th>                            
                                <th width="150" class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($doctors as $dr) { 
                                $options = "<input class='mark' type='checkbox' name='s_ids[{$dr->id}]' value='{$dr->id}'>";
                                ?>
                                <tr>
                                    <td><label><?php echo $options; ?> <?php echo ++$start; ?></label></td>     
                                    <td class="text-center">
                                        <?php echo getPhoto_v3($dr->photo, $dr->gender, $dr->fname, 60, 60); ?>
                                        <?php //echo getPhoto_v2($dr->photo, "{$dr->fname} {$dr->lname}"); ?>
                                    </td>                                
                                    <td>
                                        <a href="<?= Backend_URL . 'doctor/timeline/' . $dr->id; ?>">
                                        <?php echo "{$dr->title} {$dr->fname} {$dr->lname}"; ?><br/>
                                        <small><?php echo "{$dr->email}"; ?></small>
                                        </a>
                                    </td>                                                                                     
                                    <td>
                                        &nbsp;&nbsp;<i class="fa fa-mobile-phone"></i> <?php echo "+{$dr->phone_code}{$dr->phone}"; ?><br/>
                                        <i class="fa fa-whatsapp" ></i> <?php echo "+{$dr->whatsapp_code}{$dr->whatsapp}"; ?>
                                    </td>                                                                                     
                                    <td><?php echo $dr->country; ?></td>                                
                                    <td><?php echo $dr->present_country; ?></td>                                
                                    <td><?php echo Tools::lastStageOfProress($dr->id); ?></td>  
                                    <td><?php echo $dr->number_type .'-'. $dr->gmc_number; ?></td>                                
                                    <td class="text-center">
                                        <?php
                                            echo anchor(
                                                site_url(Backend_URL . 'doctor/timeline/' . $dr->id), 
                                                '<i class="fa fa-fw fa-user-md"></i> View Profile', 
                                                'class="btn btn-xs btn-primary"'
                                            );                           
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div style="border:2px dashed red; background: #fffcf9; padding: 25px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Select Recruitment Manager:</span>
                                <select class="form-control js_select2" name="recruitment_manager_id" id="recruitment_manager_id">
                                    <?php echo getDPRecruitmentManager(0); ?>
                                </select>                        
                                <span class="input-group-btn">
                                    <span class="btn btn-primary" onclick="popup();">                                
                                        Load Job Post/Position
                                        <i class="fa fa-arrow-right"></i>
                                    </span>
                                </span>
                            </div>
                            <div id="select_err"></div>
                        </div>
                        <div class="col-md-12" id="respond">

                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer with-border text-center">
                <?php echo $pagination; ?>
            </div>
            <?php $this->load->view('index_modal'); ?>
        </form>
    </div>
        

    
</section>


<script type="text/javascript">
    
    function checkUncheck() {
        var len = $(".mark:checked").length;
        if (len) {
            jQuery('.mark').prop('checked', '');
        } else {
            jQuery('.mark').prop('checked', 'checked');
        }
    }
    
    function save_assignment(e){
        e.preventDefault();
        var manager_id = parseInt( $('#recruitment_manager_id').val() );
        if(manager_id === 0){
            $('#select_err').html('<p class="ajax_error">Please select manager account</p>');
            return false;
        } else {
            $('#select_err').empty();
        }
        
        var assignment = $('#assignment').serialize();
        $.ajax({
            url: 'admin/doctor/suggestion_action',
            type: 'POST',
            dataType: "json",
            data: assignment,
            beforeSend: function(){
                $('#respond' ).css('display','block').html('Updating...');
            },
            success: function ( respond ) {
                $('#respond').html(respond.Msg);
                setTimeout(function(){ $('#respond').fadeOut(); }, 2000);
                
            }
        });   
        return false;
    }
</script>    