import 'datatables.net';
import domReady from '@roots/sage/client/dom-ready';

domReady(async () => {
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
      { type: 'num' },
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
})