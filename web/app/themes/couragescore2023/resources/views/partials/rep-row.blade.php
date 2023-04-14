<tr class="rep {{ get_field('senate_or_assembly') }}">
    <td>
        {{ get_field('senate_or_assembly') }}
    </td>
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
        {{ get_field('party')[0] }}
    </td>
    <td class="score">
        @include('partials.score-display')
    </td>
    <td class="grade">
        @include('partials.grade-display')
    </td>
</tr>