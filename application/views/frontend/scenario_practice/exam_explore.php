<style>
    .exam-list .item {
        padding: 15px;
        margin-bottom: 30px;
        box-shadow: 0 0 5px #ddd;
        border-radius: 5px;
        background: #fff;
        transition: all 0.3s;
    }

    .exam-list .item h4 {
        margin-top: 0;
        margin-bottom: 20px;
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        color: #6C00A1;
    }

    .exam-list .item p {
        margin-bottom: 30px;
        text-align: center;
        color: #666;
    }

    label {
        font-weight: 700;
        font-size: 14px;
    }
</style>

<div class="row exam-list">
    <div class="col-md-8 col-md-offset-2">
        <div class="item">
            <h4><?= $exam->name; ?></h4>
            <?php if ($purchaseStatus): ?>
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-block"
                           href="<?= site_url('scenario-practice/exam/view/' . $exam->id); ?>">View Scenarios <i
                                    class="fa fa-arrow-right"></i> </a>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-block" onclick="startPractice()">Practice <i
                                    class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <ul>
                            <li>Over 500 scenarios</li>
                            <li>Approaches to each scenario</li>
                            <li>Examiner marking key</li>
                            <li>Ability to set time for your practice</li>
                            <li>Delegate roles as candidates, Patient and Examiner</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <?php foreach ($packages as $package): ?>
                    <div class="col-md-4 text-center" style="margin-top: 20px">
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo $package->title; ?></div>
                            <div class="panel-body">
                                <p><?php echo $package->duration; ?></p>
                                <p><?php echo getPackageScenarioTypeName($package->scenario_type); ?></p>
                                <form id="purchasePracticeForm" method="post"
                                      action="<?php echo site_url('purchase-practice-action'); ?>">
                                    <input type="hidden" id="practice_id" name="practice_id" value="<?php echo $exam->id; ?>">
                                    <input type="hidden" id="package_id" name="package_id" value="<?php echo $package->id; ?>">
                                    <input type="hidden" id="amount" name="amount" value="<?php echo $package->price; ?>">
                                    <button class="btn btn-primary btn-block" type="submit">
                                        Purchase now: <?php echo globalCurrencyFormat($package->price); ?>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->load->view('frontend/scenario_practice/start_practice_modal'); ?>