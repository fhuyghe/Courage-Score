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
    <button id="getPossibleUpdates">Get Possible Updates</button>
    <button id="updateSubmit" disabled>Update Votes</button>

    <div id="preview">
        <h2>
            <span id="voteNumber"></span>
            <span id="previewTitle"></span>
        </h2>
        <ul id="previewList"></ul>
    </div>
    </div>
</div>

<style>
    #preview{
        display: block;
        background-color: white;
        margin-top: 20px;
        padding: 50px;
        border-radius: 20px;
        z-index: 99;
        position: relative;
    }

    #previewList{
        padding: 50px 0px;
        column-count: 3;
    }
</style>

<script>
    (function($) {
        let repsToUpdate = [];
        let updateCounter = 0

        // Display all Reps
        $('#getPossibleUpdates').on('click', function () {
            console.log('Loading...');
            $('#previewTitle').html('Loading...');
            $(this).attr('disabled', true);
            $('#previewList').html('');
            
            find_reps();
            
        });

        // LOAD ALL Votes
        $('#updateSubmit').on('click', function () {
            console.log('Updating...');
            $('#previewTitle').html('Loading...');
            $(this).attr('disabled', true);
            
            var promises = [];

            repsToUpdate.length = 3;
            update_one(repsToUpdate[updateCounter])
        });

        function update_one(rep){
            $('li[data-ID="' + rep.ID +'"]').append(' - updating');
            $.ajax({
                    // eslint-disable-next-line no-undef
                    url : ajax_object.ajax_url,
                    method: 'POST',
                    data : {
                        action: 'update_votes',
                        nonce: ajax_object.ajax_nonce,
                        scorecardID: $('#scorecards').val(),
                        rep
                    },
                    success: function (response) {
                        $('li[data-ID="' + rep.ID +'"]').css('color', 'green');
                        $('li[data-ID="' + rep.ID +'"]').append(' - ' + response.data.votes + ' votes');

                        //If there is more, run again
                        if(updateCounter < repsToUpdate.length - 1){
                            updateCounter++
                            update_one(repsToUpdate[updateCounter])
                        }

                    },
                });
        }

        function find_reps(){
            $.ajax({
                // eslint-disable-next-line no-undef
                url : ajax_object.ajax_url,
                method: 'GET',
                data : {
                    action: 'get_reps_to_update',
                    nonce: ajax_object.ajax_nonce,
                },
                success: function (response) {
                    $('#getPossibleUpdates').attr('disabled', false);
                    $('#updateSubmit').attr('disabled', false);
                    if(response.data){
                        $('#previewTitle').html('People to update');
                        repsToUpdate = response.data
                        repsToUpdate.forEach(rep => {
                            $('#previewList').append('<li data-ID="' + rep.ID +'" data-billtrackid="' + rep.billtrack_id +'">' + rep.name + '</li>');
                        });
                    } else {
                        $('#previewTitle').html('Error');
                    }
                },
            });
        }
        
        
        
	
    })( jQuery );
</script>