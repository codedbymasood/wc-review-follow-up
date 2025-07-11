(function($) {
  'use strict';
  
  $('#rrw-notify-form').on( 'submit', function(e) {
    e.preventDefault();
    
    const $form = $(this);
    const nonce = $form.data('nonce');
    const email = $form.find('input[name="email"]').val();
    const product = $form.data('product-id');

    var data = {
      email,
      product,
      nonce,
      action : 'rrw_save_notify_email'
    };

    $.ajax({
      url: woocommerce_params.ajax_url,
      method: 'POST',
      data,
      success: function(data) {
        $form.find('input[name="email"]').val('');
        $form.append(`<span class="rrw-notice">${data}</span>`);
        
        setTimeout( () => {
          $('.rrw-notice').remove();
        }, 2000 );
      }
    });
  });  
})(jQuery);