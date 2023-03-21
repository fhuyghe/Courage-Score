import autocomplete from 'autocompleter';

let searchSelect = $('#top select');

if(searchSelect){
    searchSelect.on('change', function () {
      $('#top .search-container').removeClass('active');
      $('#top .search-container.' + this.value).addClass('active');
    })
  }

    $('.address-button').on('click', function () {
      const address = $('#address-input').val();
        getDistrict(address);
    });
    
    /*****************/
    // Search by Name
    /*****************/
    var nameInput = $('.name-input')[0];

    if(nameInput){
    autocomplete({
      input: nameInput,
      render: function(item) {
        var div = document.createElement('div');
        let str = '<div class="thumbnail"><img src="' + item.thumbnail + '" /></div>'
        str += '<div class="name">' + item.post.post_title + '</div>'
        str += '<div class="score">' + item.score + '</div>'
        div.insertAdjacentHTML( 'beforeend', str );
        return div;
    },
        fetch: function(text, update) {
          text = text.toLowerCase();
          $.ajax({
              url : ajax_object.ajax_url,
              data : {
                action: 'get_name_suggestion',
                text: text,
              },
            })
            .done(function (res) {
              let suggestions = res.data;
              update(suggestions);
            });
        },
      onSelect: function (item) {
        nameInput.value = item.post.post_title;
        window.location.href = item.url;
        },
    });
  }

    /*****************/
    // Search by Address
    /*****************/
    var addressInput = document.getElementById('address-input');

    if(addressInput && addressInput.length > 0){

    autocomplete({
      input: addressInput,
      highlightMatches: true,
      className: 'suggestions',
      render: function(item) {
        var div = document.createElement('div');
        div.textContent = item.text;
        return div;
      },
      fetch: function(text, update) {
          text = text.toLowerCase();

          $.ajax({
            url: 'https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/suggest?',
            data: {
              f: 'json',
              text: text,
              category: 'Address, Residence, Postal',
              countryCode: 'usa',
              searchExtent: '-125.656774,31.611563,-113.850027,42.427281',
            },
            cache: true,
          })
            .done(function (res) {
              let suggestions = res.suggestions;
              suggestions = suggestions.filter(function (address) { return address.text.includes('CA,') });
              update(suggestions);
            });
      },
      onSelect: function(item) {
        addressInput.value = item.text;
        getDistrict(item.text);
      },
      customize: function(input, inputRect, container) {
        container.style.top = inputRect.top - 80;
        container.style.maxHeight = '200px';
      },
    });

  }

    var getDistrict = function (address) {
        // Get the reps here
      $('#address-input').attr('disabled', true);
      $('.search-container.address').addClass('loading');

      $.ajax({
        // eslint-disable-next-line no-undef
        url : ajax_object.ajax_url,
        data : {
          action: 'get_district_ajax',
          address,
        },
        success: function (response) {
          let searchResults = document.getElementById('searchResults');

          //Populate address
          $('#address').html(address);

          // Populate Assembly
          $('#assemblyRep').html(response.data[0]);
          
          // Populate Senate
          $('#senateRep').html(response.data[1]);
          
          searchResults.classList.add('active');
          searchResults.scrollIntoView({ block: 'start', behavior: 'smooth' });
          
          //Remove Loading Status
          $('#address-input').attr('disabled', false);
          $('.search-container.address').removeClass('loading');
        },
      });
    }