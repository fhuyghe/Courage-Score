import 'datatables.net';

export default {
  init() {
    // JavaScript to be fired on the about us page
    let table = $('#representativesTable').DataTable({
      paging: false,
      responsive: true,
      language: {
        search: '<i class="fal fa-search"></i>',
      },
      columnDefs: [
        { visible: false, targets: 0 },
        { responsivePriority: 1, targets: 1 },
        { responsivePriority: 2, targets: -1 },
      ],
    });

    table
    .column( 0 )
    .search( 'assembly', true, false )
      .draw();
    
    $('.tableToggle button').on('click', function () {
      // Change active status
      $('.tableToggle button').removeClass('active');
      $(this).addClass('active');

      // Filter the table
      let val = $(this).attr('id');
      table
          .column(0)
          .search( val, true, false )
          .draw();
    });
    
  },
};
