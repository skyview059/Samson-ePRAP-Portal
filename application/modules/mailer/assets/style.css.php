<style>
    .selectTemplate {border: 3px solid #ddd;}
    .selectTemplate.selected {border: 3px solid blue;}
    .template_response {text-align: left;}
    button.submit_btn {min-width: 200px;}
    .step_2 .nav>li>a {padding: 10px 25px;}
    .step_2 .navbar-nav.pull-right>li>a {padding-top: 0;padding-bottom: 0;}

    .sent_response, .students_list{
        min-height: 200px;
        max-height: 450px;
        overflow-y: scroll;
        padding-right: 10px; 
    }    
    .custom_students {
        max-height: 300px;
        overflow-y: scroll;
        padding-bottom: 20px;
        min-height: 200px;
    }
    .searched_students {
        max-height: 300px;
        overflow-y: scroll;padding-bottom: 20px;
        min-height: 200px;
    }
    .custom_date, .custom_students, .student_search{display: none;}
    #search { cursor: pointer}
    
    #mailer_tab .nav-tabs-custom>.nav-tabs>li.active>a, 
    #mailer_tab .nav-tabs-custom>.nav-tabs>li.active:hover>a {
        background-color: #FFF;
        color: #444;
    }
    #specialty_box { height: 250px; overflow-y: scroll; border: 1px solid #999;}
</style>