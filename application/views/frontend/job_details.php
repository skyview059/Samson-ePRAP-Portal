<style type="text/css">
    .job_details {
        background: #FBFBFB;
        border: 1px solid #d5d5d5;
        padding: 5px 18px 15px 10px;
        margin: 5px 0px 5px 0px;
        border-radius: 8px;
        color: #656565;
        font-size: 16px;
    }

    .job_summary h4 {
        font-size: 20px;
        text-align: center;
        font-weight: 700;
        color: #198bcc;
    }

    .job_details_left h3 {
        color: #4d7092;
        font-weight: 700;
        padding-bottom: 10px;
    }
</style>

<div class="job_details">
    <div class="row">
        <div class="col-md-9">
            <div class="job_details_left">
                <h3><?php echo $job->post_title ?></h3>
                <p><?php echo $job->description ?></p>
                <p><strong>Skills: </strong><?php echo $job->skills ?></p>
                <p><strong>Benefit: </strong><?php echo $job->benefit ?></p>
                <p><strong>Service Hour: </strong><?php echo $job->service_hour ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="job_summary">
                <h4>Job Summary</h4>
                <hr>
                <p><strong>Salary Type: </strong><?php echo $job->salary_type; ?></p>
                <?php if(in_array($job->salary_type, ['Yearly', 'Hourly'])): ?>
                    <p><strong>Salary: </strong><?php echo globalCurrencyFormat($job->rate); ?></p>
                <?php endif; ?>
                <p><strong>Job Type: </strong><?php echo $job->job_type; ?></p>
                <p><strong>Vacancy: </strong><?php echo $job->vacancy; ?></p>
                <p><strong>Deadline: </strong><?php echo globalDateFormat($job->deadline); ?></p>
                <p><strong>Location: </strong><?php echo $job->location; ?></p>
                <p><strong>Published on: </strong><?php echo globalDateFormat($job->created_at); ?></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <a href="<?php echo site_url('job/apply/'.$job->id); ?>" class="btn btn-success">Apply Now</a>
        </div>
    </div>
</div>