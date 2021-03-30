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
  <div class="col-md-8">
    <section id="general">
      <div class="row">
      <div class="col-md-8">
        <h1 class="entry-title">{!! get_the_title() !!}</h1>
        <h4 id="body">{{ $data['senate_or_assembly'] }} Assembly</h4>
        <table id="repInfo">
          <tr>
            <th>District</th>
            <th>Party</th>
            <th>Score</th>
            <th>Grade</th>
          </tr>
          <tr>
            <td id="district">{{ $data['district'] }}</td>
            <td id="party">{{ $data['party'] }}</td>
            <td id="score">{{ $score }}</td>
            <td id="grade">{{ $data['district'] }}</td>
          </tr>
        </table>
        <h3>Manual Score: {{ $data['scores'][0]['score'] }}</h3>
      </div>
        <div class="col-md-4 thumbnail">
          {!! get_the_post_thumbnail( get_the_ID(), 'people-thumbnail_314x314') !!}
        </div>
    </section>
  
  <div class="entry-content">
    @php the_content() @endphp
  </div>

  <section id="bills">
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
</div>

  <div class="col-md-4">
    <div id="mapContainer"></div>
    </div>
</article>
