<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/*
 * SCA feedback statements checkbox list (sca_feedback_domains / sca_feedback_statements).
 * Expects: $feedback_domains (each with ->statements), $selected_statements (array of statement ids).
 * Posts: feedback_statements[] — saved via Assess_model::saveFeedbackStatements().
 * Used by: qualitative_feedback.php and review.php
 */
?>
<style type="text/css">
.fb-domain { margin-bottom: 25px; }
.fb-domain > h2 { font-size: 20px; margin-bottom: 10px; }
.fb-standard { margin-bottom: 15px; }
.fb-standard ul { padding-left: 18px; margin-bottom: 0; }
.fb-statement-row { padding: 7px 0; border-bottom: 1px solid #f4f4f4; }
.fb-statement-row:last-child { border-bottom: 0; }
.fb-statement { font-weight: normal; margin: 0; cursor: pointer; }
.fb-statement input[type="checkbox"] { margin-right: 8px; }
.fb-statement .fb-no { color: #999; margin-right: 4px; }
.fb-notes-toggle { margin-left: 6px; font-size: 12px; white-space: nowrap; }
.fb-notes { display: none; margin: 8px 0 5px 24px; padding: 10px 15px; background: #f9f9f9; border-left: 3px solid #d2d6de; }
.fb-notes h3 { font-size: 14px; font-weight: bold; margin: 12px 0 5px; }
.fb-notes h3:first-child { margin-top: 0; }
.fb-notes p, .fb-notes li { font-size: 13px; }
.fb-notes ul { padding-left: 18px; }
</style>

<?php if (empty($feedback_domains)): ?>
    <div class="callout callout-warning">
        <h4>No feedback statements available</h4>
        <p>Import <code>DB/sca_feedback_statements.sql</code> to populate the SCA feedback domains and statements.</p>
    </div>
<?php else: ?>
    <?php foreach ($feedback_domains as $domain): ?>
    <div class="fb-domain">
        <h2><?php echo html_escape($domain->name); ?></h2>

        <?php if ( ! empty($domain->standard)): ?>
            <div class="callout callout-info fb-standard">
                <strong>Standard for marking</strong>
                <?php echo $domain->standard; ?>
            </div>
        <?php endif; ?>

        <?php foreach ($domain->statements as $statement): ?>
            <div class="fb-statement-row">
                <label class="fb-statement" for="statement_<?php echo $statement->id; ?>">
                    <input type="checkbox" name="feedback_statements[]" id="statement_<?php echo $statement->id; ?>" value="<?php echo $statement->id; ?>" <?php echo in_array($statement->id, $selected_statements) ? 'checked="checked"' : ''; ?>>
                    <span class="fb-no"><?php echo $statement->sl_no; ?>.</span>
                    <?php echo html_escape($statement->subject); ?>
                </label>
                <?php if ( ! empty($statement->description)): ?>
                    <a class="fb-notes-toggle" href="javascript:void(0);" data-target="#statement_notes_<?php echo $statement->id; ?>"><i class="fa fa-chevron-down"></i></a>
                    <div class="fb-notes" id="statement_notes_<?php echo $statement->id; ?>">
                        <?php echo $statement->description; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    $(document).ready(function () {
        // Educator notes of a feedback statement (sca_feedback_statements.description)
        $(document).on('click', '.fb-notes-toggle', function () {
            $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
            $($(this).data('target')).slideToggle(150);
        });
    });
</script>
