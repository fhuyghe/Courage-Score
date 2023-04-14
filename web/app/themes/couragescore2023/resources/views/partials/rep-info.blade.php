<div class="rep-info">
  <div>
    <h5>District</h5>
    <p class="district">{{ get_field('district', $post->ID) }}</p>
  </div>
  
  <div>
    <h5>Party</h5>
    <p class="party">{{ get_field('party', $post->ID)[0] }}</p>
  </div>
  
  <div>
    <h5>Score</h5>
    <div class="score">
      @include('partials.score-display')
    </div>
  </div>
</div>