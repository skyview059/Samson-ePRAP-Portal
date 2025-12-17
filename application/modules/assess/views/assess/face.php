<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php load_module_asset('users', 'css'); ?>
<section class="content-header">
    <h1>Assess</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>examine">Examine</a></li>
        <li class="active">Face</li>
    </ol>
</section>

<style>
    .cc-selector input{
        margin:0;padding:0;
        -webkit-appearance:none;
        -moz-appearance:none;
        appearance:none;
    }

    .smiley{background-image:url(assets/admin/icons/smiley.png);   }
    .neutral{background-image:url(assets/admin/icons/neutral.png); }
    .sad{background-image:url(assets/admin/icons/sad.png);  }
    
    .smiley, .neutral, .sad{ background-position: center center; }
    
    

    .drinkcard-cc, .cc-selector input:active +.drinkcard-cc{opacity: .9;}
    .drinkcard-cc, .cc-selector input:checked +.drinkcard-cc{
        -webkit-filter: none;
        -moz-filter: none;
        filter: none;
    }
    .drinkcard-cc{
        cursor:pointer;
        background-size:contain;
        background-repeat:no-repeat;
        display:inline-block;
        width:160px;
        height:140px;
        -webkit-transition: all 100ms ease-in;
        -moz-transition: all 100ms ease-in;
        transition: all 100ms ease-in;
        -webkit-filter: brightness(1.8) grayscale(.7) opacity(.7);
        -moz-filter: brightness(1.8) grayscale(.7) opacity(.7);
        filter: brightness(1.8) grayscale(.7) opacity(.7);
    }
    .drinkcard-cc:hover{
        -webkit-filter: brightness(1.2) grayscale(.3) opacity(.9);
        -moz-filter: brightness(1.2) grayscale(.3) opacity(.9);
        filter: brightness(1.2) grayscale(.3) opacity(.9);
    }

</style>

<section class="content">
    <div class="box no-border">
        <div class="box-header with-border">
            <h3 class="box-title">Student Face / <?= $summery_std_scen; ?></h3>
        </div>

        <div class="box-body">
            <div class="row">
                
                    <form class="form-horizontal" action="<?php echo $action; ?>" method="post">
                        <div class="text-center">
                            <div class="cc-selector">
                                
                                <table class="table no-border" style="width:450px;margin: 0 auto;">
                                    <tr>
                                        <td class="no-padding">
                                            <input id="smiley" type="radio" name="face" value="Smiley" <?php echo $face=='Smiley' ? 'checked="checked"' : '';?>/>
                                            <label class="drinkcard-cc smiley" for="smiley" title="Smiley"></label>
                                        </td>
                                        <td class="no-padding">
                                            <input id="neutral" type="radio" name="face" value="No Emotions" <?php echo $face=='No Emotions' ? 'checked="checked"' : '';?> />
                                            <label class="drinkcard-cc neutral" for="neutral" title="No Emotions"></label>
                                        </td>
                                        <td class="no-padding">
                                            <input id="sad" type="radio" name="face" value="Very Sad" <?php echo $face=='Very Sad' ? 'checked="checked"' : '';?>/>
                                            <label class="drinkcard-cc sad" for="sad" title="Very Sad"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold no-padding">Smiley</td>
                                        <td class="text-bold no-padding">No Emotions</td>
                                        <td class="text-bold no-padding">Very Sad</td>
                                    </tr>
                                </table>
                                <div class="clearfix"></div>
                                <?php echo form_error('face') ?>
                            </div>
                        </div>
                        
                        <div class="col-md-12 text-center" style="padding-top:20px;">
                            <input type="hidden" name="result_detail_id" value="<?php echo $result_detail_id; ?>"/>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save &amp; Continue <i class="fa fa-long-arrow-right"></i></button>
                        </div>
                    </form>
                
            </div>
        </div>
    </div>
</section>
<div class="clearfix"></div>