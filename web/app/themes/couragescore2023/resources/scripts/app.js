import 'bootstrap';
import domReady from '@roots/sage/client/dom-ready';

//import './modules/popper.js'
import './modules/datatables.js'
import './modules/carousels.js'
import './modules/search.js'

/**
 * Application entrypoint
 */
domReady(async () => {
  // Newsletter
  $('input[type="submit"]').on('click', function () {
    // Get the parent wrap
    let parentWrap = $(this).parents('.newsletter-wrap');
    
    // the form is hidden, display success
    setTimeout(function () {
      if ($('.actionkit-widget', parentWrap).is(':hidden')) {
        $('.signup-success', parentWrap).show();
      }
     }, 1000)
    
  })

  //Hamburger
  $('.hamburger').on('click', function () {
    $(this).toggleClass('is-active');
    $('.nav-wrap').toggleClass('active');
  });

  if($('.single-people')){
    import('./routes/singlePeople.js')
  }
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
