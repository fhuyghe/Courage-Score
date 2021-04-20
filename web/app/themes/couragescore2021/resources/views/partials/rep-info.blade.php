<div class="rep-info">
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
          <td class="party">{{ get_field('party', $post->ID) == 'democrat' ? 'D' : 'R' }}</td>
          <td>@include('partials.score-display')</td>
        </tr>
      </tbody>
    </table>
</div>