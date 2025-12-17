<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar-column">
            <?php echo sidebar_links($active); ?>
        </div>
        <div class="col-md-10 content-column">
            <?php
            if(in_array($this->uri->segment(1), ['mock', 'study-plan', 'scenario-practice'])) {
                $this->load->view('frontend/beta');
            }
            ?>
            <div class="content-box">
                <div class="row" style="margin-bottom: 20px">
                    <div class="col-md-6"><h4 style="margin-top: 0; font-weight: bold"><span style="color: grey">Hi,</span> <?= getLoginStudentData('student_name'); ?></h4></div>
                    <div class="col-md-6">
                        <h4 style="text-align: right; font-weight: bold">Student ID No: <?= showStudentID(); ?></h4>
                    </div>
                </div>
