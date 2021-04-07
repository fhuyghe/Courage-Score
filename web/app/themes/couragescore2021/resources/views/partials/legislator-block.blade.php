@php $senate_or_assembly = get_field('senate_or_assembly', $legislator->ID) @endphp
@php $party = get_field('party', $legislator->ID) == 'democrat' ? 'D' : 'R' @endphp

<div class="legislator-block {{ $senate_or_assembly }}">
  <div class="top">
    <div class="info">
      <h3>
        <a href="{{ get_permalink( $legislator->ID ) }}">
            {{ $legislator->post_title }}
        </a>
      </h3>
      <h4>State {{ $senate_or_assembly }}</h4>
      <div>
        <table>
          <thead>
            <tr>
              <th>District</th>
              <th>Party</th>
              <th>Score</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="district">{{ get_field('district', $legislator->ID) }}</td>
              <td class="party">{{ $party }}</td>
              <td class="Score">@include('partials.score-display')</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="portrait">
      {!! get_the_post_thumbnail( $legislator->ID, 'thumbnail' );  !!}
    </div>
  </div>
  @php $excerpt = get_field('hall_of_shame_slider_text_block', $legislator->ID); @endphp
  @if($excerpt)
    <div class="excerpt">
      {!! $excerpt !!}
    </div>
  @endif
</div>