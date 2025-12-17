<?php if (!$plans) { ?>
    <div class="box no-border">
        <div class="box-body text-center soft-warning">
            <h3 class="box-title">Your Personal Development Plan is Not Setup Yet!</h3>
            <a href="personal-development-plan?tab=setup" class="btn btn-primary">
                <i class="fa fa-edit"></i> 
                Click Here to Setup Your Plan
            </a>
        </div>
    </div>
<?php } else { ?> 


    <h3>Personal Development Plan</h3>

    <div class="personaldevelopment">
        <ul class="nav nav-tabs">
            <li class="active"><a href="personal-development-plan"> <i class="fa fa-bars"></i> My Plan</a></li>  
            <li><a href="personal-development-plan?tab=update"><i class="fa fa-edit"></i> Update Plan</a></li>
        </ul>



        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="15%">Domain</th>
                    <th width="17.5%">What specific development needs do I have?</th>
                    <th width="17.5%">How will theses development needs be achieved?</th>
                    <th width="17.5%">How will make sure I have achieved and became competent?</th>
                    <th width="15%">Timescale</th>
                    <th width="17.5%">Evaluation and outcome?</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $p) { ?>
                    <tr>
                        <th><?php echo $p->domain; ?></th>
                        <td><?php echo $p->specific_development; ?></td>
                        <td><?php echo $p->theses_development; ?></td>
                        <td><?php echo $p->i_have_achieved; ?></td>
                        <td><?php echo $p->timescale; ?></td>
                        <td><?php echo $p->evaluation_and_outcome; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
