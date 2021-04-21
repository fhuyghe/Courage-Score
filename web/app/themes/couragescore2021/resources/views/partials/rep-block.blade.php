@php $senate_or_assembly = get_field('senate_or_assembly', $post->ID) @endphp
@php $party = get_field('party', $post->ID)[0] @endphp

<div class="rep-block {{ $senate_or_assembly }}" id="{{ $post->post_name }}">
  <div class="top">
    @include('partials.rep-name-title')
    @include('partials.rep-info')
    <div class="portrait">
      <div class="portrait-wrap">
        <a href="{{ get_the_permalink($post->ID) }}">
          {!! get_the_post_thumbnail( $post->ID, 'thumbnail' );  !!}
        </a>      
      </div>
    </div>
  </div>
  @php 
    $hallOfShameList = App::getHallOfShame();
    $allStarList = App::getAllStars(); 
  @endphp
  @if(in_array($post, $hallOfShameList) || in_array($post, $allStarList))
    @php $excerpt = get_field('hall_of_shame_slider_text_block', $post->ID) @endphp
    @if($excerpt)
      <div class="excerpt">
        {!! $excerpt !!}
      </div>
    @endif
  @endif
</div>