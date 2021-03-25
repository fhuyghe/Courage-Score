@extends('layouts.app')

@section('content')

  <section id="top">
    <div class="content">
      <h1>{!! App::title() !!}</h1>
      <div id="searchWrap">
      @include('partials.search-address')
      @include('partials.search-legislator')
    </div>
  </section>

@endsection
