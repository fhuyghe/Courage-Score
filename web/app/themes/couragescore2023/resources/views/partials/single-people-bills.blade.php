<section id="bills">
    <div id="tableTop">
      <h2>Votes</h2>

      @php $topics = get_terms('vote-topic') @endphp
      <select id="topics">
        <option value="" selected>Filter by topic</option>
        @foreach ($topics as $topic)
            <option value="{{ $topic->slug }}">{{ $topic->name }}</option>
        @endforeach
      </select>
    </div>

    <div id="floorCommittee" class="tableToggle">
      <button data-val="floor_votes" class="active">Floor Vote</button>
      <button data-val="committee_votes">Committee Vote</button>  
    </div>

    @if($votes) 
    {{ count($votes[2022]) }} Votes
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