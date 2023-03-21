@extends('layouts.app')

@section('content')

  {{ $top['title'] }}

  @include('partials.frontpage-top')

  <section id="searchResults">
    <div class="container">
      <h2>Your Representatives</h2>
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
      @php $allStarList = $getAllStars @endphp
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
            @php $hallOfShameList = $getHallOfShame @endphp
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

  {{-- Custom Scorecard --}}
  @if($scorecardBanner)
  <section id="scorecardBanner">
    <div class="container">
      <div class="row">
        <div class="col-md-6 image">
          <img src="{{ $scorecardBanner['image']['url'] }}"/>
        </div>
        <div class="col-md-6 text">
          <h2>{{ $scorecardBanner['title'] }}</h2>
          <p>{!! $scorecardBanner['text'] !!}</p>
          <a class="button" href="{{ $scorecardBanner['link'] }}">{{ $scorecardBanner['button_text'] }}</a>
        </div>
      </div>
      </div>
  </section>
  @endif

@endsection
