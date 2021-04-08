@extends('layouts.app')

@section('content')

  <section id="top">
    <div class="content">
      <h1>{!! App::title() !!}</h1>
      <p>Paragraph</p>
      @include('partials.search')
      <a href="/all-representatives">See all representatives</a>
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
      <h2>{{ $allStars['title'] }}</h2>
      {!! $allStars['paragraph'] !!}
    </div>
    <div id="starList">
    Get Star list
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
          Get Shame list
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
