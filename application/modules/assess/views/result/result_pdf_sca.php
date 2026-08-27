<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/**
 * SCA exam result PDF (mPDF).
 * Shared by admin (assess/Result::download_pdf) and the student portal
 * (Student_portal::result_download) — rendered through result_pdf_download().
 * Expects the array from result_build_data(): the standard result vars plus
 * $exam_pass_mark and $sca (stations, max_score, domains[], grades[], statements[], cases[]).
 *
 * Print version of details_sca.php / result_sca.php. mPDF has no flex/grid
 * support, so every layout block here is table based and the grade charts
 * are horizontal bars.
 */
$fmt_score = function ($n) { return number_format_fk($n, 1); };
$fmt_max   = function ($n) { return rtrim(rtrim(number_format((float) $n, 1, '.', ''), '0'), '.'); };
$stations  = (int) $sca['stations'];
$grades    = $sca['grades'];

// Coloured dot + "P – Pass" label (PDF-safe version of sca_grade_badge()).
$grade_label = function ($grade_key) use ($grades) {
    $g = isset($grades[$grade_key]) ? $grades[$grade_key] : $grades['CF'];
    return '<span style="color:' . $g['color'] . ';font-size:12px;">&#9679;</span> ' . $g['short'] . ' &ndash; ' . $g['label'];
};
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SCA Exam Result</title>
        <style type="text/css">
            body { font-family: 'Segoe UI', Helvetica, Arial, sans-serif; color: #333; font-size: 11px; }
            h1.title { text-align: center; font-family: Georgia, 'Times New Roman', serif; font-weight: normal; color: #4a4a4a; font-size: 24px; margin: 4px 0 14px; }
            .card { background: #fff; border: 1px solid #ddd; padding: 14px 16px; margin-bottom: 14px; }
            .card h2 { color: #c94a68; font-weight: bold; font-size: 15px; margin: 0 0 10px; }
            .card h3 { font-size: 13px; font-weight: bold; color: #333; margin: 0; }
            p.intro { font-size: 11px; line-height: 1.5; color: #444; margin: 0 0 12px; }
            a { color: #b83a63; }

            table.candidate td { font-size: 11px; color: #444; padding: 2px 14px 2px 0; }
            table.summary { background: #e7edf5; margin: 6px 0 4px; }
            table.summary td { font-size: 12px; padding: 8px 14px; }
            .result-pass { color: #2e7d32; font-weight: bold; }
            .result-fail { color: #c0392b; font-weight: bold; }

            table.domain { background: #eef2f8; margin-bottom: 8px; }
            table.domain td.dom-l { padding: 10px 12px; vertical-align: top; }
            table.domain td.dom-r { padding: 8px 12px 8px 0; vertical-align: top; }
            .dom-name { font-weight: bold; font-size: 12px; margin-bottom: 3px; }
            .dom-score { font-size: 11px; color: #444; }
            table.bars td { font-size: 10px; color: #555; padding: 2px 4px; }
            table.bars td.g-lbl { width: 62px; }
            table.bars td.g-cnt { width: 22px; text-align: right; color: #333; font-weight: bold; }

            table.fb { background: #eef2f8; margin-bottom: 8px; }
            table.fb td { padding: 8px 10px; vertical-align: top; }
            table.fb td.fb-count { width: 30px; text-align: center; }
            .fb-count-badge { background: #e0622b; color: #fff; font-weight: bold; font-size: 10px; padding: 3px 6px; }
            .fb-subject { font-size: 12px; font-weight: bold; color: #333; margin-bottom: 4px; }
            .fb-body { font-size: 11px; line-height: 1.5; color: #444; }
            .fb-body p { margin: 0 0 6px; }
            .fb-body ul { margin: 0 0 6px; padding-left: 16px; }
            .fb-meta { font-size: 10px; color: #555; margin-top: 4px; }
            .empty { font-size: 11px; color: #777; }

            .case { page-break-inside: avoid; }
            table.grades { margin: 8px 0 6px; }
            table.grades td { font-size: 11px; padding: 3px 10px 3px 0; }
            table.grades td.g-domain { font-weight: bold; color: #333; text-align: right; }
            table.grades td.g-grade { color: #666; }
            table.case-fb { border-top: 1px solid #eee; margin-top: 4px; }
            table.case-fb td { padding: 6px 0; vertical-align: top; font-size: 11px; }
            .case-fb-body { padding: 0 0 4px 10px; border-left: 3px solid #eef2f8; margin: 2px 0 6px; font-size: 11px; line-height: 1.5; color: #444; }
            .case-fb-body p { margin: 0 0 6px; }
            .case-fb-body ul { margin: 0 0 6px; padding-left: 16px; }
            .comments { border-top: 1px solid #eee; padding-top: 8px; margin-top: 6px; font-size: 11px; line-height: 1.5; color: #444; }
            .comments b { display: block; color: #c94a68; margin-bottom: 3px; }
        </style>
    </head>
    <body>

        <table width="100%" style="margin:0 0 6px;">
            <tr>
                <td width="250"><h1 style="margin:0;padding:0;font-size:20px;">Exam Results</h1></td>
                <td align="right"><img alt="ePRAP" src="<?php echo site_url('assets/theme/images/logo.png'); ?>"/></td>
            </tr>
        </table>
        <hr/>

        

        <!-- ================= Overall summary ================= -->
        <div class="card">
            <h2>Overall Summary of Results</h2>

            <table class="candidate" cellspacing="0">
                <tr>
                    <td><?php echo html_escape($results->number_type); ?> Reference Number: <b><?php echo html_escape($results->gmc_number); ?></b></td>
                    <td>Name: <b><?php echo html_escape(trim("{$results->fname} {$results->mname} {$results->lname}")); ?></b></td>
                </tr>
                <tr>
                    <td>Test: <b><?php echo html_escape($results->exam_name); ?></b></td>
                    <td>Date: <b><?php echo globalDateTimeFormat($results->datetime); ?></b></td>
                </tr>
            </table>

            <table class="summary" width="100%" cellspacing="0">
                <tr>
                    <td>Total score: <b><?php echo $fmt_score($total_score); ?></b> out of <b><?php echo $fmt_max($sca['max_score']); ?></b></td>
                    <td>Pass mark: <b><?php echo $fmt_score($exam_pass_mark); ?></b></td>
                    <td>Result: <span class="<?php echo ($pass_or_fail == 'Pass') ? 'result-pass' : 'result-fail'; ?>"><?php echo ($pass_or_fail == 'Pass') ? 'Passed' : 'Failed'; ?></span></td>
                </tr>
            </table>
        </div>

        <!-- ================= Domain grades ================= -->
        <div class="card">
            <h2>Domain grades</h2>
            <p class="intro">The charts detail the number of times each grade was selected per marking domain over the <?php echo $stations; ?> case<?php echo $stations == 1 ? '' : 's'; ?>.</p>

            <?php foreach ($sca['domains'] as $domain_key => $domain) { ?>
                <table class="domain" width="100%" cellspacing="0">
                    <tr>
                        <td class="dom-l" width="36%">
                            <div class="dom-name"><?php echo $domain['label']; ?></div>
                            <div class="dom-score">Score: <b><?php echo $fmt_score($domain['score']); ?></b> out of <b><?php echo $fmt_max($domain['max']); ?></b></div>
                        </td>
                        <td class="dom-r">
                            <table class="bars" width="100%" cellspacing="0">
                                <?php foreach ($grades as $grade_key => $grade) {
                                    $count = (int) $domain['counts'][$grade_key];
                                    $pct   = ($stations > 0 && $count > 0) ? max(3, round($count / $stations * 100)) : 0;
                                    ?>
                                    <tr>
                                        <td class="g-lbl"><?php echo $grade['label']; ?></td>
                                        <td>
                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <?php if ($pct > 0) { ?>
                                                        <td width="<?php echo $pct; ?>%" style="background:<?php echo $grade['color']; ?>;height:9px;"></td>
                                                        <?php if ($pct < 100) { ?>
                                                            <td style="background:#dfe4ec;height:9px;"></td>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <td style="background:#dfe4ec;height:9px;"></td>
                                                    <?php } ?>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="g-cnt"><?php echo $count; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                </table>
            <?php } ?>
        </div>

        <!-- ================= Feedback statements (aggregated) ================= -->
        <div class="card">
            <h2>Feedback statements selected in your examination</h2>
            <p class="intro">For any failing domain, the examiners will have selected up to 4 relevant feedback statements. The feedback statements here correspond to the number of times each statement was chosen, with those selected most frequently appearing first. Very occasionally a feedback statement will have also been provided for a domain where you passed. Find out further information on the feedback statements, their links to the relevant marking domains and developmental suggestions on the RCGP SCA feedback statement web page: <a href="https://www.rcgp.org.uk/mrcgp-exams/simulated-consultation-assessment/feedback-statements">rcgp.org.uk</a></p>

            <?php if (empty($sca['statements'])) { ?>
                <p class="empty">No feedback statements were selected in this examination.</p>
            <?php } ?>

            <?php foreach ($sca['statements'] as $st) { ?>
                <table class="fb" width="100%" cellspacing="0">
                    <tr>
                        <td class="fb-count"><span class="fb-count-badge">x<?php echo (int) $st['count']; ?></span></td>
                        <td>
                            <div class="fb-subject"><?php echo html_escape($st['subject']); ?></div>
                            <div class="fb-body"><?php echo $st['description']; ?></div>
                            <div class="fb-meta">Domain: <b><?php echo html_escape($st['domain']); ?></b> &nbsp;|&nbsp; Selected in: <?php echo html_escape(implode(', ', $st['cases'])); ?></div>
                        </td>
                    </tr>
                </table>
            <?php } ?>
        </div>

        <!-- ================= Individual feedback ================= -->
        <div class="card">
            <h2>Individual Feedback</h2>
            <p class="intro" style="margin:0">The <?php echo $stations; ?> case<?php echo $stations == 1 ? '' : 's'; ?> taken in your SCA <?php echo $stations == 1 ? 'is' : 'are'; ?> detailed below. These include the individual grades you received per marking domain, any feedback statements selected by the examiner, and the examiner&rsquo;s comments.</p>
        </div>

        <?php foreach ($sca['cases'] as $case) { ?>
            <div class="card case">
                <h3><?php echo sprintf('%02d', $case->sl); ?>. <?php echo html_escape($case->name); ?></h3>

                <table class="grades" cellspacing="0">
                    <?php foreach ($case->grades as $g) { ?>
                        <tr>
                            <td class="g-domain"><?php echo $g['label']; ?></td>
                            <td class="g-grade"><?php echo $grade_label($g['grade']); ?></td>
                        </tr>
                    <?php } ?>
                </table>

                <?php if (empty($case->statements)) { ?>
                    <table class="case-fb" width="100%" cellspacing="0">
                        <tr><td class="empty">No feedback statements selected for this case.</td></tr>
                    </table>
                <?php } else { ?>
                    <?php foreach ($case->statements as $row) { ?>
                        <table class="case-fb" width="100%" cellspacing="0">
                            <tr><td><b><?php echo html_escape($row->subject); ?></b></td></tr>
                        </table>
                        <div class="case-fb-body">
                            <?php echo $row->description; ?>
                            <p>Domain: <b><?php echo html_escape($row->domain_name); ?></b></p>
                        </div>
                    <?php } ?>
                <?php } ?>

                <div class="comments">
                    <b>Examiner&rsquo;s comments</b>
                    <?php echo ! empty($case->examiner_comments) ? nl2br_fk(html_escape($case->examiner_comments)) : '<span class="empty">No comments.</span>'; ?>
                </div>
            </div>
        <?php } ?>

    </body>
</html>
