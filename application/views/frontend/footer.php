</div>
</div>
</div>
</div>

<!-- Footer End -->
<div class="container clearfix" style="height:40px;">&nbsp;</div>
<div class="footer" <?= $display_none; ?>>
    <section class="copyright">
        <div class="container-fluid text-center">
            &COPY; <?php echo date('Y') . ' ' . getSettingItem('SiteTitle') ?>. All Rights Reserved.
            Company Registration No: <b>09266951</b>
        </div>
    </section>
</div>

<script type='text/javascript' src="assets/lib/plugins/select2/select2.min.js"></script>
<script src="assets/theme/js/scripts.js"></script>
<script src="assets/lib/common.js"></script>
<script src="assets/lib/toast/toastr.js"></script>

<script src="assets/lib/plugins/datepicker/bootstrap-datepicker.js"></script>
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
        $('.js_datepicker').datepicker({
            format   : 'yyyy-mm-dd',
            autoclose: true,
            /*  todayHighlight: true, */
            startDate: '1d'
        });

        $('.select2').select2();
    });

</script>
</body>
</html>