@php $senate_or_assembly = get_field('senate_or_assembly') @endphp
    <tr class="rep {{ $senate_or_assembly }}">
        <td class="name">
            <a href="{{ the_permalink() }}">
                <img src="{{ get_the_post_thumbnail_url( $post, 'thumbnail') }}" />
              {{ the_title() }}
          </a>
        </td>
        <td class="district">
            {{ get_field('district') }}
        </td>
        <td class="party">
            {{ get_field('party') == 'democrat' ? 'D' : 'R' }}
        </td>
      <td class="score">
          Score
      </td>
      <td class="grade">
          @include('partials.grade-display')
        </td>
    </tr>