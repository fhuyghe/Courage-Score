<footer class="content-info">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="logo">
          <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
        </div>
        <div class="copyright">
        @php dynamic_sidebar('sidebar-footer') @endphp
        </div>
      </div>
      <div class="col-md-4">
        @include('partials.contact-links')
        @include('partials.social-links')
      </div>
    </div>
  </div>
</footer>
