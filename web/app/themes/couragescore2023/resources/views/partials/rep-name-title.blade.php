<div class="rep-name-title">
    <h3>
        <a href="{{ get_the_permalink($post->ID) }}">
            {!! get_the_title($post->ID) !!}
        </a>
        @include('partials.grade-display')
    </h3>
    <h4><span>@if(has_category('out-of-office',$post->ID))Former @endif</span>State <span class="body">{{ get_field('senate_or_assembly', $post->ID) }}</span></h4>
    @php $leadership = get_field('leadership_position', $post->ID) @endphp
    @if($leadership)
        <h4 class="leadership">{{ $leadership }}</h4>
    @endif
</div> 