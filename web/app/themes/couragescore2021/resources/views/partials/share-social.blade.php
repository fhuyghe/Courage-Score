@php $url=urlencode( get_permalink() ) @endphp

    <ul class="socials">
        <li>
            <a id="print">
            <i class="far fa-print"></i>
            </a>
        </li>
    <li>
        <a  onClick="window.open('http://www.facebook.com/sharer.php?u=<?php echo $url;?>','sharer','toolbar=0,status=0,width=700,height=400');" href="javascript: void(0)" class="">
            <i class="fab fa-facebook-f"></i>
        </a>
    </li>
    <li>
        @php $twitterText = get_field('social_share_text', 'option');
        $twitterText = str_replace('%%title%%', get_the_title(), $twitterText);
        $twitterText = str_replace('%%score%%', $score, $twitterText);
        $twitterText = urlencode($twitterText);
        @endphp

    <a target=_blank 
        href="https://twitter.com/share?text={{ $twitterText }}"  
        class="twitter"  
        onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" >
        <i class="fab fa-twitter"></i>
    </a>
    </li>
    <li>
        <a id="link">
            <i class="far fa-link"></i>
        </a>
    </li>
    </ul>