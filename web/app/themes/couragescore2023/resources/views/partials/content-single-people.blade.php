@php 
$year = get_field('score_year', 'option');

function getVotes($year){
        $year = intval($year); 
        $votes = get_field('voting');
        $missingYear = 0;
        $votesByYear = [];

        if($votes):
        while($missingYear < 2):
          //Filter 
          $thisYear = array_filter($votes, function($vote) use ($year){
            if($vote['bill_number']):
              return date("Y", strtotime(get_field('floor_voted_date',$vote['bill_number']))) == $year
                || date("Y", strtotime($vote['vote_date'])) == $year;
            endif;
          });

          // If the filter found votes, add it
          if(!empty($thisYear)):
            $votesByYear[$year] = $thisYear;
            $year--;
          else:
            $year--;
            $missingYear++;
          endif;
        endwhile;
        endif;

        return $votesByYear;
    }

$votes = getVotes($year);

$score = App\get_score($post);
$contributions = $contributions;
$senateAssembly = get_field('senate_or_assembly');

$allYears = array_keys($votes);
$year = $allYears[0];
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
          <div class="rep-name-title">
            <h3>
              {!! get_the_title($post->ID) !!}
            </h3>
            <h4>State <span class="body">{{ get_field('senate_or_assembly', $post->ID) }}</span></h4>
            @php $leadership = get_field('leadership_position', $post->ID) @endphp
            @if($leadership)
            <h4 class="leadership">{{ $leadership }}</h4>
            @endif
          </div> 
          <div class="grade-wrap">
            @include('partials.grade-display')
          </div>
          <div class="portrait">
          <div class="portrait-wrap">
            {!! get_the_post_thumbnail( $post->ID, 'thumbnail' );  !!}
          </div>
          </div>
        </div>
        <div class="rep-info">
          <div>
            <h5>District</h5>
            @php $districtNumber = get_field('district', $post->ID) @endphp
            <p class="district" data-number={{$districtNumber}}>
              {{ $senateAssembly == 'senate' ? 'SD' : 'AD' }}-<span>{{ $districtNumber }}</span>
            </p>
          </div>
          
          <div>
            <h5>Party</h5>
            <p class="party">{{ get_field('party', $post->ID) }}</p>
          </div>
          
          <div>
            <h5>Score</h5>
            <div class="score">
              @include('partials.score-display')
            </div>
          </div>
      </div>
      </div>

      {{-- If the legislator is in office --}}
      @if(!has_category(63))
      <div id="mapContainer"></div>
      @endif
    </section>

    @if($score !== 'na')
      <section id="contact"> 
        <a id="contactToggle">Contact <i class="far fa-arrow-right"></i></a>
      </section>
    @endif

    <section id="submenu">
      <ul>
        <li><a href="#bills">Votes</a></li>
        @if($contributions)
          <li><a href="#contributions">{{ $titles['contributions_title'] }}</a></li>
        @endif
        @if($partnersScores)
          <li><a href="#partnersScores">{{ $titles['partners_scores_title'] }}</a></li>
        @endif
      </ul>
    </section>
  </div>

  {{-- Main content --}}
  <div class="col-md-7" id="sections">
    <section id="yearsFilter" data-id="{{ $post->ID }}">
      <ul>
      <li><a data-year="">All Time</a></li>

      @foreach ($allYears as $yearOption)  
        <li><a data-year="{{ $yearOption }}" @if($yearOption == $year)class="active"@endif>{{ $yearOption }}</a></li>
      @endforeach
      </ul>
    </section>

    @if($additionalText)
      <div class="entry-content">
        {!! $additionalText !!}
      </div>
    @endif

  @if($score == 'na')
    <h4>
      {{ $titles['no_score_available_text'] }}
    </h4>
  @else
      @include('partials.single-people-bills')
  @endif
  
  @if($contributions)
  <section id="contributions">
    <h2>{{ $titles['contributions_title'] }}</h2>
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
              <span class="color-red">${{ number_format($contribution['sum']) }}</span>
            @else
            ${{ number_format($contribution['sum']) }}
            @endif
          </td>
          <td><i class="fal fa-info-circle"
            data-toggle="popover-click" 
            data-bs-content="{{ $source }}"
            title="Sources" ></i>
        </td>
        </tr>
      @endforeach
    </tbody>
    </table>
  </section>
  @endif

  @if($partnersScores)
    <section id="partnersScores">
      <h2>{{ $titles['partners_scores_title'] }}</h2>
      <table id="partnersScoresTable">
        <thead>
        <tr>
          <th>Partner</th>
          <th>Score</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($partnersScores as $p_score)
          <tr>
            <td>
              <a href="{{ get_field('scorecard_link', $p_score['partner']->ID) }}" target="_blank">
                {{ $p_score['partner']->post_title }}
              </a>
            </td>
            <td >
              <div class="grade {{ App\get_color($p_score['score']) }}">
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
