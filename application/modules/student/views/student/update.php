<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<?php load_module_asset('student', 'css'); ?>
<section class="content-header">
    <h1>Student<small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>student">student</a></li>
        <li class="active">Update</li>
    </ol>
</section>

<section class="content personaldevelopment studenttab studentupdateonly">
    <?php echo studentTabs($id, 'update'); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" id="user_form" class="form-horizontal"
                  enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-8">         
                        <legend>Write Note</legend>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="form-control" name="note" id="note" rows="5" placeholder="Write note here.."><?php echo $note; ?></textarea>
                                <?php echo form_error('note'); ?>
                            </div>
                        </div>

                        <legend>Basic Information</legend>

                        <div class="form-group">
                            <label for="first_name" class="col-sm-3 control-label">Full Name <sup>*</sup></label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <span class="col-md-3">
                                        <select name="title" class="form-control">
                                            <?php echo getNameTitle($title); ?>
                                        </select>
                                    </span>
                                    <span class="col-md-4">
                                        <input type="text" class="form-control" name="fname" id="fname"
                                                placeholder="First Name" value="<?php echo $fname; ?>"/>
                                                <?php echo form_error('fname') ?>
                                    </span>                                        
                                    <span class="col-md-5">
                                        <input type="text" class="form-control" name="lname" id="lname"
                                                placeholder="Last Name" value="<?php echo $lname; ?>"/>
                                                <?php echo form_error('lname') ?>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="form-group studentupdateemail">
                            <label for="email" class="col-sm-3 control-label">Email <sup>*</sup></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Email Address"
                                            value="<?php echo $email; ?>"/>
                                    <span class="input-group-addon">Must be Unique</span>
                                </div>
                                <?php echo form_error('email') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label">Gender</label>
                            <div class="col-sm-9" style="padding-top:8px;">
                                <?php echo htmlRadio('gender', $gender, array('Male' => 'Male', 'Female' => 'Female')); ?>
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <label for="whatsapp" class="col-sm-3 control-label">WhatsApp <sup>*</sup></label>
                            <div class="col-sm-9 customcountry">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <select name="whatsapp_code" class="form-control select2" style="width: 190px">
                                            <?php echo getPhoneCode($whatsapp_code); ?>
                                        </select>
                                    </span>
                                    <input type="tel" maxlength="15" onKeyPress="return DigitOnly(event);"
                                            name="whatsapp" id="whatsapp" placeholder="77xxxxxx"
                                            class="form-control" value="<?php echo $whatsapp; ?>">
                                </div>
                                <?php echo form_error('whatsapp_code'); ?>
                                <?php echo form_error('whatsapp'); ?>
                            </div>
                        </div>

                        <legend>Exam Details</legend>
                        <div class="form-group">
                            <label for="exam_id" class="col-sm-3 control-label">Examination type <sup>*</sup></label>
                            <div class="col-sm-9">
                                <select name="exam_id" class="form-control select2" id="exam_id">
                                    <?php echo getExamNameDropDown($exam_id); ?>
                                </select>
                                <?php echo form_error('exam_id') ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="exam_centre_id" class="col-sm-3 control-label">Exam Centre</label>
                            <div class="col-sm-9">
                                <select name="exam_centre_id" class="form-control select2" id="exam_centre_id">
                                    <?php echo getExamCentreDropDown($exam_centre_id); ?>
                                </select>
                                <?php echo form_error('exam_centre_id') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exam_date" class="col-sm-3 control-label">Exam Date</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control js_datepicker" name="exam_date" id="exam_date" placeholder="Exam Date"
                                            value="<?php echo $exam_date; ?>"/>                        
                                </div>
                                <?php echo form_error('exam_date') ?>                        
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="studemtadminright" style="background-color: #f3f4f6; border-radius: 4px; margin-top: 30px;padding: 15px;">
                        <div id="preview" class="text-center">                           
                            <?php echo getPhoto_v3($photo, $gender, $lname, 250, 250); ?>
                        </div>
                        
                        <div class="form-group" style="padding-top: 15px;">                            
                            <div class="col-sm-12 text-center">
                                <!--<input type="file" name="photo" id="photo"/>-->
                                
                                <div class="btn btn-default btn-file" style="margin-bottom: 15px;">
                                    <i class="fa fa-image"></i> &nbsp;
                                    Select Photo
                                    <input type="file" name="photo" id="photo" accept="image/*"
                                           onchange="return instantShowUploadImage(this, '#preview');"  />
                                </div>
                                
                                <input type="hidden" name="photo_old" value="<?php echo $photo; ?>"/>
                                <?php echo form_error('photo'); ?>                        
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">Status </label>
                            <div class="col-sm-9" style="padding-top:8px;">
                                <?php
                                echo htmlRadio('status', $status, array(
                                    'Active' => 'Active',
                                    'Inactive' => 'Inactive',
                                    'Pending' => 'Pending',
                                    'Archive' => 'Archive',
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <hr/>
                                <input type="hidden" name="id" value="<?php echo $id; ?>"/>                               
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i>
                                    Save Changes
                                </button>                        
                            </div>
                        </div>
                    </div>
</div>
                </div>


            </form>
        </div>
    </div>
</section>