</div>
</div>
<!-- Body Content End -->

<!-- ./wrapper -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Loading Time <b>{elapsed_time}</b> seconds. App v0.1 & CiF v<?php echo CI_VERSION; ?>
    </div>
    <b>Copyright &copy; <?php echo date('Y') . ' ' . $this->SiteTitle; ?>.</b>
    All rights reserved. <?php if($this->CompanyRegNo){ echo "Company Registration No: <b>{$this->CompanyRegNo}</b>"; } ?>
</footer>

</div>

<!--Toast message-->
<script src="assets/lib/toast/toastr.js"></script>
<script src="assets/lib/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="assets/lib/iCheck/icheck.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/admin/dist/js/app.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="assets/admin/dist/js/demo.js"></script>
<script src="assets/lib/common.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript">
    $(document).ready(function () {
        // toast
        toastr.clear();

        <?php if ($this->session->flashdata('msgs')) { ?>
        toastr.success("<?php echo $this->session->flashdata('msgs'); ?>");
        <?php } ?>

        <?php if ($this->session->flashdata('msge')) { ?>
        toastr.error("<?php echo $this->session->flashdata('msge'); ?>");
        <?php } ?>

        <?php if ($this->session->flashdata('msgw')) { ?>
        toastr.warning("<?php echo $this->session->flashdata('msgw'); ?>");
        <?php } ?>

        <?php if ($this->session->flashdata('msgi')) { ?>
        toastr.info("<?php echo $this->session->flashdata('msgi'); ?>");
        <?php } ?>


        $('#clear_cache').on('click', function (e){
            e.preventDefault();

            $.ajax({
                type: "GET",
                url: "admin/clear_cache",
                dataType: "json",

                beforeSend(){
                    toastr.info('Request process...');
                    $('.loader-toggle').toggle('loader');
                },
                success: function(respond) {
                    if(respond.Status === 'OK'){
                        $('.loader-toggle').toggle('loader');
                        toastr.success('Cache data clear successfully');
                    }
                }
            });
        })
    });
</script>
</body>
</html>