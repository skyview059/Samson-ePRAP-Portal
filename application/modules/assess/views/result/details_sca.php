<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/**
 * SCA exam result report.
 * Expects the standard result vars plus $sca (built by Result::_sca_report()):
 *   stations, max_score, domains[], grades[], statements[], cases[]
 */
$fmt_score = function ($n) { return number_format_fk($n, 1); };
$fmt_max   = function ($n) { return rtrim(rtrim(number_format((float) $n, 1, '.', ''), '0'), '.'); };
$stations  = (int) $sca['stations'];
?>
<style type="text/css">
    .sca-report { max-width: 1200px; margin: 0 auto; background: #eae6e2; border-top: 3px solid #d94f6e; padding: 32px 24px 60px; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; color: #333; }
    .sca-report a { color: #b83a63; }
    .sca-report a:hover { color: #8f2c4d; }
    .sca-title { text-align: center; font-family: Georgia, 'Times New Roman', serif; font-weight: 400; color: #4a4a4a; font-size: 34px; margin: 8px 0 12px; }
    .sca-back { text-align: center; margin-bottom: 28px; }
    .sca-back a { text-decoration: none; color: #c94a68; font-size: 15px; }
    .sca-card { background: #fff; border-radius: 2px; box-shadow: 0 1px 2px rgba(0,0,0,0.06); padding: 32px; margin-bottom: 24px; }
    .sca-card h2 { color: #c94a68; font-weight: 600; font-size: 20px; margin: 0 0 16px; }
    .sca-card h3 { font-size: 16px; font-weight: 600; color: #333; margin: 0; }
    .sca-card p.sca-intro { font-size: 14px; line-height: 1.6; color: #444; margin: 0 0 24px; }
    .sca-anchors { margin: 0; padding-left: 20px; line-height: 1.9; font-size: 15px; }
    .sca-anchors a { text-decoration: none; }

    /* Candidate + summary */
    .sca-candidate { display: flex; flex-wrap: wrap; gap: 6px 40px; font-size: 14px; color: #444; margin-bottom: 20px; }
    .sca-candidate strong { color: #333; }
    .sca-summary { background: #e7edf5; padding: 16px 20px; display: flex; flex-wrap: wrap; gap: 12px 40px; font-size: 15px; margin-bottom: 16px; }
    .sca-result-pass { color: #2e7d32; }
    .sca-result-fail { color: #c0392b; }
    .sca-criteria { font-size: 14px; line-height: 1.6; color: #444; margin: 0 0 20px; }


    /* Domain grade charts */
    .sca-domain { display: grid; grid-template-columns: 260px 1fr; background: #eef2f8; margin-bottom: 14px; padding: 24px; }
    .sca-domain-name { font-weight: 600; font-size: 15px; margin-bottom: 8px; }
    .sca-domain-score { font-size: 14px; color: #444; }
    .sca-bars { display: flex; align-items: flex-end; height: 120px; border-bottom: 2px solid #333; padding: 0 20px; }
    .sca-bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; position: relative; }
    .sca-bar { width: 26px; cursor: default; }
    .sca-bar-tip { position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background: #333; color: #fff; font-size: 12px; padding: 3px 8px; border-radius: 3px; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity .15s; margin-bottom: 4px; }
    .sca-bar-col:hover .sca-bar-tip { opacity: 1; }
    .sca-bar-labels { display: flex; padding: 0 20px; margin-top: 6px; grid-column: 2; }
    .sca-bar-labels div { flex: 1; text-align: center; font-size: 12px; color: #777; }

    /* Feedback statements (aggregated) */
    .sca-fb { background: #eef2f8; margin-bottom: 14px; }
    .sca-fb-head { display: flex; align-items: center; gap: 16px; padding: 18px 24px; cursor: pointer; }
    .sca-fb-count { background: #e0622b; color: #fff; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0; }
    .sca-fb-subject { flex: 1; font-size: 15px; color: #333; }
    .sca-chevron { font-size: 12px; color: #c94a68; }
    .sca-fb-body { display: none; padding: 0 24px 22px 74px; font-size: 14px; line-height: 1.7; color: #444; }
    .sca-fb-body h3 { font-size: 14px; font-weight: 600; margin: 12px 0 4px; color: #333; }
    .sca-fb-body p { margin: 0 0 10px; }
    .sca-fb-body ul { padding-left: 20px; margin: 0 0 10px; }
    .sca-fb-meta { margin-top: 14px; }
    .sca-fb.open .sca-fb-body { display: block; }
    .sca-empty { font-size: 14px; color: #777; }

    /* Individual cases */
    .sca-case-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 20px; }
    .sca-case-badges { display: flex; gap: 8px; flex-shrink: 0; }
    .sca-pill { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: #fff; white-space: nowrap; }
    .sca-pill-pass { background: #4caf50; }
    .sca-pill-fail { background: #c0392b; }
    .sca-pill-bare { opacity: .8; }
    .sca-pill-muted { background: #bbb; }
    .sca-grades { display: grid; grid-template-columns: max-content max-content max-content; justify-content: start; row-gap: 10px; column-gap: 16px; margin-bottom: 16px; }
    .sca-grades .sca-g-domain { font-size: 14px; font-weight: 600; color: #333; text-align: right; }
    .sca-grades .sca-g-grade { font-size: 14px; color: #666; display: flex; align-items: center; gap: 8px; }
    .sca-grades .sca-g-score { font-size: 13px; color: #888; }
    .sca-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .sca-marks { display: flex; justify-content: start; gap: 32px; font-size: 14px; color: #444; padding: 12px 0; border-top: 1px solid #eee; margin-bottom: 8px; }
    .sca-case-fb { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 0; border-top: 1px solid #eee; cursor: pointer; }
    .sca-case-fb .sca-fb-subject { font-size: 14px; }
    .sca-case-fb-body { display: none; padding: 0 0 16px 16px; font-size: 14px; line-height: 1.7; color: #444; border-left: 3px solid #eef2f8; margin-bottom: 8px; }
    .sca-case-fb-body h3 { font-size: 14px; font-weight: 600; margin: 12px 0 4px; color: #333; }
    .sca-case-fb-body p { margin: 0 0 10px; }
    .sca-case-fb-body ul { padding-left: 20px; margin: 0 0 10px; }
    .sca-case-fb.open + .sca-case-fb-body { display: block; }
    .sca-comments { border-top: 1px solid #eee; padding-top: 16px; font-size: 14px; line-height: 1.6; color: #444; }
    .sca-comments strong { display: block; margin-bottom: 6px; color: #333; }

    .sca-footer { text-align: center; padding-top: 8px; }

    .examiner_comment { color: #c94a68 !important;}

    @media (max-width: 767px) {
        .sca-report { padding: 20px 12px 40px; }
        .sca-card { padding: 20px; }
        .sca-domain { grid-template-columns: 1fr; }
        .sca-bar-labels { grid-column: 1; }
        .sca-grades { justify-content: start; }
        .sca-case-head { flex-direction: column; }
    }
</style>

<section class="content-header">
    <h1> View Result (SCA Exam)</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo site_url(Backend_URL . 'assess/result?id=' . $es_id) ?>">Results</a></li>
        <li class="active">SCA Result</li>
    </ol>
</section>

<section class="content">
<div class="sca-report">

    <h1 class="sca-title">Exam Results</h1>
    

    <!-- ================= Overall summary ================= -->
    <div class="sca-card">
        <h2>Overall Summary of Results</h2>

        <div class="sca-candidate">
            <div><?php echo html_escape($results->number_type); ?> Reference Number: <strong><?php echo html_escape($results->gmc_number); ?></strong></div>
            <div>Name: <strong><?php echo html_escape(trim("{$results->fname} {$results->mname} {$results->lname}")); ?></strong></div>
            <div>Test: <strong><?php echo html_escape($results->exam_name); ?></strong></div>
            <div>Date: <strong><?php echo globalDateTimeFormat($results->datetime); ?></strong></div>
        </div>

        <div class="sca-summary">
            <div>Total score: <strong><?php echo $fmt_score($total_score); ?></strong> out of <strong><?php echo $fmt_max($sca['max_score']); ?></strong></div>
            <!-- <div>Pass mark: <strong><?php // echo $fmt_score($req_pass_mark); ?></strong></div> -->
            <div>Pass mark: <strong><?php echo $fmt_score($exam_pass_mark); ?></strong><?php if ($pass_mark_is_manual) { ?> <small class="text-muted" title="Set manually on the Exam form (exam_schedules.pass_mark)">(manual)</small><?php } ?></div>
            <!-- <div>Stations passed: <strong><?php // echo (int) $passed_station; ?></strong> of <strong><?php echo $stations; ?></strong> (minimum <?php echo (int) $results->pass_station; ?>)</div> -->
            <div>Result: <strong class="<?php echo ($pass_or_fail == 'Pass') ? 'sca-result-pass' : 'sca-result-fail'; ?>"><?php echo ($pass_or_fail == 'Pass') ? 'Passed' : 'Failed'; ?></strong></div>
        </div>

        <!-- 
        <?php /* if ( ! empty($passing_criteria_str)) { ?>
            <p class="sca-criteria"><?php echo $passing_criteria_str; ?></p>
        <?php } */ ?> 
        -->

        <ul class="sca-anchors">
            <li><a href="<?php echo current_url(); ?>#domain-grades">Domain Grades</a></li>
            <li><a href="<?php echo current_url(); ?>#feedback-statements">Feedback Statements</a></li>
            <li><a href="<?php echo current_url(); ?>#individual-feedback">Individual Feedback</a></li>
        </ul>
    </div>

    <!-- ================= Domain grades ================= -->
    <div id="domain-grades" class="sca-card">
        <h2>Domain grades</h2>
        <p class="sca-intro">The graphs detail the number of times each grade was selected per marking domain over the <?php echo $stations; ?> case<?php echo $stations == 1 ? '' : 's'; ?>. Hovering over a column will reveal the number of times each grade was selected.</p>

        <?php foreach ($sca['domains'] as $domain_key => $domain) { ?>
            <div class="sca-domain">
                <div>
                    <div class="sca-domain-name"><?php echo $domain['label']; ?></div>
                    <div class="sca-domain-score">Score: <strong><?php echo $fmt_score($domain['score']); ?></strong> out of <strong><?php echo $fmt_max($domain['max']); ?></strong></div>
                </div>
                <div class="sca-bars">
                    <?php foreach ($sca['grades'] as $grade_key => $grade) {
                        $count  = $domain['counts'][$grade_key];
                        $height = ($stations > 0 && $count > 0) ? max(4, round($count / $stations * 110)) : 2;
                        $color  = ($count > 0) ? $grade['color'] : '#c9c9c9';
                        ?>
                        <div class="sca-bar-col">
                            <div class="sca-bar-tip"><?php echo $grade['label']; ?>: <?php echo $count; ?></div>
                            <div class="sca-bar" style="height:<?php echo $height; ?>px;background:<?php echo $color; ?>"></div>
                        </div>
                    <?php } ?>
                </div>
                <div></div>
                <div class="sca-bar-labels">
                    <?php foreach ($sca['grades'] as $grade) { ?>
                        <div><?php echo $grade['label']; ?></div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- ================= Feedback statements (aggregated) ================= -->
    <div id="feedback-statements" class="sca-card">
        <h2>Feedback statements selected in your examination</h2>
        <p class="sca-intro">For any failing domain, the examiners will have selected up to 4 relevant feedback statements. The feedback statements here correspond to the number of times each statement was chosen, with those selected most frequently appearing first. Very occasionally a feedback statement will have also been provided for a domain where you passed. Find out further information on the feedback statements, their links to the relevant marking domains and developmental suggestions on the <a href="https://www.rcgp.org.uk/mrcgp-exams/simulated-consultation-assessment/feedback-statements" target="_blank" rel="noopener">RCGP SCA feedback statement web page</a>.</p>

        <?php if (empty($sca['statements'])) { ?>
            <p class="sca-empty">No feedback statements were selected in this examination.</p>
        <?php } ?>

        <?php foreach ($sca['statements'] as $i => $st) { ?>
            <div class="sca-fb<?php echo ($i == 0) ? ' open' : ''; ?>">
                <div class="sca-fb-head sca-toggle">
                    <div class="sca-fb-count">x<?php echo (int) $st['count']; ?></div>
                    <div class="sca-fb-subject"><?php echo html_escape($st['subject']); ?></div>
                    <div class="sca-chevron"><?php echo ($i == 0) ? '&#9650;' : '&#9660;'; ?></div>
                </div>
                <div class="sca-fb-body">
                    <?php echo $st['description']; ?>
                    <p class="sca-fb-meta">Domain: <strong><?php echo html_escape($st['domain']); ?></strong></p>
                    <p>Selected in: <?php echo html_escape(implode(', ', $st['cases'])); ?></p>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- ================= Individual feedback ================= -->
    <div id="individual-feedback" class="sca-card">
        <h2>Individual Feedback</h2>
        <p class="sca-intro" style="margin:0">The <?php echo $stations; ?> case<?php echo $stations == 1 ? '' : 's'; ?> taken in your SCA <?php echo $stations == 1 ? 'is' : 'are'; ?> detailed below. These include the individual grades you received per marking domain, any feedback statements selected by the examiner, and the examiner's comments.</p>
    </div>

    <?php foreach ($sca['cases'] as $case) { ?>
        <div class="sca-card sca-case" id="result_details_id_<?php echo $case->id; ?>">
            <div class="sca-case-head">
                <h3><?php echo html_escape($case->name); ?></h3>
                <div class="sca-case-badges">
                    <span class="sca-pill <?php echo ($case->result == 'Pass') ? 'sca-pill-pass' : 'sca-pill-fail'; ?>"><?php echo $case->result; ?></span>
                    <?php echo sca_judgment_badge($case->overall_judgment); ?>
                </div>
            </div>

            <div class="sca-grades">
                <?php foreach ($case->grades as $g) { ?>
                    <div class="sca-g-domain"><?php echo $g['label']; ?></div>
                    <div class="sca-g-grade"><?php echo sca_grade_badge($g['grade']); ?></div>
                    <div class="sca-g-score"><?php // echo $fmt_score($g['score']) . '/'.  $fmt_max($g['max']); ?></div>
                <?php } ?>
            </div>

            <!-- <div class="sca-marks">
                <div>Total mark: <strong><?php echo $fmt_score($case->total); ?></strong></div>
                <div>Pass mark: <strong><?php echo $fmt_score($case->pass_mark); ?></strong></div>
            </div> -->

            <?php if (empty($case->statements)) { ?>
                <div class="sca-case-fb" style="cursor:default">
                    <div class="sca-fb-subject sca-empty">No feedback statements selected for this case.</div>
                </div>
            <?php } else { ?>
                <?php foreach ($case->statements as $row) { ?>
                    <div class="sca-case-fb sca-toggle">
                        <div class="sca-fb-subject"><?php echo html_escape($row->subject); ?></div>
                        <div class="sca-chevron">&#9660;</div>
                    </div>
                    <div class="sca-case-fb-body">
                        <?php echo $row->description; ?>
                        <p>Domain: <strong><?php echo html_escape($row->domain_name); ?></strong></p>
                    </div>
                <?php } ?>
            <?php } ?>

            <div class="sca-comments">
                <strong class="examiner_comment">Examiner&rsquo;s comments</strong>
                <?php echo ! empty($case->examiner_comments) ? nl2br_fk(html_escape($case->examiner_comments)) : '<span class="sca-empty">No comments.</span>'; ?>
            </div>
        </div>
    <?php } ?>

    <div class="sca-footer">
        <a href="admin/assess/result/download/<?= "{$s_id}/{$es_id}"; ?>" class="btn btn-lg text-white btn-primary">
            <i class="fa fa-fw fa-download"></i>
            Download
        </a>
    </div>

</div>
</section>

<script type="text/javascript">
    $(function () {
        $('.sca-report').on('click', '.sca-toggle', function () {
            var $head = $(this);
            var $wrap = $head.closest('.sca-fb');
            var opened;
            if ($wrap.length) {
                $wrap.toggleClass('open');
                opened = $wrap.hasClass('open');
            } else {
                $head.toggleClass('open');
                opened = $head.hasClass('open');
            }
            $head.find('.sca-chevron').html(opened ? '&#9650;' : '&#9660;');
        });
    });
</script>
