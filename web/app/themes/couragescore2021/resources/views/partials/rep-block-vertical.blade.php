@php $senate_or_assembly = get_field('senate_or_assembly', $post->ID) @endphp
@php $party = get_field('party', $post->ID) == 'democrat' ? 'D' : 'R' @endphp

<div class="rep-block vertical">
    <div class="portrait">
      <div class="portrait-wrap">
      <a href="{{ get_the_permalink($post->ID) }}">
      {!! get_the_post_thumbnail( $post->ID, 'medium' );  !!}
      </a>
    </div>
    </div>
    <div class="rep-name-title">
      <h3>
          <a href="{{ get_the_permalink($post->ID) }}">
              {!! get_the_title($post->ID) !!}
          </a>
      </h3>
      <h4 class="body">State <span class="body">{{ get_field('senate_or_assembly', $post->ID) }}</span></h4>
      <h4 class="district">District {{ get_field('district', $post->ID) }}</h4>
    </div>
    @include('partials.grade-display')
</div>