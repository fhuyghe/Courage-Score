@php global $post @endphp


<section id="tableWrap">
  <div id="filter">
    <select id="years">
      @php $score_year = get_field('score_year', 'option') @endphp
      @while ($score_year > 2014)
          <option value="{{ $score_year }}">{{ $score_year }}</option>
          @php --$score_year @endphp
      @endwhile
    </select>
  </div>
<table id="billsTable" style="width:100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    @php $bills = PageBills::bills(2020); @endphp
    @foreach($bills as $post)
    @php setup_postdata( $post ) @endphp
    @include('partials.bill-row')
    @php wp_reset_postdata() @endphp
    @endforeach
  </tbody>
  </table>
</section>