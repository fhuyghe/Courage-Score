@php $score = App\get_score($post); @endphp


    @if ($score == 'na')
        <span class="hidden">-1</span>@include('partials.alert-novote')        
    @else
        {{ $score }}
    @endif