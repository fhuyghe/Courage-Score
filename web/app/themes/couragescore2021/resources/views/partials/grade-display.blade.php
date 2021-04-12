@php 
    $score = App\get_score($post);

    if ( $score == 'na' || empty($score) && $score != "0" ){
        $score = 'na';
        $color = 'grey';
        $letter = 'NA';
    } elseif ($score < 60){
        $color = 'red';
        $letter = 'F';
    } elseif($score < 70 && $score > 59){
        $color = 'orange';
        $letter = 'D';
    } elseif($score < 80 && $score > 69){
        $color = 'yellow';
        $letter = 'C';
    } elseif($score < 90 && $score > 79){
        $color = 'green';
        $letter = 'B';
    } elseif( $score > 89){
        $color = 'blue';
        $letter = 'A';
        if($score == 100){
            $letter = 'A+';
        };
    }
    if(get_field('reason_of_resign')){
        $color = 'grey';
    }
@endphp

<div class="grade {{ $color }}">
    {{ $letter }}
</div>