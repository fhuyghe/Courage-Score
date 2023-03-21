<div id="yearFilter">
    <select id="years">
      @php $score_year = get_field('score_year', 'option') @endphp
      @while ($score_year > 2014)
          <option value="{{ $score_year }}">{{ $score_year }}</option>
          @php --$score_year @endphp
      @endwhile
    </select>
  </div>