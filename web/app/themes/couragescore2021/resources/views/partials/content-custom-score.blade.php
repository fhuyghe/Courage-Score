@php global $post @endphp
@php $score_select = get_field('score') @endphp

<div class="intro">
  @php the_content() @endphp
</div>

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
    @foreach($representatives as $post)
    @php setup_postdata( $post ) @endphp
    @include('partials.rep-row-custom')
    @php wp_reset_postdata() @endphp
    @endforeach
  </tbody>
  </table>
</section>