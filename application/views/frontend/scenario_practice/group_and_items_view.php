<style>
    #accordion {
        margin: auto;
        max-width: 100%;
        padding-top: 15px;
    }

    .panel-heading a {
        display: block;
        position: relative;
        font-weight: bold;

        &::after {
            content: "";
            border: solid black;
            border-width: 0 3px 3px 0;
            display: inline-block;
            padding: 5px;
            position: absolute;
            right: 0;
            top: 0;
            transform: rotate(45deg);
        }

        &[aria-expanded="true"]::after {
            transform: rotate(-135deg);
            top: 5px;
        }
    }

    .custom_title {
        cursor: pointer;
    }

    .panel-title > a:hover {
        text-decoration: none;
    }

    .custom_title:hover {
        text-decoration: underline;
        color: #6C00A1;
        font-weight: bold;
        transition: color 0.4s, font-weight 0.4s;
    }

    .form-group label {
        font-size: 15px;
        font-weight: normal;
    }

    li.diagnosis_title {
        display: flex;
        justify-content: space-between;
        padding: 7px 0;
        transition: background-color 0.3s;
    }

    li.diagnosis_title:hover {
        background-color: #f5f5f5;
    }

    .show_subject_items {
        font-size: 13px;
        margin-left: 15px;
        background: #D7D7D7;
        padding: 2px 10px;
        border-radius: 5px;
        color: black;
    }

    .show_subject_items.success {
        background: green;
        color: white;
    }
</style>

<div class="row" style="padding-bottom: 15px">
    <div class="col-md-5">
        <h2 style="margin-top: 0;"><?= $exam->name; ?> /UKMLA OSCE Practice</h2>
        <p>Total scenarios: <span id="total_scenarios_count" class="label label-info"></span></p>
    </div>
    <div class="col-md-7 text-right">
        <form method="get" action="" style="display: inline-block; vertical-align: middle; margin-right: 10px;">
            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" name="search" class="form-control" placeholder="Search diagnosis or presentation..." value="<?= isset($search) ? html_escape($search) : '' ?>">
                <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                    <?php if (isset($search) && $search !== ''): ?>
                        <a href="<?= current_url() ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                    <?php endif; ?>
            </span>
            </div>
        </form>

        <button type="button" class="btn btn-xs btn-success" id="showHideFullList"><i class="fa fa-arrows-v"></i> Show
            Full List
        </button>
        <a href="<?= site_url('scenario-practice'); ?>" class="btn btn-xs btn-primary"><i
                    class="fa fa-long-arrow-left"></i> Back to Exams</a>
        <button class="btn btn-xs btn-primary" onclick="startPractice()">Practice <i class="fa fa-long-arrow-right"></i>
        </button>
    </div>
</div>

<?php
if ($scenarioItems):
    foreach ($scenarioItems as $subject):
        ?>
        <div class="panel-group" id="accordion">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion"
                           href="#collapseItem<?php echo $subject->subject_id; ?>"><?php echo $subject->subject_name; ?></a>
                    </h3>
                </div>
                <div id="collapseItem<?php echo $subject->subject_id; ?>" class="panel-collapse collapse <?= (isset($search) && $search !== '') ? 'in' : '' ?>">
                    <div class="panel-body">
                        <div class="row">
                            <ol>
                                <?php
                                foreach ($subject->topics as $topic):
                                    echo '<li><h4 style="font-weight: bold">' . $topic->topic_name . '</h4></li>';
                                    echo '<ol>';
                                    $sl = 0;
                                    foreach ($topic->topic_items as $item):
                                        ?>
                                        <li id="li-<?= $item->id; ?>"
                                            class="diagnosis_title status_<?= strtolower($item->status); ?>">
                                            <div>
                                                <span><?php echo ++$sl; ?>.</span>
                                                <a class="custom_title"
                                                   href="<?= site_url('scenario-practice/exam/' . $exam->id . '/item/' . $item->id); ?>">
                                                    <?php
                                                    $displayName = $item->display_title;
                                                    if (isset($search) && $search !== '') {
                                                        $displayName = preg_replace('/(' . preg_quote($search, '/') . ')/i', '<span style="background-color: yellow; font-weight: bold;">$1</span>', $displayName);
                                                    }
                                                    echo $displayName;
                                                    ?>
                                                </a>
                                            </div>
                                        </li>
                                    <?php
                                    endforeach;
                                    echo '</ol>';
                                endforeach; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<script type="application/javascript">

    // show or hide full list
    $('#showHideFullList').click(function () {
        const text = $(this).text();
        if (text === 'Show Full List') {
            $(this).text('Hide Full List');
            $('.panel-collapse').addClass('in');
        } else {
            $(this).text('Show Full List');
            $('.panel-collapse').removeClass('in');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // count li.diagnosis_title by accordion item
        countItems();
    });

    function countItems() {
        const accordionItems = document.querySelectorAll('.panel-collapse');
        let totalItems       = 0;
        let totalCompleted   = 0;
        accordionItems.forEach(function (item) {
            const count          = item.querySelectorAll('li.diagnosis_title').length;
            const countCompleted = item.querySelectorAll('li.diagnosis_title .status_completed').length;

            // remove previous count
            if (item.previousElementSibling.querySelector('a').querySelector('.show_subject_items')) {
                item.previousElementSibling.querySelector('a').querySelector('.show_subject_items').remove();
            }
            item.previousElementSibling.querySelector('a').innerHTML += ` <span class="show_subject_items" style="margin-left: 15px;">${count}</span>`;

            if (countCompleted === count && countCompleted > 0) {
                // remove previous completed
                if (item.previousElementSibling.querySelector('a').querySelector('.show_subject_items.success')) {
                    item.previousElementSibling.querySelector('a').querySelector('.show_subject_items.success').remove();
                }
                item.previousElementSibling.querySelector('a').innerHTML += ` <span class="show_subject_items success" style="background-color: green;">Completed</span>`;
            }
            totalItems += count;
            totalCompleted += countCompleted;
        });
        // show total completed items
        document.querySelector('#total_scenarios_count').innerHTML = `${totalItems}`;
    }
</script>

<?php $this->load->view('frontend/scenario_practice/start_practice_modal'); ?>