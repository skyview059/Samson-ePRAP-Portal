<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1> Dashboard <small> Mock Calender View </small> </h1>    
    <ol class="breadcrumb">
        <li><a href="<?= Backend_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Dashboard</li>
        <li class="active">Calendar</li>
    </ol>
</section>
<link href="assets/lib/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>
<script src="assets/lib/plugins/fullcalendar/moment.min.js"></script>
<script src="assets/lib/plugins/fullcalendar/fullcalendar.js"></script>
<style type="text/css">
    /*.fc-time { display: none;}*/ 
    .past { opacity: 0.5;} 
    .fc-button-group { text-transform: capitalize; }
</style>
<section class="content">    
    <div class="box">                
        <div class="box-body" id="calendar"></div>                
    </div>       
</section>

<script>
$(function(){        
    $('#calendar').fullCalendar({        
        header: {
            right: 'prev,next',
            center: 'title',
            left: 'listWeek,month,agendaWeek,agendaThreeDay'
        },
        defaultView: 'listWeek',
        events: {
            url: '<?= site_url('admin/calendar/schedule'); ?>',
            method: 'GET',            
            failure: function() {
                alert('there was an error while fetching events!');
            },
            color: '#145885', // a non-ajax option
            textColor: '#FFF' // a non-ajax option
        }
    });
});
</script>