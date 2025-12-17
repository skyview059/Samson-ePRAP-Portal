<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Result <small>Control panel</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Result</li>
    </ol>
</section>

<section class="content">

    <div class="row">

        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border text-center">
                    <div class="box-title">Scenario Result for Diabetic Foot</div>
                </div>

                <div class="box-body">
                    <?php echo $this->session->flashdata('message'); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                            <tr>
                                <th width="40" class="text-center">SL</th>
                                <th class="text-center">Student Name</th>
                                <th class="text-center">Data  gathering technical and Assessment skills</th>
                                <th class="text-center">Clinical Management  skills</th>
                                <th class="text-center">Interpersonal skills</th>
                                <th class="text-center">Total Score</th>
                                <th class="text-center">Overall Examiner’s  Judgment</th>
                            </tr>
                            <tr>
                                <td class="text-center">01</td>
                                <td class="text-center">john</td>
                                <td class="text-center">2</td>
                                <td class="text-center">0</td>
                                <td class="text-center">1</td>
                                <td class="text-center">3</td>
                                <td class="text-center">F</td>
                            </tr>
                            <tr>
                                <td class="text-center">02</td>
                                <td class="text-center">john 08</td>
                                <td class="text-center">2</td>
                                <td class="text-center">0</td>
                                <td class="text-center">1</td>
                                <td class="text-center">3</td>
                                <td class="text-center">P</td>
                            </tr>
                            <tr>
                                <td class="text-center">03</td>
                                <td class="text-center">john 03</td>
                                <td class="text-center">2</td>
                                <td class="text-center">0</td>
                                <td class="text-center">1</td>
                                <td class="text-center">3</td>
                                <td class="text-center">B</td>
                            </tr>
                            <tr>
                                <td class="text-center">04</td>
                                <td class="text-center">john 02</td>
                                <td class="text-center">2</td>
                                <td class="text-center">0</td>
                                <td class="text-center">1</td>
                                <td class="text-center">3</td>
                                <td class="text-center">F</td>
                            </tr>
                            <tr>
                                <td class="text-center">05</td>
                                <td class="text-center">john 05</td>
                                <td class="text-center">2</td>
                                <td class="text-center">0</td>
                                <td class="text-center">1</td>
                                <td class="text-center">3</td>
                                <td class="text-center">F</td>
                            </tr>
                            <tr>
                                <td class="text-center">06</td>
                                <td class="text-center">john 07</td>
                                <td class="text-center">2</td>
                                <td class="text-center">0</td>
                                <td class="text-center">1</td>
                                <td class="text-center">3</td>
                                <td class="text-center">F</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>


                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a class="btn btn-primary" href="admin/result/minimum_pass_score">Generate minimum pass score</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>