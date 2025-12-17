<style type="text/css">
    .job_search_result {
        padding-bottom: 15px;
    }

    .job_grid {
        background: #fff;
        border: 1px solid #d5d5d5;
        padding: 5px 18px 15px 10px;
        margin: 5px 0px 5px 0px;
        border-radius: 8px;
        color: #656565;
    }

    .job_grid:hover {
        background: #F5F5F5;
        -webkit-box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
        -moz-box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.16);
    }

    .job_grid .job_deadline {
        font-size: 14px;
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .job_grid h4 a {
        text-decoration: none;
        color: #4D7092;
    }

    .job_grid p {
        font-size: 14px;
    }
</style>

<div class="job_search_result">
    <form action="<?php echo site_url('jobs'); ?>" class="form-inline" method="get">
        <div class="row">
            <div class="col-md-8">
                <h3>Jobs</h3>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">

                <select name="job_for" id="job_for" class="form-control">
                    <option value="">Any</option>
                    <?php echo Tools::getJobFor($job_for); ?>
                </select>

                <button class="btn btn-primary" type="submit">Search</button>
                <a href="<?php echo site_url('jobs'); ?>" class="btn btn-default">Reset</a>
            </div>
        </div>
    </form>

    <?php if($jobs): ?>
    <div class="clearfix" style="padding-bottom: 10px;"></div>

    <?php foreach ($jobs as $job): ?>
        <div class="job_grid">
            <div class="row">
                <div class="col-md-10">
                    <h4>
                        <a href="<?php echo site_url('job-details/' . $job->id); ?>">
                            <?php echo $job->post_title ?>
                        </a>
                    </h4>
                    <p><i class="fa fa-map-marker"></i> <?php echo $job->location; ?></p>                                        
                    <p><?php echo getShortContent($job->description, 350); ?></p>
                </div>
                <div class="col-md-2 text-center">
                    <div class="job_deadline">
                        <i class="fa fa-calendar"></i> Deadline: <?php echo globalDateFormat($job->deadline); ?>
                    </div>
                    <p><i class="fa fa-users"></i> <?php echo $job->vacancy; ?></p>
                    <a class="btn btn-primary btn-xs"
                       href="<?php echo site_url('job-details/' . $job->id); ?>">Details</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="row">
        <div class="col-md-6">
            <span class="btn btn-primary">Total Record: <?php echo $total_rows ?></span>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $pagination; ?>
        </div>
    </div>

    <?php else: ?>
        <div class="alert alert-info" style="margin-top: 15px;">
            <p>No record found.</p>
        </div>
    <?php endif; ?>
</div>