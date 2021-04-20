<footer class="content-info">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="logo">
          <a class="brand" href="{{ home_url('/') }}">
            <img src="@asset('images/couragescore-logo_300.png')" />
          </a>
        </div>
        @include('partials.contact-links')
        <div class="copyright">
        @php dynamic_sidebar('sidebar-footer') @endphp
        </div>
      </div>
      <div class="col-md-4">
        <p class="signup-title">{{ get_field('footer_newsletter_title', 'option') }}</p>
        @include('partials.newsletter-form')
        @include('partials.social-links')
      </div>
    </div>
  </div>
</footer>