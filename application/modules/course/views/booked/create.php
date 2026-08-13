<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1> Course Booking <small> as Admin </small> <a href="<?php echo site_url(Backend_URL . 'course/booked') ?>" class="btn btn-default">Back</a> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Backend_URL ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li><a href="<?php echo Backend_URL ?>course">Course</a></li>
        <li><a href="<?php echo Backend_URL ?>course/booked">Booked</a></li>
        <li class="active">Add New</li>
    </ol>
</section>

<?php load_module_asset('course', 'css'); ?>
<section class="content">
    <form id="bookingForm" name="bookingForm" class="form-horizontal">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="student_id" class="col-sm-2 control-label">Select Student :</label>
                    <div class="col-sm-10">
                        <select class="form-control select2" name="student_id" id="student_id">
                            <?php // echo getDropDownStudentList($student_id); ?>
                        </select>
                        <?php echo form_error('student_id') ?>
                    </div>
                </div>
            </div>
        </div>
        <br/>

        <div id="bookCourse"></div>      
    </form>
</section>


<script type="text/javascript">
$(function() {
    setTimeout(function() {
        $('#student_id').select2({
            placeholder: 'Search Student...',
            ajax: {
                url: '<?= site_url('admin/student/ai/search'); ?>',
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                dataType: 'JSON',
                method: 'GET',
                delay: 50,
                processResults: function(response, params) {
                    params.page = params.page || 1;
                    return {
                        results: response.clients,
                        pagination: {
                            more: (params.page * 10) < response.total
                        }
                    }              
                },                
                cache: true
            }
        });

        $('#student_id').on('change', function() {
            var student_id = $(this).val();
            if (!student_id) {
                $('#bookCourse').html('');
                return;
            }
            $.ajax({
                url: '<?= site_url('admin/course/booked/related_data'); ?>/' + student_id,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                    $('#bookCourse').html('<p class="ajax_processing">Please Wait...</p>');
                },
                success: function(respond) {
                    $('#bookCourse').html(respond.Msg);
                }
            });
        });
    }, 1500);
});
</script>    


<?php // load_module_asset('course', 'js'); ?>
<?php // load_module_asset('course', 'js', 'script.common.js.php'); ?>
