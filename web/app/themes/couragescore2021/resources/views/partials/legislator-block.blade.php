@php $senate_or_assembly = get_field('senate_or_assembly', $legislator->ID) @endphp

<div class="legislator-block {{ $senate_or_assembly }}">
    <div class="portrait">
      {!! get_the_post_thumbnail( $legislator->ID, 'medium' );  !!}
    </div>
    <h3>
      <a href="{{ get_permalink( $legislator->ID ) }}">
          {{ $legislator->post_title }}
      </a>
    </h3>
    <h5>District 
      @if($senate_or_assembly == 'assembly')
        {{ get_field('state_assembly_district', $legislator->ID) }}
      @else
        {{ get_field('district', $legislator->ID) }}
      @endif
    </h5>
  <h6>{{ get_field('party', $legislator->ID) }}</h6>
  <div class="score">@include('partials.score-display')</div>
</div>