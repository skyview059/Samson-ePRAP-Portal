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
                <div class="box-header with-border">
                    <div class="box-title">Scenario List</div>
                </div>

                <div class="box-body">
                    <?php echo $this->session->flashdata('message'); ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                            <tr>
                                <th width="40" class="text-center">SL</th>
                                <th width="100" class="text-center">Scenario No</th>
                                <th>Scenario Name</th>
                                <th class="text-center hide_on_print" width="150">Produce Result</th>
                            </tr>
                            <tr>
                                <td class="text-center">01</td>
                                <td class="text-center">005</td>
                                <td>Diabetic Foot
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-warning"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">02</td>
                                <td class="text-center">006</td>
                                <td>Head injury in an adult
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">03</td>
                                <td class="text-center">035</td>
                                <td>Angry Patient (post op wound infection-1)
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">04</td>
                                <td class="text-center">050</td>
                                <td>Injured Infant( NAI)
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">05</td>
                                <td class="text-center">053</td>
                                <td>Immunisation (MMR Vaccination)
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">06</td>
                                <td class="text-center">054</td>
                                <td>Constipation
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">07</td>
                                <td class="text-center">073</td>
                                <td>Headache (SAH)
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">08</td>
                                <td class="text-center">121</td>
                                <td>Pain management - breast cancer
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">09</td>
                                <td class="text-center">173</td>
                                <td>GERD
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">10</td>
                                <td class="text-center">189</td>
                                <td>obstructive sleep apnea
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">11</td>
                                <td class="text-center">201</td>
                                <td>AAA (Abdominal aortic aneurysm)
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">12</td>
                                <td class="text-center">210</td>
                                <td>back pain with spinal cord compression
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">13</td>
                                <td class="text-center">220</td>
                                <td>Upper GI bleeding in a talking manikin
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">14</td>
                                <td class="text-center">232</td>
                                <td>Postpartum Hemorrhage - talking maniking
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">15</td>
                                <td class="text-center">290</td>
                                <td>Tonsillitis in a young female
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">16</td>
                                <td class="text-center">292</td>
                                <td>TIA scenario C
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">17</td>
                                <td class="text-center">335</td>
                                <td>Forgetfullness in a 65 year old
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">18</td>
                                <td class="text-center">340</td>
                                <td>Enuresis in a 4 year old
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-fw fa-plus"></i> Create</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <span class="btn btn-primary">Total Result: <?php echo 0 ?></span>

                        </div>
                        <div class="col-md-6 text-right">
                            <?php // echo $pagination ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>