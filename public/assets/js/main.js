(function($) {
  'use strict';
  
  $('#revifoup-notify-form').on( 'submit', function(e) {
    e.preventDefault();
    
    const $form = $(this);
    const nonce = $form.data('nonce');
    const email = $form.find('input[name="email"]').val();
    const product = $form.data('product-id');

    var data = {
      email,
      product,
      nonce,
      action : 'revifoup_save_notify_email'
    };

    $.ajax({
      url: woocommerce_params.ajax_url,
      method: 'POST',
      data,
      success: function(data) {
        $form.find('input[name="email"]').val('');
        $form.append(`<span class="revifoup-notice">${data}</span>`);
        
        setTimeout( () => {
          $('.revifoup-notice').remove();
        }, 2000 );
      }
    });
  });  
})(jQuery);