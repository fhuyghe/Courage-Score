<div class="col-md-4">
    <div class="rep-block vertical">
      <div class="portrait">
        <div class="portrait-wrap">
       <img src="{{ $rep['photo']['url'] }}"/>
      </div>
      </div>
      <div class="rep-name-title">
        <h3><a >{!! $rep['name'] !!}</a></h3>
        <h5>Score</h5>
        <p> {{ $rep['score'] }}</p>
        <h5>Previous Position</h5>
        <p>{{ $rep['previous_position'] }}</p>
        <h5>Current Position</h5>
        <p>{{ $rep['current_position'] }}</p>
      </div>
  </div>
  </div>