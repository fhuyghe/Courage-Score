import autocomplete from 'autocompleter';

export default {
  init() {
    // JavaScript to be fired on the home page

    var input = document.getElementById('address-input');

    autocomplete({
      input: input,
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
                category: 'Address',
                countryCode: 'usa',
                searchExtent: '-125.656774,31.611563,-113.850027,42.427281',
              },
              cache: false,
            })
              .done(function (res) {
                let suggestions = res.suggestions;
                suggestions = suggestions.filter(function(address){return address.text.includes('CA,')});
                update(suggestions);
              });
        },
        onSelect: function(item) {
            input.value = item.text;
        },
    });

    $('#address-button').on('click', function () {
      console.log('Button click');
      console.log(process.env);

      $.ajax({
        // eslint-disable-next-line no-undef
        url : my_ajax_object.ajax_url,
        data : {
        action: 'call_example_function',
        },
        success: function(response) {
        console.log(response);
        },
        });
    });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
