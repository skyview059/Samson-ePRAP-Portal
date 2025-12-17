<?php
defined('BASEPATH') OR exit('No direct script access allowed');

load_module_asset('users', 'css');
load_module_asset('users', 'js'); 

?>

<section class="content-header">
    <h1> User <small>list</small> &nbsp;&nbsp;
        <?php echo anchor(site_url(Backend_URL . 'users/create'), ' + Add User', 'class="btn btn-default"'); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin </a></li>        
        <li class="active">User list</li>
    </ol>
</section>

<section class="content"> 
    <div class="box">

        <div class="box-header">
            <?php $this->load->view('filter_form'); ?>       
        </div>
        <div class="box-body">

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="40">ID</th>                            
                            <th>Full Name</th>
                            <th width="100">Role</th>
                            <th width="100">Allowed</th>
                            <th>Email/User Name</th>
                            <th>Contact</th>
                            <th width="120" class="text-center">Status</th>
                            <th width="120">Registered on</th>
                            <th width="120">Updated on</th>
                            <th width="140" class="text-center">Action </th>
                        </tr>   
                    </thead>
                    <tbody>
                        <?php foreach ($users_data as $user) { ?>
                            <tr>
                                <td><?php echo $user->id; ?></td>
                                
                                <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                                <td><?php echo getRoleName($user->role_id); ?></td>
                                <td><?php echo viewAllowed($user->allowed); ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td><?php echo "+{$user->mobile_code}{$user->mobile_number}"; ?></td>                                                                
                                <td class="reset-status text-center" id="<?= $user->id; ?>"><?= expHeadStatus($user->status); ?></td>
                                <td><?php echo globalDateFormat($user->created_at); ?></td>
                                <td><?php echo globalDateFormat($user->updated_at); ?></td>
                                <td class="text-center"><?php
                                    echo anchor(site_url(Backend_URL . 'users/profile/' . $user->id), '<i class="fa fa-fw fa-external-link"></i> View', 'class="btn btn-xs btn-default"');                                  
                                    echo anchor(site_url(Backend_URL . 'users/update/' . $user->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-primary"');
                                    ?>                                                      
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>     

            </div>
        </div>

        <div class="row" style="padding-top: 10px; padding-bottom: 10px; margin: 0;">
            <div class="col-md-6">
                <span class="btn btn-primary">Total Record : <?php echo $total_rows ?></span>
            </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </div>
</section>    
<script type="text/javascript">
    $(document).on('click', '.btn-success', function () {
        var id = ($(this).parent().attr('id'));
        setNewStatus(id, 'Inactive');
    });
    
    $(document).on('click', '.btn-danger', function () {    
        var id = ($(this).parent().attr('id'));
        setNewStatus(id, 'Active');
    });


    function setNewStatus(id, status) {
        $.ajax({
            type: 'POST',
            data: {id: id, status: status},
            url: 'admin/users/set_status',
            dataType: 'json',
            beforeSend: function () {
                $(`#${id}`).html('<span class="ajax_processing">Wait</span>');
            },
            success: function (respond) {
                $(`#${id}`).html(respond.Msg);                
            }
        });
    }
</script>    