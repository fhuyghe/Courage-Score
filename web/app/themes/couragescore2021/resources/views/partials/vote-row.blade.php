<tr class="vote-row">
    <td class="name">
        <p>{{ $bill->post_title }}</p>
    </td>
    <td class="description">
        <p>{{ get_field('title', $bill->ID) }}</p>
    </td>
    <td class="vote-casted">
    @php 
      switch ($vote['vote']) {
        case 'n_e':
            echo '<span class="square grey">N/E</span>';
            break;
        case 'a':
            echo '<span class="square orange">A</span>';
            break;
        case 'n':
            echo '<span class="square green">NO</span>';
            break;
        case 'y':
            echo '<span class="square green">'.__('YES','progressive').'</span>';
            break;
        }
    @endphp
    </td>
</tr>