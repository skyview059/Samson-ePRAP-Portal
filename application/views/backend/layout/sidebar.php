<aside class="main-sidebar adminsidebar">
    
    <section class="sidebar">                             
        <ul class="sidebar-menu">       
            <?php              
                // General
                echo add_main_menu('Dashboard', 'admin', 'dashboard', 'fa-dashboard');                
                echo Modules::run('assess/_menu');
                
                echo Modules::run('course/_menu');
                echo add_main_menu('Promo Codes', 'admin/promocodes', 'promocodes', 'fa-gift');
                echo Modules::run('exam/_menu');
                echo Modules::run('online_mock/_menu');
                echo Modules::run('practice/_menu');
                echo Modules::run('centre/_menu');
                echo Modules::run('scenario/_menu');

                echo Modules::run('student/_menu');               
                echo Modules::run('message/_menu');
                echo Modules::run('development_plan/_menu');
                echo Modules::run('personal_dev_plan/_menu');
                
//                echo Modules::run('result/_menu');
//                echo Modules::run('exam_preparation/_menu');
//                echo Modules::run('examine/_menu');                                
                echo Modules::run('mailer/_menu');
                echo Modules::run('mailbox/_menu');
                echo Modules::run('users/_menu');
                
                echo Modules::run('job/_menu');
                echo Modules::run('doctor/_menu');
                             
                echo Modules::run('email_templates/menu');
                echo add_main_menu('Settings', 'admin/settings', 'settings', 'fa-gear');
                echo add_main_menu('WhatsApp & Telegram', 'admin/whatsapp', 'whatsapp', 'fa-whatsapp');
                echo Modules::run('progression/_menu');
                echo Modules::run('job_specialty/_menu');
                echo Modules::run('file/_menu');
                echo Modules::run('sms/_menu');
                
                echo add_main_menu('DB Backup & Restore', 'admin/db_sync', 'db_sync', 'fa-hdd-o');
                echo Modules::run('module/menu');
                echo Modules::run('profile/_menu');
           ?>
           <li><a href="admin/logout"><i class="fa fa-sign-out"></i><span>Sign Out</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>


<!-- Body Content Start -->
<div class="content-wrapper">
    <div id="ajaxContent">