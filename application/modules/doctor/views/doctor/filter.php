<style type="text/css">
    .form-horizontal .form-group {
        margin-right: -15px;
        margin-left: 0; 
    }
    .select2-container--default .select2-selection--single {
        border-radius: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top:3px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #000;
    }
</style>
<form action="<?php echo site_url(Backend_URL . 'doctor'); ?>" class="form-horizontal" method="get">
    <input type="hidden" name="type" value="<?= $type; ?>"/>
    <div class="row">
        <div class="col-md-2">                
            <div class="form-group">
                <label class="control-label">Ethnicity</label>
                <select name="ethnicity_id" class="form-control" id="ethnicity_id">
                    <option value="0">--Any--</option>
                    <?php echo getDropDownEthnicitys($ethnicity_id); ?>
                </select>                            
            </div>   
        </div>   
        <div class="col-md-2">    
            <div class="form-group">
                <label class="control-label">Gender</label>
                <select name="gender" class="form-control" id="gender">
                    <?php echo selectOptions($gender, [
                        '' => 'Any',
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ]); ?>
                </select>                    
            </div>
        </div>

        <div class="col-md-2">    
            <div class="form-group">
                <label class="control-label">Country of Origin</label>
                <select name="country_id" class="form-control js_select2" id="country_id">
                    <?php echo getDropDownCountries($country_id, 'Any'); ?>
                </select>                    
            </div>
        </div>
        <div class="col-md-2">    
            <div class="form-group">
                <label class="control-label">Current Location </label>
                <select name="cl_id" class="form-control js_select2" id="cl_id">
                    <?php echo getDropDownCountries($cl_id, 'Any'); ?>
                </select>                    
            </div>
        </div>
        <div class="col-md-2">    
            <div class="form-group">
                <label class="control-label">Stage of Progression (+Other)</label>
                <select name="stage_id" class="form-control js_select2" id="stage_id">
                    <?php echo Tools::getDropDownProgress($stage_id,$type); ?>
                </select>                    
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Internship</label>
                <select name="internship" class="form-control js_select2" id="internship">                    
                    <?php echo selectOptions($internship, [
                        '0' => 'Any',
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ]); ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Specialty</label>
                <select name="specialties[]" multiple="multiple" class="form-control js_select2" id="specialties">
                    <?php echo getSpecialtySearch( $specialties ); ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Specialty Experience >=</label>
                <select name="expariense" class="form-control" id="expariense">
                    <option value="0">Any</option>
                    <?php echo SpecialtyDuration(1, 11, 1, $expariense, 'Months'); ?>
                    <?php echo SpecialtyDuration(12, 54, 6, $expariense, 'Years'); ?>
                    <?php echo SpecialtyDuration(60, 120, 12, $expariense, 'Years'); ?>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Interested in</label>
                <select name="interested" class="form-control js_select2" id="interested">
                    <?php echo getSpecialtySearch($interested); ?>
                </select>
            </div>
        </div>
        
    </div>
    
    
    
    
    <div class="row">
        

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">UK Status</label>
                <select name="uk_status" class="form-control js_select2" id="uk_status">
                    <option value="0">Any</option>
                    <?php echo selectOptions($uk_status, [
                        'British' => 'British',
                        'Visa with right work' => 'Visa with right work',
                        'Visa with no right to work' => 'Visa with no right to work',
                        'Refugee' => 'Refugee',
                        'Leave to remain' => 'Leave to remain',
                        'Indefinite leave to remain' => 'Indefinite leave to remain',
                        'Other' => 'Other',
                    ]); ?>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Right to work in UK</label>
                <select name="work_uk" class="form-control" id="work_uk">
                    <option value="">Any</option>
                    <?php echo selectOptions($work_uk, [
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ]); ?>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Training Courses</label>
                <select class="form-control js_select2" name="training_courses" id="training_courses">
                    <option value="0">Any</option>
                    <?php echo selectOptions($training_courses, [
                        'ALS' => 'ALS',
                        'APLS' => 'APLS',
                        'BLS' => 'BLS',
                        'ATLS' => 'ATLS',
                        'Other' => 'Other'
                    ]); ?>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <label class="control-label">Keyword</label>
            <input name="q" value="<?php echo $key; ?>" placeholder="Name, Email, Phone, ID" type="text" class="form-control"/>            
        </div>
        <div class="col-md-2" style="padding-top: 26px;">
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-search"></i> 
                Filter
            </button>
            <a href="<?php echo site_url(Backend_URL . "doctor?type={$type}" ); ?>" class="btn btn-default"><i class="fa fa-times"></i> Reset</a>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('.js_select2').select2({
            'placeholder': 'Select or Any'
        });
    });
</script>    