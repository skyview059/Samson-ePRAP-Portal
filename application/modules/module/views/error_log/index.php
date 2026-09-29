<?php defined('BASEPATH') OR exit('No direct script access allowed');
$badges = ['php' => 'warning', 'exception' => 'danger', 'db' => 'danger', 'general' => 'primary', '404' => 'default'];
?>
<section class="content-header">
    <h1> Error Log <small><?php echo html_escape($file_path); ?> (<?php echo number_format($file_size / 1024, 1); ?> KB)</small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL; ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Error Log</li>
    </ol>
</section>

<section class="content">
    <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-success"><?php echo html_escape($this->session->flashdata('message')); ?></div>
    <?php endif; ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <form method="get" action="<?php echo site_url(Backend_URL . 'module/error_log'); ?>" class="form-inline">
                <select name="type" class="form-control input-sm">
                    <option value="">All Types</option>
                    <?php foreach (['php' => 'PHP', 'exception' => 'Exception', 'db' => 'Database', 'general' => 'General', '404' => '404'] as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($type === (string) $key) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="q" value="<?php echo html_escape($q); ?>" class="form-control input-sm" placeholder="Search message, URL, file...">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Filter</button>
                <a href="<?php echo site_url(Backend_URL . 'module/error_log'); ?>" class="btn btn-sm btn-default">Reset</a>
            </form>
        </div>
        <div class="panel-body">
            <p class="text-muted">
                Showing <?php echo count($entries); ?> of <?php echo $total; ?> entries, newest first<?php echo count($entries) >= $max_rows ? " (limited to {$max_rows})" : ''; ?>.
                Click a row to see full details.
            </p>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed" id="error_log">
                    <thead>
                        <tr>
                            <th width="140">Time</th>
                            <th width="90">Type</th>
                            <th>Message</th>
                            <th>URL</th>
                            <th width="130">User | Student</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!$entries): ?>
                        <tr><td colspan="5" class="text-center text-muted">No errors logged.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($entries as $i => $entry): ?>
                        <tr style="cursor:pointer" data-toggle="collapse" data-target="#err_<?php echo $i; ?>">
                            <td><?php echo html_escape($entry['time']); ?></td>
                            <td>
                                <span class="label label-<?php echo isset($badges[$entry['type']]) ? $badges[$entry['type']] : 'default'; ?>"><?php echo html_escape(strtoupper($entry['type'])); ?></span>
                                <?php if ($entry['severity']): ?><br><small><?php echo html_escape($entry['severity']); ?></small><?php endif; ?>
                            </td>
                            <td><?php echo html_escape(mb_strimwidth($entry['message'], 0, 200, '...')); ?></td>
                            <td style="word-break:break-all"><small><?php echo html_escape($entry['url']); ?></small></td>
                            <td><?php echo html_escape($entry['user']); ?></td>
                        </tr>
                        <tr id="err_<?php echo $i; ?>" class="collapse">
                            <td colspan="5"><pre style="max-height:400px;overflow:auto;white-space:pre-wrap;margin:0"><?php echo html_escape($entry['raw']); ?></pre></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($file_size): ?>
            <div class="panel-footer text-right">
                <?php echo form_open(site_url(Backend_URL . 'module/error_log/clear'), ['onsubmit' => "return confirm('Clear the whole error log file?')", 'style' => 'display:inline']); ?>
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Clear Log File</button>
                <?php echo form_close(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
