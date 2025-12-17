<style>
    #scenarioPracticeContent {
        -webkit-user-select: none; /* Disable text selection in Safari */
        -moz-user-select: none; /* Disable text selection in Firefox */
        -ms-user-select: none; /* Disable text selection in IE/Edge */
        user-select: none; /* Disable text selection in modern browsers */
    }

    img {
        pointer-events: none;
        -webkit-user-drag: none;
    }

    .tabbable-panel {
        border: 1px solid #eee;
        padding: 10px;
    }

    /* Default mode */
    .tabbable-line > .nav-tabs {
        border: none;
        margin: 0;
    }

    .tabbable-line > .nav-tabs > li {
        margin-right: 2px;
        padding-left: 35px;
        padding-right: 35px;
    }

    .tabbable-line > .nav-tabs > li > a {
        border: 0;
        margin-right: 0;
        color: #737373;
    }

    .tabbable-line > .nav-tabs > li > a > i {
        color: #a6a6a6;
    }

    .tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
        border-bottom: 4px solid #fbcdcf;
    }

    .tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
        border: 0;
        background: none !important;
        color: #333333;
    }

    .tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
        color: #a6a6a6;
    }

    .tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
        margin-top: 0;
    }

    .tabbable-line > .nav-tabs > li.active {
        border-bottom: 4px solid #f3565d;
        position: relative;
    }

    .tabbable-line > .nav-tabs > li.active > a {
        border: 0;
        color: #333333;
    }

    .tabbable-line > .nav-tabs > li.active > a > i {
        color: #404040;
    }

    .tabbable-line > .tab-content {
        margin-top: -3px;
        background-color: #fff;
        border: 0;
        border-top: 1px solid #eee;
        padding: 15px 0;
    }

    .portlet .tabbable-line > .tab-content {
        padding-bottom: 0;
    }

    /* Below tabs mode */

    .tabbable-line.tabs-below > .nav-tabs > li {
        border-top: 4px solid transparent;
    }

    .tabbable-line.tabs-below > .nav-tabs > li > a {
        margin-top: 0;
    }

    .tabbable-line.tabs-below > .nav-tabs > li:hover {
        border-bottom: 0;
        border-top: 4px solid #fbcdcf;
    }

    .tabbable-line.tabs-below > .nav-tabs > li.active {
        margin-bottom: -2px;
        border-bottom: 0;
        border-top: 4px solid #f3565d;
    }

    .tabbable-line.tabs-below > .tab-content {
        margin-top: -10px;
        border-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .form-group label {
        font-size: 15px;
        font-weight: normal;
    }

    h3.title {
        font-size: 20px;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: underline;
    }
</style>
<div id="scenarioPracticeContent">
    <?php foreach ($scenarios as $scenario): ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div style="padding: 50px 0">
                        <h2><?php echo $scenario->presentation; ?></h2>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <div style="padding-top: 60px">
                        <a href="<?= site_url('admin/online_mock?id=' . $scenario->exam_id); ?>"
                           class="btn btn-primary"><i class="fa fa-long-arrow-left"></i> Back to Mock</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#examiner_information" data-toggle="tab">Examiner</a>
                                </li>
                                <li>
                                    <a href="#candidate_instructions" data-toggle="tab">Candidate</a>
                                </li>
                                <li>
                                    <a href="#patient_information" data-toggle="tab">Patient</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane" id="candidate_instructions">
                                    <?= $scenario->candidate_instructions; ?>
                                </div>

                                <div class="tab-pane" id="patient_information">

                                    <?php if ($scenario->candidate_instructions): ?>
                                        <h3 class="title">Candidate Information:</h3>
                                        <?= $scenario->candidate_instructions; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->patient_information): ?>
                                        <h3 class="title">Patient Information:</h3>
                                        <?= $scenario->patient_information; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->examiner_information): ?>
                                        <h3 class="title">Examiner Information:</h3>
                                        <?= $scenario->examiner_information; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->setup): ?>
                                        <h3 class="title">Set up:</h3>
                                        <?= $scenario->setup; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->setup): ?>
                                        <h3 class="title">Set up:</h3>
                                        <?= $scenario->setup; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->exam_findings): ?>
                                        <h3 class="title">Examination Findings:</h3>
                                        <?= $scenario->exam_findings; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->approach): ?>
                                        <h3 class="title">Approach:</h3>
                                        <?= $scenario->approach; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->explanation): ?>
                                        <h3 class="title">Explanation:</h3>
                                        <?= $scenario->explanation; ?>
                                        <br><br>
                                    <?php endif; ?>
                                </div>

                                <div class="tab-pane active" id="examiner_information">
                                    <?php if ($scenario->candidate_instructions): ?>
                                        <h3 class="title">Candidate Information:</h3>
                                        <?= $scenario->candidate_instructions; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->patient_information): ?>
                                        <h3 class="title">Patient Information:</h3>
                                        <?= $scenario->patient_information; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->examiner_information): ?>
                                        <h3 class="title">Examiner Information:</h3>
                                        <?= $scenario->examiner_information; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->setup): ?>
                                        <h3 class="title">Set up:</h3>
                                        <?= $scenario->setup; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->setup): ?>
                                        <h3 class="title">Set up:</h3>
                                        <?= $scenario->setup; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->exam_findings): ?>
                                        <h3 class="title">Examination Findings:</h3>
                                        <?= $scenario->exam_findings; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->approach): ?>
                                        <h3 class="title">Approach:</h3>
                                        <?= $scenario->approach; ?>
                                        <br><br>
                                    <?php endif; ?>

                                    <?php if ($scenario->explanation): ?>
                                        <h3 class="title">Explanation:</h3>
                                        <?= $scenario->explanation; ?>
                                        <br><br>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3" style="position: sticky; top: 20px; z-index: 99;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">Time Left</h3>
                        </div>
                        <div class="panel-body text-left">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Reading Time</td>
                                    <td><span id="reading_time"
                                              data-value="<?= $scenario->reading_time; ?>"><?= timeToSec($scenario->reading_time); ?> minutes</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Practice Time</td>
                                    <td><span id="practice_time"
                                              data-value="<?= $scenario->practice_time; ?>"><?= timeToSec($scenario->practice_time); ?> minutes</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Time</th>
                                    <th>
                                <span id="total_time"
                                      data-value="<?= $scenario->reading_time + $scenario->practice_time; ?>"><?= timeToSec($scenario->reading_time + $scenario->practice_time); ?> minutes</span>
                                    </th>
                                </tr>
                            </table>

                            <!--                        <div class="text-center">-->
                            <!--                            <button type="button" class="btn btn-success" id="start_timer_btn"><i-->
                            <!--                                        class="fa fa-play"></i>-->
                            <!--                                Start Timer-->
                            <!--                            </button>-->
                            <!--                        </div>-->
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">Mock Details</h3>
                        </div>
                        <div class="panel-body text-left">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Total Time</td>
                                    <td><?= timeToSec($total_mock_time); ?> minutes</td>
                                </tr>
                                <tr>
                                    <td>Scenarios</td>
                                    <td><?= '<strong>' . $start + 1 . '</strong>/' . $total_rows; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="container hidden">
    <div class="row">
        <div class="col-md-12" id="scenario_pagination">
            <?php echo $pagination; ?>
        </div>
    </div>
</div>

<script type="application/javascript">
    // get next page url
    function go_next_page() {
        let next_page_url = $('#scenario_pagination').find('li.active').next().find('a').attr('href');
        if (next_page_url) {
            audioForMoveToTheNextStation();
            window.location.href = next_page_url;
        } else {
            audioForEndOfTheExam();
            toastr.success('Practice finished!, No more scenarios to this practice.');
        }
    }

    $(document).ready(function () {
        readingTimeCountdown();
        audioForPleaseReadTheTask();
        totalTimeCountdown();
    });

    // Start the timer
    // $('#start_timer_btn').click(function () {
    //     $(this).prop('disabled', true);
    //     playAudioSound();
    //     totalTimeCountdown();
    //     readingTimeCountdown();
    // });

    function totalTimeCountdown() {
        let totalTime    = $('#total_time').data('value');
        let totalSeconds = totalTime * 60;
        const interval   = setInterval(function () {
            const minutes  = parseInt(totalSeconds / 60).toFixed(0).padStart(2, '0');
            const seconds  = parseInt(totalSeconds % 60).toFixed(0).padStart(2, '0');
            const timeHTML = '<span style="font-weight: bold">' + minutes + ':' + seconds + ' minutes' + '</span>';
            $('#total_time').html(timeHTML);
            if (totalSeconds <= 0) {
                clearInterval(interval);
                $('#total_time').html('<span style="color: red">Time Up</span>');
            } else {
                totalSeconds -= 1;
            }
        }, 1000);
    }

    // Reading time countdown
    function readingTimeCountdown() {
        let readingTime  = $('#reading_time').data('value');
        let totalSeconds = readingTime * 60;
        const interval   = setInterval(function () {
            const minutes  = parseInt(totalSeconds / 60).toFixed(0).padStart(2, '0');
            const seconds  = parseInt(totalSeconds % 60).toFixed(0).padStart(2, '0');
            const timeHTML = '<span style="color: green; font-weight: bold">' + minutes + ':' + seconds + ' minutes' + '</span>';
            $('#reading_time').html(timeHTML);
            if (totalSeconds <= 0) {
                clearInterval(interval);
                $('#reading_time').html('<span style="color: red">Time Up</span>');
                practiceTimeCountdown();
            } else {
                totalSeconds -= 1;
            }
        }, 1000);
    }

    function practiceTimeCountdown() {
        let practiceTime = $('#practice_time').data('value');
        let totalSeconds = practiceTime * 60;
        const interval   = setInterval(function () {
            const minutes  = parseInt(totalSeconds / 60).toFixed(0).padStart(2, '0');
            const seconds  = parseInt(totalSeconds % 60).toFixed(0).padStart(2, '0');
            const timeHTML = '<span style="color: green; font-weight: bold">' + minutes + ':' + seconds + ' minutes' + '</span>';
            $('#practice_time').html(timeHTML);

            // you have 2 minutes remaining
            if (totalSeconds === 120) {
                audioForYouHave2MinutesRemaining();
            }
            if (totalSeconds <= 0) {
                clearInterval(interval);
                $('#practice_time').html('<span style="color: red">Time Up</span>');
                go_next_page();
            } else {
                totalSeconds -= 1;
            }
        }, 1000);
        audioForBegin();
    }

    function audioForPleaseReadTheTask() {
        const audio    = new Audio('<?= site_url() ?>assets/audio/please_read_the_task.opus');
        audio.autoplay = true;
        audio.play();
    }

    function audioForBegin() {
        const audio    = new Audio('<?= site_url() ?>assets/audio/begin.opus');
        audio.autoplay = true;
        audio.play();
    }

    function audioForYouHave2MinutesRemaining() {
        const audio    = new Audio('<?= site_url() ?>assets/audio/you_have_2_minutes_remaining.opus');
        audio.autoplay = true;
        audio.play();
    }

    function audioForEndOfTheExam() {
        const audio    = new Audio('<?= site_url() ?>assets/audio/end_of_the_exam.opus');
        audio.autoplay = true;
        audio.play();
    }

    function audioForMoveToTheNextStation() {
        const audio    = new Audio('<?= site_url() ?>assets/audio/move_to_the_next_station.opus');
        audio.autoplay = true;
        audio.play();
    }

    document.addEventListener('contextmenu', function (event) {
        event.preventDefault(); // Disable right-click
    });

    document.addEventListener('keydown', function (event) {
        if (event.ctrlKey && (event.key === "c" || event.key === "x" || event.key === "u")) {
            event.preventDefault(); // Disable Ctrl+C, Ctrl+X, and Ctrl+U (view source)
        }
    });

    document.onkeydown = function (event) {
        if (event.keyCode === 123) { // F12 key
            return false;
        }
        if (event.ctrlKey && event.shiftKey && (event.keyCode === 73 || event.keyCode === 74)) {
            return false; // Ctrl+Shift+I (DevTools) and Ctrl+Shift+J (Console)
        }
    };
</script>