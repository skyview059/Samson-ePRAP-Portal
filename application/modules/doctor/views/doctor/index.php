<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Medical Professional  <small>Search Database</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Doctor</li>
    </ol>
</section>

<section class="content">       
    
    <div class="panel panel-default">  
        <div class="box-header with-border">
            <?php echo $this->load->view('filter'); ?>
        </div>
        <form method="post" id="suggestion">
            <!--<pre><?php // echo $sql; ?></pre>-->
            <div class="panel-body"> 

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th width="50">S/L</th> 
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
                            <?php foreach ($doctors as $dr){ ?>
                                <tr>
                                    <td><?php echo ++$start; ?></td>     
                                    <td class="text-center">
                                        <?php echo getPhoto_v3($dr->photo, $dr->gender, $dr->fname, 60, 60); ?>                                        
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

            </div>
            <div class="box-footer with-border text-center">
                <?php echo $pagination; ?>
            </div>            
        </form>
    </div>       
</section>  