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

    .job_details h3 {
        color: #4d7092;
        font-weight: 700;
        padding-bottom: 10px;
    }
</style>

<div class="job_details">
    <form action="<?php echo site_url('job/apply/action'); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $job->id; ?>">
        <div class="row">
            <div class="col-md-12">
                <h3><?php echo $job->post_title; ?></h3>
                <label>Cover Letter<sup>*</sup></label>
                <div class="clearfix"></div>
                <textarea name="cover_letter" id="cover_letter" class="form-control" rows="8"><?php echo $cover_letter; ?></textarea>
                <div class="clearfix"></div>
                <?php echo form_error('cover_letter'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center" style="padding-top: 15px;">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>