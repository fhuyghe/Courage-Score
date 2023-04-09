<script src="https://kit.fontawesome.com/28ecd9ba70.js" crossorigin="anonymous"></script>

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
    <button id="resetSubmit" disabled>Reset 2022 Votes</button>

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

    #preview li{
        cursor: pointer;
    }

    #preview li.loading{
        pointer-events: none;
        opacity: .5;

        -webkit-animation: pulse 3s infinite ease-in-out;
        -o-animation: pulse 3s infinite ease-in-out;
        -ms-animation: pulse 3s infinite ease-in-out; 
        -moz-animation: pulse 3s infinite ease-in-out; 
        animation: pulse 3s infinite ease-in-out;
    }
    #preview li.loading:after{
        content: ' - Loading...';
    }

    @-webkit-keyframes pulse {
    0% { opacity: 0.7; }
    50% { opacity: .3; }
    100% { opacity: 0.7; }
}

@keyframes pulse {
    0% { opacity: 0.7; }
    50% {  opacity: .3; }
    100% { opacity: 0.7; }
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
            
            update_one(repsToUpdate[updateCounter], true)
        });
        
        // RESET ALL Votes
        $('#resetSubmit').on('click', function () {
            console.log('Resetting...');
            $('#previewTitle').html('Resetting...');
            $(this).attr('disabled', true);
            
            reset_one(repsToUpdate[updateCounter], true)
        });

        //UPDATE ONE REP
        $('#preview').on('click', 'li', (e)=>{
            let rep = {
                ID: $(e.target).attr('data-ID'),
                name: $(e.target).html(),
                billtrack_id: $(e.target).attr('data-billtrackid'),
            };
            update_one(rep, false)
        });

        function update_one(rep, repeat){
            $('li[data-ID="' + rep.ID +'"]').addClass('loading');

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
                        console.log(response.data)

                        $('li[data-ID="' + rep.ID +'"]').removeClass('loading');

                    if(response.success){
                        $('li[data-ID="' + rep.ID +'"]').css('color', 'green');
                        $('li[data-ID="' + rep.ID +'"]').append(' - ' + response.data.created + ' created, ' + response.data.updated + ' updated');
                    } else {
                        $('li[data-ID="' + rep.ID +'"]').css('color', 'red');
                        $('li[data-ID="' + rep.ID +'"]').append(' - ' + response.data);
                    }

                    //If there is more, run again
                    if(repeat & updateCounter < repsToUpdate.length - 1){
                        updateCounter++
                        update_one(repsToUpdate[updateCounter], true)
                    }

                    },
                });
        }
        
        function reset_one(rep, repeat){
            $('li[data-ID="' + rep.ID +'"]').addClass('loading');

            $.ajax({
                    // eslint-disable-next-line no-undef
                    url : ajax_object.ajax_url,
                    method: 'POST',
                    data : {
                        action: 'reset_votes',
                        nonce: ajax_object.ajax_nonce,
                        year: 2022,
                        rep
                    },
                    success: function (response) {
                        console.log(response.data)

                        $('li[data-ID="' + rep.ID +'"]').removeClass('loading');

                    if(response.success){
                        $('li[data-ID="' + rep.ID +'"]').css('color', 'green');
                        if (response.data)
                            $('li[data-ID="' + rep.ID +'"]').append(' - ' + response.data.reset + ' deleted.');
                    } else {
                        $('li[data-ID="' + rep.ID +'"]').css('color', 'red');
                        $('li[data-ID="' + rep.ID +'"]').append(' - ' + response.data);
                    }

                    //If there is more, run again
                    if(repeat & updateCounter < repsToUpdate.length - 1){
                        updateCounter++
                        reset_one(repsToUpdate[updateCounter], true)
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
                    $('#resetSubmit').attr('disabled', false);
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