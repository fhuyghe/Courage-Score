@php
$scorecardsImport = App\get_scorecards();
$scorecards = $scorecardsImport->scorecards;
@endphp
<div class="wrap">
    <h2>Load Bills</h2>
    <label for="cars">Choose a Scorecard to import:</label>
    <select name="scorecards" id="scorecards">
    @foreach(array_reverse($scorecards) as $scorecard):
        <option value="{{ $scorecard->scorecardID }}">{{ $scorecard->scorecardName }}</option>
    @endforeach 
    </select>
    <button id="loadSubmit">Load Bills</button>
    <button id="updateSubmit" disabled>Update All</button>

    <div id="preview">
        <h2 id="previewTitle"></h2>
        <ul id="previewList"></ul>
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
        let bills = [];

        // LOAD ALL BILLS
        $('#loadSubmit').on('click', function () {
            console.log('Loading...');
            $('#previewTitle').html('Loading...');
            $('#updateSubmit').attr('disabled', true);
            $('#previewList').html('');

            $.ajax({
                // eslint-disable-next-line no-undef
                url : ajax_object.ajax_url,
                data : {
                action: 'get_bills_by_scorecard',
                scorecardID: $('#scorecards').val(),
                },
                success: function (response) {
                    console.log(response);
                    bills = response.data;
                    // If we receive bills
                    if (bills.length > 0){
                        $('#updateSubmit').attr('disabled', false);
                        $('#previewTitle').html(response.data.length + ' Bills');
                        bills.forEach(bill => {
                            $('#previewList').append('<li data-id="' + bill.billID + '">' + bill.stateBillID + ' - ' + bill.billName + '</li>');
                        });
                    // If the response is empty
                    } else {
                        $('#previewTitle').html('No bills to import');
                    }
                },
            });
        });

        // Update all selected bills
        $('#updateSubmit').on('click', function () {
            if(bills.length > 0){
                $('#updateSubmit').attr('disabled', true);
                $('#updateSubmit').html('Updating');
                $('#previewList li').css('opacity', .5);

                //Update Bills one at a time
                //bills.length = 1;
                bills.forEach(bill => {
                    console.log('Before Ajax call: ', bill);
                    $.ajax({
                        // eslint-disable-next-line no-undef
                        url : ajax_object.ajax_url,
                        method: 'POST',
                        data : {
                            action: 'update_bill',
                            nonce: ajax_object.ajax_nonce,
                            bill: bill,
                        },
                        success: function (response) {
                            console.log('Response: ' + response.data);
                            $('#previewList li[data-id=' + bill.billID + ']').css('opacity', 1);
                        },
                        error: function (response) {
                            console.log('Error: ' + response.data);
                            $('#previewList li[data-id=' + bill.billID + ']').css('opacity', 1).css('color', 'red');
                        }
                    }); 
                });

                $('#updateSubmit').html('Updated');
                $('#updateSubmit').attr('disabled', false);

            } else {
                $('#previewTitle').html('No bills to update');
            }
        });
	
    })( jQuery );
</script>