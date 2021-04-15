@php 
$year = get_field('score_year', 'option');
$votes = SinglePeople::votes($year);
$contributions = $data['contributions'];
$partners_scores = $data['partners_scores'];

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

$score = $voteNumber > 0 ? round($points * 100 / $voteNumber) : 'na';
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
        @if($contributions)
          <li><a href="#contributions">Contributions</a></li>
        @endif
        @if($partners_scores)
          <li><a href="#partnerScores">Partner Scores</a></li>
        @endif
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
          <th></th>
          <th>Vote</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($votes as $vote)
          @include('partials.vote-row')
        @endforeach
      </tbody>
      </table>
    @endif
  </section>

  
  @if($contributions)
  <section id="contributions">
    <h2>Contributions</h2>
    <table id="contributionsTable">
      <thead>
      <tr>
        <th>Type</th>
        <th>Amount</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($contributions as $contribution)
      @php 
      $sources = get_field('source_information', 'option') ;
      $source = '';
      $threshold = 0;
      foreach ($sources as $item) {
        if($item['type'] == $contribution['type'])
          $source = $item['info'];
          $threshold = $item['threshold'];
      }
      @endphp
      <tr>
        <td>{{ App\get_industry($contribution['type']) }}</td>
        <td>{{ $contribution['sum'] }}</td>
        <td><i class="fal fa-info-circle"
          data-toggle="popover" 
          data-content="{{ $source }}"
          title="Sources" ></i>
      </td>
      </tr>
      @endforeach
    </tbody>
    </table>
  </section>
  @endif

  @if($partners_scores)
    <section id="partnersScores">
      <h2>Partners Scores</h2>
      <table id="partnersScoresTable">
        <thead>
        <tr>
          <th>Partner</th>
          <th>Score</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($partners_scores as $p_score)
          <tr>
            <td>{{ $p_score['partner']->post_title }}</td>
            <td class="{{ App\get_color($p_score['score']) }}">{{ $p_score['score'] }}</td>
          </tr>
        @endforeach
      </tbody>
      </table>
    </section>
  @endif
  
  <section id="share">
    @include('partials.share-social')
    @include('partials.share-email')
  </section>
</div>
</article>
