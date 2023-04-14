@php $repObject = $rep['representative'] @endphp
<div class="rep-block">
  @if($repObject)
  <div class="portrait">
    <div class="portrait-wrap">
      {!! get_the_post_thumbnail($repObject, 'medium') !!}
    </div>
  </div> 

    <div class="rep-name-title">
      <h3><a href="{{ get_permalink($repObject) }}">{!! $repObject->post_title !!}</a></h3>
      @php $title = get_field('senate_or_assembly', $repObject->ID) == 'senate' ? 'Senator' : 'Assembly Member' @endphp
      <h4>{{ $rep['former'] ? 'Former ' : '' }}{{ $title }}</h4>
    </div> 
    
    @if($rep['explanation'])
      <div class="explanation">
        {{ $rep['explanation'] }}
      </div>
    @endif

    <div class="rep-info">
      <div>
        <h5>District</h5>
        <p>{{ get_field('district', $repObject) }}</p>
      </div>      
      
      <div>
        <h5>Party</h5>
        <p>@if(get_field('party', $repObject) == 'democrat') D @else R @endif</p>
      </div>

      <div>
        <h5>Score</h5>
        @php $score = App\get_score($repObject); @endphp
        <p>{{ $score }}</p>
      </div>
    </div>

  @else
  
  <div class="portrait">
      <div class="portrait-wrap">
        <a href="{{ get_permalink($repObject) }}">
         <img src="{{ $rep['photo']['url'] }}"/>
        </a>
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