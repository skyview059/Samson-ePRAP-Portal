<h3>Let’s Connect</h3>
<div class="panel panel-default">
  <div class="panel-heading">Contact us</div>
  <div class="panel-body">
      
<form class="form-horizontal" id="query" method="post" onsubmit="return send(event);">

        <div class="form-group">
            <label class="col-sm-3" for="send_to">Select Department</label>
            <div class="col-sm-4">
                <select id="send_to" name="send_to" class="form-control">                    
                    <option value="info@samsoncourses.com">For general queries &lt;info@samsoncourses.com&gt;</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-3" for="subject">Query Subject</label>  
            <div class="col-sm-8">
                <input id="subject" name="subject" type="text" placeholder="Enter Subject" class="form-control input-md">                
            </div>
        </div>

        
        <div class="form-group">
            <label class="col-sm-3" for="message">Query Message</label>
            <div class="col-sm-8">                     
                <textarea class="form-control" rows="10" id="message" name="message"></textarea>
            </div>
        </div>
        
        <div class="form-group">            
            <div class="col-sm-4 col-sm-offset-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-location-arrow"></i>
                    Send Inquiry
                </button>
            </div>
        </div>

        <div class="form-group">    
            <div class="col-md-12" id="respond"></div>
        </div>
</form>


<p class="hidden">
    For general queries: <a href="mailto:info@samsoncourses.com">info@samsoncourses.com</a><br/>
    For account problems: <a href="mailto:prop@samsoncourses.com">prop@samsoncourses.com</a><br/>
    For compliant: <a href="mailto:compliant@samsoncourses.com">compliant@samsoncourses.com</a>
</p>
  </div>
</div>






<script type="text/javascript">
    function send(e){
        e.preventDefault();
        var data = $('#query').serialize();
        var error = 0;
        
        var subject = $('#subject').val();
        if(!subject){
            $('#subject').addClass('required');
            error = 1;
        }
        
        if(!error){
            $.ajax({
                type: "post",
                dataType: "json",
                url: '<?= site_url('ajax/send_query'); ?>',
                data: data,
                beforeSend: function(){            
                    $('#respond')
                            .css('display','block')
                            .html('<p class="ajax_proc">Updating, Please wait...</p>');
                },
                success: function (respond) {
                    $('#respond').html(respond.Msg);
                    if( respond.Status === 'OK' ){
                        setTimeout(function(){ jQuery('#respond').slideUp('slow'); }, 2000);                
                    }
                }
            });
        }
        return false;
    }
</script>    