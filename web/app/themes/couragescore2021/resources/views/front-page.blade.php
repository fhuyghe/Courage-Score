@extends('layouts.app')

@section('content')

  <section id="top">
    <div class="squares-top">
      @for ($i = 0; $i < 8; $i++)
        <div class="square" id="square-{{ $i }}"></div>
      @endfor
    </div>
    <div class="squares-left">
      <div>
      <div class="square"></div>
      <div class="square"></div>
      </div>
      <div>
      <div class="square"></div>
      <div class="square"></div>
      </div>
  </div>
    <div class="content">
      <h1>{!! App::title() !!}</h1>
      @include('partials.search')
      <a href="/all-representatives">See all representatives</a>
    </div>
      <div class="squares-right">
        <div>
          <div class="square"></div>
          <div class="square"></div>
          </div>
          <div>
          <div class="square"></div>
          <div class="square"></div>
          </div>
      </div>
      <div class="squares-bottom">
        @for ($i = 0; $i < 8; $i++)
      <div class="square" id="square-{{ $i }}"></div>
    @endfor
      </div>
  </section>

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
    <div class="section-footer">
      <a class="button" href="{{ $allStars['link'] }}">Hall of Shame</a>
    </div>
    </div>
  </section>
  @endif

  @php $hallOfShame = $data['hall_of_shame'] @endphp
  @if($hallOfShame)
  <section id="hallOfShame">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h2>{{ $hallOfShame['title'] }}</h2>
          {!! $hallOfShame['paragraph'] !!}
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
      <div class="section-footer">
        <a class="button" href="{{ $hallOfShame['link'] }}">Hall of Shame</a>
      </div>
    </div>
  </section>
  @endif

  @php $honorableMentions = $data['honorable_mentions'] @endphp
  @if($honorableMentions)
  <section id="honorableMentions">
    <div class="container">
      <div class="section-header">
        <h2>{{ $allStars['title'] }}</h2>
        {!! $allStars['paragraph'] !!}
      </div>
    </div>
  </section>
  @endif

@endsection
