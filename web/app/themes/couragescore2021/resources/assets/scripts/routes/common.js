export default {
  init() {
    // JavaScript to be fired on all pages
    $('.address-button').on('click', function () {
      const address = $('.address-input').val();
      $('#districts').html('Loading...');

      $.ajax({
        // eslint-disable-next-line no-undef
        url : my_ajax_object.ajax_url,
        data : {
          action: 'get_district',
          address,
        },
        success: function (response) {
          console.log(response);
          // if (response.data.sldu && response.data.sldl) {
          //   $('#districts').html('Senate : ' + response.data.sldu + ', Assembly: ' + response.data.sldl);
          // } else {
          //   $('#districts').html('No district information found');
          // }
        },
        });
    });
    
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
