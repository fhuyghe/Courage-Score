import autocomplete from 'autocompleter';

export default {
  init() {
    // JavaScript to be fired on the home page

    //Switch between search engines
    let searchSelect = $('#top select');

    searchSelect.on('change', function () {
      $('#top .search-container').removeClass('active');
      $('#top .search-container.' + this.value).addClass('active');
    })
    
    /*****************/
    // Search by Name
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
        onSelect: function(item) {
          nameInput.value = item.post_title;
        },
    });
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
