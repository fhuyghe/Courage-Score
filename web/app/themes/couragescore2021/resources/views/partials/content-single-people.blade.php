<article @php post_class() @endphp>
  <div class="row">
  <div class="col-md-6">
    <header>
      <h1 class="entry-title">{!! get_the_title() !!}</h1>
      <div id="parliement">{{ $data['senate_or_assembly'] }}</div>
      <div id="district">{{ $data['district'] }}</div>
    </header>
  </div>
  <div class="col-md-6">
  <div id="mapContainer"></div>
  </div>
  <div class="entry-content">
    @php the_content() @endphp
  </div>
  <section id="bills">
    @php 
    $year = 2019;
    $votes = SinglePeople::votes($year);

    // Courage Score
    $points = 0;
    foreach ($votes as $vote) {
      if($vote['vote'] == 'y' && !get_field('oppose', $vote['bill_number']->ID)){
        $points++;
      }
    }
    @endphp
    <h2>Score: {{ $points * 100 / count($votes) }}</h2>
    <h3>Manual Score: {{ $data['scores'][0]['score'] }}</h3>

    @if($votes)
      @foreach ($votes as $vote)
      @php $bill = $vote['bill_number']; @endphp
      <div class="bill-row">
        <p>{{ $bill->post_title }}</p>
        @php 
          switch ($vote['vote']) {
            case 'n_e':
                echo '<span class="square grey">N/E</span>';
                break;
            case 'a':
                echo '<span class="square orange">A</span>';
                break;
            case 'n':
                echo '<span class="square green">NO</span>';
                break;
            case 'y':
                echo '<span class="square green">'.__('YES','progressive').'</span>';
                break;
            }
        @endphp
      </div>
      @endforeach
    @endif
  </section>
</article>
