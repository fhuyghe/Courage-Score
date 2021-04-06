@extends('layouts.app')

@section('content')

  <section id="top">
    <div class="content">
      <h1>{!! App::title() !!}</h1>
      @include('partials.search')
  </section>

@endsection
