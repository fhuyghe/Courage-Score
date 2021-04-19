<section id="bills">
    <div id="tableTop">
      <h2>Votes</h2>

      <select id="yearsFilter">
        <option value="{{ $year }}" selected>{{ $year }}</option>

        @php $allYears = array_keys($votes) @endphp
        @foreach ($allYears as $otherYear)
          @if($otherYear < $year)
            <option value="{{ $otherYear }}">{{ $otherYear }}</option>
          @endif
        @endforeach
      </select>

      @php $topics = get_terms('vote-topic') @endphp
      <select id="topics">
        <option value="" selected>Filter by topic</option>
        @foreach ($topics as $topic)
            <option value="{{ $topic->slug }}">{{ $topic->name }}</option>
        @endforeach
      </select>
    </div>

    <div id="floorCommittee">
      <button data-val="floor_votes" class="active">Floor Vote</button>
      <button data-val="committee_votes">Committee Vote</button>  
    </div>

    @if($votes[$year]) 
      <table id="billsTable">
        <thead>
        <tr>
          <th>Type</th>
          <th>Year</th>
          <th>Categories</th>
          <th>Name</th>
          <th>Description</th>
          <th></th>
          <th>Vote</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($allYears as $currentYear)
          @foreach ($votes[$currentYear] as $vote)
            @include('partials.vote-row')
          @endforeach
        @endforeach
      </tbody>
      </table>
    @endif
  </section>