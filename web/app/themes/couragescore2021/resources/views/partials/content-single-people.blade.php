@php 
$year = get_field('score_year', 'option');
$votes = SinglePeople::votes($year);
//$autoScore = App\get_auto_score($votes);
$score = App\get_score($post);
$contributions = $data['contributions'];
$partners_scores = $data['partners_scores'];
@endphp

<article @php post_class() @endphp>
  <div class="row">
  <div class="col-md-5 sticky">
    <section>
      @include('partials.share-social')
    </section>
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

    <div id="floorCommittee">
          <button data-val="floor_votes" class="active">Floor Vote</button>
          <button data-val="committee_votes">Committee Vote</button> 
    </div>

    @php $topics = get_terms('vote-topic') @endphp
    <select id="topics">
      <option value="" default>Bill Topics</option>
      @foreach ($topics as $topic)
          <option value="{{ $topic->slug }}">{{ $topic->name }}</option>
      @endforeach
    </select>

    @if($votes)
      <table id="billsTable">
        <thead>
        <tr>
          <th>Type</th>
          <th>Categories</th>
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
  
  @include('partials.share-email')
</div>
</article>
