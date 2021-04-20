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
          <div class="rep-name-title">
            <h3>
              {!! get_the_title($post->ID) !!}
              @include('partials.grade-display')
            </h3>
            <h4>State <span class="body">{{ get_field('senate_or_assembly', $post->ID) }}</span></h4>
            @php $leadership = get_field('leadership_position', $post->ID) @endphp
            @if($leadership)
                <h4 class="leadership">{{ $leadership }}</h4>
            @endif
        </div> 
          <div class="portrait">
          <div class="portrait-wrap">
            {!! get_the_post_thumbnail( $post->ID, 'thumbnail' );  !!}
          </div>
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
                <td >{{ $senateAssembly == 'senate' ? 'SD' : 'AD' }}-<span class="district">{{ get_field('district', $post->ID) }}</span></td>
                <td class="party">{{ get_field('party', $post->ID) }}</td>
                <td>@include('partials.score-display')</td>
              </tr>
            </tbody>
          </table>
      </div>
      </div>
      <div id="mapContainer"></div>
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
          <li><a href="#contributions">{{ $data['titles']['contributions_title'] }}</a></li>
        @endif
        @if($partners_scores)
          <li><a href="#partnersScores">{{ $data['titles']['partners_scores_title'] }}</a></li>
        @endif
      </ul>
    </section>
  </div>

  {{-- Main content --}}
  <div class="col-md-7" id="sections">
    <section id="yearsFilter" data-id="{{ $post->ID }}">
      <ul>
      <li><a data-year="">All Time</a></li>

      @php $allYears = array_keys($votes) @endphp
      @foreach ($allYears as $yearOption)  
        <li><a data-year="{{ $yearOption }}" @if($yearOption == $year)class="active"@endif>{{ $yearOption }}</a></li>
      @endforeach
      </ul>
    </section>

    @php 
      $hallOfShameList = App::getHallOfShame();
      $allStarList = App::getAllStars(); 
    @endphp
    @if(in_array($post, $hallOfShameList) || in_array($post, $allStarList))
      <div class="entry-content">
        {!! $data['additional_text'] !!}
      </div>
    @endif

  @if($score == 'na')
    <h4>
      {{ $data['titles']['no_score_available_text'] }}
    </h4>
  @else
      @include('partials.single-people-bills')
  @endif
  
  @if($contributions)
  <section id="contributions">
    <h2>{{ $data['titles']['contributions_title'] }}</h2>
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
      <h2>{{ $data['titles']['partners_scores_title'] }}</h2>
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
