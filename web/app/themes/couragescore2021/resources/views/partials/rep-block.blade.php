@php $senate_or_assembly = get_field('senate_or_assembly', $post->ID) @endphp
@php $party = get_field('party', $post->ID) == 'democrat' ? 'D' : 'R' @endphp

<div class="rep-block {{ $senate_or_assembly }}">
  <div class="top">
    @include('partials.rep-name-title')
    @include('partials.rep-info')
    <div class="portrait">
      {!! get_the_post_thumbnail( $post->ID, 'thumbnail' );  !!}
    </div>
  </div>
  @php $excerpt = get_field('hall_of_shame_slider_text_block', $post->ID) @endphp
  @if($excerpt)
    <div class="excerpt">
      {!! $excerpt !!}
    </div>
  @endif
</div>