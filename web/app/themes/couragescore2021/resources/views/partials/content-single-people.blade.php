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
  <div class="col-md-5 sticky">
    <section id="general">
      @include('partials.badge')
      <div class="rep-block">
        <div class="top">
          @include('partials.rep-name-title')
          <div class="portrait">
            {!! get_the_post_thumbnail( $post->ID, 'thumbnail' );  !!}
          </div>
        </div>
          @include('partials.rep-info')
      </div>
      <div id="mapContainer"></div>
      <h3>Manual Score: {{ $data['scores'][0]['score'] }} / {{ $score }}</h3>
    </section>

    <section id="contact">
      <a id="contactToggle">Contact <i class="far fa-arrow-right"></i></a>
    </section>

    <section id="submenu">
      <ul>
        <li><a href="#bills">Votes</a></li>
        <li><a href="#contributions">Contributions</a></li>
          <li><a href="#partnerScores">Partner Scores</a></li>
      </ul>
    </section>
  </div>

  {{-- Main content --}}
  <div class="col-md-7" id="sections">
  <div class="entry-content">
    @php the_content() @endphp
  </div>

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

  <section id="contributions">
    <h2>Contributions</h2>
  </section>

  <section id="partnersScores">
    <h2>Partners Scores</h2>
  </section>
  
  <section id="share">
    @include('partials.share-social')
    @include('partials.share-email')
  </section>
</div>
</article>
