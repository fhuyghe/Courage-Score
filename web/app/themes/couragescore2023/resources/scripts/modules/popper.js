import '@popperjs/core'
import domReady from '@roots/sage/client/dom-ready';

domReady(async () => {
  //Popover
  var elements = $('[data-toggle="popover-click"]');
  elements.popover({ html: true, });

  $('body').on('click', function (e) {
      //Use each to hide Popovers with the same class
      elements.each(function(index, elm) {
          hidePopover(elm, e);
      }); 
  });

  // hide any open popovers when anywhere else in the body is clicked
  var hidePopover = function(element, e){
    if (!$(element).is(e.target) && $(element).has(e.target).length === 0 && $('.popover').has(e.target).length === 0){
      $(element).popover('hide');
    }
  }

  $('[data-toggle="popover"]').popover({
            trigger: 'hover',
            html: true,
          });

})