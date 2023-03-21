@php $bill = $vote['bill_number']; @endphp
@php $opposed = get_field('oppose',$bill->ID) @endphp

<tr class="vote-row {{$vote['floorcommittee']}}">
    <td class="type">
        {{ $vote['floorcommittee'] }}
    </td>
    <td class="year">
        {{ $currentYear }}
    </td>
    <td class="categories"> 
        <ul>
        @php $topics = get_the_terms($bill->ID, 'vote-topic') @endphp
        @if($topics)
        @foreach (get_the_terms($bill->ID, 'vote-topic') as $cat)
            <li>{{ $cat->slug }}</li>
        @endforeach
        @endif
        </ul>
    </td>
    <td class="name">
        <p>{{ $bill->post_title }}</p>
    </td>

    <td class="description">
        <p>{{ get_field('title', $bill->ID) }}</p>
    </td>

    <td class="info">
        @if($opposed)
            <i class="fal fa-info-circle" 
            data-toggle="popover" 
            title="Opposed Bill" 
            data-content="This is a bad bill."></i>
        @endif
    </td>

    <td class="vote-casted">
        @switch ($vote['vote'])
            @case('n_e')
                <span class="square grey">N/A</span>
                @break
            @case('a')
                @if($vote['excused'])
                    <span class="square grey">Excused</span>
                @else
                    <span class="square yellow">No Vote</span>
                @endif
                @break
            @case('n')
                <span class="square {{ $opposed ? 'green' : 'red' }}">Oppose</span>
                @break
            @case('y')
                <span class="square {{ $opposed ? 'red' : 'green' }}">Support</span>
                @break
        @endswitch
    </td>
</tr>