<footer class="content-info">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="logo">
          <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
        </div>
        @include('partials.contact-links')
        <div class="copyright">
        @php dynamic_sidebar('sidebar-footer') @endphp
        </div>
      </div>
      <div class="col-md-4">
        <h3>{{ get_field('footer_newsletter_title', 'option') }}</h3>
        @include('partials.newsletter-form')
        @include('partials.social-links')
      </div>
    </div>
  </div>
</footer>
