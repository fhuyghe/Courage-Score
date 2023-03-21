@php global $post @endphp

<section id="tableWrap">
  <div class="tableToggle">
    <button id="">All</button>
    <button id="assembly" class="active">Assembly</button>
    <button id="senate">Senate</button>
  </div>
<table id="representativesTable" style="width:100%">
  <thead>
    <tr>
      <th>Body</th>
      <th>Name</th>
      <th>District</th>
      <th>Party</th>
      <th>Score</th>
      <th>Grade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($allRepresentatives as $post)
      @php setup_postdata( $post ) @endphp
      @include('partials.rep-row')
      @php wp_reset_postdata() @endphp
    @endforeach
  </tbody>
  </table>
</section>