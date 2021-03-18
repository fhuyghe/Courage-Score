@php the_content() @endphp

@if($data['allStars'])
  <section id="legislators">
    <div class="row">
      @foreach($data['allStars'] as $legislator)
      <div class="col-md-4">
        @include('partials.legislator-block')
      </div>
      @endforeach
    </div>
  </section>
@endif