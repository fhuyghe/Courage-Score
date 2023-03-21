import '@popperjs/core/dist/umd/popper.min.js';
import 'bootstrap';
import domReady from '@roots/sage/client/dom-ready';

domReady(async () => {
//Popover
var elements = document.querySelectorAll('[data-toggle="popover-click"]');
if (elements.length  > 0) {
    elements.forEach(element => {

        element.popover({
            html: true,
          });
    })

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

let popoverToggles = document.querySelectorAll('[data-toggle="popover"]');
if(popoverToggles.length > 0){
    popoverToggles.forEach(popoverToggle => {
        console.log(popoverToggle)
        popoverToggle.popover({
            //placement: 'left',
            trigger: 'hover',
            html: true,
          });
    })
}

})