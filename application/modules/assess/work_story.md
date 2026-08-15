Claude, I have a project feature upgrading story to share with you.

Previously this (ePRAP) project support only PLAB Part 2 type of exam assessment. 

Now client want to Start SCA type assessment. 

How ever both are kind of similar. but we need to make few logical update for SAC type. 

Work Directory: @application\modules\assess\
Main Controller: @application\modules\assess\controllers\Assess.php

It's start from this view file:
@application\modules\assess\views\assess\search_student.php 
Assess controller line:20

I start upgrading works. and want handover to you to finish next steps.
adding logical part based on exam_type = ("SCA" || "PLAB Part 2")
application\modules\assess\views\assess\initial_approach.php

application\modules\assess\views\assess\quantitative_feedback.php 
    (old file: application\modules\assess\views\assess\quantitative_feedback.bak.php)

[Note: in qualitative_feedback.php page we need to manage this domain / feedback option form database --- done. Need to update in review page  ]
application\modules\assess\views\assess\qualitative_feedback.php 
    (old file: application\modules\assess\views\assess\qualitative_feedback.bak.php)

application\modules\assess\views\assess\overall_judgment.php
application\modules\assess\views\assess\review.php

https://eprapportal.test/admin/assess/initial_approach/705790
https://eprapportal.test/admin/assess/quantitative_feedback/705790
https://eprapportal.test/admin/assess/qualitative_feedback/705790


qualitative_feedback
Need to upgrade pure html to populate html view form mysql tables 
sca_feedback_domains
sca_feedback_statements



















Also note that after finishing field and ui upgrading.
I want to make it ajax, pjax, alpine.js, vue.js based submission with url change option to run faster
which one easiest for this project. 

Note: my point is keep the controller part same. just handel form submit work using javascript. and render next view with js and change url as right now. so if need to reload page it works by default. how ever you need review this part. 
