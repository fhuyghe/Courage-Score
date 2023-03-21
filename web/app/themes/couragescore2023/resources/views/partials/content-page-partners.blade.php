@php global $post; @endphp

<div class="page-header">
    <h1>{!! $title !!}</h1>
    @php the_content() @endphp
</div>

@php 
$withLink = []; 
$withLink = [];
foreach ($partners as $partner):
    $scoreLink = get_field('scorecard_link', $partner->ID);
    if($scoreLink):
        $withLink[] = $partner;
    else:
        $withoutLink[] = $partner;
    endif;
endforeach;
@endphp

<div class="container">
<section id="partners">
        <div class="row">
            @foreach ($withLink as $post)
            @php setup_postdata($post) @endphp
            <div class="partner col-6 col-md-3">
                <a href="{{ get_field('website') }}" target="blank">
                    @if( has_post_thumbnail() )
                    {!! the_post_thumbnail('medium') !!}
                    @else
                    <h3>{{ the_title() }}</h3>
                    @endif
                </a>
                
                @php $scorecard_link = get_field('scorecard_link') @endphp
                @if($scorecard_link)
                @if(has_term("official", "partner-category"))
                <p>
                            <a href="{{ $scorecard_link }}" target="blank">
                                Scorecard
                            </a>
                        </p>
                        @endif
                        @endif
                    </div>
                @php wp_reset_postdata($post) @endphp
            @endforeach
        </div>
</section>

<section id="extra">
    {!! get_field('partners_without_scorecards') !!}
</section>
    
    <section id="partnersNoLogo">
        <ul>
            @foreach ($withoutLink as $post)
            @php setup_postdata($post) @endphp
            <li>
                <a href="{{ get_field('website') }}" target="blank">
                    {{ the_title() }}
                </a>
            </li>
                @php wp_reset_postdata($post) @endphp
            @endforeach
        </ul>
</section>