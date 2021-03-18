@php the_content() @endphp

<table style="width:100%">
    <tr>
      <th>Name</th>
      <th>District</th>
      <th>Party</th>
      <th>Courage Score</th>
    </tr>
    @if($data['hallOfShame'])
    @foreach($data['hallOfShame'] as $legislator)
    @php $senate_or_assembly = get_field('senate_or_assembly', $legislator->ID) @endphp
    <tr class="{{ $senate_or_assembly }}">
        <td>
            <a href="{{ get_permalink( $legislator->ID ) }}">
              {{ $legislator->post_title }}
          </a>
        </td>
      <td>
          @if($senate_or_assembly == 'assembly')
            {{ get_field('state_assembly_district', $legislator->ID) }}
          @else
            {{ get_field('district', $legislator->ID) }}
          @endif
        </td>
      <td>{{ get_field('party', $legislator->ID) }}</td>
      <td>@include('partials.score-display')</td>
    </tr>
    @endforeach
    @endif
  </table>