import autocomplete from 'autocompleter';

export default {
  init() {
    // JavaScript to be fired on all pages
    $('.address-button').on('click', function () {
      const address = $('.address-input').val();
      $('#districts').html('Loading...');

      $.ajax({
        // eslint-disable-next-line no-undef
        url : ajax_object.ajax_url,
        data : {
          action: 'get_district',
          address,
        },
        success: function (response) {
          console.log(response);
          // if (response.data.sldu && response.data.sldl) {
          //   $('#districts').html('Senate : ' + response.data.sldu + ', Assembly: ' + response.data.sldl);
          // } else {
          //   $('#districts').html('No district information found');
          // }
        },
        });
    });

    /*****************/
    // Search by Address
    /*****************/
    var addressInput = $('.address-input')[0];

    autocomplete({
      input: addressInput,
      render: function(item) {
        var div = document.createElement('div');
        div.textContent = item.text;
        return div;
    },
        fetch: function(text, update) {
            text = text.toLowerCase();
            // you can also use AJAX requests instead of preloaded data
            //var suggestions = countries.filter(n => n.label.toLowerCase().startsWith(text))
            $.ajax({
              url: 'https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/suggest?',
              data: {
                f: 'json',
                text: text,
                category: 'Address, Residence, Postal',
                countryCode: 'usa',
                searchExtent: '-125.656774,31.611563,-113.850027,42.427281',
              },
              cache: false,
            })
              .done(function (res) {
                let suggestions = res.suggestions;
                //suggestions = suggestions.filter(function(address){return address.text.includes('CA,')});
                update(suggestions);
              });
        },
        onSelect: function(item) {
          addressInput.value = item.text;
        },
    });
    
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
