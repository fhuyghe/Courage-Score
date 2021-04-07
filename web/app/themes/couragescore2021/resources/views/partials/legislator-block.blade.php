@php $senate_or_assembly = get_field('senate_or_assembly') @endphp
@php $party = get_field('party') == 'democrat' ? 'D' : 'R' @endphp

<div class="legislator-block {{ $senate_or_assembly }}">
  <div class="top">
    @include('partials.rep-info')
    <div class="portrait">
      {!! get_the_post_thumbnail( get_the_ID(), 'thumbnail' );  !!}
    </div>
  </div>
  @php $excerpt = get_field('hall_of_shame_slider_text_block') @endphp
  @if($excerpt)
    <div class="excerpt">
      {!! $excerpt !!}
    </div>
  @endif
</div>