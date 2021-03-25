import autocomplete from 'autocompleter';

export default {
  init() {
    // JavaScript to be fired on the home page

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
                nonce: window.sage.nonce,
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
    
    /*****************/
    // Search by Address
    /*****************/
    var nameInput = $('.name-input')[0];

    autocomplete({
      input: nameInput,
      render: function(item) {
        var div = document.createElement('div');
        div.textContent = item.post_title;
        return div;
    },
        fetch: function(text, update) {
            text = text.toLowerCase();
            // you can also use AJAX requests instead of preloaded data
            //var suggestions = countries.filter(n => n.label.toLowerCase().startsWith(text))
          $.ajax({
              // eslint-disable-next-line no-undef
              url : my_ajax_object.ajax_url,
              data : {
                action: 'get_name_suggestion',
                text: text,
                nonce: window.sage.nonce,
              },
            })
              .done(function (res) {
                let suggestions = res.data;
                update(suggestions);
              });
        },
        onSelect: function(item) {
          nameInput.value = item.post_title;
        },
    });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
