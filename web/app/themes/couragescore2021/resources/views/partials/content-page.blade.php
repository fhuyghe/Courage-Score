@php global $post; @endphp

@php the_content() @endphp

<section id="partners">
    <div class="container">
        <div class="row">
            @foreach ($partners as $post)
            @php setup_postdata($post) @endphp
            <div class="partner col-md-3">
                <a href="{{ get_field('website') }}" target="blank">
                    {!! the_post_thumbnail() !!}
                    <h3>{{ the_title() }}</h3>
                    </a>

                    @php $scorecard_link = get_field('scorecard_link') @endphp
                    @if($scorecard_link)
                        <h5>
                        <a href="{{ $scorecard_link }}" target="blank">
                            Scorecard
                        </a>
                        </h5>
                    @endif
                </div>
                @php wp_reset_postdata($post) @endphp
            @endforeach
        </div>
    </div>
</section>