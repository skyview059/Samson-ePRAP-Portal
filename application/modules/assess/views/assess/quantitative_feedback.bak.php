<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Assess<small><?php echo $button ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>assess">Assess</a></li>
        <li class="active">Domains Grade</li>
    </ol>
</section>

<section class="content">
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Domains Grade/ <?= $summery_std_scen; ?></h3>
        </div>

        <div class="box-body">
            <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <h3><?= $result_details->scenario_name; ?></h3>

                        <table class="table table-bordered">
                            <tbody>                                
                                <tr>
                                    <td width="350">Data gathering & diagnosis</td>
                                    <td>
                                        <?php echo htmlRadio('technical_skills', $technical_skills, 
                                        array(0=>'Clear Fail (0)', 1=>'Fail (1)', 2=>'Pass (2)', 3=>'Clear Pass (3)'), 'class="icheck-radio"'); ?>
                                        <?php echo form_error('technical_skills') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Clinical management & complexity</td>
                                    <td>
                                        <?php echo htmlRadio('clinical_skills', $clinical_skills, array(0=>0, 1=>1, 2=>2, 3=>3, "4.5"=>4.5), 'class="icheck-radio"'); ?>
                                        <?php echo form_error('clinical_skills') ?>
                                    </td>
                                </tr>
                                <tr>

                                    <td>Relating to others</td>
                                    <td>
                                        <?php echo htmlRadio('interpersonal_skills', $interpersonal_skills, array(0=>0, 1=>1, 2=>2, 3=>3), 'class="icheck-radio"'); ?>
                                        <?php echo form_error('interpersonal_skills') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Mark</td>
                                    <td><span class="badge bg-yellow" id="total_mark">
                                        <?= floatval($technical_skills+$clinical_skills+$interpersonal_skills);?>
                                    </span></td>
                                </tr>

                            </tbody>
                        </table>

                        <p style="font-size: 12pt;" class="text-center">
                            <span class="text-red">0 = Clear fail, </span>
                            <span class="text-red">1 = Fail, </span>
                            <span class="text-green">2 = Pass, </span>
                            <span class="text-green">3 = Clear Pass,</span> 
                            <span class="text-green">4.5 = Distinction</span>
                        </p>
                    </div>
                    <div class="col-md-12 text-center" style="padding-top:20px;">
                        <input type="hidden" name="result_detail_id" value="<?php echo $result_detail_id; ?>"/>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save &amp; Continue <i class="fa fa-long-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $('input[type=radio][name=technical_skills], input[type=radio][name=clinical_skills], input[type=radio][name=interpersonal_skills]').on('ifChecked', function(event){
            var total_mark = 0;
            var t_skill_mark = $("input[name=technical_skills]:checked").val();
            var c_skill_mark = $("input[name=clinical_skills]:checked").val();
            var i_skill_mark = $("input[name=interpersonal_skills]:checked").val();
            
            if(t_skill_mark!==undefined){
                total_mark+=Number(t_skill_mark);
            }
            if(c_skill_mark!==undefined){
                total_mark+=Number(c_skill_mark);
            }
            if(i_skill_mark!==undefined){
                total_mark+=Number(i_skill_mark);
            }
            $('#total_mark').html(total_mark);
            
        });        
    });
    
</script>