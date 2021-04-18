@extends('layouts.app')

@section('content')

  @include('partials.frontpage-top')

  <section id="searchResults">
    <div class="container">
      <h2>You Representatives</h2>
      <h5 id="address"></h5>
      <div class="row">
        <div class="col-md-6">
          <div id="assemblyRep"></div>
        </div>
        <div class="col-md-6">
          <div id="senateRep"></div>
        </div>
      </div>
    </div>
  </section>

  <section id="about">
    <div class="container">
      {!! the_content() !!}
    </div>
  </section>


  @php $allStars = $data['all_stars'] @endphp
  @if($allStars)
  <section id="allStar">
    <div class="container">
      <div class="section-header">
        <div class="header-wrap">
        <h2>{{ $allStars['title'] }}</h2>
        {!! $allStars['paragraph'] !!}
      </div>
      </div>
      <div class="section-footer">
        <a class="button" href="{{ $allStars['link'] }}">All Stars</a>
      </div>
      <div id="starList" class="row">
      @php $allStarList = App::getAllStars() @endphp
    @for ($i = 0; $i < 6; $i++)
     @php 
        $post = $allStarList[$i];
        setup_postdata( $post ) 
      @endphp
      @include('partials.rep-block-vertical')
      @php wp_reset_postdata() @endphp
    @endfor
    </div>
    </div>
  </section>
  @endif

  @php $hallOfShame = $data['hall_of_shame'] @endphp
  @if($hallOfShame)
  <section id="hallOfShame">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>{{ $hallOfShame['title'] }}</h2>
          {!! $hallOfShame['paragraph'] !!}
          <a class="button" href="{{ $hallOfShame['link'] }}">Hall of Shame</a>
        </div>
      </div>
      </div>
      <div class="row">
        <div class="col-md-6">
           
        </div>
        <div class="col-md-6">
          <div id="carousel">
            @php $hallOfShameList = App::getHallOfShame() @endphp
            @foreach ($hallOfShameList as $post)
            @php setup_postdata( $post ) @endphp
              @include('partials.rep-block-vertical')
              @php wp_reset_postdata() @endphp
            @endforeach
          </div>
          <div class="arrows">
            <div class="arrow prevArrow"><i class="fas fa-caret-left"></i></div>
            <div class="arrow nextArrow"><i class="fas fa-caret-right"></i></div>
          </div>
        </div>
    </div>
  </section>
  @endif

  @php $honorableMentions = $data['honorable_mentions'] @endphp
  @if($honorableMentions)
  <section id="honorableMentions">
    <div class="container">
      <div class="section-header">
        <h2>{{ $honorableMentions['title'] }}</h2>
        {!! $honorableMentions['paragraph'] !!}
      </div>
        <div class="row">
        @foreach ($honorableMentions['representatives'] as $rep)
            <div class="col-md-4">
              <div class="rep-block vertical">
                <div class="portrait">
                 <img src="{{ $rep['photo']['url'] }}"/>
                </div>
                <div class="rep-name-title">
                  <h3>{!! $rep['name'] !!} </h3>
                  <h5>Score</h5>
                  <p> {{ $rep['score'] }}</p>
                  <h5>Previous Position</h5>
                  <p>{{ $rep['previous_position'] }}</p>
                  <h5>Current Position</h5>
                  <p>{{ $rep['current_position'] }}</p>
                </div>
            </div>
            </div>
        @endforeach
      </div>
      </div>
  </section>
  @endif

@endsection
