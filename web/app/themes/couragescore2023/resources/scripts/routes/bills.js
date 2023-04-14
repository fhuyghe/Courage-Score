import domReady from '@roots/sage/client/dom-ready';
import 'datatables.net';

domReady(async () => {
    // JavaScript to be fired on the about us page
    var table = $('#billsTable').DataTable({
      paging: false,
    });

    //Update the list per year
    $('#years').on('change', function () {
      let newYear = $(this).val();

      $('tbody').css('opacity', .5);

      //Get new bill rows
      $.ajax({
        url : ajax_object.ajax_url,
        data : {
          action: 'get_bills_per_year',
          year: newYear,
        },
      })
        .done(function (res) {
          $('tbody').css('opacity', 0);
          table
            .rows()
            .remove();
        //update the table
          table
            .rows
            .add(res.data)
            .draw();
          
          $('tbody').css('opacity', 1);
      });
    })
});
