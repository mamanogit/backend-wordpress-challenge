(function ($) {

jQuery("#form_cf_submit").click(function (e) {

  e.preventDefault();

  handle_cf_form();

});
    function handle_cf_form(e) {
  
        var data = {
          action: "callback_form_cf",
          id: jQuery("#form_cf_id").val(),
          name: jQuery("#form_cf_name").val(),
          email: jQuery("#form_cf_email").val(),
        };
        console.log(e);
        jQuery.ajax({
          url: cursos_fuerza_object.ajax_url,
          type: "post",
          data: data,
          success: function (response) {
            if (response.success) {
    
              console.log(response);
    
            } else {
    
              console.error(response);
    
            }
          }, // end success;
          error: function (error) {
    
            console.error(error);
    
          }, // end error;
    
        }); // end ajax;

    }

})(jQuery);

