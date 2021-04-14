@php
$scorecardsImport = App\get_scorecards();
$scorecards = $scorecardsImport->scorecards;
@endphp
<div class="wrap">
    <h2>Load Votes</h2>
    <label for="scorecards">Choose a Scorecard to import:</label>
    <select name="scorecards" id="scorecards">
        @foreach(array_reverse($scorecards) as $scorecard):
            <option value="{{ $scorecard->scorecardID }}">{{ $scorecard->scorecardName }}</option>
        @endforeach
    </select>
    <button id="updateSubmit">Update Votes</button>

    <div id="preview">
        <h2>
            <span id="voteNumber"></span>
            <span id="previewTitle"></span>
        </h2>
        <ul id="previewList"></ul>
    </div>
    </div>
</div>

<script>
    (function($) {
        let bills = [];

        // LOAD ALL Votes
        $('#updateSubmit').on('click', function () {
            console.log('Loading...');
            $('#previewTitle').html('Loading...');
            $(this).attr('disabled', true);
            $('#previewList').html('');
            
            update_one();
            
        });
        
        function update_one(){
            $.ajax({
                // eslint-disable-next-line no-undef
                url : ajax_object.ajax_url,
                data : {
                action: 'update_votes',
                scorecardID: $('#scorecards').val(),
                },
                success: function (response) {
                    console.log(response);
                    currentNumber = parseInt($('#voteNumber').html());
                    $('#voteNumber').html(currentNumber + response.data.votes.length);
                    $('#previewTitle').html(' Votes');
                    $('#previewList').append('<li>' + response.data.name + ' - ' + response.data.votes.length + ' votes</li>');

                    if(response.success){
                        update_one();
                    }
                },
            });
        }
        
	
    })( jQuery );
</script>