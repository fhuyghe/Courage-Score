@php global $post @endphp

<section id="top">
  <h1>{!! App::title() !!}</h1>
  @php the_content() @endphp
</section>

@if($data['allStars'])

<section id="photos">
  <div class="photos-wrap">
    @foreach($data['allStars'] as $post)
      <div class="photo">
        <a href="#{{ $post->post_name }}">
        {!! get_the_post_thumbnail($post->ID, 'thumbnail') !!}
        </a>
      </div>
    @endforeach
  </div>
</section>

  <section id="legislators">
    <div class="row">
      @foreach($data['allStars'] as $post)
      @php setup_postdata( $post ) @endphp

      <div class="col-md-6">
        @include('partials.rep-block')
      </div>
      
      @php wp_reset_postdata() @endphp
      @endforeach
    </div>
  </section>
@endif

@php $honorableMentions = $data['honorable_mentions'] @endphp
  @if($honorableMentions)
  <section id="honorableMentions">
    <div class="container">
      <div class="section-header">
        <h2>{{ $honorableMentions['title'] }}</h2>
        {!! $honorableMentions['paragraph'] !!}
      </div>
        <div id="honorableWrap" class="row">
        @foreach ($honorableMentions['representatives'] as $rep)
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