@php $repObject = $rep['representative'] @endphp
<div class="rep-block vertical">
  @if($repObject)
    <div class="portrait">
      <div class="portrait-wrap">
        {!! get_the_post_thumbnail($repObject, 'medium') !!}
      </div>
    </div>
    <div class="rep-name-title">
      <h3><a href="{{ get_permalink($repObject) }}">{!! $repObject->post_title !!}</a></h3>

      <h5>Score</h5>
      <p> {{ $repObject->post_title }}</p>

      <h5>Party</h5>
      <p>@if(get_field('party', $repObject) == 'democrat') D @else R @endif</p>

      <h5>District</h5>
      <p>{{ $rep['district'] }}</p>

    </div>
  @else
  <div class="portrait">
      <div class="portrait-wrap">
         <img src="{{ $rep['photo']['url'] }}"/>
      </div>
  </div>
  <div class="rep-name-title">
    <h3><a >{!! $rep['name'] !!}</a></h3>
    <h5>Score</h5>
    <p> {{ $rep['score'] }}</p>
    @if( isset($rep['party']) )
    <h5>Party</h5>
    <p>{{ $rep['party'] }}</p>
    @endif
    @if( isset($rep['district']) )
    <h5>District</h5>
    <p>{{ $rep['district'] }}</p>
    @endif
  </div>
  @endif
</div>