@php global $post @endphp

<section id="top">
  <h1>{!! $title !!}</h1>
  @php the_content() @endphp
</section>

@if($hallOfShame)
  <section id="legislators">
    <div class="row">
      @foreach($hallOfShame  as $post)
      @php setup_postdata( $post ) @endphp

      <div class="col-md-6 col-xl-4">
        @include('partials.rep-block')
      </div>

      @php wp_reset_postdata() @endphp
      @endforeach
    </div>
  </section>
@endif

  @if($dishonorableMentions)
  <section id="dishonorableMentions">
    <div class="container">
      <div class="section-header">
        <h2>{{ $dishonorableMentions['title'] }}</h2>
        {!! $dishonorableMentions['paragraph'] !!}
      </div>
        <div id="honorableWrap" class="row">
        @foreach ($dishonorableMentions['representatives'] as $rep)
        <div class="col-md-6 col-xl-4">
              @include('partials.rep-mention')
            </div>
        @endforeach
      </div>
      </div>
  </section>
  @endif