@foreach($all_representatives as $rep)
    @php $leadership = get_field('leadership_position', $rep) @endphp
<div @if(!$leadership) style="color: lightgrey;" @endif>
    <b>{{ $rep->post_title }}</b> {{ $leadership }}
</div>
@endforeach