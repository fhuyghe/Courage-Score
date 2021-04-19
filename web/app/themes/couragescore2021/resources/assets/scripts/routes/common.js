import '@popperjs/core'

export default {
  init() {

    // JavaScript to be fired on all pages

    //Hmaburger
    $('.hamburger').on('click', function () {
      $(this).toggleClass('is-active');
      $('.nav-wrap').toggleClass('active');
    });

    //Popover
    $('[data-toggle="popover-click"]').popover({
      //placement: 'left',
      trigger: 'click',
      html: true,
    });
    $('[data-toggle="popover"]').popover({
      //placement: 'left',
      trigger: 'hover',
      html: true,
    });
    
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
