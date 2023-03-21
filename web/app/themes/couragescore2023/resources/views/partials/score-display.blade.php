@php $score = App\get_score($post); @endphp

<div class="score">
    @if ($score == 'na')
        @include('partials.alert-novote')        
    @else
        {{ $score }}
    @endif
</div>