import 'datatables.net';

export default {
  init() {
    // JavaScript to be fired on the about us page
    var table = $('#billsTable').DataTable({
      //searching: false,
      paging: false,
      // columnDefs: [
      //   { 'visible': false, 'targets': 0 },
      // ],
    });


    // $('.filters button').on('click', function () {
    //   $('.filters button').removeClass('active');
    //   $(this).addClass('active');
    //   let filterItem = $(this).attr('id');
    //   $('.rep').hide();
    //   $('.rep.' + filterItem).show();
    // });

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
    
  },
};
