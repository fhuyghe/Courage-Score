<article @php post_class() @endphp>
  <header>
    <h1 class="entry-title">{!! get_the_title() !!}</h1>
  </header>
  <div class="entry-content">
    @php the_content() @endphp
  </div>
</article>
