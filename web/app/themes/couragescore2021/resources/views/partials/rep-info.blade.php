<div class="rep-info">
    <h3>
      <a href="{{ get_the_permalink($post->ID) }}">
          {{ get_the_title($post->ID) }}
      </a>
    </h3>
    <h4>State <span class="body">{{ get_field('senate_or_assembly', $post->ID) }}</span></h4>
    <div>
      <table>
        <thead>
          <tr>
            <th>District</th>
            <th>Party</th>
            <th>Score</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="district">{{ get_field('district', $post->ID) }}</td>
            <td class="party">{{ get_field('party', $post->ID) }}</td>
            <td class="Score">@include('partials.score-display')</td>
          </tr>
        </tbody>
      </table>
    </div>
</div>