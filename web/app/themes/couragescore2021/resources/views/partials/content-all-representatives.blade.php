@php global $post @endphp

<section id="tableWarp">
<table id="representativesTable" style="width:100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>District</th>
      <th>Party</th>
      <th>Score</th>
      <th>Grade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($all_representatives as $post)
    @php setup_postdata( $post ) @endphp
    @include('partials.representative-row')
    @php wp_reset_postdata() @endphp
    @endforeach
  </tbody>
  </table>
</section>