<section id="top" style="background-image: url('{{ get_field('top_image_desktop')['url'] }}')">
  <div class="mobile-bg">
    <img src="@asset('../../images/landing-top-mobile.png')" />
  </div>
    <div class="content">
      <h1>{{ $top['title'] }}</h1>
      <p>{{ $top['paragraph'] }}</p>
      @include('partials.search')
      <a href="/all-representatives">See all representatives</a>
    </div>
  </section>