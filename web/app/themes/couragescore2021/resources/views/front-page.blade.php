@extends('layouts.app')

@section('content')

  <section id="top">
    <div class="content">
      <h1>{!! App::title() !!}</h1>
      <div id="address-container">
        <input id="address-input" type="text" placeholder="Enter an address e.g. 1 York St" size="50" />
        <div id="suggestions"></div>
        <button id="address-button">Find</button>
      </div>
    </div>
  </section>

@endsection
