<section id="top" style="background-image: url('{{ get_field('top_image_desktop')['url'] }}')">
    <div class="content">
      <h1>{{ $data['top']['title'] }}</h1>
      <p>{{ $data['top']['paragraph'] }}</p>
      @include('partials.search')
      <a href="/all-representatives">See all representatives</a>
    </div>
  </section>

  {{-- <section id="top">
    <div class="squares-top">
      @for ($i = 0; $i < 8; $i++)
        <div class="square" id="square-{{ $i }}"></div>
      @endfor
    </div>
    <div class="squares-left">
      <div>
      <div class="square"></div>
      <div class="square"></div>
      </div>
      <div>
      <div class="square"></div>
      <div class="square"></div>
      </div>
  </div>
    <div class="content">
      <h1>{!! App::title() !!}</h1>
      @include('partials.search')
      <a href="/all-representatives">See all representatives</a>
    </div>
      <div class="squares-right">
        <div>
          <div class="square"></div>
          <div class="square"></div>
          </div>
          <div>
          <div class="square"></div>
          <div class="square"></div>
          </div>
      </div>
      <div class="squares-bottom">
        @for ($i = 0; $i < 8; $i++)
      <div class="square" id="square-{{ $i }}"></div>
    @endfor
      </div>
  </section> --}}