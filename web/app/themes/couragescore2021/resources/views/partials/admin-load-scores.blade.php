@php
$scorecardsImport = App\get_scorecards();
$scorecards = $scorecardsImport->scorecards;
@endphp
<div class="wrap">
    <h2>Load Scores</h2>
    <label for="cars">Choose a Scorecard to import:</label>
    <select name="scorecards" id="scorecards">
    @foreach(array_reverse($scorecards) as $scorecard):
        <option value="{{ $scorecard->scorecardID }}">{{ $scorecard->scorecardName }}</option>
    @endforeach
    </select>
    <button id="loadSubmit">Load Scores</button>
    <button id="updateSubmit" disabled>Update All</button>

    <div id="preview">
        <h2 id="previewTitle"></h2>
        <ul id="previewList"></ul>
    </div>
    </div>
</div>

<script>
    (function($) {
        let legislators = [];
        let scorecard = '';

        // LOAD ALL Scores
        $('#loadSubmit').on('click', function () {
            console.log('Loading...');
            $('#previewTitle').html('Loading...');
            $('#updateSubmit').attr('disabled', true);
            $('#previewList').html('');

            scorecard = $('#scorecards option:selected').text(),
            console.log(scorecard)

            $.ajax({
                // eslint-disable-next-line no-undef
                url : ajax_object.ajax_url,
                data : {
                action: 'get_scores_by_scorecard',
                scorecardID: $('#scorecards').val(),
                },
                success: function (response) {
                    console.log(response);
                    legislators = response.data;
                    // If we receive legislators
                    if (legislators.length > 0){
                        $('#updateSubmit').attr('disabled', false);
                        $('#previewTitle').html(response.data.length + ' Scores');
                        legislators.forEach(legislator => {
                            $('#previewList').append(`<li data-id="${legislator.legislatorID}">${legislator.firstName} ${legislator.lastName} - ${Math.floor(legislator.voteIndex)}</li>`);
                        });
                    // If the response is empty
                    } else {
                        $('#previewTitle').html('No scores to import');
                    }
                },
            });
        });

        // Update all selected bills
        $('#updateSubmit').on('click', function () {
            if(legislators.length > 0){
                $('#updateSubmit').attr('disabled', true);
                $('#updateSubmit').html('Updating');
                $('#previewList li').css('opacity', .5);

                //Update Bills one at a time
                legislators.forEach(legislator => {
                    $.ajax({
                        // eslint-disable-next-line no-undef
                        url : ajax_object.ajax_url,
                        data : {
                        action: 'update_score',
                        legislator,
                        scorecard
                        },
                        success: function (response) {
                            $('#previewList li[data-id=' + legislator.legislatorID + ']').css('opacity', 1);
                            $('#previewList li[data-id=' + legislator.legislatorID + ']').append(' - ' + response.data);
                            if(response.data == 'No legislator') 
                                $('#previewList li[data-id=' + legislator.legislatorID + ']').css('color', 'red');
                        }
                    }); 
                });


                $('#updateSubmit').html('Updated');
                $('#updateSubmit').attr('disabled', false);

            } else {
                $('#previewTitle').html('No Scores to update');
            }
        });
	
    })( jQuery );
</script>