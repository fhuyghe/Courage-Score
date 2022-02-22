@php $scores = get_field('alternate_scores') @endphp
@php $score = '' @endphp
@if($scores)
    @foreach($scores as $item)
        @if($item['name'] == $score_select)
            @php $score = $item['score'] @endphp
        @endif
    @endforeach
@endif

<tr class="rep {{ get_field('senate_or_assembly') }}">
    <td>
        {{ get_field('senate_or_assembly') }}
    </td>
    <td class="name">
        <a href="{{ the_permalink() }}">
            <img src="{{ get_the_post_thumbnail_url( $post, 'thumbnail') }}" />
            {{ the_title() }}
        </a>
    </td>
    <td class="district">
        {{ get_field('district') }}
    </td>
    <td class="party">
        {{ get_field('party')[0] }}
    </td>
    <td>
        <div class="score">
            @if($score)
                {{ $score }}
            @else
                N/A
            @endif
        </div>
    </td>
    <td class="grade">
        @php 
            $color = App\get_color($score);
            $letter = App\get_letter($score);

            if(get_field('reason_of_resign', $post->ID)){
                $color = 'grey';
            }
        @endphp

        <div class="grade {{ $color }}">
            @if($letter == 'NA')
                N/A
            @else
                {{ $letter }}
            @endif
</div>
    </td>
</tr>