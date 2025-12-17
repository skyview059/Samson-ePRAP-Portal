<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Mailer  <small>Control panel</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Mailer</li>
    </ol>
</section>

<section class="content">
    
    <div class="col-md-8 col-md-offset-2">
        
            <div class="box">
                <div class="box-body">
                    <form class="form-horizontal" action="<?php echo site_url( Backend_URL . 'mailer/compose');?>" method="get">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="id">Select Template</label>
                        <div class="col-md-4">
                            <select name="id" class="form-control">
                                <?php echo $template_list; ?>
                            </select>
                            
                            <button style="margin-top: 20px;" type="submit" class="btn btn-success submit_btn">
                                Continue 
                                <i class="fa fa-long-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                        
                        
                    </form>                                        
                    
                </div>                       
            </div>                                                                
        
    </div>
</section>