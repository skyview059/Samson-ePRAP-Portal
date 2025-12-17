<form action="<?php echo site_url(Backend_URL . 'doctor'); ?>" class="form-inline" method="get">
    <input type="hidden" name="type" value="<?= $type; ?>"/>
    <div class="row">
        <div class="col-md-3">                
            <div class="input-group">
                <span class="input-group-addon">Ethnicity</span>
                <select name="ethnicity_id" class="form-control" id="ethnicity_id">
                    <option value="0">--Select Ethnicity--</option>
                    <?php echo getDropDownEthnicitys($ethnicity_id); ?>
                </select>                            
            </div>   
        </div>   
        <div class="col-md-3">    
            <div class="input-group">
                <span class="input-group-addon">Gender</span>
                <select name="gender" class="form-control" id="gender">
                    <?php echo selectOptions($gender, [
                        '' => 'Any',
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ]); ?>
                </select>                    
            </div>
        </div>

        <div class="col-md-3">    
            <div class="input-group">
                <span class="input-group-addon">Country of Origin</span>
                <select name="country_id" class="form-control js_select2" id="country_id">
                    <?php echo getDropDownCountries($country_id); ?>
                </select>                    
            </div>
        </div>
        <div class="col-md-3">    
            <div class="input-group">
                <span class="input-group-addon">Current Location </span>
                <select name="cl_id" class="form-control js_select2" id="cl_id">
                    <?php echo getDropDownCountries($cl_id); ?>
                </select>                    
            </div>
        </div>
        <div class="col-md-3">    
            <div class="input-group">
                <span class="input-group-addon">Stage of Progression</span>
                <select name="stage_id" class="form-control js_select2" id="stage_id">
                    <?php echo Tools::getDropDownProgress($stage_id,$type); ?>
                </select>                    
            </div>
        </div>


        <div class="col-md-3">            
            <button class="btn btn-primary" type="submit">Search</button>
            <a href="<?php echo site_url(Backend_URL . "doctor?type={$type}" ); ?>" class="btn btn-default">Reset</a>
        </div>
    </div>
</form>