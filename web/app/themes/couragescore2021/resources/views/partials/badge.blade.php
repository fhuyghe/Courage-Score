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
  </div>
@endif