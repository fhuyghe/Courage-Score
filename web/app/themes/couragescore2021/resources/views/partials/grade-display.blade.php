@php 
    $score = App\get_score($post);
    $color = App\get_color($score);
    $letter = App\get_letter($score);

    if(get_field('reason_of_resign', $post->ID)){
        $color = 'grey';
    }
@endphp

<div class="grade {{ $color }}">
    {{ $letter }}
</div>