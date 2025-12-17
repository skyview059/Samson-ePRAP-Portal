<div class="panel panel-info">
    <div class="panel-heading">Students</div>
    <div class="panel-body" style="overflow-x: auto; height: 100vh">
        <?php if ($messages): ?>
            <table class="table table-hover">
                <tbody>
                <?php foreach ($messages as $m):
                    $link = site_url('student-messages/message_view/' . $m->id);
                    $name = ($login_id == $m->from_student_id) ? $m->to_student : $m->from_student;
                    $photo = ($login_id == $m->from_student_id) ? $m->to_student_photo : $m->from_student_photo;
                    // $mail = openBySwitch($m);
                    $active = ($this->uri->segment(3) == $m->id) ? 'active' : '';
                    ?>
                    <tr>
                        <td class="<?php echo $active; ?>">
                            <a href="<?= $link; ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img class="img-circle"
                                             src="<?php echo getPhoto($photo, '', 70, 70); ?>"
                                             width="70" height="70" alt="<?php echo $name; ?>">
                                    </div>
                                    <div class="col-md-9">
                                        <b><?php echo $name; ?></b><br>
                                        <span><?php echo getShortContent($m->subject, 30); ?></span><br>
                                        <small><em><?php echo globalDateTimeFormat($m->open_at); ?></em></small>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="ajax_notice">No Message Found</div>
        <?php endif; ?>
    </div>
</div>