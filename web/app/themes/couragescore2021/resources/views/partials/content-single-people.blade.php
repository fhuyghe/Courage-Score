<article @php post_class() @endphp>
  <div class="row">
  <div class="col-md-6">
    <header>
      <h1 class="entry-title">{!! get_the_title() !!}</h1>
      <div id="parliement">{{ $data['senate_or_assembly'] }}</div>
      <div id="district">{{ $data['district'] }}</div>
    </header>
  </div>
  <div class="col-md-6">
  <div id="mapContainer"></div>
  </div>
  <div class="entry-content">
    @php the_content() @endphp
  </div>
  <footer>
  </footer>
</article>
