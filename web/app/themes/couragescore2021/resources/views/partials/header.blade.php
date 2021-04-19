<header class="banner">
  <div class="container">
    <div class="row">
      <div class="col" id="logo">
        <a class="brand" href="{{ home_url('/') }}">
          <img src="@asset('images/couragescore-logo_300.png')" />
        </a>
      </div>
      {{-- <div class="col" id="bannerSearch">
        @if(!is_front_page())
          @include('partials.search')
        @endif
      </div> --}}
      <div class="col" id="topRight">
        <div id="social">
          @include('partials.social-links')
        </div>
        <div id="languages">
          {!! App\languages_list() !!}
        </div>
      </div>
      <button class="hamburger hamburger--arrow" type="button">
        <span class="hamburger-box">
          <span class="hamburger-inner"></span>
        </span>
      </button>
    </div>
    <div class="nav-wrap">
      <nav class="nav-primary">
        @if (has_nav_menu('primary_navigation'))
          {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
        @endif
      </nav>
      @include('partials.social-links')
      <div id="languages">
        {!! App\languages_list() !!}
      </div>
    </div>
  </div>
</header>
