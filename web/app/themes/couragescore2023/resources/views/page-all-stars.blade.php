@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
  <div class="container">
    @include('partials.content-all-stars')
  </div>
  @endwhile
@endsection
