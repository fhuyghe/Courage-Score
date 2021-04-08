@php global $post @endphp

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
    @foreach($all_legislators as $post)
    @php setup_postdata( $post ) @endphp
    @php $senate_or_assembly = get_field('senate_or_assembly') @endphp
    <tr class="{{ $senate_or_assembly }}">
        <td>
            <a href="{{ the_permalink() }}">
              {{ the_title() }}
          </a>
        </td>
      <td class="body">
          {{$senate_or_assembly}}
      </td>
      <td>
            {{ get_field('district') }}
        </td>
      <td>{{ get_field('party') }}</td>
      <td>@include('partials.score-display')</td>
    </tr>
    @php wp_reset_postdata() @endphp
    @endforeach
  </tbody>
  </table>