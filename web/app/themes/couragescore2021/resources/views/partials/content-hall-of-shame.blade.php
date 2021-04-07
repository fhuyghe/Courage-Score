<section id="top">
  <h1>{!! App::title() !!}</h1>
  @php the_content() @endphp
</section>

@if($data['hallOfShame'])
  <section id="legislators">
    <div class="row">
      @foreach($data['hallOfShame'] as $legislator)
      <div class="col-md-6">
        @include('partials.legislator-block')
      </div>
      @endforeach
    </div>
  </section>
@endif