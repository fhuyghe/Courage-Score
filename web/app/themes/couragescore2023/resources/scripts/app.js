import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import domReady from '@roots/sage/client/dom-ready';

//import './modules/popper.js'
import './modules/datatables.js'
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

  //Carousels
  if($('.home').length > 0){
    import('./modules/carousels.js')
  }

  // All Bills page
  if($('.page.bills').length > 0){
    import('./routes/bills.js')
  }

  //Hamburger
  $('.hamburger').on('click', function () {
    $(this).toggleClass('is-active');
    $('.nav-wrap').toggleClass('active');
  });

  if($('.single-people').length > 0){
    console.log('Loaded Single People script');
    import('./routes/singlePeople.js')
  }

  if($('[data-toggle="popover"]').length > 0){
    console.log('Loaded Popper');
    import('./modules/popper.js');
  }
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
