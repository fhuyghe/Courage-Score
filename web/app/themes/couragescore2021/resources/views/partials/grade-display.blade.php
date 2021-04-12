@php 
    $score = get_field('progressive_voting_by_member_in_assembly');
    
    // if(have_rows('progressive_voting_by_member')): 
    //     $current_year = date('Y');
    //     while(have_rows('progressive_voting_by_member')): the_row();
    //         if(get_sub_field('na') == 1){$score = 'na';} else {$score = get_sub_field('score');}
    //         $vote_info[] = array( 'year' => get_sub_field('years') , 'score' => $score);
    //         $score = $vote_info[0]['score']; 
            
    //     endwhile;
    // endif;

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