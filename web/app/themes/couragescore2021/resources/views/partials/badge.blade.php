@php
  $isAllStar = false;
  $isHallOfShame = false;

 $allStarList = App::getAllStars();
  if(in_array($post, $allStarList)){
    $isAllStar = true;
    $class="all-star";
  }
  
  $hallOfShameList = App::getHallOfShame();
  if(in_array($post, $hallOfShameList)){
    $isHallOfShame = true;
    $class="hall-of-shame";
  }
@endphp

@if($isAllStar || $isHallOfShame)
  <div class="list-badge {{ $class }}">
      <svg width="60px" height="60px" viewBox="0 0 58 58" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g id="all-star" fill="#12F28F">
                  <polygon id="Star" points="29 53.8746745 23.671264 57.5062199 20.0142311 52.1949432 13.7334673 53.6562969 12.2420402 47.3826063 5.85750041 46.4764045 6.73310452 40.0875965 1.10705635 36.9362267 4.2314365 31.2951454 0.123708887 26.3242176 5.07490022 22.1927222 3.04026455 16.0735877 9.14958124 14.0096596 9.46282633 7.5687414 15.9051713 7.85112551 18.5239917 1.95830535 24.4292906 4.54886412 29 0 33.5707094 4.54886412 39.4760083 1.95830535 42.0948287 7.85112551 48.5371737 7.5687414 48.8504188 14.0096596 54.9597354 16.0735877 52.9250998 22.1927222 57.8762911 26.3242176 53.7685635 31.2951454 56.8929437 36.9362267 51.2668955 40.0875965 52.1424996 46.4764045 45.7579598 47.3826063 44.2665327 53.6562969 37.9857689 52.1949432 34.328736 57.5062199"></polygon>
              </g>
          </g>
      </svg>
  </div>
@endif