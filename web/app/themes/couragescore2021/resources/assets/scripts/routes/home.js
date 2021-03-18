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

    // $('#address-input').on('propertychange change click keyup input paste', function () {
    //   let val = $(this).val();
    //   if(val)
    //   $.ajax({
    //     url: 'https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/suggest?',
    //     data: {
    //       f: 'json',
    //       text: val,
    //       category: 'Address',
    //     },
    //     cache: false,
    //   })
    //     .done(function( res ) {
    //       $('#suggestions').html('');
    //       res.suggestions.forEach(result => {
    //         $('#suggestions').append(
    //           '<div id="' + result.magicKey + '">' + result.text + '</div>'
    //         );
    //       });
    //     });
    // })
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
