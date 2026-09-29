<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Course  <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url(Backend_URL) ?>"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Course</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">            
        <div class="box-header with-border">
            <form action="<?php echo site_url(Backend_URL . 'course'); ?>" class="form-inline" method="get">
                
                <div class="col-md-3 text-right">
                    <div class="input-group">
                        <select class="form-control" name="category_id" id="category_id">
                            <?php echo getDropDownCategory($category_id); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group">
                        <select class="form-control" name="package_type" id="package_type">
                            <?php echo getDropDownPackageType($package_type, '--Package Type--'); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group">
                        <select class="form-control" name="status" id="status">
                            <?php echo selectOptions($status, [
                                '' => '--Status--',
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ]); ?>
                        </select>
                    </div>
                </div>

                
                <div class="col-md-3">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                        Search
                    </button>
                    <a href="<?php echo site_url(Backend_URL . 'course'); ?>" class="btn btn-default">
                        <i class="fa fa-times"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="box-body">            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th width="40">S/L</th>
                            <th>Category</th>
                            <th>Package Type</th>
                            <th>Name</th>
                            <th class="text-center">Schedule</th>                            
                            <th class="text-center">Booked</th>                            
                            <th class="text-right">Price</th>
                            <th class="text-right">Duration</th>
                            <th class="text-center">Seat</th>
                            <th class="text-center">WhatsApp</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($courses as $course) { ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><?php echo $course->category; ?></td>
                                <td>
                                    <?php $btn_class = getPackageTypeBtnClass($course->package_type); ?>
                                    <div class="btn-group package-type-inline" data-id="<?php echo $course->id; ?>" data-class="<?php echo $btn_class; ?>">
                                        <button type="button" class="btn btn-xs <?php echo $btn_class; ?> pt-label"><?php echo $course->package_type ?: '--'; ?></button>
                                        <button type="button" class="btn btn-xs <?php echo $btn_class; ?> dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Change Package Type">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php foreach (getPackageTypes() as $type) { ?>
                                                <li<?php echo ($type == $course->package_type) ? ' class="active"' : ''; ?>>
                                                    <a href="#" data-value="<?php echo $type; ?>" data-class="<?php echo getPackageTypeBtnClass($type); ?>"><?php echo $type; ?></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>
                                <td><?php echo $course->name; ?></td>                                
                                <td class="text-center"><?php echo intOnly($course->schedule); ?></td>                                
                                <td class="text-center"><?php echo intOnly($course->booked); ?></td>                                
                                <td class="text-right"><?php echo GBP($course->price); ?></td>
                                <td class="text-right"><?php echo $course->duration; ?> days</td>
                                <td class="text-center"><?php echo $course->booking_limit; ?></td>
                                <td class="text-center"><?php echo Wa::hasWhatsApp( $course->id ); ?></td>
                                <td class="text-center"><?php echo isActive($course->status); ?></td>                                
                                <td class="text-center">
                                    <?php
                                    echo anchor(site_url(Backend_URL . 'course/read/' . $course->id), '<i class="fa fa-fw fa-bars"></i> Preview', 'class="btn btn-xs btn-success" title="Details"');
                                    echo anchor(site_url(Backend_URL . 'course/update/' . $course->id), '<i class="fa fa-fw fa-edit"></i> Edit', 'class="btn btn-xs btn-default" title="Edit"');                                    
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="box-footer">
            <div class="row">                
                <div class="col-md-6">
                    <span class="btn btn-primary">Total Course: <?= $total_rows; ?></span>                    
                </div>
                <div class="col-md-6 text-right">
                    <?= $pagination; ?>
                </div>                
            </div>
        </div>
    </div>
</section>

<style type="text/css">
    .package-type-inline { display: flex; }
</style>
<script type="text/javascript">
    $(function () {
        // .table-responsive clips the dropdown menu, so let it overflow while open
        $('.package-type-inline').on('show.bs.dropdown', function () {
            $(this).closest('.table-responsive').css('overflow', 'visible');
        }).on('hide.bs.dropdown', function () {
            $(this).closest('.table-responsive').css('overflow', '');
        });

        $('.package-type-inline .dropdown-menu a').on('click', function (e) {
            e.preventDefault();
            var link  = $(this);
            var box   = link.closest('.package-type-inline');
            var label = box.find('.pt-label');
            var type  = link.data('value');

            if (link.parent().hasClass('active')) {
                return;
            }

            $.ajax({
                type: 'POST',
                data: { id: box.data('id'), package_type: type },
                url: 'admin/course/update_package_type',
                dataType: 'json',
                beforeSend: function () {
                    label.prepend('<i class="fa fa-refresh fa-spin"></i> ');
                    box.find('.btn').prop('disabled', true);
                },
                success: function (respond) {
                    if (respond.Status === 'OK') {
                        label.text(type);
                        box.find('.btn').removeClass(box.data('class')).addClass(link.data('class'));
                        box.data('class', link.data('class'));
                        box.find('li').removeClass('active');
                        link.parent().addClass('active');
                    } else {
                        alert(respond.Msg);
                    }
                },
                error: function () {
                    alert('Package Type could not be updated. Please try again.');
                },
                complete: function () {
                    label.find('.fa-spin').remove();
                    box.find('.btn').prop('disabled', false);
                }
            });
        });
    });
</script>