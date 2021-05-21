import '@popperjs/core'

export default {
  init() {

    // JavaScript to be fired on all pages

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

    //Popover
    var element = '[data-toggle="popover-click"]';
    if (element.length) {
      $(element).popover({
        html: true,
      });
  
      $('body').on('click', function (e) {
          
          //Use each to hide Popovers with the same class
          $(element).each(function(index, elm) {
              hidePopover(elm, e);
          }); 
      });
  
      // hide any open popovers when anywhere else in the body is clicked
      var hidePopover = function(element, e){
        if (!$(element).is(e.target) && $(element).has(e.target).length === 0 && $('.popover').has(e.target).length === 0){
          $(element).popover('hide');
        }
      }
    }



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

  