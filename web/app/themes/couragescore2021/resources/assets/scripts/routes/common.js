export default {
  init() {

    // JavaScript to be fired on all pages

    //Hmaburger
    $('.hamburger').on('click', function () {
      $(this).toggleClass('is-active');
      $('.nav-wrap').toggleClass('active');
    });

    
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
