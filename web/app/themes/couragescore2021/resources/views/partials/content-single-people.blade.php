@php 
$year = get_field('score_year', 'option');
$votes = SinglePeople::votes($year);
//$autoScore = App\get_auto_score($votes);
$score = App\get_score($post);
$contributions = $data['contributions'];
$partners_scores = $data['partners_scores'];
$senateAssembly = get_field('senate_or_assembly');
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
        <div class="rep-info">
          <table>
            <thead>
              <tr>
                <th>District</th>
                <th>Party</th>
                <th>Score</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="district">{{ $senateAssembly == 'senate' ? 'SD' : 'AD' }}-{{ get_field('district', $post->ID) }}</td>
                <td class="party">{{ get_field('party', $post->ID) }}</td>
                <td class="score">@include('partials.score-display')</td>
              </tr>
            </tbody>
          </table>
      </div>
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
          <li><a href="#partnersScores">Partner Scores</a></li>
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

    <div id="floorCommittee">
      <button data-val="floor_votes" class="active">Floor Vote</button>
      <button data-val="committee_votes">Committee Vote</button> 
    </div>

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
          <td>
            {{ App\get_industry($contribution['type']) }}
          </td>
          <td>
            @if($contribution['sum'] > $threshold)
              <span class="red">${{ number_format($contribution['sum']) }}</span>
            @else
            ${{ number_format($contribution['sum']) }}
            @endif
          </td>
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
            <td>
              <a href="{{ get_field('scorecard_link', $p_score['partner']->ID) }}" target="_blank">
                {{ $p_score['partner']->post_title }}
              </a>
            </td>
            <td class="{{ App\get_color($p_score['score']) }}">
              <div class="square">
                {{ $p_score['score'] }}
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
      </table>
    </section>
  @endif
  
  @include('partials.share-email')
</div>
</article>
