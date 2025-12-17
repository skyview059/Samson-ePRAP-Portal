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
        font-size: 20px;
        font-weight: 700;
        color: #6C00A1;
    }
    .exam-list .item p {
        margin-bottom: 30px;
        text-align: center;
        color: #666;
    }

    .exam-list .item a {
        display: block;
        text-align: center;
        padding: 5px 10px;
        border-radius: 5px;
        background: #6C00A1;
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        transition: all 0.3s;
    }
</style>

<?php
if ($exams):
    echo '<p class="text-center">Please select the course</p>';
    echo '<p>&nbsp;</p>';
    echo '<div class="row exam-list">';
    foreach ($exams as $exam):
        ?>
        <div class="col-md-3">
            <div class="item">
                <h4><?= $exam->name; ?></h4>
                <p>You will get total<br><?php echo ($exam->scenarios); ?> scenarios<br>under <?php echo ($exam->topics); ?> topics</p>
<!--                <a href="--><?php //= site_url('scenario-practice/exam/' . $exam->id); ?><!--">Explore Now <i class="fa fa-arrow-right"></i> </a>-->
                <a href="<?= site_url('scenario-practice/exam/explore/' . $exam->id); ?>">Explore Now <i class="fa fa-arrow-right"></i> </a>
            </div>
        </div>
    <?php
    endforeach;
    echo '</div>';
endif;
?>