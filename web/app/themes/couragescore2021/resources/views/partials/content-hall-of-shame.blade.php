@php global $post @endphp

<section id="top">
  <h1>{!! App::title() !!}</h1>
  @php the_content() @endphp
</section>

@if($data['hallOfShame'])
  <section id="legislators">
    <div class="row">
      @foreach($data['hallOfShame']  as $post)
      @php setup_postdata( $post ) @endphp

      <div class="col-md-6">
        @include('partials.rep-block')
      </div>

      @php wp_reset_postdata() @endphp
      @endforeach
    </div>
  </section>
@endif

@php $dishonorableMentions = $data['dishonorable_mentions'] @endphp
  @if($dishonorableMentions)
  <section id="dishonorableMentions">
    <div class="container">
      <div class="section-header">
        <h2>{{ $dishonorableMentions['title'] }}</h2>
        {!! $dishonorableMentions['paragraph'] !!}
      </div>
        <div id="honorableWrap" class="row">
        @foreach ($dishonorableMentions['representatives'] as $rep)
            <div class="col-md-4">
              <div class="rep-block vertical">
                <div class="portrait">
                  <div class="portrait-wrap">
                 <img src="{{ $rep['photo']['url'] }}"/>
                </div>
                </div>
                <div class="rep-name-title">
                  <h3>{!! $rep['name'] !!} </h3>
                  <h5>Score</h5>
                  <p> {{ $rep['score'] }}</p>
                  <h5>Previous Position</h5>
                  <p>{{ $rep['previous_position'] }}</p>
                  <h5>Current Position</h5>
                  <p>{{ $rep['current_position'] }}</p>
                </div>
            </div>
            </div>
        @endforeach
      </div>
      </div>
  </section>
  @endif