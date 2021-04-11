<header class="banner">
  <div class="container">
    <div class="row">
      <div class="col" id="logo">
        <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
      </div>
      <div class="col" id="bannerSearch">
        @if(!is_front_page())
          @include('partials.search')
        @endif
      </div>
      <div class="col" id="social">
        @include('partials.social-links')
      </div>
    </div>
    <nav class="nav-primary">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
      @endif
    </nav>
  </div>
</header>
