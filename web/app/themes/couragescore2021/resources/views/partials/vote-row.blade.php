@php $opposed = get_field('oppose',$bill->ID) @endphp
<tr class="vote-row">
    <td class="name">
        <p>{{ $bill->post_title }}</p>
    </td>
    <td class="description">
        <p>{{ get_field('title', $bill->ID) }}</p>
    </td>
    <td class="vote-casted">
        @switch ($vote['vote'])
            @case('n_e')
                <span class="square grey">N/E</span>
                @break
            @case('a')
                <span class="square orange">A</span>
                @break
            @case('n')
                <span class="square {{ $opposed ? 'green' : 'red' }}">Oppose</span>
                @break
            @case('y')
                <span class="square green">Support</span>
                @break
        @endswitch
    </td>
</tr>