@foreach($all_representatives as $rep)
    @php $leadership = get_field('leadership_position', $rep) @endphp
<div @if(!$leadership) style="color: lightgrey;" @endif>
    <b><a href="{{ get_permalink($rep) }}">{{ $rep->post_title }}</a></b> {{ $leadership }}
</div>
@endforeach