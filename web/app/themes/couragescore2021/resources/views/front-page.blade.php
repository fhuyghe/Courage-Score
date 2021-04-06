@extends('layouts.app')

@section('content')

  <section id="top">
    <div class="content">
      <h1>{!! App::title() !!}</h1>
      <p>Paragraph</p>
      @include('partials.search')
      <a href="/all-representatives">See all representatives</a>
  </section>

@endsection
