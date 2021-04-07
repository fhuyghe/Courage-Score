<div class="rep-info">
    <h3>
      <a href="{{ the_permalink() }}">
          {{ the_title() }}
      </a>
    </h3>
    <h4>State <span class="body">{{ get_field('senate_or_assembly') }}</span></h4>
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
            <td class="district">{{ get_field('district') }}</td>
            <td class="party">{{ get_field('party') }}</td>
            <td class="Score">@include('partials.score-display')</td>
          </tr>
        </tbody>
      </table>
    </div>
</div>