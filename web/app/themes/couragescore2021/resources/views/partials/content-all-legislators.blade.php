<table id="legislatorsTable" style="width:100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Body</th>
      <th>District</th>
      <th>Party</th>
      <th>Courage Score</th>
    </tr>
  </thead>
  <tbody>
    @foreach($all_legislators as $legislator)
    @php $senate_or_assembly = get_field('senate_or_assembly', $legislator->ID) @endphp
    <tr class="{{ $senate_or_assembly }}">
        <td>
            <a href="{{ get_permalink( $legislator->ID ) }}">
              {{ $legislator->post_title }}
          </a>
        </td>
      <td class="body">
          {{$senate_or_assembly}}
      </td>
      <td>
            {{ get_field('district', $legislator->ID) }}
        </td>
      <td>{{ get_field('party', $legislator->ID) }}</td>
      <td>@include('partials.score-display')</td>
    </tr>
    @endforeach
  </tbody>
  </table>