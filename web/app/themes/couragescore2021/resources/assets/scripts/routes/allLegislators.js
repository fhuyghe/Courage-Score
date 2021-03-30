import 'datatables.net';

export default {
  init() {
    // JavaScript to be fired on the about us page
    $('#legislatorsTable').DataTable({
      paging: false,
      buttons: [
        'selectAll',
        'selectNone',
      ],
      language: {
        buttons: {
            selectAll: 'Select all items',
            selectNone: 'Select none',
        },
    },
    });
  },
};
