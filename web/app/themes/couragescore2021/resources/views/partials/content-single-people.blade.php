@php 
$year = 2019;
$votes = SinglePeople::votes($year);

// Courage Score
$points = 0;
$voteNumber = 0;
foreach ($votes as $vote) {
  if($vote['vote'] !== 'n_e'){
    //If the rep could vote
    $voteNumber++;
    if(get_field('oppose', $vote['bill_number']->ID)){
      //If the bill is bad
      if($vote['vote'] == 'n' || $vote['vote'] == 'a'){
        $points++;
      }
    } else {
      //If the bill is good
      if($vote['vote'] == 'y'){
        $points++;
      }
    }
  }
}

$score = round($points * 100 / $voteNumber);
@endphp

<article @php post_class() @endphp>
  <div class="row">
  <div class="col-md-4 sticky">
    <section id="general">
      <div class="row">
        <div class="col-md-6 thumbnail">
          {!! get_the_post_thumbnail( get_the_ID(), 'people-thumbnail_314x314') !!}
        </div>
        <div class="col-md-6 badges">
        </div>
      </div>
      <div class="row">
      <div class="col-md-12">
        @include('partials.rep-info')
        <h3>Manual Score: {{ $data['scores'][0]['score'] }} / {{ $score }}</h3>
      </div>
      </div>
    </section>
  </div>
  <div class="col-md-8" id="sections">
  <div class="entry-content">
    @php the_content() @endphp
  </div>

  <section>
    <h2>{{ $data['senate_or_assembly'] }} District {{ $data['district'] }}</h2>
    <div id="mapContainer"></div>
  </section>

  <section id="contributions">
    <h2>Contributions</h2>
  </section>

  <section id="bills">
    <h2>Votes</h2>
    @if($votes)
      <table id="billsTable">
        <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Vote</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($votes as $vote)
          @php $bill = $vote['bill_number']; @endphp
          @include('partials.vote-row')
        @endforeach
      </tbody>
      </table>
    @endif
  </section>

  <section id="partnersScores">
    <h2>Partners Scores</h2>
  </section>
</div>
</article>
